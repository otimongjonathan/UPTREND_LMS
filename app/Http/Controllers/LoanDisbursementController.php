<?php

namespace App\Http\Controllers;

use App\Models\LoanDisbursement;
use App\Models\LoanApplication;
use App\Services\NotificationService;
use App\Services\RepaymentScheduleService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoanDisbursementController extends Controller
{
    protected RepaymentScheduleService $scheduleService;

    public function __construct(RepaymentScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function index(Request $request): View
    {
        $query = LoanDisbursement::with(['loanApplication', 'approver', 'disburser']);
        
        // Search by borrower name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('loanApplication.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by method
        if ($request->filled('method')) {
            $query->where('disbursement_method', $request->method);
        }
        
        $disbursements = $query->latest()->paginate(15)->appends($request->query());
        
        return view('disbursements.index', compact('disbursements'));
    }

    public function show(LoanDisbursement $disbursement): View
    {
        $disbursement->load([
            'loanApplication',
            'approver',
            'disburser',
            'verifier',
            'loanSupervisor',
            'repaymentSchedules'
        ]);

        return view('disbursements.show', compact('disbursement'));
    }

    public function create(LoanApplication $loan): View
    {
        $loan->load(['user', 'product', 'collaterals.loanSupervisor', 'guarantors']);
        
        // Get all staff members for loan supervisor assignment
        $staffMembers = \App\Models\User::where('role', 'staff')->get();

        return view('disbursements.create', compact('loan', 'staffMembers'));
    }

    public function store(Request $request, LoanApplication $loan): RedirectResponse
    {
        if ($loan->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved loans can be disbursed.');
        }

        if (! $loan->collaterals()->exists()) {
            return redirect()->route('collaterals.create', $loan)
                ->with('error', 'Capture at least one collateral before disbursing this loan.');
        }

        if (! $loan->guarantors()->exists()) {
            return redirect()->route('guarantors.create', $loan)
                ->with('error', 'Capture at least one guarantor before disbursing this loan.');
        }

        $validated = $request->validate([
            'loan_supervisor_id' => 'required|exists:users,id',
            'disbursement_amount' => 'required|numeric|min:0|max:' . $loan->amount,
            'disbursement_date' => 'required|date',
            'disbursement_method' => 'required|in:bank_transfer,mobile_money,cash,cheque',
            'payment_frequency' => 'required|in:weekly,bi-weekly,monthly,quarterly',
            'number_of_installments' => 'required|integer|min:1|max:360',
            
            // Bank Transfer Details
            'bank_name' => 'nullable|string|max:100',
            'account_holder_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'routing_number' => 'nullable|string|max:20',
            'transaction_id' => 'nullable|string|max:100',
            
            // Mobile Money Details
            'mobile_number' => 'nullable|string|max:20',
            'mobile_network' => 'nullable|string|max:50',
            
            // Cash Details
            'cash_received_by' => 'nullable|string|max:100',
            
            // General
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        // Calculate fees and taxes from loan product
        $loanProduct = $loan->product;
        $disbursementAmount = $validated['disbursement_amount'];
        
        $processingFeePercent = $loanProduct->processing_fee_percent ?? 0;
        $processingFeeAmount = ($disbursementAmount * $processingFeePercent) / 100;
        
        $insurancePremiumPercent = $loanProduct->insurance_premium_percent ?? 0;
        $insurancePremiumAmount = ($disbursementAmount * $insurancePremiumPercent) / 100;
        
        // Tax is typically 10% in Uganda (VAT on fees)
        $taxPercent = 10;
        $taxAmount = (($processingFeeAmount + $insurancePremiumAmount) * $taxPercent) / 100;
        
        $totalDeductions = $processingFeeAmount + $insurancePremiumAmount + $taxAmount;
        $netDisbursementAmount = $disbursementAmount - $totalDeductions;

        // Create disbursement record
        $disbursement = $loan->disbursements()->create([
            'loan_supervisor_id' => $validated['loan_supervisor_id'],
            'disbursement_amount' => $disbursementAmount,
            'processing_fee_percent' => $processingFeePercent,
            'processing_fee_amount' => $processingFeeAmount,
            'insurance_premium_percent' => $insurancePremiumPercent,
            'insurance_premium_amount' => $insurancePremiumAmount,
            'tax_percent' => $taxPercent,
            'tax_amount' => $taxAmount,
            'total_deductions' => $totalDeductions,
            'net_disbursement_amount' => $netDisbursementAmount,
            'disbursement_date' => $validated['disbursement_date'],
            'disbursement_method' => $validated['disbursement_method'],
            'payment_frequency' => $validated['payment_frequency'],
            'number_of_installments' => $validated['number_of_installments'],
            'bank_name' => $validated['bank_name'] ?? null,
            'account_holder_name' => $validated['account_holder_name'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'routing_number' => $validated['routing_number'] ?? null,
            'transaction_id' => $validated['transaction_id'] ?? null,
            'cash_received_by' => $validated['cash_received_by'] ?? null,
            'reference_number' => $validated['reference_number'] ?? 'DISB-' . $loan->id . '-' . now()->format('YmdHis'),
            'notes' => $validated['notes'] ?? null,
            'status' => 'disbursed',
            'approved_by' => Auth::guard('staff')->id(),
            'approved_at' => now(),
            'disbursed_by' => Auth::guard('staff')->id(),
            'disbursed_at' => now(),
            'transaction_status' => 'completed',
            'transaction_recorded_at' => now(),
        ]);

        // Update loan status to active (ISSUED)
        $loan->update([
            'status' => 'active',
            'disbursement_date' => $validated['disbursement_date']
        ]);

        // Generate repayment schedule automatically
        RepaymentScheduleService::generateSchedule($disbursement);

        // Send notification to customer
        \App\Services\ComprehensiveNotificationService::notifyLoanDisbursed($disbursement);

        return redirect()->route('schedules.show', $loan->loanRepaymentSchedule)
            ->with('success', 'Loan disbursed successfully! Repayment schedule has been generated with 2-month grace period.');
    }

    /**
     * Show form to configure disbursement details and repayment schedule
     */
    public function edit(LoanDisbursement $disbursement): View
    {
        $disbursement->load('loanApplication');
        
        return view('disbursements.edit', compact('disbursement'));
    }

    /**
     * Update disbursement with repayment schedule details
     */
    public function update(Request $request, LoanDisbursement $disbursement): RedirectResponse
    {
        if ($disbursement->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot edit disbursement that is not pending.');
        }

        $validated = $request->validate([
            // Repayment Schedule Configuration
            'payment_frequency' => 'required|in:daily,weekly,bi-weekly,monthly,bi-monthly,quarterly,semi-annual,annual',
            'number_of_installments' => 'required|integer|min:1|max:360',
            'first_payment_date' => 'required|date|after:today',
            
            // Transaction Details - Bank Transfer
            'bank_name' => 'nullable|string|max:100',
            'account_holder_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'routing_number' => 'nullable|string|max:20',
            'transaction_id' => 'nullable|string|max:100',
            
            // Transaction Details - Cash
            'cash_received_by' => 'nullable|string|max:100',
            'cash_notes' => 'nullable|string',
            
            // General Transaction Info
            'transaction_notes' => 'nullable|string',
        ]);

        $disbursement->update($validated);

        return redirect()->route('disbursements.show', $disbursement)
            ->with('success', 'Disbursement configuration updated. Ready for approval.');
    }

    public function approve(LoanDisbursement $disbursement): RedirectResponse
    {
        if (!$disbursement->payment_frequency || !$disbursement->number_of_installments) {
            return redirect()->back()
                ->with('error', 'Please configure repayment schedule before approving.');
        }

        $disbursement->update([
            'status' => 'approved',
            'approved_by' => Auth::guard('staff')->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Disbursement approved. Ready to disburse.');
    }

    /**
     * Finalize disbursement - record transaction and generate repayment schedule
     */
    public function disburse(LoanDisbursement $disbursement): RedirectResponse
    {
        if ($disbursement->status !== 'approved') {
            return redirect()->back()->with('error', 'Disbursement must be approved first.');
        }

        $disbursement->update([
            'status' => 'disbursed',
            'disbursed_by' => Auth::guard('staff')->id(),
            'disbursed_at' => now(),
            'transaction_status' => 'completed',
            'transaction_recorded_at' => now(),
        ]);

        // Generate repayment schedule
        $this->scheduleService->generateSchedule($disbursement);

        // Send notification to borrower
        NotificationService::notifyDisbursement($disbursement->loanApplication, $disbursement->disbursement_amount);

        return redirect()->back()
            ->with('success', 'Disbursement completed. Repayment schedule generated and borrower notified.');
    }

    /**
     * Verify and record transaction details (for reconciliation)
     */
    public function verify(LoanDisbursement $disbursement): RedirectResponse
    {
        if ($disbursement->status !== 'disbursed') {
            return redirect()->back()
                ->with('error', 'Can only verify disbursed amounts.');
        }

        $disbursement->update([
            'verified_by' => Auth::guard('staff')->id(),
            'verified_at' => now(),
            'transaction_status' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Disbursement verified and recorded.');
    }

    public function cancel(LoanDisbursement $disbursement): RedirectResponse
    {
        $disbursement->update([
            'status' => 'cancelled',
            'transaction_status' => 'cancelled',
        ]);

        // Delete generated repayment schedules
        $disbursement->repaymentSchedules()->delete();

        return redirect()->back()->with('success', 'Disbursement cancelled and schedules removed.');
    }
}
