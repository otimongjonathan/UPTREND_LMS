<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\Repayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanInstallmentModificationService
{
    /**
     * Handle early loan completion (e.g., 4 installments paid in 2)
     */
    public static function handleEarlyCompletion(LoanApplication $loan, $totalPaidAmount, $paymentDate)
    {
        DB::transaction(function () use ($loan, $totalPaidAmount, $paymentDate) {
            // Calculate interest savings for early payment
            $originalTotalRepayable = $loan->total_repayable;
            $remainingInstallments = $loan->repayments()->where('status', 'pending')->count();
            
            // Calculate interest savings (simple interest reduction)
            $interestSavings = self::calculateEarlyPaymentSavings($loan, $remainingInstallments);
            $adjustedPayoffAmount = $originalTotalRepayable - $loan->total_paid - $interestSavings;
            
            // Mark all pending installments as completed
            $pendingRepayments = $loan->repayments()->where('status', 'pending')->get();
            
            foreach ($pendingRepayments as $repayment) {
                $repayment->update([
                    'status' => 'completed',
                    'paid_date' => $paymentDate,
                    'paid_amount' => $repayment->amount,
                    'payment_method' => 'early_completion',
                    'notes' => 'Completed as part of early loan payoff'
                ]);
            }
            
            // Update loan status
            $loan->update([
                'status' => 'completed',
                'completion_date' => $paymentDate,
                'total_paid' => $loan->amount + $loan->total_interest - $interestSavings + $loan->processing_fee + $loan->insurance_fee,
                'outstanding_balance' => 0,
                'early_completion' => true,
                'interest_savings' => $interestSavings
            ]);
            
            // Create adjustment record
            self::createAdjustmentRecord($loan, 'early_completion', $interestSavings, 'Interest savings from early completion');
        });
    }
    
    /**
     * Handle loan default and restructuring (e.g., 4 installments extended to 6)
     */
    public static function restructureLoan(LoanApplication $loan, $newTermMonths, $newPaymentFrequency = null, $additionalFees = 0)
    {
        DB::transaction(function () use ($loan, $newTermMonths, $newPaymentFrequency, $additionalFees) {
            // Calculate current outstanding balance
            $outstandingBalance = $loan->outstanding_balance;
            $overdueAmount = self::calculateOverdueAmount($loan);
            $restructuringFee = $additionalFees;
            
            // New total to be repaid
            $newTotalRepayable = $outstandingBalance + $overdueAmount + $restructuringFee;
            
            // Mark existing pending payments as restructured
            $loan->repayments()->where('status', 'pending')->update([
                'status' => 'restructured',
                'notes' => 'Original schedule restructured due to default'
            ]);
            
            // Calculate new payment schedule
            $paymentFrequency = $newPaymentFrequency ?? $loan->repayment_schedule;
            $paymentsPerYear = self::getPaymentsPerYear($paymentFrequency);
            $totalPayments = round(($newTermMonths / 12) * $paymentsPerYear);
            
            // New payment amount (simple division for restructured loans)
            $newPaymentAmount = round($newTotalRepayable / $totalPayments, 2);
            
            // Generate new repayment schedule
            $startDate = Carbon::now()->addDays(30); // Grace period
            
            for ($i = 1; $i <= $totalPayments; $i++) {
                $dueDate = self::getNextPaymentDate($startDate, $paymentFrequency, $i);
                
                Repayment::create([
                    'loan_application_id' => $loan->id,
                    'installment_number' => 1000 + $i, // Different numbering for restructured
                    'amount' => $newPaymentAmount,
                    'principal_amount' => $newPaymentAmount, // Simplified for restructured loans
                    'interest_amount' => 0,
                    'due_date' => $dueDate,
                    'remaining_balance' => max(0, $newTotalRepayable - ($newPaymentAmount * $i)),
                    'status' => 'pending',
                    'payment_frequency' => $paymentFrequency,
                    'notes' => 'Restructured payment plan'
                ]);
            }
            
            // Update loan with restructuring details
            $loan->update([
                'status' => 'restructured',
                'actual_term_months' => $newTermMonths,
                'repayment_schedule' => $paymentFrequency,
                'total_repayable' => $loan->total_paid + $newTotalRepayable,
                'outstanding_balance' => $newTotalRepayable,
                'restructuring_date' => now(),
                'restructuring_fee' => $restructuringFee,
                'restructuring_count' => ($loan->restructuring_count ?? 0) + 1
            ]);
            
            // Create adjustment record
            self::createAdjustmentRecord($loan, 'restructuring', $restructuringFee, "Loan restructured to {$newTermMonths} months with {$totalPayments} payments");
        });
    }
    
    /**
     * Handle partial default with payment plan modification
     */
    public static function handlePartialDefault(LoanApplication $loan, $missedPayments, $proposedNewSchedule)
    {
        DB::transaction(function () use ($loan, $missedPayments, $proposedNewSchedule) {
            // Calculate penalty fees for missed payments
            $penaltyRate = $loan->loanProduct->late_payment_fee_percent ?? 5.0;
            $totalPenalties = 0;
            
            // Mark missed payments and calculate penalties
            foreach ($missedPayments as $missedPayment) {
                $repayment = Repayment::find($missedPayment['repayment_id']);
                $daysOverdue = Carbon::parse($repayment->due_date)->diffInDays(now());
                $penalty = ($repayment->amount * $penaltyRate / 100);
                
                $repayment->update([
                    'status' => 'defaulted',
                    'days_overdue' => $daysOverdue,
                    'late_fee' => $penalty,
                    'notes' => "Defaulted - {$daysOverdue} days overdue"
                ]);
                
                $totalPenalties += $penalty;
            }
            
            // Create new payment schedule for remaining balance + penalties
            $remainingBalance = $loan->outstanding_balance + $totalPenalties;
            
            foreach ($proposedNewSchedule as $index => $newPayment) {
                Repayment::create([
                    'loan_application_id' => $loan->id,
                    'installment_number' => 2000 + $index + 1, // Different numbering for default recovery
                    'amount' => $newPayment['amount'],
                    'principal_amount' => $newPayment['amount'],
                    'interest_amount' => 0,
                    'due_date' => $newPayment['due_date'],
                    'remaining_balance' => max(0, $remainingBalance - array_sum(array_column(array_slice($proposedNewSchedule, 0, $index + 1), 'amount'))),
                    'status' => 'pending',
                    'notes' => 'Default recovery payment plan'
                ]);
            }
            
            // Update loan status
            $loan->update([
                'status' => 'default_recovery',
                'total_penalties' => ($loan->total_penalties ?? 0) + $totalPenalties,
                'outstanding_balance' => $remainingBalance,
                'default_date' => now(),
                'recovery_plan_date' => now()
            ]);
            
            // Create adjustment record
            self::createAdjustmentRecord($loan, 'default_recovery', $totalPenalties, "Default recovery plan with {$totalPenalties} in penalties");
        });
    }
    
    /**
     * Handle overpayment scenarios (paying more than scheduled)
     */
    public static function handleOverpayment(LoanApplication $loan, $overpaymentAmount, $paymentDate, $applyToFuture = true)
    {
        DB::transaction(function () use ($loan, $overpaymentAmount, $paymentDate, $applyToFuture) {
            if ($applyToFuture) {
                // Apply overpayment to future installments
                $futurePayments = $loan->repayments()
                    ->where('status', 'pending')
                    ->orderBy('due_date')
                    ->get();
                
                $remainingOverpayment = $overpaymentAmount;
                
                foreach ($futurePayments as $payment) {
                    if ($remainingOverpayment <= 0) break;
                    
                    $paymentNeeded = $payment->amount - ($payment->paid_amount ?? 0);
                    $amountToApply = min($remainingOverpayment, $paymentNeeded);
                    
                    $payment->update([
                        'paid_amount' => ($payment->paid_amount ?? 0) + $amountToApply,
                        'paid_date' => $amountToApply >= $paymentNeeded ? $paymentDate : $payment->paid_date,
                        'status' => $amountToApply >= $paymentNeeded ? 'completed' : 'partial',
                        'payment_method' => 'overpayment_application'
                    ]);
                    
                    $remainingOverpayment -= $amountToApply;
                }
                
                // If still overpayment remaining, consider early completion
                if ($remainingOverpayment > 0) {
                    self::handleEarlyCompletion($loan, $loan->total_paid + $overpaymentAmount, $paymentDate);
                }
            } else {
                // Create credit balance for future use
                $loan->update([
                    'credit_balance' => ($loan->credit_balance ?? 0) + $overpaymentAmount
                ]);
                
                self::createAdjustmentRecord($loan, 'overpayment_credit', $overpaymentAmount, 'Overpayment held as credit balance');
            }
        });
    }
    
    /**
     * Recalculate entire loan schedule based on current status
     */
    public static function recalculateFullSchedule(LoanApplication $loan, $newParameters = [])
    {
        DB::transaction(function () use ($loan, $newParameters) {
            // Get current loan status
            $totalPaid = $loan->repayments()->where('status', 'completed')->sum('paid_amount');
            $outstandingBalance = $loan->outstanding_balance;
            
            // Apply new parameters if provided
            if (isset($newParameters['interest_rate'])) {
                $loan->applied_interest_rate = $newParameters['interest_rate'];
            }
            
            if (isset($newParameters['term_months'])) {
                $loan->actual_term_months = $newParameters['term_months'];
            }
            
            if (isset($newParameters['payment_frequency'])) {
                $loan->repayment_schedule = $newParameters['payment_frequency'];
            }
            
            // Mark existing pending payments as recalculated
            $loan->repayments()->where('status', 'pending')->update([
                'status' => 'recalculated',
                'notes' => 'Original schedule recalculated'
            ]);
            
            // Generate new schedule for remaining balance
            $newSchedule = LoanInstallmentService::generateRepaymentSchedule($loan);
            
            // Create new repayment records
            foreach ($newSchedule as $payment) {
                Repayment::create([
                    'loan_application_id' => $loan->id,
                    'installment_number' => 3000 + $payment['installment_number'], // Different numbering for recalculated
                    'amount' => $payment['payment_amount'],
                    'principal_amount' => $payment['principal_amount'],
                    'interest_amount' => $payment['interest_amount'],
                    'due_date' => $payment['due_date'],
                    'remaining_balance' => $payment['remaining_balance'],
                    'status' => 'pending',
                    'notes' => 'Recalculated payment schedule'
                ]);
            }
            
            $loan->save();
            
            self::createAdjustmentRecord($loan, 'recalculation', 0, 'Full schedule recalculated with new parameters');
        });
    }
    
    /**
     * Calculate interest savings for early payment
     */
    private static function calculateEarlyPaymentSavings(LoanApplication $loan, $remainingInstallments)
    {
        $pendingInterest = $loan->repayments()
            ->where('status', 'pending')
            ->sum('interest_amount');
        
        // Apply early payment discount (e.g., 50% of remaining interest)
        $discountRate = 0.5; // 50% discount on remaining interest
        return round($pendingInterest * $discountRate, 2);
    }
    
    /**
     * Calculate total overdue amount including penalties
     */
    private static function calculateOverdueAmount(LoanApplication $loan)
    {
        return $loan->repayments()
            ->where('status', '!=', 'completed')
            ->where('due_date', '<', now())
            ->sum('amount') + $loan->repayments()->sum('late_fee');
    }
    
    /**
     * Create adjustment record for audit trail
     */
    private static function createAdjustmentRecord(LoanApplication $loan, $type, $amount, $description)
    {
        // This would typically go to a loan_adjustments table
        // For now, we'll add it to the loan notes
        $adjustment = [
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'date' => now(),
            'staff_id' => auth()->id()
        ];
        
        $existingAdjustments = json_decode($loan->adjustments ?? '[]', true);
        $existingAdjustments[] = $adjustment;
        
        $loan->update(['adjustments' => json_encode($existingAdjustments)]);
    }
    
    /**
     * Get payments per year based on frequency
     */
    private static function getPaymentsPerYear($frequency)
    {
        return match($frequency) {
            'weekly' => 52,
            'bi_weekly' => 26,
            'monthly' => 12,
            'quarterly' => 4,
            default => 12
        };
    }
    
    /**
     * Calculate next payment date based on frequency
     */
    private static function getNextPaymentDate($startDate, $frequency, $installmentNumber)
    {
        return match($frequency) {
            'weekly' => $startDate->copy()->addWeeks($installmentNumber),
            'bi_weekly' => $startDate->copy()->addWeeks($installmentNumber * 2),
            'monthly' => $startDate->copy()->addMonths($installmentNumber),
            'quarterly' => $startDate->copy()->addMonths($installmentNumber * 3),
            default => $startDate->copy()->addMonths($installmentNumber)
        };
    }
    
    /**
     * Get comprehensive loan modification history
     */
    public static function getLoanModificationHistory(LoanApplication $loan)
    {
        return [
            'original_schedule' => $loan->repayments()->where('installment_number', '<', 1000)->get(),
            'restructured_schedule' => $loan->repayments()->whereBetween('installment_number', [1000, 1999])->get(),
            'default_recovery_schedule' => $loan->repayments()->whereBetween('installment_number', [2000, 2999])->get(),
            'recalculated_schedule' => $loan->repayments()->where('installment_number', '>=', 3000)->get(),
            'adjustments' => json_decode($loan->adjustments ?? '[]', true),
            'modification_summary' => [
                'restructuring_count' => $loan->restructuring_count ?? 0,
                'total_penalties' => $loan->total_penalties ?? 0,
                'interest_savings' => $loan->interest_savings ?? 0,
                'credit_balance' => $loan->credit_balance ?? 0,
                'early_completion' => $loan->early_completion ?? false
            ]
        ];
    }
}