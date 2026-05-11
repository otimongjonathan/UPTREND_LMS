<?php

namespace App\Services;

class LoanCalculationService
{
    /**
     * Calculate monthly payment amount
     * Uses: Principal, Interest Rate, Term in months
     */
    public static function calculateMonthlyPayment($principal, $annualRate, $months)
    {
        if ($months == 0 || $annualRate == 0) {
            return $principal / ($months ?: 1);
        }

        $monthlyRate = $annualRate / 100 / 12;
        $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $months)) / 
                         (pow(1 + $monthlyRate, $months) - 1);
        
        return round($monthlyPayment, 2);
    }

    /**
     * Calculate total interest amount
     */
    public static function calculateTotalInterest($principal, $monthlyPayment, $months)
    {
        return round(($monthlyPayment * $months) - $principal, 2);
    }

    /**
     * Calculate processing fee
     */
    public static function calculateProcessingFee($principal, $feePercent)
    {
        return round($principal * ($feePercent / 100), 2);
    }

    /**
     * Calculate late payment fee
     */
    public static function calculateLateFee($monthlyPayment, $daysOverdue, $feePercent)
    {
        if ($daysOverdue <= 0) {
            return 0;
        }
        
        return round($monthlyPayment * ($feePercent / 100), 2);
    }

    /**
     * Calculate insurance premium
     */
    public static function calculateInsurancePremium($principal, $premiumPercent)
    {
        return round($principal * ($premiumPercent / 100), 2);
    }

    /**
     * Calculate total loan cost
     */
    public static function calculateTotalLoanCost($principal, $monthlyPayment, $months, $processingFee = 0, $insurance = 0)
    {
        $totalInterest = self::calculateTotalInterest($principal, $monthlyPayment, $months);
        return round($principal + $totalInterest + $processingFee + $insurance, 2);
    }

    /**
     * Calculate amortization schedule
     */
    public static function generateAmortizationSchedule($principal, $monthlyPayment, $annualRate, $months)
    {
        $schedule = [];
        $monthlyRate = $annualRate / 100 / 12;
        $balance = $principal;
        
        for ($i = 1; $i <= $months; $i++) {
            $interest = round($balance * $monthlyRate, 2);
            $principalPayment = round($monthlyPayment - $interest, 2);
            $balance = round($balance - $principalPayment, 2);
            
            $schedule[] = [
                'month' => $i,
                'beginning_balance' => $balance + $principalPayment,
                'payment' => $monthlyPayment,
                'principal' => $principalPayment,
                'interest' => $interest,
                'ending_balance' => max(0, $balance),
            ];
        }
        
        return $schedule;
    }

    /**
     * Calculate debt-to-income ratio
     */
    public static function calculateDebtToIncomeRatio($monthlyPayment, $monthlyIncome)
    {
        if ($monthlyIncome == 0) {
            return 0;
        }
        
        return round(($monthlyPayment / $monthlyIncome) * 100, 2);
    }
}
