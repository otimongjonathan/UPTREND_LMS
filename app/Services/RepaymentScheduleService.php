<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\LoanDisbursement;
use App\Models\LoanRepaymentSchedule;
use Carbon\Carbon;

class RepaymentScheduleService
{
    const GRACE_PERIOD_MONTHS = 2;

    /**
     * Generate single repayment schedule when loan is disbursed
     */
    public static function generateSchedule(LoanDisbursement $disbursement): LoanRepaymentSchedule
    {
        $loan = $disbursement->loanApplication;
        
        // Delete existing schedule if any
        LoanRepaymentSchedule::where('loan_application_id', $loan->id)->delete();
        
        $installments = $disbursement->number_of_installments ?? self::calculateInstallments($loan);
        $frequency = $disbursement->payment_frequency ?? $loan->confirmed_payment_frequency ?? $loan->repayment_schedule ?? 'monthly';
        
        // Calculate first payment date with grace period
        $gracePeriod = $disbursement->grace_period_months ?? self::GRACE_PERIOD_MONTHS;
        $disbursementDate = Carbon::parse($disbursement->disbursement_date);
        
        if ($disbursement->first_payment_date) {
            $firstPaymentDate = Carbon::parse($disbursement->first_payment_date);
        } else {
            $firstPaymentDate = $disbursementDate->copy()->addMonths($gracePeriod);
        }
        
        $gracePeriodEndDate = $firstPaymentDate->copy()->subDay();
        
        // Calculate amounts
        $loanAmount = $loan->amount;
        $interestRate = $loan->loanProduct->interest_rate ?? 15.0;
        
        // Calculate total interest for the entire loan period
        $totalInterest = ($loanAmount * $interestRate / 100);
        
        // Get fees from loan application (if calculated)
        $processingFee = $loan->processing_fee_amount ?? 0;
        $insuranceFee = $loan->insurance_fee_amount ?? 0;
        $vatAmount = $loan->vat_amount ?? 0;
        $withholdingTax = $loan->withholding_tax_amount ?? 0;
        
        // Total fees and taxes
        $totalFees = $processingFee + $insuranceFee;
        $totalTaxes = $vatAmount + $withholdingTax;
        
        // Total repayable = Principal + Interest + Fees + Taxes
        $totalRepayable = $loanAmount + $totalInterest + $totalFees + $totalTaxes;
        
        // Each installment amount
        $installmentAmount = $totalRepayable / $installments;
        
        // Per installment breakdown
        $principalPerInstallment = $loanAmount / $installments;
        $interestPerInstallment = $totalInterest / $installments;
        $feePerInstallment = $totalFees / $installments;
        $taxPerInstallment = $totalTaxes / $installments;
        
        // Generate installments array
        $installmentsData = [];
        $remainingBalance = $totalRepayable;
        
        for ($i = 1; $i <= $installments; $i++) {
            $dueDate = self::calculateDueDate($firstPaymentDate, $i - 1, $frequency);
            
            $remainingBalance -= $installmentAmount;
            
            $installmentsData[] = [
                'number' => $i,
                'due_date' => $dueDate->format('Y-m-d'),
                'principal_amount' => round($principalPerInstallment, 2),
                'interest_amount' => round($interestPerInstallment, 2),
                'fee_amount' => round($feePerInstallment, 2),
                'tax_amount' => round($taxPerInstallment, 2),
                'total_amount' => round($installmentAmount, 2),
                'paid_amount' => 0,
                'remaining_balance' => round(max(0, $remainingBalance), 2),
                'status' => 'pending',
                'paid_date' => null,
                'payment_method' => null,
                'payment_reference' => null,
                'notes' => null,
            ];
        }
        
        $finalPaymentDate = self::calculateDueDate($firstPaymentDate, $installments - 1, $frequency);
        
        // Create single schedule record
        $schedule = LoanRepaymentSchedule::create([
            'loan_application_id' => $loan->id,
            'loan_disbursement_id' => $disbursement->id,
            'total_installments' => $installments,
            'payment_frequency' => $frequency,
            'installment_amount' => round($installmentAmount, 2),
            'total_loan_amount' => $loanAmount,
            'total_interest' => round($totalInterest, 2),
            'total_fees' => round($totalFees, 2),
            'total_taxes' => round($totalTaxes, 2),
            'total_repayable' => round($totalRepayable, 2),
            'grace_period_months' => $gracePeriod,
            'grace_period_end_date' => $gracePeriodEndDate,
            'first_payment_date' => $firstPaymentDate,
            'final_payment_date' => $finalPaymentDate,
            'installments' => $installmentsData,
            'installments_paid' => 0,
            'installments_pending' => $installments,
            'installments_overdue' => 0,
            'total_paid' => 0,
            'total_outstanding' => round($totalRepayable, 2),
            'status' => 'active',
        ]);
        
        return $schedule;
    }

