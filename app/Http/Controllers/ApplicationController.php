<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::guard('staff')->user();
        
        // Applications are for pending/approved/rejected statuses
        $query = LoanApplication::with(['user', 'product'])
            ->where(function ($q) use ($user) {
                $q->whereHas('product', function ($subQuery) use ($user) {
                    $subQuery->where('provider_id', $user->id);
                })
                ->orWhereNull('loan_product_id');
            })
            ->whereIn('status', ['pending', 'approved', 'rejected']); // Only applications, not issued loans
        
        $statusCounts = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];
        
        $applications = $query->latest()->paginate(15);

        return view('applications.index', [
            'applications' => $applications,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function show(LoanApplication $application): View
    {
        $user = Auth::guard('staff')->user();
        
        if ($application->product && $application->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized access to this application.');
        }
        
        $application->load(['user', 'repayments']);
        
        $loanProducts = LoanProduct::where('provider_id', $user->id)->where('is_active', true)->get();
        
        return view('applications.show', compact('application', 'loanProducts'));
    }

    public function approve(LoanApplication $application): RedirectResponse
    {
        $user = Auth::guard('staff')->user();
        
        if ($application->product && $application->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized to approve this application.');
        }
        
        if (!$application->loan_product_id) {
            $product = LoanProduct::where('provider_id', $user->id)
                ->where('is_active', true)
                ->first();
            
            if ($product) {
                $application->update(['loan_product_id' => $product->id]);
            }
        }
        
        $oldStatus = $application->status;
        $application->update(['status' => 'approved']);
        
        // Send notification to customer - pending final approval
        \App\Services\ComprehensiveNotificationService::notifyLoanPendingFinalApproval($application);
        
        try {
            $schedule = \App\Services\RepaymentWorkflowService::generateScheduleOnApproval($application);
            $installmentCount = count($schedule);
            
            $productName = $application->product?->name ?? 'Default';
            return redirect()->route('applications.show', $application)
                ->with('success', "Application approved successfully! Product '{$productName}' auto-assigned. Repayment schedule created with {$installmentCount} installments.");
        } catch (\Exception $e) {
            return redirect()->route('applications.show', $application)
                ->with('warning', 'Application approved but repayment schedule generation failed: ' . $e->getMessage());
        }
    }

    public function reject(LoanApplication $application): RedirectResponse
    {
        $user = Auth::guard('staff')->user();
        
        if ($application->product && $application->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized to reject this application.');
        }
        
        $reason = request()->input('rejection_reason', 'Application did not meet our criteria.');
        $application->update(['status' => 'rejected']);
        
        // Send notification to customer
        \App\Services\ComprehensiveNotificationService::notifyLoanRejected($application, $reason);
        
        return redirect()->route('applications.show', $application)->with('success', 'Application rejected successfully.');
    }
    
    public function updateTerms(LoanApplication $application): RedirectResponse
    {
        $user = Auth::guard('staff')->user();
        
        if ($application->product && $application->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized to modify this application.');
        }
        
        $validated = request()->validate([
            'payment_frequency' => 'required|in:weekly,bi_weekly,monthly,quarterly',
            'first_due_date' => 'required|date|after:today'
        ]);
        
        $validation = \App\Services\LoanTermSyncService::validateTerms(
            $validated['payment_frequency'],
            $validated['first_due_date'],
            $application->disbursement_date
        );
        
        if (!$validation['valid']) {
            return back()->withErrors(['terms' => $validation['message']]);
        }
        
        $result = \App\Services\LoanTermSyncService::regenerateTerms(
            $application,
            $validated['payment_frequency'],
            $validated['first_due_date']
        );
        
        $application->update([
            'terms_last_modified' => now(),
            'terms_modified_by' => $user->id
        ]);
        
        return redirect()->route('applications.show', $application)
            ->with('success', "Loan terms updated successfully! New term: {$result['term_months']} months with {$result['total_installments']} installments.");
    }
    
    public function calculateFees(LoanApplication $application): RedirectResponse
    {
        $user = Auth::guard('staff')->user();
        
        if ($application->product && $application->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized to modify this application.');
        }
        
        $validated = request()->validate([
            'processing_fee_percent' => 'required|numeric|min:0|max:10',
            'insurance_fee_percent' => 'required|numeric|min:0|max:5',
            'vat_percent' => 'required|numeric|min:0|max:25',
            'withholding_tax_percent' => 'required|numeric|min:0|max:10',
        ]);
        
        $calculations = \App\Services\LoanFeeCalculationService::applyFeesToLoan($application, $validated);
        
        return redirect()->route('applications.show', $application)
            ->with('success', "Fees calculated successfully! Net disbursement: UGX " . number_format($calculations['net_disbursement_amount'], 2));
    }
    
    public function assignProduct(LoanApplication $application): RedirectResponse
    {
        $user = Auth::guard('staff')->user();
        
        if ($application->product && $application->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized to modify this application.');
        }
        
        $validated = request()->validate([
            'loan_product_id' => 'required|exists:loan_products,id',
        ]);
        
        $product = LoanProduct::findOrFail($validated['loan_product_id']);
        if ($product->provider_id !== $user->id) {
            abort(403, 'You can only assign your own loan products.');
        }
        
        $application->update(['loan_product_id' => $validated['loan_product_id']]);
        
        return redirect()->route('applications.show', $application)
            ->with('success', "Loan product '{$product->name}' assigned successfully!");
    }
}
