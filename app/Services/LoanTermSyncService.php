<?php

namespace App\Services;

use App\Models\LoanApplication;
use Carbon\Carbon;

class LoanTermSyncService
{
    /**
     * Regenerate loan terms based on payment frequency and due date
     */
    public static function regenerateTerms(LoanApplication $loan, $paymentFrequency, $firstDueDate = null)
    {
        $firstDueDate = $firstDueDate ? Carbon::parse($firstDueDate) : now()->addMonth();
        $disbursementDate = $loan->disbursement_date ? Carbon::parse($loan->disbursement_date) : now();
        
        // Calculate term in months based on frequency and due date
        $termMonths = self::calculateTermFromFrequency($paymentFrequency, $disbursementDate, $firstDueDate);
        
        // Update loan application with new terms
        $loan->update([
            'repayment_schedule' => $paymentFrequency,
            'term_months' => $termMonths,
            'actual_term_months' => $termMonths
        ]);
        
        // If loan is already approved/active, regenerate repayment schedule
        if (in_array($loan->status, ['approved', 'active'])) {
            self::regenerateRepaymentSchedule($loan, $firstDueDate);
        }
        
        return [
            'term_months' => $termMonths,
            'payment_frequency' => $paymentFrequency,
            'first_due_date' => $firstDueDate->format('Y-m-d'),
            'total_installments' => self::calculateInstallmentCount($paymentFrequency, $termMonths)
        ];
    }
    
    /**
     * Calculate term in months based on frequency and due date
     */
    private static function calculateTermFromFrequency($frequency, $startDate, $endDate)
    {
        $diffInDays = $startDate->diffInDays($endDate);
        
        return match($frequency) {
            'weekly' => max(1, round($diffInDays / 7 / 4.33)), // weeks to months
            'bi_weekly' => max(1, round($diffInDays / 14 / 2.17)), // bi-weeks to months
            'monthly' => max(1, $startDate->diffInMonths($endDate)),
            'quarterly' => max(1, round($startDate->diffInMonths($endDate) / 3)),
            default => 12
        };
    }
    
    /**
     * Calculate number of installments based on frequency and term
     */
    private static function calculateInstallmentCount($frequency, $termMonths)
    {
        return match($frequency) {
            'weekly' => $termMonths * 4, // 4 weeks per month
            'bi_weekly' => $termMonths * 2, // 2 bi-weeks per month
            'monthly' => $termMonths,
            'quarterly' => max(1, round($termMonths / 3)),
            default => $termMonths
        };
    }
    
    /**
     * Regenerate repayment schedule with new terms
     */
    private static function regenerateRepaymentSchedule(LoanApplication $loan, $firstDueDate)
    {
        // Delete existing pending repayments
        $loan->repayments()->where('status', 'pending')->delete();
        
        // Generate new schedule
        $installmentCount = self::calculateInstallmentCount($loan->repayment_schedule, $loan->term_months);
        $installmentAmount = $loan->amount / $installmentCount;
        
        $currentDueDate = $firstDueDate->copy();
        
        for ($i = 1; $i <= $installmentCount; $i++) {
            $loan->repayments()->create([
                'installment_number' => $i,
                'amount' => round($installmentAmount, 2),
                'principal_amount' => round($installmentAmount, 2),
                'interest_amount' => 0,
                'due_date' => $currentDueDate->copy(),
                'original_due_date' => $currentDueDate->copy(),
                'status' => 'pending',
                'payment_frequency' => $loan->repayment_schedule,
                'remaining_balance' => $loan->amount - ($installmentAmount * $i)
            ]);
            
            // Move to next due date based on frequency
            $currentDueDate = self::getNextDueDate($currentDueDate, $loan->repayment_schedule);
        }
    }
    
    /**
     * Get next due date based on payment frequency
     */
    private static function getNextDueDate(Carbon $currentDate, $frequency)
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
     * Calculate loan terms from due date and frequency
     */
    public static function calculateFromDueDate($amount, $frequency, $dueDate, $disbursementDate = null)
    {
        $disbursementDate = $disbursementDate ? Carbon::parse($disbursementDate) : now();
        $dueDate = Carbon::parse($dueDate);
        
        $termMonths = self::calculateTermFromFrequency($frequency, $disbursementDate, $dueDate);
        $installmentCount = self::calculateInstallmentCount($frequency, $termMonths);
        $installmentAmount = $amount / $installmentCount;
        
        return [
            'term_months' => $termMonths,
            'installment_count' => $installmentCount,
            'installment_amount' => round($installmentAmount, 2),
            'total_amount' => $amount,
            'first_due_date' => $dueDate->format('Y-m-d'),
            'final_due_date' => self::calculateFinalDueDate($dueDate, $frequency, $installmentCount)->format('Y-m-d')
        ];
    }
    
    /**
     * Calculate final due date
     */
    private static function calculateFinalDueDate(Carbon $firstDueDate, $frequency, $installmentCount)
    {
        $finalDate = $firstDueDate->copy();
        
        for ($i = 1; $i < $installmentCount; $i++) {
            $finalDate = self::getNextDueDate($finalDate, $frequency);
        }
        
        return $finalDate;
    }
    
    /**
     * Validate frequency and due date combination
     */
    public static function validateTerms($frequency, $dueDate, $disbursementDate = null)
    {
        $disbursementDate = $disbursementDate ? Carbon::parse($disbursementDate) : now();
        $dueDate = Carbon::parse($dueDate);
        
        if ($dueDate->lte($disbursementDate)) {
            return ['valid' => false, 'message' => 'Due date must be after disbursement date'];
        }
        
        $minDays = match($frequency) {
            'weekly' => 7,
            'bi_weekly' => 14,
            'monthly' => 30,
            'quarterly' => 90,
            default => 30
        };
        
        if ($disbursementDate->diffInDays($dueDate) < $minDays) {
            return ['valid' => false, 'message' => "Due date must be at least {$minDays} days from disbursement for {$frequency} payments"];
        }
        
        return ['valid' => true, 'message' => 'Terms are valid'];
    }
}