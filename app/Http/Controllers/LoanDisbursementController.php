<?php

namespace App\Http\Controllers;

use App\Models\LoanDisbursement;
use App\Models\LoanApplication;
use App\Services\NotificationService;
use App\Services\RepaymentScheduleService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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
            'repaymentSchedules'
        ]);

        return view('disbursements.show', compact('disbursement'));
    }

    public function create(LoanApplication $loan): View
    {
        return view('disbursements.create', compact('loan'));
    }

    public function store(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'disbursement_amount' => 'required|numeric|min:0|max:' . $loan->amount,
            'disbursement_date' => 'required|date',
            'disbursement_method' => 'required|in:bank_transfer,check,cash',
            'bank_account' => 'nullable|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'disbursement_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('disbursement_document')) {
            $validated['disbursement_document_path'] = $request->file('disbursement_document')
                ->store('loan-disbursements', 'public');
        }

        $disbursement = $loan->disbursements()->create($validated);

        return redirect()->route('disbursements.show', $disbursement)
            ->with('success', 'Disbursement created successfully. Configure repayment schedule.');
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
            'approved_by' => auth()->id(),
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
            'disbursed_by' => auth()->id(),
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
            'verified_by' => auth()->id(),
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
