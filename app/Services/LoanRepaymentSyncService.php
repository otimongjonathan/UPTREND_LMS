<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\Repayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanRepaymentSyncService
{
    /**
     * Sync loan application with its repayment data
     */
    public static function syncLoanWithRepayments(LoanApplication $loan)
    {
        DB::transaction(function () use ($loan) {
            $repayments = $loan->repayments;
            
            // Calculate totals from repayments
            $totalPaid = $repayments->sum('paid_amount') ?? 0;
            $totalInterest = $repayments->sum('interest_amount') ?? 0;
            $totalRepayable = $repayments->sum('amount') + $repayments->sum('late_fee');
            $outstandingBalance = max(0, $totalRepayable - $totalPaid);
            
            // Update loan with calculated values
            $loan->update([
                'total_paid' => $totalPaid,
                'total_interest' => $totalInterest,
                'total_repayable' => $totalRepayable,
                'outstanding_balance' => $outstandingBalance,
            ]);
            
            // Update loan status based on repayments
            self::updateLoanStatus($loan);
        });
    }
    
    /**
     * Update loan status based on repayment progress
     */
    private static function updateLoanStatus(LoanApplication $loan)
    {
        $repayments = $loan->repayments;
        $completedCount = $repayments->where('status', 'completed')->count();
        $totalCount = $repayments->count();
        $overdueCount = $repayments->where('status', 'pending')
            ->where('due_date', '<', now())->count();
        
        if ($completedCount === $totalCount && $totalCount > 0) {
            $loan->update(['status' => 'completed', 'completion_date' => now()]);
        } elseif ($overdueCount > 0) {
            $loan->update(['status' => 'overdue']);
        } elseif ($completedCount > 0 && $loan->status === 'approved') {
            $loan->update(['status' => 'active']);
        }
    }
    
    /**
     * Create repayment schedule when loan is approved
     */
    public static function createRepaymentSchedule(LoanApplication $loan)
    {
        // Delete existing repayments
        $loan->repayments()->delete();
        
        $principal = $loan->amount;
        $rate = $loan->product->interest_rate ?? 15.0;
        $months = $loan->term_months;
        
        // Calculate monthly payment
        $monthlyRate = $rate / 100 / 12;
        $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $months)) / 
                         (pow(1 + $monthlyRate, $months) - 1);
        
        $balance = $principal;
        $startDate = Carbon::parse($loan->disbursement_date ?? now());
        
        // Create repayment records
        for ($i = 1; $i <= $months; $i++) {
            $interestAmount = $balance * $monthlyRate;
            $principalAmount = $monthlyPayment - $interestAmount;
            $balance -= $principalAmount;
            
            Repayment::create([
                'loan_application_id' => $loan->id,
                'installment_number' => $i,
                'amount' => round($monthlyPayment, 2),
                'principal_amount' => round($principalAmount, 2),
                'interest_amount' => round($interestAmount, 2),
                'remaining_balance' => round(max(0, $balance), 2),
                'due_date' => $startDate->copy()->addMonths($i),
                'status' => 'pending'
            ]);
        }
        
        // Sync loan totals
        self::syncLoanWithRepayments($loan);
    }
    
    /**
     * Process a payment and update loan
     */
    public static function processPayment(Repayment $repayment, $amount, $paymentDate, $method = null, $reference = null)
    {
        DB::transaction(function () use ($repayment, $amount, $paymentDate, $method, $reference) {
            // Calculate late fee if overdue
            $lateFee = 0;
            if (Carbon::parse($paymentDate)->gt($repayment->due_date)) {
                $daysLate = Carbon::parse($paymentDate)->diffInDays($repayment->due_date);
                $lateFeePercent = $repayment->loanApplication->product->late_payment_fee_percent ?? 5.0;
                $lateFee = ($repayment->amount * $lateFeePercent / 100);
            }
            
            // Update repayment
            $totalDue = $repayment->amount + $lateFee;
            $status = $amount >= $totalDue ? 'completed' : 'partial';
            
            $repayment->update([
                'paid_amount' => $amount,
                'paid_date' => $paymentDate,
                'late_fee' => $lateFee,
                'payment_method' => $method,
                'payment_reference' => $reference,
                'status' => $status
            ]);
            
            // Sync loan with updated repayment data
            self::syncLoanWithRepayments($repayment->loanApplication);
        });
    }
}