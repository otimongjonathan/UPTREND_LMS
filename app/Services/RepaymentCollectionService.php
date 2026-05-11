<?php

namespace App\Services;

use App\Models\RepaymentSchedule;
use App\Models\PaymentReceipt;
use App\Models\LoanApplication;
use App\Models\RepaymentReminder;
use Carbon\Carbon;

class RepaymentCollectionService
{
    protected RepaymentScheduleService $scheduleService;

    public function __construct(RepaymentScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Record a payment against a repayment schedule
     */
    public function recordPayment(
        RepaymentSchedule $schedule,
        float $amount,
        string $paymentMethod,
        string $paymentReference = null,
        string $notes = null
    ): PaymentReceipt {
        $remainingAmount = $schedule->total_amount - $schedule->paid_amount;
        $amountToApply = min($amount, $remainingAmount);

        // Allocate payment to interest first, then principal
        $interestRemaining = max(0, $schedule->interest_amount - 
            $schedule->paymentReceipts()->sum('interest_paid'));
        $principalRemaining = max(0, $schedule->principal_amount - 
            $schedule->paymentReceipts()->sum('principal_paid'));

        $interestPaid = min($amountToApply, $interestRemaining);
        $principalPaid = $amountToApply - $interestPaid;

        // Create payment receipt
        $receipt = PaymentReceipt::create([
            'repayment_schedule_id' => $schedule->id,
            'loan_application_id' => $schedule->loan_application_id,
            'amount_paid' => $amountToApply,
            'principal_paid' => round($principalPaid, 2),
            'interest_paid' => round($interestPaid, 2),
            'penalty_paid' => 0,
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
            'payment_date' => now(),
            'notes' => $notes,
        ]);

        // Update schedule
        $newPaidAmount = $schedule->paid_amount + $amountToApply;
        $schedule->update([
            'paid_amount' => round($newPaidAmount, 2),
        ]);

        // Update status
        $this->scheduleService->updateScheduleStatus($schedule);

        // Mark reminder as completed
        $schedule->reminders()
            ->where('reminded', false)
            ->update(['reminded' => true, 'reminder_sent_at' => now()]);

        return $receipt;
    }

    /**
     * Record multiple installment payments at once
     */
    public function recordBulkPayments(LoanApplication $loan, array $payments): array
    {
        $receipts = [];

        foreach ($payments as $scheduleId => $amount) {
            $schedule = RepaymentSchedule::find($scheduleId);
            if ($schedule && $amount > 0) {
                $receipt = $this->recordPayment(
                    $schedule,
                    $amount,
                    'bank_transfer',
                    null,
                    'Bulk payment processed'
                );
                $receipts[] = $receipt;
            }
        }

        return $receipts;
    }

    /**
     * Get pending installments for a loan
     */
    public function getPendingInstallments(LoanApplication $loan): \Illuminate\Database\Eloquent\Collection
    {
        return RepaymentSchedule::where('loan_application_id', $loan->id)
            ->whereIn('status', ['pending', 'overdue', 'partially_paid'])
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Get overdue installments
     */
    public function getOverdueInstallments(LoanApplication $loan): \Illuminate\Database\Eloquent\Collection
    {
        return RepaymentSchedule::where('loan_application_id', $loan->id)
            ->where('due_date', '<', today())
            ->whereIn('status', ['pending', 'overdue', 'partially_paid'])
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Calculate total arrears for a loan
     */
    public function calculateArrears(LoanApplication $loan): array
    {
        $overdueSchedules = $this->getOverdueInstallments($loan);

        $totalArrears = 0;
        $totalPrincipal = 0;
        $totalInterest = 0;
        $overdueCount = $overdueSchedules->count();

        foreach ($overdueSchedules as $schedule) {
            $remaining = $schedule->total_amount - $schedule->paid_amount;
            $totalArrears += $remaining;
            
            // Allocate remaining to interest first
            $interestRemaining = max(0, $schedule->interest_amount - 
                $schedule->paymentReceipts()->sum('interest_paid'));
            $principalRemaining = max(0, $schedule->principal_amount - 
                $schedule->paymentReceipts()->sum('principal_paid'));

            if ($remaining <= $interestRemaining) {
                $totalInterest += $remaining;
            } else {
                $totalInterest += $interestRemaining;
                $totalPrincipal += $remaining - $interestRemaining;
            }
        }

        return [
            'total_arrears' => round($totalArrears, 2),
            'principal_arrears' => round($totalPrincipal, 2),
            'interest_arrears' => round($totalInterest, 2),
            'overdue_count' => $overdueCount,
            'oldest_due_date' => $overdueSchedules->min('due_date'),
        ];
    }

    /**
     * Create payment reminders for upcoming installments
     */
    public function createReminders(LoanApplication $loan, int $daysBeforeDue = 5): int
    {
        $futureSchedules = RepaymentSchedule::where('loan_application_id', $loan->id)
            ->where('status', 'pending')
            ->whereDate('due_date', '=', today()->addDays($daysBeforeDue))
            ->get();

        $count = 0;
        foreach ($futureSchedules as $schedule) {
            $existingReminder = RepaymentReminder::where('repayment_schedule_id', $schedule->id)
                ->where('days_before_due', $daysBeforeDue)
                ->exists();

            if (!$existingReminder) {
                RepaymentReminder::create([
                    'repayment_schedule_id' => $schedule->id,
                    'loan_application_id' => $loan->id,
                    'user_id' => $loan->user_id,
                    'days_before_due' => $daysBeforeDue,
                    'reminder_type' => 'email',
                    'reminder_message' => "Reminder: Your payment of UGX " . number_format($schedule->total_amount, 0) . 
                        " is due on " . $schedule->due_date->format('M d, Y'),
                ]);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Mark overdue installments
     */
    public function markOverdueInstallments(LoanApplication $loan): int
    {
        $overdueSchedules = RepaymentSchedule::where('loan_application_id', $loan->id)
            ->where('due_date', '<', today())
            ->whereIn('status', ['pending', 'partially_paid'])
            ->get();

        $count = 0;
        foreach ($overdueSchedules as $schedule) {
            $schedule->update([
                'status' => 'overdue',
                'days_overdue' => today()->diffInDays($schedule->due_date),
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Get payment history for a schedule
     */
    public function getPaymentHistory(RepaymentSchedule $schedule): \Illuminate\Database\Eloquent\Collection
    {
        return $schedule->paymentReceipts()
            ->orderByDesc('payment_date')
            ->get();
    }

    /**
     * Calculate early payment discount (if applicable)
     */
    public function calculateEarlyPaymentDiscount(RepaymentSchedule $schedule, float $amount): float
    {
        // Example: 1% discount if paid 5+ days early
        if ($schedule->due_date->diffInDays(today()) >= 5 && $schedule->due_date > today()) {
            return $amount * 0.01;
        }

        return 0;
    }

    /**
     * Apply payment with early payment discount
     */
    public function recordEarlyPayment(
        RepaymentSchedule $schedule,
        float $amount,
        string $paymentMethod,
        string $paymentReference = null
    ): PaymentReceipt {
        $discount = $this->calculateEarlyPaymentDiscount($schedule, $amount);
        $netAmount = $amount - $discount;

        $receipt = $this->recordPayment(
            $schedule,
            $netAmount,
            $paymentMethod,
            $paymentReference,
            "Early payment with discount: UGX " . number_format($discount, 2)
        );

        return $receipt;
    }
}
