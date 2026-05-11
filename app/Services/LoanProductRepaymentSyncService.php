<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\Repayment;
use App\Models\LoanProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanProductRepaymentSyncService
{
    /**
     * Sync loan repayments with loan product details
     */
    public static function syncRepaymentWithProduct(LoanApplication $loan)
    {
        if (!$loan->product) {
            throw new \Exception("Loan application must have an associated product");
        }

        DB::transaction(function () use ($loan) {
            // Get product details
            $product = $loan->product;
            
            // Update loan application with product details
            self::updateLoanFromProduct($loan, $product);
            
            // Regenerate repayment schedule based on product
            self::regenerateRepaymentSchedule($loan, $product);
            
            // Update loan totals
            self::updateLoanTotals($loan, $product);
        });
    }
    
    /**
     * Update loan application fields from product
     */
    private static function updateLoanFromProduct(LoanApplication $loan, LoanProduct $product)
    {
        $loan->update([
            'applied_interest_rate' => $product->interest_rate,
            'processing_fee' => ($loan->amount * $product->processing_fee_percent / 100),
            'insurance_fee' => ($loan->amount * $product->insurance_premium_percent / 100),
        ]);
    }
    
    /**
     * Regenerate repayment schedule based on product details
     */
    private static function regenerateRepaymentSchedule(LoanApplication $loan, LoanProduct $product)
    {
        // Delete existing repayments
        $loan->repayments()->delete();
        
        $principal = $loan->amount;
        $annualRate = $product->interest_rate;
        $termMonths = $loan->term_months;
        $frequency = $loan->repayment_schedule ?? 'monthly';
        
        // Calculate payment details based on frequency
        $paymentData = self::calculatePaymentSchedule($principal, $annualRate, $termMonths, $frequency);
        
        // Create repayment records
        $startDate = Carbon::parse($loan->disbursement_date ?? now());
        $balance = $principal;
        
        foreach ($paymentData as $index => $payment) {
            $dueDate = self::calculateDueDate($startDate, $frequency, $index + 1);
            
            Repayment::create([
                'loan_application_id' => $loan->id,
                'installment_number' => $index + 1,
                'amount' => $payment['total_payment'],
                'principal_amount' => $payment['principal'],
                'interest_amount' => $payment['interest'],
                'remaining_balance' => $payment['balance'],
                'due_date' => $dueDate,
                'payment_frequency' => $frequency,
                'status' => 'pending'
            ]);
        }
    }
    
    /**
     * Calculate payment schedule based on frequency
     */
    private static function calculatePaymentSchedule($principal, $annualRate, $termMonths, $frequency)
    {
        $paymentsPerYear = self::getPaymentsPerYear($frequency);
        $totalPayments = self::getTotalPayments($termMonths, $frequency);
        $periodRate = ($annualRate / 100) / $paymentsPerYear;
        
        // Calculate payment amount
        if ($periodRate == 0) {
            $paymentAmount = $principal / $totalPayments;
        } else {
            $paymentAmount = $principal * ($periodRate * pow(1 + $periodRate, $totalPayments)) / 
                           (pow(1 + $periodRate, $totalPayments) - 1);
        }
        
        $schedule = [];
        $balance = $principal;
        
        for ($i = 0; $i < $totalPayments; $i++) {
            $interestAmount = $balance * $periodRate;
            $principalAmount = $paymentAmount - $interestAmount;
            
            // Adjust final payment
            if ($i == $totalPayments - 1) {
                $principalAmount = $balance;
                $paymentAmount = $principalAmount + $interestAmount;
            }
            
            $balance -= $principalAmount;
            
            $schedule[] = [
                'total_payment' => round($paymentAmount, 2),
                'principal' => round($principalAmount, 2),
                'interest' => round($interestAmount, 2),
                'balance' => round(max(0, $balance), 2)
            ];
        }
        
        return $schedule;
    }
    
    /**
     * Update loan totals based on product fees
     */
    private static function updateLoanTotals(LoanApplication $loan, LoanProduct $product)
    {
        $repayments = $loan->repayments;
        
        $totalInterest = $repayments->sum('interest_amount');
        $processingFee = $loan->amount * $product->processing_fee_percent / 100;
        $insuranceFee = $loan->amount * $product->insurance_premium_percent / 100;
        $totalRepayable = $repayments->sum('amount') + $processingFee + $insuranceFee;
        
        $loan->update([
            'total_interest' => $totalInterest,
            'processing_fee' => $processingFee,
            'insurance_fee' => $insuranceFee,
            'total_repayable' => $totalRepayable,
            'outstanding_balance' => $totalRepayable
        ]);
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
     * Calculate due date based on frequency
     */
    private static function calculateDueDate($startDate, $frequency, $installmentNumber)
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
     * Update repayment when product details change
     */
    public static function updateRepaymentForProductChange(LoanProduct $product)
    {
        $activeLoans = $product->loanApplications()
            ->whereIn('status', ['approved', 'active'])
            ->get();
        
        foreach ($activeLoans as $loan) {
            self::syncRepaymentWithProduct($loan);
        }
        
        return $activeLoans->count();
    }
    
    /**
     * Calculate late fee based on product settings
     */
    public static function calculateLateFee(Repayment $repayment, $daysOverdue)
    {
        $product = $repayment->loanApplication->product;
        
        if (!$product || $daysOverdue <= 0) {
            return 0;
        }
        
        $lateFeePercent = $product->late_payment_fee_percent ?? 0;
        return ($repayment->amount * $lateFeePercent / 100);
    }
    
    /**
     * Validate loan against product constraints
     */
    public static function validateLoanAgainstProduct(LoanApplication $loan)
    {
        $product = $loan->product;
        $errors = [];
        
        if (!$product) {
            $errors[] = 'No loan product associated';
            return $errors;
        }
        
        // Check amount limits
        if ($loan->amount < $product->min_amount) {
            $errors[] = "Amount below minimum: {$product->min_amount}";
        }
        
        if ($loan->amount > $product->max_amount) {
            $errors[] = "Amount above maximum: {$product->max_amount}";
        }
        
        // Check term limits
        if ($loan->term_months < $product->min_term) {
            $errors[] = "Term below minimum: {$product->min_term} months";
        }
        
        if ($loan->term_months > $product->max_term) {
            $errors[] = "Term above maximum: {$product->max_term} months";
        }
        
        // Check if product is active
        if (!$product->is_active) {
            $errors[] = 'Loan product is not active';
        }
        
        return $errors;
    }
    
    /**
     * Get loan summary with product details
     */
    public static function getLoanProductSummary(LoanApplication $loan)
    {
        $product = $loan->product;
        
        if (!$product) {
            return null;
        }
        
        return [
            'product_name' => $product->name,
            'provider' => $product->provider_company,
            'interest_rate' => $product->interest_rate,
            'processing_fee_percent' => $product->processing_fee_percent,
            'insurance_premium_percent' => $product->insurance_premium_percent,
            'late_payment_fee_percent' => $product->late_payment_fee_percent,
            'calculated_fees' => [
                'processing_fee' => $loan->amount * $product->processing_fee_percent / 100,
                'insurance_fee' => $loan->amount * $product->insurance_premium_percent / 100,
            ],
            'repayment_summary' => [
                'frequency' => $loan->repayment_schedule,
                'total_payments' => $loan->repayments->count(),
                'monthly_payment' => $loan->repayments->first()?->amount ?? 0,
                'total_interest' => $loan->repayments->sum('interest_amount'),
            ]
        ];
    }
}