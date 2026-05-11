<?php

namespace App\Services;

use App\Models\CreditScore;
use App\Models\LoanApplication;
use App\Models\User;

class CreditScoringService
{
    // Credit score ranges: 0-1000
    const EXCELLENT_SCORE = 800;
    const GOOD_SCORE = 700;
    const FAIR_SCORE = 600;
    const POOR_SCORE = 0;

    /**
     * Calculate credit score for a user
     */
    public static function calculateCreditScore(User $user)
    {
        $score = 500; // Base score
        
        // Get user's loan history
        $loans = LoanApplication::where('user_id', $user->id)->get();
        
        if ($loans->count() == 0) {
            return self::createCreditScore($user, $score, 'new_customer', 'low');
        }

        $completedLoans = $loans->where('status', 'completed')->count();
        $totalLoans = $loans->count();
        $defaultedLoans = $loans->where('status', 'defaulted')->count();
        
        // Calculate metrics
        $completionRate = ($totalLoans > 0) ? ($completedLoans / $totalLoans) * 100 : 0;
        $defaultRate = ($totalLoans > 0) ? ($defaultedLoans / $totalLoans) * 100 : 0;
        
        // Adjust score based on completion rate (max +300)
        $score += ($completionRate / 100) * 300;
        
        // Penalize for defaults (max -200)
        $score -= ($defaultRate / 100) * 200;
        
        // Bonus for timely payments
        $onTimePayments = self::countOnTimePayments($user);
        $score += min(($onTimePayments * 5), 150);
        
        // Penalize for overdue payments
        $overduePayments = self::countOverduePayments($user);
        $score -= min(($overduePayments * 20), 150);
        
        // Ensure score is within bounds
        $score = max(0, min(1000, $score));
        
        // Determine risk level
        $riskLevel = self::determineRiskLevel($score);
        
        return self::createCreditScore($user, $score, 'calculated', $riskLevel);
    }

    /**
     * Count on-time payments
     */
    private static function countOnTimePayments(User $user)
    {
        $loans = LoanApplication::where('user_id', $user->id)->get();
        $onTimeCount = 0;
        
        foreach ($loans as $loan) {
            $onTimeCount += $loan->repayments()
                ->where('status', 'completed')
                ->where('paid_date', '<=', \DB::raw('due_date'))
                ->count();
        }
        
        return $onTimeCount;
    }

    /**
     * Count overdue payments
     */
    private static function countOverduePayments(User $user)
    {
        $loans = LoanApplication::where('user_id', $user->id)->get();
        $overdueCount = 0;
        
        foreach ($loans as $loan) {
            $overdueCount += $loan->repayments()
                ->where('status', '!=', 'completed')
                ->where('due_date', '<', now())
                ->count();
        }
        
        return $overdueCount;
    }

    /**
     * Determine risk level based on score
     */
    public static function determineRiskLevel($score)
    {
        if ($score >= self::EXCELLENT_SCORE) {
            return 'low';
        } elseif ($score >= self::GOOD_SCORE) {
            return 'low';
        } elseif ($score >= self::FAIR_SCORE) {
            return 'medium';
        }
        return 'high';
    }

    /**
     * Create or update credit score
     */
    private static function createCreditScore(User $user, $score, $method = 'calculated', $riskLevel = 'medium')
    {
        $loans = LoanApplication::where('user_id', $user->id)->get();
        
        return CreditScore::updateOrCreate(
            ['user_id' => $user->id],
            [
                'score' => $score,
                'score_date' => now()->date,
                'credit_history_months' => $user->created_at->diffInMonths(now()),
                'total_loans' => $loans->count(),
                'completed_loans' => $loans->where('status', 'completed')->count(),
                'defaulted_loans' => $loans->where('status', 'defaulted')->count(),
                'default_rate' => $loans->count() > 0 ? 
                    ($loans->where('status', 'defaulted')->count() / $loans->count() * 100) : 0,
                'average_loan_amount' => $loans->avg('amount') ?? 0,
                'average_repayment_rate' => 100, // Placeholder
                'on_time_payment_rate' => self::countOnTimePayments($user) > 0 ? 100 : 0,
                'risk_level' => $riskLevel,
                'remarks' => "Credit score calculated via {$method} method",
            ]
        );
    }

    /**
     * Check if user is eligible for loan
     */
    public static function isEligibleForLoan(User $user, $loanAmount)
    {
        $creditScore = CreditScore::where('user_id', $user->id)->first();
        
        if (!$creditScore) {
            return false; // No credit history
        }
        
        // Reject if critical risk level
        if ($creditScore->risk_level === 'critical') {
            return false;
        }
        
        // Check debt-to-income ratio
        $monthlyIncome = $user->loanApplications()->latest()->first()->monthly_income ?? 0;
        $monthlyPayment = LoanCalculationService::calculateMonthlyPayment($loanAmount, 15, 12);
        $dtiRatio = LoanCalculationService::calculateDebtToIncomeRatio($monthlyPayment, $monthlyIncome);
        
        // DTI should not exceed 40%
        if ($dtiRatio > 40) {
            return false;
        }
        
        return true;
    }
}
