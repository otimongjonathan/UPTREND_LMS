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
        
        // Loans are for issued/active/completed/overdue statuses only
        $query = LoanApplication::with(['user', 'product'])
            ->where(function ($q) use ($user) {
                $q->whereHas('product', function ($subQuery) use ($user) {
                    $subQuery->where('provider_id', $user->id);
                })
                ->orWhereNull('loan_product_id');
            })
            ->whereIn('status', ['approved', 'active', 'completed', 'overdue']); // Approved (pending issue) and issued loans
        
        // Separate loans by status for grouped display
        $pendingIssue = (clone $query)->where('status', 'approved')->count(); // Approved applications ready to be issued
        $active = (clone $query)->where('status', 'active')->count(); // Disbursed and active loans
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
            return redirect()->back()->with('error', 'Loan must be linked to a loan product before issuing.');
        }

        // Redirect to disbursement form instead of directly issuing
        return redirect()->route('disbursements.create', $loan)
            ->with('info', 'Please complete the disbursement details to issue this loan.');
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
