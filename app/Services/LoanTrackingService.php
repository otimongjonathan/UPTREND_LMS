<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\Repayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class LoanTrackingService
{
    /**
     * Get all active loans with repayment tracking
     */
    public static function getActiveLoanTracking(int $providerId)
    {
        return LoanApplication::with(['user', 'product', 'repayments'])
            ->whereHas('product', function ($query) use ($providerId) {
                $query->where('provider_id', $providerId);
            })
            ->where('status', 'active')
            ->get()
            ->map(function ($loan) {
                return self::enrichLoanWithTracking($loan);
            });
    }

    /**
     * Get loan with detailed tracking information
     */
    public static function getLoanTracking(LoanApplication $loan): array
    {
        $loan->load(['user', 'product', 'repayments']);
        
        $repayments = $loan->repayments()->orderBy('installment_number')->get();
        
        $totalInstallments = $repayments->count();
        $paidInstallments = $repayments->where('status', 'completed')->count();
        $pendingInstallments = $repayments->where('status', 'pending')->count();
        $partialInstallments = $repayments->where('status', 'partial')->count();
        $overdueInstallments = $repayments->where('status', 'pending')
            ->filter(fn($r) => Carbon::parse($r->due_date)->isPast())
            ->count();

        $totalAmount = $loan->total_repayable ?? 0;
        $totalPaid = $repayments->where('status', 'completed')->sum('paid_amount') +
                     $repayments->where('status', 'partial')->sum('paid_amount');
        $totalDue = $totalAmount - $totalPaid;
        
        // Get next payment due
        $nextPayment = $repayments->where('status', '!=', 'completed')
            ->sortBy('due_date')
            ->first();

        return [
            'loan' => $loan,
            'repayments' => $repayments,
            'progress' => [
                'total_installments' => $totalInstallments,
                'paid_installments' => $paidInstallments,
                'pending_installments' => $pendingInstallments,
                'partial_installments' => $partialInstallments,
                'overdue_installments' => $overdueInstallments,
                'completion_percentage' => $totalInstallments > 0 ? round(($paidInstallments / $totalInstallments) * 100, 2) : 0,
            ],
            'amounts' => [
                'total_repayable' => $totalAmount,
                'total_paid' => $totalPaid,
                'total_due' => $totalDue,
                'outstanding_balance' => $totalDue,
            ],
            'next_payment' => $nextPayment ? [
                'installment_number' => $nextPayment->installment_number,
                'amount' => $nextPayment->amount,
                'due_date' => $nextPayment->due_date->format('Y-m-d'),
                'days_until_due' => max(0, Carbon::parse($nextPayment->due_date)->diffInDays(now())),
                'is_overdue' => Carbon::parse($nextPayment->due_date)->isPast(),
                'days_overdue' => Carbon::parse($nextPayment->due_date)->isPast() 
                    ? Carbon::parse($nextPayment->due_date)->diffInDays(now()) 
                    : 0,
            ] : null,
            'status_timeline' => self::getStatusTimeline($loan),
        ];
    }

    /**
     * Enrich loan with tracking summary
     */
    private static function enrichLoanWithTracking(LoanApplication $loan): array
    {
        $repayments = $loan->repayments;
        $paidCount = $repayments->where('status', 'completed')->count();
        $totalCount = $repayments->count();
        
        return [
            'loan_id' => $loan->id,
            'customer_name' => $loan->user->name,
            'customer_phone' => $loan->user->tel_no,
            'amount_disbursed' => $loan->disbursed_amount ?? $loan->amount,
            'total_repayable' => $loan->total_repayable,
            'loan_status' => $loan->status,
            'disbursement_date' => $loan->disbursement_date,
            'installment_progress' => "$paidCount / $totalCount",
            'completion_percentage' => $totalCount > 0 ? round(($paidCount / $totalCount) * 100, 2) : 0,
            'next_payment' => $repayments->where('status', '!=', 'completed')->sortBy('due_date')->first(),
            'overdue_count' => $repayments->where('status', 'pending')
                ->filter(fn($r) => Carbon::parse($r->due_date)->isPast())
                ->count(),
        ];
    }

    /**
     * Get repayment status timeline
     */
    private static function getStatusTimeline(LoanApplication $loan): array
    {
        $timeline = [
            'application_date' => $loan->application_date,
            'approval_date' => $loan->updated_at, // Estimated
            'disbursement_date' => $loan->disbursement_date,
            'first_payment_due' => $loan->confirmed_first_due_date,
            'expected_completion' => $loan->confirmed_final_due_date,
        ];

        return array_filter($timeline);
    }

    /**
     * Get overdue loans summary
     */
    public static function getOverdueLoans(int $providerId): Collection
    {
        return LoanApplication::with(['user', 'product', 'repayments'])
            ->whereHas('product', function ($query) use ($providerId) {
                $query->where('provider_id', $providerId);
            })
            ->where('status', 'active')
            ->get()
            ->filter(function ($loan) {
                $overdue = $loan->repayments
                    ->where('status', 'pending')
                    ->filter(fn($r) => Carbon::parse($r->due_date)->isPast())
                    ->count();
                return $overdue > 0;
            })
            ->values();
    }

    /**
     * Get repayment summary for a period
     */
    public static function getRepaymentSummary(int $providerId, string $period = 'monthly'): array
    {
        $query = Repayment::whereHas('loanApplication.product', function ($q) use ($providerId) {
            $q->where('provider_id', $providerId);
        });

        $dateStart = match($period) {
            'daily' => now()->startOfDay(),
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'yearly' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        return [
            'period' => $period,
            'period_start' => $dateStart,
            'period_end' => now(),
            'total_due' => (clone $query)->where('status', '!=', 'completed')
                ->sum('amount'),
            'total_paid' => (clone $query)->where('status', 'completed')
                ->whereBetween('paid_date', [$dateStart, now()])
                ->sum('paid_amount'),
            'total_pending' => (clone $query)->where('status', 'pending')->count(),
            'total_completed' => (clone $query)->where('status', 'completed')->count(),
            'total_overdue' => (clone $query)->where('status', 'pending')
                ->where('due_date', '<', now()->toDateString())
                ->count(),
            'average_payment' => (clone $query)->where('status', 'completed')
                ->whereBetween('paid_date', [$dateStart, now()])
                ->avg('paid_amount'),
            'on_time_rate' => self::calculateOnTimePaymentRate($providerId, $period),
            'default_rate' => self::calculateDefaultRate($providerId, $period),
        ];
    }

    /**
     * Calculate on-time payment rate
     */
    private static function calculateOnTimePaymentRate(int $providerId, string $period): float
    {
        $query = Repayment::whereHas('loanApplication.product', function ($q) use ($providerId) {
            $q->where('provider_id', $providerId);
        })->where('status', 'completed');

        $dateStart = match($period) {
            'daily' => now()->startOfDay(),
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'yearly' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $completedPayments = (clone $query)
            ->whereBetween('paid_date', [$dateStart, now()])
            ->count();

        if ($completedPayments === 0) {
            return 0;
        }

        $onTimePayments = (clone $query)
            ->whereBetween('paid_date', [$dateStart, now()])
            ->whereRaw('DATE(paid_date) <= DATE(due_date)')
            ->count();

        return round(($onTimePayments / $completedPayments) * 100, 2);
    }

    /**
     * Calculate default rate
     */
    private static function calculateDefaultRate(int $providerId, string $period): float
    {
        $dateStart = match($period) {
            'daily' => now()->startOfDay(),
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'yearly' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $totalPayments = Repayment::whereHas('loanApplication.product', function ($q) use ($providerId) {
            $q->where('provider_id', $providerId);
        })->whereBetween('created_at', [$dateStart, now()])->count();

        if ($totalPayments === 0) {
            return 0;
        }

        $defaultedPayments = Repayment::whereHas('loanApplication.product', function ($q) use ($providerId) {
            $q->where('provider_id', $providerId);
        })->where('status', 'pending')
            ->where('due_date', '<', now()->toDateString())
            ->whereBetween('created_at', [$dateStart, now()])
            ->count();

        return round(($defaultedPayments / $totalPayments) * 100, 2);
    }

    /**
     * Get payment distribution chart data
     */
    public static function getPaymentDistribution(int $providerId, int $months = 12): array
    {
        $data = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            $monthLabel = $monthStart->format('M Y');

            $totalPaid = Repayment::whereHas('loanApplication.product', function ($q) use ($providerId) {
                $q->where('provider_id', $providerId);
            })->where('status', 'completed')
                ->whereBetween('paid_date', [$monthStart, $monthEnd])
                ->sum('paid_amount');

            $data[] = [
                'month' => $monthLabel,
                'amount' => $totalPaid,
            ];
        }

        return $data;
    }
}
