<?php

namespace App\Services;

use App\Models\LoanDisbursement;
use App\Models\LoanApplication;
use App\Models\RepaymentSchedule;
use Carbon\Carbon;

class RepaymentScheduleService
{
    /**
     * Generate repayment schedule for a disbursement
     */
    public function generateSchedule(LoanDisbursement $disbursement): void
    {
        // Get loan details
        $loan = $disbursement->loanApplication;
        
        if (!$disbursement->payment_frequency || !$disbursement->number_of_installments) {
            return;
        }

        // Calculate payment amounts
        $principalAmount = $disbursement->disbursement_amount;
        $totalAmount = $loan->total_interest_amount ? 
            $principalAmount + $loan->total_interest_amount : 
            $principalAmount;

        $interestPerInstallment = $loan->total_interest_amount 
            ? $loan->total_interest_amount / $disbursement->number_of_installments 
            : 0;
        
        $principalPerInstallment = $principalAmount / $disbursement->number_of_installments;
        
        $startDate = $disbursement->first_payment_date ?? $disbursement->disbursement_date;
        $dueDate = $this->calculateFirstDueDate($startDate, $disbursement->payment_frequency);
        
        $remainingBalance = $principalAmount;

        // Create schedule entries
        for ($i = 1; $i <= $disbursement->number_of_installments; $i++) {
            // For last installment, adjust for rounding
            if ($i === $disbursement->number_of_installments) {
                $principal = $remainingBalance;
                $interest = $interestPerInstallment;
                $total = $principal + $interest;
            } else {
                $principal = $principalPerInstallment;
                $interest = $interestPerInstallment;
                $total = $principal + $interest;
            }

            $remainingBalance -= $principal;

            RepaymentSchedule::create([
                'loan_disbursement_id' => $disbursement->id,
                'loan_application_id' => $loan->id,
                'installment_number' => $i,
                'due_date' => $dueDate,
                'principal_amount' => round($principal, 2),
                'interest_amount' => round($interest, 2),
                'total_amount' => round($total, 2),
                'remaining_balance' => round(max(0, $remainingBalance), 2),
                'status' => 'pending',
                'original_due_date' => $dueDate,
            ]);

            // Calculate next due date
            $dueDate = $this->getNextDueDate($dueDate, $disbursement->payment_frequency);
        }
    }

    /**
     * Calculate first due date based on frequency and disbursement date
     */
    private function calculateFirstDueDate(Carbon|string $startDate, string $frequency): Carbon
    {
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }

        return match ($frequency) {
            'daily' => $startDate->addDay(),
            'weekly' => $startDate->addWeek(),
            'bi-weekly' => $startDate->addWeeks(2),
            'monthly' => $startDate->addMonth(),
            'bi-monthly' => $startDate->addMonths(2),
            'quarterly' => $startDate->addQuarter(),
            'semi-annual' => $startDate->addMonths(6),
            'annual' => $startDate->addYear(),
            default => $startDate->addMonth(),
        };
    }

    /**
     * Get next due date
     */
    private function getNextDueDate(Carbon|string $currentDate, string $frequency): Carbon
    {
        if (is_string($currentDate)) {
            $currentDate = Carbon::parse($currentDate);
        }

        return match ($frequency) {
            'daily' => $currentDate->addDay(),
            'weekly' => $currentDate->addWeek(),
            'bi-weekly' => $currentDate->addWeeks(2),
            'monthly' => $currentDate->addMonth(),
            'bi-monthly' => $currentDate->addMonths(2),
            'quarterly' => $currentDate->addQuarter(),
            'semi-annual' => $currentDate->addMonths(6),
            'annual' => $currentDate->addYear(),
            default => $currentDate->addMonth(),
        };
    }

    /**
     * Update schedule status based on payment
     */
    public function updateScheduleStatus(RepaymentSchedule $schedule): void
    {
        if ($schedule->paid_amount >= $schedule->total_amount) {
            $schedule->update(['status' => 'paid']);
        } elseif ($schedule->paid_amount > 0) {
            $schedule->update(['status' => 'partially_paid']);
        } elseif ($schedule->due_date < today()) {
            $schedule->update([
                'status' => 'overdue',
                'days_overdue' => today()->diffInDays($schedule->due_date)
            ]);
        }
    }

    /**
     * Recalculate all schedule statuses for a loan
     */
    public function recalculateScheduleStatuses(LoanApplication $loan): void
    {
        $schedules = RepaymentSchedule::where('loan_application_id', $loan->id)->get();
        
        foreach ($schedules as $schedule) {
            $this->updateScheduleStatus($schedule);
        }
    }

    /**
     * Get schedule summary for a loan
     */
    public function getScheduleSummary(LoanApplication $loan): array
    {
        $schedules = RepaymentSchedule::where('loan_application_id', $loan->id)->get();

        return [
            'total_scheduled' => $schedules->sum('total_amount'),
            'total_paid' => $schedules->sum('paid_amount'),
            'remaining' => $schedules->sum('remaining_balance'),
            'total_installments' => $schedules->count(),
            'paid_installments' => $schedules->where('status', 'paid')->count(),
            'pending_installments' => $schedules->where('status', 'pending')->count(),
            'overdue_installments' => $schedules->where('status', 'overdue')->count(),
            'partially_paid_installments' => $schedules->where('status', 'partially_paid')->count(),
            'next_due_date' => $schedules->where('status', 'pending')->min('due_date'),
            'next_due_amount' => $schedules->where('status', 'pending')->min('total_amount'),
        ];
    }
}