    /**
     * Record payment for a specific installment
     */
    public static function recordPayment(LoanRepaymentSchedule $schedule, int $installmentNumber, float $amount, array $data = []): LoanRepaymentSchedule
    {
        $installments = $schedule->installments;
        
        foreach ($installments as $key => $installment) {
            if ($installment['number'] === $installmentNumber) {
                $newPaidAmount = $installment['paid_amount'] + $amount;
                $totalDue = $installment['total_amount'];
                
                $installments[$key]['paid_amount'] = $newPaidAmount;
                $installments[$key]['status'] = $newPaidAmount >= $totalDue ? 'paid' : 'partial';
                $installments[$key]['paid_date'] = $newPaidAmount >= $totalDue ? now()->format('Y-m-d') : $installment['paid_date'];
                $installments[$key]['payment_method'] = $data['payment_method'] ?? $installment['payment_method'];
                $installments[$key]['payment_reference'] = $data['payment_reference'] ?? $installment['payment_reference'];
                $installments[$key]['notes'] = $data['notes'] ?? $installment['notes'];
                
                break;
            }
        }
        
        $schedule->update(['installments' => $installments]);
        $schedule->updateCounts();
        
        return $schedule->fresh();
    }

    /**
     * Update overdue statuses
     */
    public static function updateOverdueStatuses(LoanRepaymentSchedule $schedule): void
    {
        $installments = $schedule->installments;
        $updated = false;
        
        foreach ($installments as $key => $installment) {
            if ($installment['status'] !== 'paid' && Carbon::parse($installment['due_date'])->isPast()) {
                $installments[$key]['status'] = 'overdue';
                $updated = true;
            }
        }
        
        if ($updated) {
            $schedule->update(['installments' => $installments]);
            $schedule->updateCounts();
        }
    }

    /**
     * Calculate number of installments based on frequency
     */
    private static function calculateInstallments(LoanApplication $loan): int
    {
        $termMonths = $loan->confirmed_term_months ?? $loan->term_months ?? 12;
        $frequency = $loan->confirmed_payment_frequency ?? $loan->repayment_schedule ?? 'monthly';
        
        return match($frequency) {
            'weekly' => $termMonths * 4,
            'bi-weekly' => $termMonths * 2,
            'monthly' => $termMonths,
            'quarterly' => max(1, (int)($termMonths / 3)),
            default => $termMonths,
        };
    }

    /**
     * Calculate due date based on frequency
     */
    private static function calculateDueDate(Carbon $startDate, int $index, string $frequency): Carbon
    {
        return match($frequency) {
            'weekly' => $startDate->copy()->addWeeks($index),
            'bi-weekly' => $startDate->copy()->addWeeks($index * 2),
            'monthly' => $startDate->copy()->addMonths($index),
            'quarterly' => $startDate->copy()->addMonths($index * 3),
            default => $startDate->copy()->addMonths($index),
        };
    }
}
