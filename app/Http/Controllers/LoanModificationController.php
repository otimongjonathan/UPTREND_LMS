<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\Repayment;
use App\Services\LoanInstallmentModificationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoanModificationController extends Controller
{
    /**
     * Show loan modification options
     */
    public function show(LoanApplication $loan): View
    {
        $loan->load(['repayments', 'loanProduct', 'user']);
        $modificationHistory = LoanInstallmentModificationService::getLoanModificationHistory($loan);
        
        return view('loans.modifications.show', compact('loan', 'modificationHistory'));
    }
    
    /**
     * Handle early loan completion
     */
    public function completeEarly(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|string',
            'confirm_early_completion' => 'required|accepted'
        ]);
        
        LoanInstallmentModificationService::handleEarlyCompletion(
            $loan, 
            $validated['payment_amount'], 
            $validated['payment_date']
        );
        
        return redirect()->route('loans.show', $loan)
            ->with('success', 'Loan completed early with interest savings applied.');
    }
    
    /**
     * Restructure loan due to default
     */
    public function restructure(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'new_term_months' => 'required|integer|min:1|max:60',
            'new_payment_frequency' => 'required|in:weekly,bi_weekly,monthly,quarterly',
            'restructuring_fee' => 'required|numeric|min:0',
            'reason' => 'required|string|max:500'
        ]);
        
        LoanInstallmentModificationService::restructureLoan(
            $loan,
            $validated['new_term_months'],
            $validated['new_payment_frequency'],
            $validated['restructuring_fee']
        );
        
        return redirect()->route('loans.show', $loan)
            ->with('success', 'Loan restructured successfully with new payment schedule.');
    }
    
    /**
     * Handle partial default with recovery plan
     */
    public function createRecoveryPlan(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'missed_payments' => 'required|array',
            'missed_payments.*.repayment_id' => 'required|exists:repayments,id',
            'recovery_schedule' => 'required|array',
            'recovery_schedule.*.amount' => 'required|numeric|min:0',
            'recovery_schedule.*.due_date' => 'required|date|after:today'
        ]);
        
        LoanInstallmentModificationService::handlePartialDefault(
            $loan,
            $validated['missed_payments'],
            $validated['recovery_schedule']
        );
        
        return redirect()->route('loans.show', $loan)
            ->with('success', 'Default recovery plan created successfully.');
    }
    
    /**
     * Process overpayment
     */
    public function processOverpayment(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'overpayment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date|before_or_equal:today',
            'apply_to_future' => 'boolean',
            'payment_method' => 'required|string'
        ]);
        
        LoanInstallmentModificationService::handleOverpayment(
            $loan,
            $validated['overpayment_amount'],
            $validated['payment_date'],
            $validated['apply_to_future'] ?? true
        );
        
        return redirect()->route('loans.show', $loan)
            ->with('success', 'Overpayment processed successfully.');
    }
    
    /**
     * Recalculate loan schedule
     */
    public function recalculateSchedule(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'new_interest_rate' => 'nullable|numeric|min:0|max:100',
            'new_term_months' => 'nullable|integer|min:1|max:60',
            'new_payment_frequency' => 'nullable|in:weekly,bi_weekly,monthly,quarterly',
            'reason' => 'required|string|max:500'
        ]);
        
        $newParameters = array_filter([
            'interest_rate' => $validated['new_interest_rate'] ?? null,
            'term_months' => $validated['new_term_months'] ?? null,
            'payment_frequency' => $validated['new_payment_frequency'] ?? null
        ]);
        
        LoanInstallmentModificationService::recalculateFullSchedule($loan, $newParameters);
        
        return redirect()->route('loans.show', $loan)
            ->with('success', 'Loan schedule recalculated with new parameters.');
    }
    
    /**
     * Show modification history
     */
    public function history(LoanApplication $loan): View
    {
        $modificationHistory = LoanInstallmentModificationService::getLoanModificationHistory($loan);
        
        return view('loans.modifications.history', compact('loan', 'modificationHistory'));
    }
    
    /**
     * Approve modification request
     */
    public function approveModification(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'modification_type' => 'required|in:early_completion,restructuring,recovery_plan,overpayment,recalculation',
            'approval_notes' => 'nullable|string|max:500'
        ]);
        
        // Update loan with approval
        $loan->update([
            'modification_approved' => true,
            'modification_approved_by' => auth()->id(),
            'modification_approved_at' => now(),
            'modification_approval_notes' => $validated['approval_notes']
        ]);
        
        return redirect()->route('loans.modifications.show', $loan)
            ->with('success', 'Loan modification approved successfully.');
    }
    
    /**
     * Calculate modification impact
     */
    public function calculateImpact(Request $request, LoanApplication $loan)
    {
        $type = $request->input('type');
        $parameters = $request->input('parameters', []);
        
        $impact = match($type) {
            'early_completion' => $this->calculateEarlyCompletionImpact($loan, $parameters),
            'restructuring' => $this->calculateRestructuringImpact($loan, $parameters),
            'recovery_plan' => $this->calculateRecoveryPlanImpact($loan, $parameters),
            default => ['error' => 'Invalid modification type']
        };
        
        return response()->json($impact);
    }
    
    /**
     * Calculate early completion impact
     */
    private function calculateEarlyCompletionImpact(LoanApplication $loan, $parameters)
    {
        $remainingBalance = $loan->outstanding_balance;
        $remainingInterest = $loan->repayments()->where('status', 'pending')->sum('interest_amount');
        $interestSavings = $remainingInterest * 0.5; // 50% discount
        $payoffAmount = $remainingBalance - $interestSavings;
        
        return [
            'remaining_balance' => $remainingBalance,
            'interest_savings' => $interestSavings,
            'payoff_amount' => $payoffAmount,
            'savings_percentage' => round(($interestSavings / $remainingBalance) * 100, 2)
        ];
    }
    
    /**
     * Calculate restructuring impact
     */
    private function calculateRestructuringImpact(LoanApplication $loan, $parameters)
    {
        $newTermMonths = $parameters['new_term_months'] ?? 12;
        $restructuringFee = $parameters['restructuring_fee'] ?? 0;
        $outstandingBalance = $loan->outstanding_balance;
        
        $newTotalRepayable = $outstandingBalance + $restructuringFee;
        $paymentsPerYear = match($parameters['payment_frequency'] ?? 'monthly') {
            'weekly' => 52,
            'bi_weekly' => 26,
            'monthly' => 12,
            'quarterly' => 4,
            default => 12
        };
        
        $totalPayments = round(($newTermMonths / 12) * $paymentsPerYear);
        $newPaymentAmount = round($newTotalRepayable / $totalPayments, 2);
        
        return [
            'current_outstanding' => $outstandingBalance,
            'restructuring_fee' => $restructuringFee,
            'new_total_repayable' => $newTotalRepayable,
            'new_payment_amount' => $newPaymentAmount,
            'total_payments' => $totalPayments,
            'payment_reduction' => $loan->repayments()->where('status', 'pending')->first()->amount - $newPaymentAmount
        ];
    }
    
    /**
     * Calculate recovery plan impact
     */
    private function calculateRecoveryPlanImpact(LoanApplication $loan, $parameters)
    {
        $missedPayments = count($parameters['missed_payments'] ?? []);
        $penaltyRate = $loan->loanProduct->late_payment_fee_percent ?? 5.0;
        $totalPenalties = 0;
        
        foreach ($parameters['missed_payments'] ?? [] as $missedPayment) {
            $repayment = Repayment::find($missedPayment['repayment_id']);
            if ($repayment) {
                $totalPenalties += ($repayment->amount * $penaltyRate / 100);
            }
        }
        
        $recoveryAmount = array_sum(array_column($parameters['recovery_schedule'] ?? [], 'amount'));
        
        return [
            'missed_payments_count' => $missedPayments,
            'total_penalties' => $totalPenalties,
            'recovery_amount' => $recoveryAmount,
            'additional_cost' => $totalPenalties,
            'recovery_period_months' => count($parameters['recovery_schedule'] ?? [])
        ];
    }
}