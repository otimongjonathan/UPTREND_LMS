<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\Repayment;
use App\Models\PaymentReceipt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RepaymentWorkflowService
{
    /**
     * Generate repayment schedule when loan is approved
     */
    public static function generateScheduleOnApproval(LoanApplication $loan)
    {
        if (!$loan->product) {
            throw new \Exception('Loan must have an associated product to generate repayment schedule');
        }

        // Set disbursement date to today if not set
        if (!$loan->disbursement_date) {
            $loan->update(['disbursement_date' => now()]);
        }

        // Use confirmed terms if available, otherwise use original terms
        $terms = $loan->getEffectiveTerms();
        $frequency = $terms['payment_frequency'];
        $firstDueDate = $terms['first_due_date'] ? \Carbon\Carbon::parse($terms['first_due_date']) : now()->addMonth();
        
        // If no confirmed terms, set them based on original application
        if (!$loan->hasConfirmedTerms()) {
            \App\Services\LoanTermSyncService::regenerateTerms($loan, $loan->repayment_schedule, $firstDueDate);
            $terms = $loan->fresh()->getEffectiveTerms();
        }

        // Generate the repayment schedule using confirmed terms
        $schedule = self::createRepaymentScheduleFromTerms($loan, $terms);
        
        // Update loan status to active
        $loan->update(['status' => 'active']);
        
        Log::info("Repayment schedule generated for loan {$loan->id} with " . count($schedule) . " installments using {$frequency} frequency");
        
        return $schedule;
    }

    /**
     * Calculate late fees based on product settings and days overdue
     */
    public static function calculateLateFee(Repayment $repayment, Carbon $paymentDate = null)
    {
        $paymentDate = $paymentDate ?? now();
        $product = $repayment->loanApplication->product;
        
        if (!$product || $paymentDate->lte($repayment->due_date)) {
            return 0;
        }

        $daysOverdue = $paymentDate->diffInDays($repayment->due_date);
        $lateFeePercent = $product->late_payment_fee_percent ?? 0;
        
        // Calculate late fee as percentage of installment amount
        $lateFee = ($repayment->amount * $lateFeePercent / 100);
        
        // Update repayment with calculated late fee and days overdue
        $repayment->update([
            'late_fee' => $lateFee,
            'days_overdue' => $daysOverdue
        ]);
        
        return $lateFee;
    }

    /**
     * Process payment with enhanced workflow
     */
    public static function processPayment(Repayment $repayment, $paidAmount, $paymentDate, $paymentMethod, $reference = null)
    {
        $paymentDate = Carbon::parse($paymentDate);
        
        // Calculate late fee if payment is late
        $lateFee = self::calculateLateFee($repayment, $paymentDate);
        
        // Total amount due including late fee
        $totalDue = $repayment->amount + $lateFee;
        
        // Determine payment status
        $status = 'partial';
        if ($paidAmount >= $totalDue) {
            $status = 'completed';
        } elseif ($paidAmount <= 0) {
            $status = 'pending';
        }

        // Update repayment record
        $repayment->update([
            'paid_amount' => $paidAmount,
            'paid_date' => $paymentDate,
            'payment_method' => $paymentMethod,
            'payment_reference' => $reference,
            'status' => $status
        ]);

        // Handle overpayment
        if ($paidAmount > $totalDue) {
            $overpayment = $paidAmount - $totalDue;
            self::handleOverpayment($repayment->loanApplication, $overpayment);
        }

        // Update loan status based on repayment progress
        self::updateLoanStatus($repayment->loanApplication);
        
        // Send payment confirmation
        self::sendPaymentConfirmation($repayment);
        
        Log::info("Payment processed for repayment {$repayment->id}: UGX {$paidAmount}, Status: {$status}");
        
        return $repayment;
    }

    /**
     * Record a payment and create receipt
     */
    public static function recordPayment(Repayment $repayment, array $data): PaymentReceipt
    {
        return DB::transaction(function () use ($repayment, $data) {
            $paidAmount = $data['amount'];
            $paymentDate = $data['payment_date'] ?? now();
            $paymentMethod = $data['payment_method'] ?? 'cash';
            $referenceNumber = $data['reference_number'] ?? null;
            $notes = $data['notes'] ?? null;

            // Process payment using existing workflow
            self::processPayment(
                $repayment,
                $paidAmount,
                $paymentDate,
                $paymentMethod,
                $referenceNumber
            );

            // Create payment receipt
            $receipt = PaymentReceipt::create([
                'repayment_id' => $repayment->id,
                'loan_application_id' => $repayment->loan_application_id,
                'user_id' => $repayment->loanApplication->user_id,
                'receipt_number' => self::generateReceiptNumber(),
                'amount_paid' => $paidAmount,
                'payment_date' => $paymentDate,
                'payment_method' => $paymentMethod,
                'reference_number' => $referenceNumber,
                'notes' => $notes,
            ]);

            return $receipt;
        });
    }

    /**
     * Generate receipt number
     */
    private static function generateReceiptNumber(): string
    {
        $lastReceipt = PaymentReceipt::latest('id')->first();
        $lastNumber = $lastReceipt ? intval(substr($lastReceipt->receipt_number, -6)) : 0;
        return 'RCP-' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Handle overpayment by applying to future installments
     */
    private static function handleOverpayment(LoanApplication $loan, $overpaymentAmount)
    {
        $futureRepayments = $loan->repayments()
            ->where('status', '!=', 'completed')
            ->orderBy('due_date')
            ->get();

        foreach ($futureRepayments as $repayment) {
            if ($overpaymentAmount <= 0) break;

            $remainingDue = $repayment->amount - ($repayment->paid_amount ?? 0);
            $amountToApply = min($overpaymentAmount, $remainingDue);

            $newPaidAmount = ($repayment->paid_amount ?? 0) + $amountToApply;
            $newStatus = $newPaidAmount >= $repayment->amount ? 'completed' : 'partial';

            $repayment->update([
                'paid_amount' => $newPaidAmount,
                'status' => $newStatus,
                'paid_date' => $newStatus === 'completed' ? now() : $repayment->paid_date
            ]);

            $overpaymentAmount -= $amountToApply;
        }
    }

    /**
     * Update loan status based on repayment progress
     */
    private static function updateLoanStatus(LoanApplication $loan)
    {
        $repayments = $loan->repayments;
        $completedCount = $repayments->where('status', 'completed')->count();
        $totalCount = $repayments->count();
        $overdueCount = $repayments->where('status', '!=', 'completed')
            ->where('due_date', '<', now())->count();

        if ($completedCount === $totalCount && $totalCount > 0) {
            $loan->update([
                'status' => 'completed',
                'completion_date' => now()
            ]);
        } elseif ($overdueCount > 0) {
            $loan->update(['status' => 'overdue']);
        } elseif ($completedCount > 0) {
            $loan->update(['status' => 'active']);
        }
    }

    /**
     * Get repayments due for reminders based on frequency
     */
    public static function getRepaymentsForReminders()
    {
        $reminders = [];
        
        // Get all pending repayments
        $pendingRepayments = Repayment::where('status', 'pending')
            ->with(['loanApplication.product', 'loanApplication.user'])
            ->get();

        foreach ($pendingRepayments as $repayment) {
            $daysUntilDue = now()->diffInDays($repayment->due_date, false);
            $frequency = $repayment->loanApplication->repayment_schedule;
            
            // Calculate reminder threshold based on frequency
            $reminderThreshold = self::getReminderThreshold($frequency);
            
            // Check if reminder should be sent
            if ($daysUntilDue <= $reminderThreshold && $daysUntilDue >= 0) {
                $reminders[] = [
                    'repayment' => $repayment,
                    'days_until_due' => $daysUntilDue,
                    'reminder_type' => self::getReminderType($daysUntilDue, $frequency)
                ];
            }
        }

        return $reminders;
    }

    /**
     * Get reminder threshold based on payment frequency (half the period)
     */
    private static function getReminderThreshold($frequency)
    {
        return match($frequency) {
            'weekly' => 3,      // 3-4 days before (half of 7 days)
            'bi_weekly' => 7,   // 7 days before (half of 14 days)
            'monthly' => 15,    // 15 days before (half of 30 days)
            'quarterly' => 45,  // 45 days before (half of 90 days)
            default => 15
        };
    }

    /**
     * Get reminder type based on days until due
     */
    private static function getReminderType($daysUntilDue, $frequency)
    {
        $threshold = self::getReminderThreshold($frequency);
        
        if ($daysUntilDue <= 1) {
            return 'urgent';
        } elseif ($daysUntilDue <= ($threshold / 3)) {
            return 'final';
        } else {
            return 'early';
        }
    }

    /**
     * Send payment reminders
     */
    public static function sendPaymentReminders()
    {
        $reminders = self::getRepaymentsForReminders();
        $sentCount = 0;

        foreach ($reminders as $reminder) {
            $repayment = $reminder['repayment'];
            $user = $repayment->loanApplication->user;
            
            // Send reminder notification
            $sent = self::sendReminderNotification($user, $repayment, $reminder['reminder_type'], $reminder['days_until_due']);
            
            if ($sent) {
                $sentCount++;
            }
        }

        Log::info("Sent {$sentCount} payment reminders");
        return $sentCount;
    }
    
    /**
     * Create repayment schedule from confirmed terms
     */
    private static function createRepaymentScheduleFromTerms(LoanApplication $loan, $terms)
    {
        // Delete existing pending repayments
        $loan->repayments()->where('status', 'pending')->delete();
        
        $installmentCount = $terms['installment_count'];
        $installmentAmount = $terms['installment_amount'];
        $currentDueDate = \Carbon\Carbon::parse($terms['first_due_date']);
        $frequency = $terms['payment_frequency'];
        
        $schedule = [];
        
        for ($i = 1; $i <= $installmentCount; $i++) {
            $repayment = $loan->repayments()->create([
                'installment_number' => $i,
                'amount' => round($installmentAmount, 2),
                'principal_amount' => round($installmentAmount, 2),
                'interest_amount' => 0,
                'due_date' => $currentDueDate->copy(),
                'original_due_date' => $currentDueDate->copy(),
                'status' => 'pending',
                'payment_frequency' => $frequency,
                'remaining_balance' => $loan->amount - ($installmentAmount * $i)
            ]);
            
            $schedule[] = $repayment;
            
            // Move to next due date based on frequency
            $currentDueDate = self::getNextDueDateForSchedule($currentDueDate, $frequency);
        }
        
        return $schedule;
    }
    
    /**
     * Get next due date based on payment frequency for schedule generation
     */
    private static function getNextDueDateForSchedule(\Carbon\Carbon $currentDate, $frequency)
    {
        return match($frequency) {
            'weekly' => $currentDate->addWeek(),
            'bi_weekly' => $currentDate->addWeeks(2),
            'monthly' => $currentDate->addMonth(),
            'quarterly' => $currentDate->addMonths(3),
            default => $currentDate->addMonth()
        };
    }

    /**
     * Send reminder notification to borrower
     */
    private static function sendReminderNotification($user, $repayment, $reminderType, $daysUntilDue)
    {
        try {
            // Here you would integrate with your notification system
            // For now, we'll log the reminder
            
            $message = self::getReminderMessage($reminderType, $daysUntilDue, $repayment);
            
            Log::info("Payment reminder sent to {$user->email}: {$message}");
            
            // You can integrate with:
            // - Email service
            // - SMS service  
            // - Push notifications
            // - In-app notifications
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send reminder to {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get reminder message based on type
     */
    private static function getReminderMessage($reminderType, $daysUntilDue, $repayment)
    {
        $amount = number_format($repayment->amount);
        $dueDate = $repayment->due_date->format('M d, Y');
        
        return match($reminderType) {
            'urgent' => "URGENT: Your loan payment of UGX {$amount} is due tomorrow ({$dueDate}). Please make your payment to avoid late fees.",
            'final' => "REMINDER: Your loan payment of UGX {$amount} is due in {$daysUntilDue} days ({$dueDate}). Please prepare your payment.",
            'early' => "Payment Reminder: Your loan payment of UGX {$amount} is due in {$daysUntilDue} days ({$dueDate}). Plan ahead to make your payment on time.",
            default => "Payment reminder for UGX {$amount} due on {$dueDate}"
        };
    }

    /**
     * Send payment confirmation
     */
    private static function sendPaymentConfirmation($repayment)
    {
        $user = $repayment->loanApplication->user;
        $amount = number_format($repayment->paid_amount);
        $reference = $repayment->payment_reference;
        
        $message = "Payment Confirmed: UGX {$amount} received for your loan. Reference: {$reference}. Thank you!";
        
        Log::info("Payment confirmation sent to {$user->email}: {$message}");
        
        // Integrate with notification system here
    }

    /**
     * Get repayment analytics for dashboard
     */
    public static function getRepaymentAnalytics($providerId = null)
    {
        $query = Repayment::query();
        
        if ($providerId) {
            $query->whereHas('loanApplication.product', function($q) use ($providerId) {
                $q->where('provider_id', $providerId);
            });
        }

        $totalRepayments = $query->count();
        $completedRepayments = (clone $query)->where('status', 'completed')->count();
        $overdueRepayments = (clone $query)->where('status', '!=', 'completed')
            ->where('due_date', '<', now())->count();
        $upcomingRepayments = (clone $query)->where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays(7)])->count();

        return [
            'total_repayments' => $totalRepayments,
            'completed_repayments' => $completedRepayments,
            'overdue_repayments' => $overdueRepayments,
            'upcoming_repayments' => $upcomingRepayments,
            'completion_rate' => $totalRepayments > 0 ? round(($completedRepayments / $totalRepayments) * 100, 2) : 0,
            'overdue_rate' => $totalRepayments > 0 ? round(($overdueRepayments / $totalRepayments) * 100, 2) : 0,
        ];
    }

    /**
     * Get repayment schedule for a loan
     */
    public static function getRepaymentSchedule(LoanApplication $loan): array
    {
        $repayments = $loan->repayments()->orderBy('installment_number')->get();
        
        return [
            'loan_id' => $loan->id,
            'customer_name' => $loan->user->name,
            'total_amount' => $loan->total_repayable ?? $loan->amount,
            'total_installments' => $repayments->count(),
            'repayment_frequency' => $loan->repayment_schedule ?? 'monthly',
            'first_due_date' => $loan->confirmed_first_due_date ?? ($repayments->first()?->due_date),
            'final_due_date' => $loan->confirmed_final_due_date ?? ($repayments->last()?->due_date),
            'installments' => $repayments->map(function ($repayment) {
                return [
                    'installment_number' => $repayment->installment_number,
                    'due_date' => $repayment->due_date,
                    'principal' => $repayment->principal_amount,
                    'interest' => $repayment->interest_amount,
                    'total_amount' => $repayment->amount,
                    'status' => $repayment->status,
                    'paid_amount' => $repayment->paid_amount,
                    'remaining' => $repayment->remaining_balance,
                    'is_overdue' => $repayment->isOverdue(),
                ];
            })->toArray(),
        ];
    }

    /**
     * Get payment history for a loan
     */
    public static function getPaymentHistory(LoanApplication $loan): array
    {
        $payments = PaymentReceipt::where('loan_application_id', $loan->id)
            ->orderBy('payment_date', 'desc')
            ->get();

        return [
            'loan_id' => $loan->id,
            'total_payments_made' => $payments->count(),
            'total_amount_paid' => $payments->sum('amount_paid'),
            'payments' => $payments->map(function ($payment) {
                return [
                    'receipt_number' => $payment->receipt_number,
                    'payment_date' => $payment->payment_date,
                    'amount_paid' => $payment->amount_paid,
                    'payment_method' => $payment->payment_method,
                    'reference_number' => $payment->reference_number,
                    'notes' => $payment->notes,
                ];
            })->toArray(),
        ];
    }
}