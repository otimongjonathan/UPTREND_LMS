<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\Repayment;
use Carbon\Carbon;

class AutomatedLoanScheduleService
{
    /**
     * Automatically generate complete repayment schedule when loan is issued
     */
    public function generateRepaymentSchedule(LoanApplication $loan): array
    {
        // Load loan product to get calculation settings
        $loan->load('loanProduct');
        $loanProduct = $loan->loanProduct;
        
        if (!$loanProduct) {
            throw new \Exception('Loan product not found for loan application');
        }
        
        $principal = $loan->amount;
        $annualRate = ($loan->applied_interest_rate ?? $loanProduct->interest_rate) / 100;
        $termMonths = $loan->repayment_term ?? 12;
        $startDate = Carbon::parse($loan->disbursement_date ?? now());
        
        // Use loan product's interest calculation method
        $interestMethod = $loanProduct->interest_calculation_method ?? 'compound';
        
        // Use loan product's default repayment frequency if not specified
        $repaymentFrequency = $loan->repayment_schedule ?? $loanProduct->default_repayment_frequency ?? 'monthly';
        
        if ($interestMethod === 'simple') {
            return $this->generateSimpleInterestSchedule($loan, $principal, $annualRate, $termMonths, $startDate, $repaymentFrequency);
        } else {
            return $this->generateCompoundInterestSchedule($loan, $principal, $annualRate, $termMonths, $startDate, $repaymentFrequency);
        }
    }
    
