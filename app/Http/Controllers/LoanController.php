<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\LoanDisbursement;
use App\Services\LoanInstallmentService;
use App\Services\LoanCalculationService;
use App\Services\AutomatedLoanScheduleService;
use App\Services\RepaymentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('staff')->user();
        
        $query = LoanApplication::with(['user', 'product'])
            ->where(function ($q) use ($user) {
                // Show loans linked to this provider's loan products
                $q->whereHas('product', function ($subQuery) use ($user) {
                    $subQuery->where('provider_id', $user->id);
                })
                // Also show loans without a product (for backwards compatibility)
                ->orWhereNull('loan_product_id');
            });
        
        // Separate loans by status for grouped display
        $pendingIssue = (clone $query)->where('status', 'approved')->count();
        $active = (clone $query)->where('status', 'active')->count();
        $completed = (clone $query)->where('status', 'completed')->count();
        $overdue = (clone $query)->where('status', 'overdue')->count();
        
        $loans = $query->latest()->paginate(15);
        
        return view('loans.index', compact('loans', 'pendingIssue', 'active', 'completed', 'overdue'));
    }

    public function show(LoanApplication $loan)
    {
        $user = Auth::guard('staff')->user();
        
        // Ensure user can only view loans for their products
        // If loan has a product, verify ownership
        if ($loan->product && $loan->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized access to this loan.');
        }
        
        $loan->load(['user', 'repayments', 'product']);
        
        // Get loan summary if active
        $loanSummary = null;
        if (in_array($loan->status, ['active', 'completed', 'overdue'])) {
            $loanSummary = LoanInstallmentService::getLoanSummary($loan);
        }
        
        return view('loans.show', compact('loan', 'loanSummary'));
    }

    public function issue(LoanApplication $loan): RedirectResponse
    {
        $user = Auth::guard('staff')->user();
        
        if ($loan->product && $loan->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized to issue this loan.');
        }
        
        if ($loan->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved loans can be issued.');
        }

        if (!$loan->loan_product_id) {
            return redirect()->back()->with('error', 'Loan must be linked to a loan product before issuing. Please assign a loan product first.');
        }

        $scheduleService = app(AutomatedLoanScheduleService::class);
        $notificationService = app(RepaymentNotificationService::class);
        
        try {
            $loanSummary = $scheduleService->calculateLoanSummary($loan);
            
            $loan->update([
                'status' => 'active',
                'disbursement_date' => now(),
                'disbursed_amount' => $loan->amount,
                'applied_interest_rate' => $loanSummary['annual_rate'],
                'actual_term_months' => $loanSummary['term_months'],
                'total_interest' => $loanSummary['total_interest'],
                'total_repayable' => $loanSummary['total_amount'] + $loanSummary['processing_fee'] + $loanSummary['insurance_premium'],
                'outstanding_balance' => $loanSummary['total_amount'] + $loanSummary['processing_fee'] + $loanSummary['insurance_premium'],
                'processing_fee' => $loanSummary['processing_fee'],
                'insurance_fee' => $loanSummary['insurance_premium']
            ]);
            
            $installmentsCreated = $scheduleService->createRepaymentRecords($loan);
            
            LoanDisbursement::create([
                'loan_application_id' => $loan->id,
                'disbursement_amount' => $loan->amount,
                'disbursement_date' => now(),
                'disbursement_method' => 'bank_transfer',
                'status' => 'disbursed',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'disbursed_by' => $user->id,
                'disbursed_at' => now(),
                'reference_number' => 'DISB-' . $loan->id . '-' . now()->format('YmdHis'),
                'notes' => 'Automatically created upon loan issuance',
            ]);
            
            $notificationService->sendLoanIssuedNotification($loan);
            
            return redirect()->route('loans.show', $loan)
                ->with('success', "Loan issued and disbursed successfully! {$installmentsCreated} installments created. Customer notified.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error issuing loan: ' . $e->getMessage());
        }
    }

    public function downloadDocument(LoanApplication $loan, $field)
    {
        $user = Auth::guard('staff')->user();
        
        // Ensure user can only download documents for their loan products
        // If loan has a product, verify ownership
        if ($loan->product && $loan->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized access to this document.');
        }
        
        $allowedFields = [
            'police_letter_path',
            'financial_statement_path',
            'national_id_path',
            'loan_guarantee_one_path',
            'loan_guarantee_two_path',
            'proof_of_residence_path',
        ];

        if (!in_array($field, $allowedFields) || !$loan->$field) {
            abort(404, 'Document not found.');
        }

        $path = $loan->$field;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found on disk.');
        }

        return Storage::disk('public')->download($path);
    }
}
