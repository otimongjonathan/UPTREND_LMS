<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\Repayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ComprehensiveRepaymentDynamicsService
{
    /**
     * 1. PREPAYMENT SCENARIOS
     */
    
    /**
     * Full Prepayment - Pay entire loan balance early
     */
    public static function handleFullPrepayment(LoanApplication $loan, $paymentAmount, $paymentDate, $prepaymentPenalty = 0)
    {
        DB::transaction(function () use ($loan, $paymentAmount, $paymentDate, $prepaymentPenalty) {
            // Calculate unearned interest discount
            $unearnedInterest = self::calculateUnearnedInterest($loan);
            $prepaymentDiscount = $unearnedInterest * 0.3; // 30% discount on unearned interest
            
            $actualPayoffAmount = $loan->outstanding_balance - $prepaymentDiscount + $prepaymentPenalty;
            
            // Mark all future payments as prepaid
            $loan->repayments()->where('status', 'pending')->update([
                'status' => 'prepaid',
                'paid_date' => $paymentDate,
                'paid_amount' => DB::raw('amount'),
                'payment_method' => 'full_prepayment',
                'notes' => 'Paid as part of full loan prepayment'
            ]);
            
            $loan->update([
                'status' => 'prepaid',
                'completion_date' => $paymentDate,
                'prepayment_penalty' => $prepaymentPenalty,
                'unearned_interest_discount' => $prepaymentDiscount,
                'actual_payoff_amount' => $actualPayoffAmount,
                'outstanding_balance' => 0
            ]);
            
            self::createAdjustmentRecord($loan, 'full_prepayment', $prepaymentDiscount, 'Full loan prepayment with unearned interest discount');
        });
    }
    
    /**
     * Partial Prepayment - Pay down principal early
     */
    public static function handlePartialPrepayment(LoanApplication $loan, $principalAmount, $paymentDate, $recalculateSchedule = true)
    {
        DB::transaction(function () use ($loan, $principalAmount, $paymentDate, $recalculateSchedule) {
            // Apply prepayment to principal
            $loan->update([
                'outstanding_balance' => $loan->outstanding_balance - $principalAmount,
                'total_prepayments' => ($loan->total_prepayments ?? 0) + $principalAmount
            ]);
            
            if ($recalculateSchedule) {
                // Option 1: Reduce payment amounts (keep same term)
                self::recalculatePaymentsKeepTerm($loan);
            } else {
                // Option 2: Reduce term (keep same payment amounts)
                self::recalculateTermKeepPayments($loan);
            }
            
            // Create prepayment record
            Repayment::create([
                'loan_application_id' => $loan->id,
                'installment_number' => 9000, // Special number for prepayments
                'amount' => $principalAmount,
                'principal_amount' => $principalAmount,
                'interest_amount' => 0,
                'due_date' => $paymentDate,
                'paid_date' => $paymentDate,
                'paid_amount' => $principalAmount,
                'status' => 'completed',
                'payment_method' => 'partial_prepayment',
                'notes' => 'Principal prepayment'
            ]);
            
            self::createAdjustmentRecord($loan, 'partial_prepayment', $principalAmount, 'Partial prepayment applied to principal');
        });
    }
    
    /**
     * 2. PAYMENT HOLIDAY / MORATORIUM SCENARIOS
     */
    
    /**
     * Payment Holiday - Temporary suspension of payments
     */
    public static function grantPaymentHoliday(LoanApplication $loan, $holidayMonths, $startDate, $interestTreatment = 'capitalize')
    {
        DB::transaction(function () use ($loan, $holidayMonths, $startDate, $interestTreatment) {
            $holidayStart = Carbon::parse($startDate);
            $holidayEnd = $holidayStart->copy()->addMonths($holidayMonths);
            
            // Mark affected payments as on holiday
            $affectedPayments = $loan->repayments()
                ->where('status', 'pending')
                ->whereBetween('due_date', [$holidayStart, $holidayEnd])
                ->get();
            
            $totalInterestDuringHoliday = $affectedPayments->sum('interest_amount');
            
            foreach ($affectedPayments as $payment) {
                $payment->update([
                    'status' => 'holiday',
                    'original_due_date' => $payment->due_date,
                    'due_date' => $holidayEnd->copy()->addMonths($payment->installment_number - $affectedPayments->first()->installment_number + 1),
                    'notes' => "Payment holiday from {$holidayStart->format('M Y')} to {$holidayEnd->format('M Y')}"
                ]);
            }
            
            // Handle interest during holiday
            if ($interestTreatment === 'capitalize') {
                $loan->update([
                    'outstanding_balance' => $loan->outstanding_balance + $totalInterestDuringHoliday,
                    'capitalized_interest' => ($loan->capitalized_interest ?? 0) + $totalInterestDuringHoliday
                ]);
            } elseif ($interestTreatment === 'waive') {
                $loan->update([
                    'waived_interest' => ($loan->waived_interest ?? 0) + $totalInterestDuringHoliday
                ]);
            }
            
            $loan->update([
                'payment_holiday_start' => $holidayStart,
                'payment_holiday_end' => $holidayEnd,
                'payment_holiday_months' => $holidayMonths,
                'holiday_interest_treatment' => $interestTreatment
            ]);
            
            self::createAdjustmentRecord($loan, 'payment_holiday', $totalInterestDuringHoliday, "Payment holiday granted for {$holidayMonths} months");
        });
    }
    
    /**
     * 3. REFINANCING SCENARIOS
     */
    
    /**
     * Rate Refinancing - Change interest rate mid-term
     */
    public static function refinanceRate(LoanApplication $loan, $newRate, $effectiveDate, $refinancingFee = 0)
    {
        DB::transaction(function () use ($loan, $newRate, $effectiveDate, $refinancingFee) {
            $effectiveDate = Carbon::parse($effectiveDate);
            
            // Mark existing schedule as refinanced
            $loan->repayments()->where('status', 'pending')->where('due_date', '>=', $effectiveDate)->update([
                'status' => 'refinanced',
                'notes' => 'Original schedule before rate refinancing'
            ]);
            
            // Calculate new payment schedule with new rate
            $remainingBalance = $loan->outstanding_balance + $refinancingFee;
            $remainingPayments = $loan->repayments()->where('status', 'pending')->count();
            
            $newPaymentAmount = self::calculatePaymentWithNewRate($remainingBalance, $newRate, $remainingPayments);
            
            // Create new payment schedule
            $startDate = $effectiveDate;
            for ($i = 1; $i <= $remainingPayments; $i++) {
                $dueDate = self::getNextPaymentDate($startDate, $loan->repayment_schedule, $i);
                $interestAmount = ($remainingBalance * ($newRate / 100)) / 12;
                $principalAmount = $newPaymentAmount - $interestAmount;
                $remainingBalance -= $principalAmount;
                
                Repayment::create([
                    'loan_application_id' => $loan->id,
                    'installment_number' => 4000 + $i, // Refinanced payments
                    'amount' => $newPaymentAmount,
                    'principal_amount' => $principalAmount,
                    'interest_amount' => $interestAmount,
                    'due_date' => $dueDate,
                    'remaining_balance' => max(0, $remainingBalance),
                    'status' => 'pending',
                    'notes' => "Refinanced at {$newRate}% rate"
                ]);
            }
            
            $loan->update([
                'applied_interest_rate' => $newRate,
                'refinancing_date' => $effectiveDate,
                'refinancing_fee' => $refinancingFee,
                'refinancing_count' => ($loan->refinancing_count ?? 0) + 1
            ]);
            
            self::createAdjustmentRecord($loan, 'rate_refinancing', $refinancingFee, "Rate refinanced to {$newRate}%");
        });
    }
    
    /**
     * 4. FORBEARANCE SCENARIOS
     */
    
    /**
     * Temporary Forbearance - Reduced payments for hardship
     */
    public static function grantForbearance(LoanApplication $loan, $forbearanceMonths, $reducedPaymentAmount, $startDate)
    {
        DB::transaction(function () use ($loan, $forbearanceMonths, $reducedPaymentAmount, $startDate) {
            $forbearanceStart = Carbon::parse($startDate);
            $forbearanceEnd = $forbearanceStart->copy()->addMonths($forbearanceMonths);
            
            // Identify affected payments
            $affectedPayments = $loan->repayments()
                ->where('status', 'pending')
                ->whereBetween('due_date', [$forbearanceStart, $forbearanceEnd])
                ->get();
            
            $totalShortfall = 0;
            
            foreach ($affectedPayments as $payment) {
                $shortfall = $payment->amount - $reducedPaymentAmount;
                $totalShortfall += $shortfall;
                
                $payment->update([
                    'amount' => $reducedPaymentAmount,
                    'forbearance_shortfall' => $shortfall,
                    'status' => 'forbearance',
                    'notes' => "Forbearance - reduced payment from {$payment->amount} to {$reducedPaymentAmount}"
                ]);
            }
            
            // Add shortfall to loan balance or create catch-up payments
            $loan->update([
                'outstanding_balance' => $loan->outstanding_balance + $totalShortfall,
                'forbearance_amount' => ($loan->forbearance_amount ?? 0) + $totalShortfall,
                'forbearance_start' => $forbearanceStart,
                'forbearance_end' => $forbearanceEnd
            ]);
            
            self::createAdjustmentRecord($loan, 'forbearance', $totalShortfall, "Forbearance granted for {$forbearanceMonths} months");
        });
    }
    
    /**
     * 5. BALLOON PAYMENT SCENARIOS
     */
    
    /**
     * Convert to Balloon Payment Structure
     */
    public static function convertToBalloonPayment(LoanApplication $loan, $balloonAmount, $balloonDate)
    {
        DB::transaction(function () use ($loan, $balloonAmount, $balloonDate) {
            $balloonDate = Carbon::parse($balloonDate);
            
            // Reduce regular payment amounts
            $regularPayments = $loan->repayments()->where('status', 'pending')->where('due_date', '<', $balloonDate)->get();
            $totalRegularAmount = $loan->outstanding_balance - $balloonAmount;
            $newRegularPayment = $totalRegularAmount / $regularPayments->count();
            
            foreach ($regularPayments as $payment) {
                $payment->update([
                    'amount' => $newRegularPayment,
                    'principal_amount' => $newRegularPayment * 0.8, // Mostly principal
                    'interest_amount' => $newRegularPayment * 0.2,
                    'notes' => 'Reduced payment due to balloon structure'
                ]);
            }
            
            // Create balloon payment
            Repayment::create([
                'loan_application_id' => $loan->id,
                'installment_number' => 8000, // Special number for balloon
                'amount' => $balloonAmount,
                'principal_amount' => $balloonAmount,
                'interest_amount' => 0,
                'due_date' => $balloonDate,
                'remaining_balance' => 0,
                'status' => 'pending',
                'payment_type' => 'balloon',
                'notes' => 'Balloon payment - final loan balance'
            ]);
            
            $loan->update([
                'balloon_payment_amount' => $balloonAmount,
                'balloon_payment_date' => $balloonDate,
                'loan_structure' => 'balloon'
            ]);
            
            self::createAdjustmentRecord($loan, 'balloon_conversion', $balloonAmount, 'Converted to balloon payment structure');
        });
    }
    
    /**
     * 6. INTEREST-ONLY PERIODS
     */
    
    /**
     * Interest-Only Payment Period
     */
    public static function setInterestOnlyPeriod(LoanApplication $loan, $interestOnlyMonths, $startDate)
    {
        DB::transaction(function () use ($loan, $interestOnlyMonths, $startDate) {
            $interestOnlyStart = Carbon::parse($startDate);
            $interestOnlyEnd = $interestOnlyStart->copy()->addMonths($interestOnlyMonths);
            
            // Convert affected payments to interest-only
            $affectedPayments = $loan->repayments()
                ->where('status', 'pending')
                ->whereBetween('due_date', [$interestOnlyStart, $interestOnlyEnd])
                ->get();
            
            $deferredPrincipal = 0;
            
            foreach ($affectedPayments as $payment) {
                $deferredPrincipal += $payment->principal_amount;
                
                $payment->update([
                    'amount' => $payment->interest_amount,
                    'deferred_principal' => $payment->principal_amount,
                    'principal_amount' => 0,
                    'status' => 'interest_only',
                    'notes' => 'Interest-only payment period'
                ]);
            }
            
            // Redistribute deferred principal to remaining payments
            $remainingPayments = $loan->repayments()
                ->where('status', 'pending')
                ->where('due_date', '>', $interestOnlyEnd)
                ->get();
            
            $additionalPrincipal = $deferredPrincipal / $remainingPayments->count();
            
            foreach ($remainingPayments as $payment) {
                $payment->update([
                    'amount' => $payment->amount + $additionalPrincipal,
                    'principal_amount' => $payment->principal_amount + $additionalPrincipal
                ]);
            }
            
            $loan->update([
                'interest_only_start' => $interestOnlyStart,
                'interest_only_end' => $interestOnlyEnd,
                'interest_only_months' => $interestOnlyMonths,
                'deferred_principal' => $deferredPrincipal
            ]);
            
            self::createAdjustmentRecord($loan, 'interest_only_period', $deferredPrincipal, "Interest-only period for {$interestOnlyMonths} months");
        });
    }
    
    /**
     * 7. GRADUATED PAYMENT SCENARIOS
     */
    
    /**
     * Graduated Payment Mortgage (GPM) - Payments increase over time
     */
    public static function convertToGraduatedPayments(LoanApplication $loan, $graduationRate, $graduationPeriods)
    {
        DB::transaction(function () use ($loan, $graduationRate, $graduationPeriods) {
            $pendingPayments = $loan->repayments()->where('status', 'pending')->orderBy('due_date')->get();
            $paymentsPerPeriod = ceil($pendingPayments->count() / $graduationPeriods);
            
            $basePayment = $pendingPayments->first()->amount * 0.8; // Start 20% lower
            $currentPayment = $basePayment;
            
            foreach ($pendingPayments->chunk($paymentsPerPeriod) as $periodIndex => $periodPayments) {
                foreach ($periodPayments as $payment) {
                    $payment->update([
                        'amount' => $currentPayment,
                        'graduation_period' => $periodIndex + 1,
                        'notes' => "Graduated payment - Period " . ($periodIndex + 1)
                    ]);
                }
                
                // Increase payment for next period
                $currentPayment *= (1 + $graduationRate / 100);
            }
            
            $loan->update([
                'payment_structure' => 'graduated',
                'graduation_rate' => $graduationRate,
                'graduation_periods' => $graduationPeriods
            ]);
            
            self::createAdjustmentRecord($loan, 'graduated_payments', 0, "Converted to graduated payment structure with {$graduationRate}% increases");
        });
    }
    
    /**
     * 8. SKIP PAYMENT SCENARIOS
     */
    
    /**
     * Skip Payment Option - Skip specific payments
     */
    public static function allowSkipPayment(LoanApplication $loan, $skipPaymentIds, $skipFee = 0)
    {
        DB::transaction(function () use ($loan, $skipPaymentIds, $skipFee) {
            $totalSkippedAmount = 0;
            
            foreach ($skipPaymentIds as $paymentId) {
                $payment = Repayment::find($paymentId);
                if ($payment && $payment->status === 'pending') {
                    $totalSkippedAmount += $payment->amount;
                    
                    $payment->update([
                        'status' => 'skipped',
                        'skip_fee' => $skipFee,
                        'skip_date' => now(),
                        'notes' => 'Payment skipped by customer request'
                    ]);
                }
            }
            
            // Add skipped amount to loan balance or extend term
            $loan->update([
                'outstanding_balance' => $loan->outstanding_balance + $totalSkippedAmount + ($skipFee * count($skipPaymentIds)),
                'total_skip_fees' => ($loan->total_skip_fees ?? 0) + ($skipFee * count($skipPaymentIds)),
                'skipped_payments_count' => ($loan->skipped_payments_count ?? 0) + count($skipPaymentIds)
            ]);
            
            self::createAdjustmentRecord($loan, 'skip_payments', $totalSkippedAmount, count($skipPaymentIds) . " payments skipped");
        });
    }
    
    /**
     * 9. PAYMENT FREQUENCY CHANGES
     */
    
    /**
     * Change Payment Frequency (e.g., monthly to bi-weekly)
     */
    public static function changePaymentFrequency(LoanApplication $loan, $newFrequency, $effectiveDate)
    {
        DB::transaction(function () use ($loan, $newFrequency, $effectiveDate) {
            $effectiveDate = Carbon::parse($effectiveDate);
            
            // Mark existing schedule as frequency changed
            $loan->repayments()->where('status', 'pending')->where('due_date', '>=', $effectiveDate)->update([
                'status' => 'frequency_changed',
                'notes' => "Original {$loan->repayment_schedule} schedule before frequency change"
            ]);
            
            // Calculate new schedule with new frequency
            $remainingBalance = $loan->outstanding_balance;
            $newPaymentsPerYear = self::getPaymentsPerYear($newFrequency);
            $oldPaymentsPerYear = self::getPaymentsPerYear($loan->repayment_schedule);
            
            // Adjust payment amount for new frequency
            $oldAnnualPayment = $loan->repayments()->where('status', 'pending')->first()->amount * $oldPaymentsPerYear;
            $newPaymentAmount = $oldAnnualPayment / $newPaymentsPerYear;
            
            // Generate new schedule
            $remainingMonths = $loan->repayments()->where('status', 'pending')->count() * (12 / $oldPaymentsPerYear);
            $newTotalPayments = ceil($remainingMonths * ($newPaymentsPerYear / 12));
            
            for ($i = 1; $i <= $newTotalPayments; $i++) {
                $dueDate = self::getNextPaymentDate($effectiveDate, $newFrequency, $i);
                
                Repayment::create([
                    'loan_application_id' => $loan->id,
                    'installment_number' => 5000 + $i, // Frequency changed payments
                    'amount' => $newPaymentAmount,
                    'principal_amount' => $newPaymentAmount * 0.8,
                    'interest_amount' => $newPaymentAmount * 0.2,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                    'payment_frequency' => $newFrequency,
                    'notes' => "New {$newFrequency} payment schedule"
                ]);
            }
            
            $loan->update([
                'repayment_schedule' => $newFrequency,
                'frequency_change_date' => $effectiveDate,
                'previous_frequency' => $loan->repayment_schedule
            ]);
            
            self::createAdjustmentRecord($loan, 'frequency_change', 0, "Payment frequency changed from {$loan->repayment_schedule} to {$newFrequency}");
        });
    }
    
    /**
     * 10. LOAN MODIFICATION SCENARIOS
     */
    
    /**
     * Comprehensive Loan Modification
     */
    public static function comprehensiveLoanModification(LoanApplication $loan, $modifications)
    {
        DB::transaction(function () use ($loan, $modifications) {
            $modificationSummary = [];
            
            // Rate modification
            if (isset($modifications['new_rate'])) {
                self::refinanceRate($loan, $modifications['new_rate'], $modifications['effective_date'] ?? now());
                $modificationSummary[] = "Rate changed to {$modifications['new_rate']}%";
            }
            
            // Term extension
            if (isset($modifications['new_term_months'])) {
                self::extendLoanTerm($loan, $modifications['new_term_months']);
                $modificationSummary[] = "Term extended to {$modifications['new_term_months']} months";
            }
            
            // Payment amount change
            if (isset($modifications['new_payment_amount'])) {
                self::changePaymentAmount($loan, $modifications['new_payment_amount']);
                $modificationSummary[] = "Payment amount changed to {$modifications['new_payment_amount']}";
            }
            
            // Principal reduction
            if (isset($modifications['principal_reduction'])) {
                $loan->update([
                    'outstanding_balance' => $loan->outstanding_balance - $modifications['principal_reduction'],
                    'principal_forgiveness' => ($loan->principal_forgiveness ?? 0) + $modifications['principal_reduction']
                ]);
                $modificationSummary[] = "Principal reduced by {$modifications['principal_reduction']}";
            }
            
            $loan->update([
                'modification_date' => now(),
                'modification_summary' => implode('; ', $modificationSummary),
                'modification_count' => ($loan->modification_count ?? 0) + 1
            ]);
            
            self::createAdjustmentRecord($loan, 'comprehensive_modification', 0, implode('; ', $modificationSummary));
        });
    }
    
    /**
     * HELPER METHODS
     */
    
    private static function calculateUnearnedInterest(LoanApplication $loan)
    {
        return $loan->repayments()->where('status', 'pending')->sum('interest_amount');
    }
    
    private static function recalculatePaymentsKeepTerm(LoanApplication $loan)
    {
        $pendingPayments = $loan->repayments()->where('status', 'pending')->get();
        $newPaymentAmount = $loan->outstanding_balance / $pendingPayments->count();
        
        foreach ($pendingPayments as $payment) {
            $payment->update(['amount' => $newPaymentAmount]);
        }
    }
    
    private static function recalculateTermKeepPayments(LoanApplication $loan)
    {
        $currentPayment = $loan->repayments()->where('status', 'pending')->first()->amount;
        $newTermPayments = ceil($loan->outstanding_balance / $currentPayment);
        
        // Remove excess payments or add new ones as needed
        $currentPayments = $loan->repayments()->where('status', 'pending')->count();
        
        if ($newTermPayments < $currentPayments) {
            $loan->repayments()->where('status', 'pending')
                ->orderByDesc('due_date')
                ->limit($currentPayments - $newTermPayments)
                ->delete();
        }
    }
    
    private static function calculatePaymentWithNewRate($balance, $rate, $payments)
    {
        $monthlyRate = $rate / 100 / 12;
        return $balance * ($monthlyRate * pow(1 + $monthlyRate, $payments)) / (pow(1 + $monthlyRate, $payments) - 1);
    }
    
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
    
    private static function extendLoanTerm(LoanApplication $loan, $newTermMonths)
    {
        // Implementation for term extension
        $loan->update(['actual_term_months' => $newTermMonths]);
    }
    
    private static function changePaymentAmount(LoanApplication $loan, $newAmount)
    {
        $loan->repayments()->where('status', 'pending')->update(['amount' => $newAmount]);
    }
    
    private static function createAdjustmentRecord(LoanApplication $loan, $type, $amount, $description)
    {
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
}