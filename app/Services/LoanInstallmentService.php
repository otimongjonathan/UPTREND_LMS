<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\Repayment;
use Carbon\Carbon;

class LoanInstallmentService
{
    /**
     * Generate complete repayment schedule for a loan
     */
    public static function generateRepaymentSchedule(LoanApplication $loan)
    {
        // Get loan details
        $principal = $loan->amount;
        $annualRate = $loan->loanProduct->interest_rate ?? 15.0;
        $termMonths = $loan->term_months ?? 12;
        $repaymentSchedule = $loan->repayment_schedule; // weekly, bi_weekly, monthly, quarterly
        
        // Calculate payment frequency
        $paymentsPerYear = self::getPaymentsPerYear($repaymentSchedule);
        $totalPayments = self::getTotalPayments($termMonths, $repaymentSchedule);
        
        // Calculate payment amount
        $paymentAmount = self::calculatePaymentAmount($principal, $annualRate, $totalPayments, $paymentsPerYear);
        
        // Generate schedule
        $schedule = self::createPaymentSchedule($loan, $paymentAmount, $totalPayments, $repaymentSchedule);
        
        return $schedule;
    }
    
    /**
     * Calculate payment amount based on frequency
     */
    private static function calculatePaymentAmount($principal, $annualRate, $totalPayments, $paymentsPerYear)
    {
        if ($totalPayments == 0 || $annualRate == 0) {
            return $principal / ($totalPayments ?: 1);
        }
        
        $periodRate = ($annualRate / 100) / $paymentsPerYear;
        
        $paymentAmount = $principal * ($periodRate * pow(1 + $periodRate, $totalPayments)) / 
                        (pow(1 + $periodRate, $totalPayments) - 1);
        
        return round($paymentAmount, 2);
    }
    