    /**
     * Generate schedule using simple interest method
     */
    private function generateSimpleInterestSchedule(LoanApplication $loan, float $principal, float $annualRate, int $termMonths, Carbon $startDate, string $repaymentFrequency): array
    {
        // Simple Interest: P × R × T
        $totalInterest = $principal * $annualRate * ($termMonths / 12);
        $totalAmount = $principal + $totalInterest;
        
        $principalPerMonth = $principal / $termMonths;
        $interestPerMonth = $totalInterest / $termMonths;
        $installmentAmount = $totalAmount / $termMonths;
        
        $schedule = [];
        $remainingPrincipal = $principal;
        
        for ($i = 1; $i <= $termMonths; $i++) {
            $dueDate = $this->calculateDueDate($startDate, $i, $repaymentFrequency);
            
            // Calculate remaining balance AFTER this payment
            $remainingBalanceAfterPayment = $remainingPrincipal - $principalPerMonth;
            $remainingPrincipal = $remainingBalanceAfterPayment;
            
            $installment = [
                'loan_application_id' => $loan->id,
                'installment_number' => $i,
                'amount' => round($installmentAmount, 2),
                'principal_amount' => round($principalPerMonth, 2),
                'interest_amount' => round($interestPerMonth, 2),
                'late_fee' => 0,
                'remaining_balance' => round(max(0, $remainingBalanceAfterPayment), 2),
                'due_date' => $dueDate->format('Y-m-d'),
                'original_due_date' => $dueDate->format('Y-m-d'),
                'payment_frequency' => $repaymentFrequency,
                'days_overdue' => 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $schedule[] = $installment;
        }
        
        return $schedule;
    }
    
    /**
     * Generate schedule using compound interest (EMI) method
     */
    private function generateCompoundInterestSchedule(LoanApplication $loan, float $principal, float $annualRate, int $termMonths, Carbon $startDate, string $repaymentFrequency): array
    {
        $monthlyRate = $annualRate / 12;
        
        // Calculate EMI (Equal Monthly Installment)
        $emi = $principal * ($monthlyRate * pow(1 + $monthlyRate, $termMonths)) / (pow(1 + $monthlyRate, $termMonths) - 1);
        
        $schedule = [];
        $remainingPrincipal = $principal;
        
        for ($i = 1; $i <= $termMonths; $i++) {
            $dueDate = $this->calculateDueDate($startDate, $i, $repaymentFrequency);
            
            // Calculate interest and principal for this month
            $interestAmount = $remainingPrincipal * $monthlyRate;
            $principalAmount = $emi - $interestAmount;
            
            // Calculate remaining balance AFTER this payment
            $remainingBalanceAfterPayment = $remainingPrincipal - $principalAmount;
            $remainingPrincipal = $remainingBalanceAfterPayment;
            
            $installment = [
                'loan_application_id' => $loan->id,
                'installment_number' => $i,
                'amount' => round($emi, 2),
                'principal_amount' => round($principalAmount, 2),
                'interest_amount' => round($interestAmount, 2),
                'late_fee' => 0,
                'remaining_balance' => round(max(0, $remainingBalanceAfterPayment), 2),
                'due_date' => $dueDate->format('Y-m-d'),
                'original_due_date' => $dueDate->format('Y-m-d'),
                'payment_frequency' => $repaymentFrequency,
                'days_overdue' => 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $schedule[] = $installment;
        }
        
        return $schedule;
    }
    
    /**
     * Create all repayment records in database
     */
    public function createRepaymentRecords(LoanApplication $loan): int
    {
        // Delete existing repayments if any
        Repayment::where('loan_application_id', $loan->id)->delete();
        
        // Generate new schedule
        $schedule = $this->generateRepaymentSchedule($loan);
        
        // Bulk insert for better performance
        Repayment::insert($schedule);
        
        return count($schedule);
    }
    
    /**
     * Calculate due date based on repayment frequency
     */
    private function calculateDueDate(Carbon $startDate, int $installmentNumber, string $frequency): Carbon
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
     * Get payment frequency from repayment schedule
     */
    private function getPaymentFrequency(?string $repaymentSchedule): string
    {
        return match($repaymentSchedule) {
            'weekly' => 'weekly',
            'bi_weekly' => 'bi_weekly',
            'monthly' => 'monthly',
            'quarterly' => 'quarterly',
            default => 'monthly'
        };
    }
    
    /**
     * Calculate loan summary
     */
    public function calculateLoanSummary(LoanApplication $loan): array
    {
        // Load loan product to get settings
        $loan->load('loanProduct');
        $loanProduct = $loan->loanProduct;
        
        if (!$loanProduct) {
            throw new \Exception('Loan product not found for loan application');
        }
        
        $principal = $loan->amount;
        $annualRate = ($loan->applied_interest_rate ?? $loanProduct->interest_rate) / 100;
        $termMonths = $loan->repayment_term ?? 12;
        $interestMethod = $loanProduct->interest_calculation_method ?? 'compound';
        
        if ($interestMethod === 'simple') {
            $totalInterest = $principal * $annualRate * ($termMonths / 12);
            $totalAmount = $principal + $totalInterest;
            $monthlyPayment = $totalAmount / $termMonths;
        } else {
            $monthlyRate = $annualRate / 12;
            $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $termMonths)) / (pow(1 + $monthlyRate, $termMonths) - 1);
            $totalAmount = $monthlyPayment * $termMonths;
            $totalInterest = $totalAmount - $principal;
        }
        
        return [
            'principal' => $principal,
            'total_interest' => round($totalInterest, 2),
            'total_amount' => round($totalAmount, 2),
            'monthly_payment' => round($monthlyPayment, 2),
            'interest_method' => $interestMethod,
            'annual_rate' => $annualRate * 100,
            'term_months' => $termMonths,
            'loan_product' => $loanProduct->name,
            'processing_fee' => round(($principal * $loanProduct->processing_fee_percent) / 100, 2),
            'late_payment_fee_percent' => $loanProduct->late_payment_fee_percent,
            'insurance_premium' => round(($principal * $loanProduct->insurance_premium_percent) / 100, 2),
        ];
    }
    
    /**
     * Regenerate schedule if loan terms change
     */
    public function regenerateSchedule(LoanApplication $loan): int
    {
        return $this->createRepaymentRecords($loan);
    }
}