    /**
     * Create detailed payment schedule with dates
     */
    private static function createPaymentSchedule(LoanApplication $loan, $paymentAmount, $totalPayments, $frequency)
    {
        $schedule = [];
        $currentDate = Carbon::parse($loan->disbursement_date ?? now());
        $balance = $loan->amount;
        $annualRate = $loan->loanProduct->interest_rate ?? 15.0;
        $paymentsPerYear = self::getPaymentsPerYear($frequency);
        $periodRate = ($annualRate / 100) / $paymentsPerYear;
        
        for ($i = 1; $i <= $totalPayments; $i++) {
            // Calculate next payment date
            $dueDate = self::getNextPaymentDate($currentDate, $frequency, $i);
            
            // Calculate interest and principal portions
            $interestAmount = round($balance * $periodRate, 2);
            $principalAmount = round($paymentAmount - $interestAmount, 2);
            
            // Adjust for final payment
            if ($i == $totalPayments) {
                $principalAmount = $balance;
                $paymentAmount = $principalAmount + $interestAmount;
            }
            
            $balance = max(0, $balance - $principalAmount);
            
            $schedule[] = [
                'installment_number' => $i,
                'due_date' => $dueDate,
                'payment_amount' => $paymentAmount,
                'principal_amount' => $principalAmount,
                'interest_amount' => $interestAmount,
                'remaining_balance' => $balance,
                'status' => 'pending'
            ];
        }
        
        return $schedule;
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
     * Get total number of payments
     */
    private static function getTotalPayments($termMonths, $frequency)
    {
        $paymentsPerYear = self::getPaymentsPerYear($frequency);
        return round(($termMonths / 12) * $paymentsPerYear);
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
     * Create repayment records in database
     */
    public static function createRepaymentRecords(LoanApplication $loan)
    {
        // Delete existing repayments if any
        $loan->repayments()->delete();
        
        // Generate new schedule
        $schedule = self::generateRepaymentSchedule($loan);
        
        // Create repayment records
        foreach ($schedule as $payment) {
            Repayment::create([
                'loan_application_id' => $loan->id,
                'installment_number' => $payment['installment_number'],
                'amount' => $payment['payment_amount'],
                'principal_amount' => $payment['principal_amount'],
                'interest_amount' => $payment['interest_amount'],
                'due_date' => $payment['due_date'],
                'remaining_balance' => $payment['remaining_balance'],
                'status' => 'pending'
            ]);
        }
        
        return $schedule;
    }
    
    /**
     * Process a payment and update schedule
     */
    public static function processPayment(Repayment $repayment, $paidAmount, $paymentDate, $paymentMethod = null, $reference = null)
    {
        $loan = $repayment->loanApplication;
        $annualRate = $loan->loanProduct->interest_rate ?? 15.0;
        
        // Calculate late fees if applicable
        $lateFee = 0;
        if (Carbon::parse($paymentDate)->gt($repayment->due_date)) {
            $daysLate = Carbon::parse($paymentDate)->diffInDays($repayment->due_date);
            $lateFeePercent = $loan->loanProduct->late_payment_fee_percent ?? 5.0;
            $lateFee = ($repayment->amount * $lateFeePercent / 100);
        }
        
        // Update repayment record
        $repayment->update([
            'paid_amount' => $paidAmount,
            'paid_date' => $paymentDate,
            'late_fee' => $lateFee,
            'payment_method' => $paymentMethod,
            'payment_reference' => $reference,
            'status' => $paidAmount >= ($repayment->amount + $lateFee) ? 'completed' : 'partial'
        ]);
        
        // Update remaining installments if overpayment
        if ($paidAmount > ($repayment->amount + $lateFee)) {
            self::handleOverpayment($loan, $paidAmount - ($repayment->amount + $lateFee));
        }
        
        // Check if loan is fully paid
        self::checkLoanCompletion($loan);
        
        return $repayment;
    }
    
    /**
     * Handle overpayment by applying to future installments
     */
    private static function handleOverpayment(LoanApplication $loan, $overpaymentAmount)
    {
        $futurePayments = $loan->repayments()
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->get();
        
        foreach ($futurePayments as $payment) {
            if ($overpaymentAmount <= 0) break;
            
            $paymentNeeded = $payment->amount - ($payment->paid_amount ?? 0);
            $amountToApply = min($overpaymentAmount, $paymentNeeded);
            
            $payment->update([
                'paid_amount' => ($payment->paid_amount ?? 0) + $amountToApply,
                'status' => (($payment->paid_amount ?? 0) + $amountToApply) >= $payment->amount ? 'completed' : 'partial'
            ]);
            
            $overpaymentAmount -= $amountToApply;
        }
    }
    
    /**
     * Check if loan is fully paid and update status
     */
    private static function checkLoanCompletion(LoanApplication $loan)
    {
        $pendingPayments = $loan->repayments()->where('status', '!=', 'completed')->count();
        
        if ($pendingPayments == 0) {
            $loan->update(['status' => 'completed']);
        }
    }
    
    /**
     * Get loan summary with payment statistics
     */
    public static function getLoanSummary(LoanApplication $loan)
    {
        $repayments = $loan->repayments;
        
        return [
            'total_amount' => $loan->amount,
            'total_payments' => $repayments->count(),
            'completed_payments' => $repayments->where('status', 'completed')->count(),
            'pending_payments' => $repayments->where('status', 'pending')->count(),
            'overdue_payments' => $repayments->where('status', 'pending')
                ->where('due_date', '<', now())->count(),
            'total_paid' => $repayments->sum('paid_amount'),
            'total_due' => $repayments->where('status', '!=', 'completed')->sum('amount'),
            'total_interest' => $repayments->sum('interest_amount'),
            'total_late_fees' => $repayments->sum('late_fee'),
            'next_payment_date' => $repayments->where('status', 'pending')
                ->sortBy('due_date')->first()?->due_date,
            'next_payment_amount' => $repayments->where('status', 'pending')
                ->sortBy('due_date')->first()?->amount
        ];
    }
    
    /**
     * Recalculate schedule if loan terms change
     */
    public static function recalculateSchedule(LoanApplication $loan, $newTermMonths = null, $newRate = null)
    {
        if ($newTermMonths) {
            $loan->term_months = $newTermMonths;
        }
        
        if ($newRate && $loan->loanProduct) {
            $loan->loanProduct->update(['interest_rate' => $newRate]);
        }
        
        $loan->save();
        
        // Recreate repayment schedule
        return self::createRepaymentRecords($loan);
    }
}