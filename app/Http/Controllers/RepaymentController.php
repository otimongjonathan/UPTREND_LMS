<?php

namespace App\Http\Controllers;

use App\Models\Repayment;
use App\Models\LoanApplication;
use App\Services\LoanInstallmentService;
use App\Services\RepaymentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RepaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('staff')->user();
        
        $query = Repayment::with('loanApplication.user')
            ->whereHas('loanApplication.product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            });

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->overdue();
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('loan_id')) {
            $query->where('loan_application_id', $request->loan_id);
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $repayments = $query->latest('due_date')->paginate(15);

        $summary = [
            'total_due' => Repayment::whereHas('loanApplication.product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            })->where('status', '!=', 'completed')->sum('amount'),
            'total_paid' => Repayment::whereHas('loanApplication.product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            })->where('status', 'completed')->sum('paid_amount'),
            'overdue' => Repayment::whereHas('loanApplication.product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            })->overdue()->count(),
            'pending' => Repayment::whereHas('loanApplication.product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            })->where('status', 'pending')->count(),
        ];

        return view('repayments.index', compact('repayments', 'summary'));
    }

    public function show(Repayment $repayment)
    {
        $user = Auth::guard('staff')->user();
        
        // Ensure user can only view repayments for their loan products
        if ($repayment->loanApplication->product && $repayment->loanApplication->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized access to this repayment.');
        }
        
        $repayment->load('loanApplication.user');
        return view('repayments.show', compact('repayment'));
    }

    public function create()
    {
        $user = Auth::guard('staff')->user();
        
        $loans = LoanApplication::whereIn('status', ['approved', 'active'])
            ->whereHas('product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            })
            ->with('user')
            ->get();

        return view('repayments.create', compact('loans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_application_id' => 'required|exists:loan_applications,id',
            'installment_number' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0.01',
            'principal_amount' => 'required|numeric|min:0',
            'interest_amount' => 'required|numeric|min:0',
            'remaining_balance' => 'nullable|numeric|min:0',
            'due_date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:pending,partial,completed',
            'payment_method' => 'nullable|string|max:100',
            'payment_frequency' => 'nullable|in:weekly,bi_weekly,monthly,quarterly',
            'notes' => 'nullable|string|max:500',
        ]);

        // Set defaults for optional fields
        $validated['remaining_balance'] = $validated['remaining_balance'] ?? 0;
        $validated['late_fee'] = 0;
        $validated['days_overdue'] = 0;
        $validated['original_due_date'] = $validated['due_date'];

        $repayment = Repayment::create($validated);

        return redirect()->route('repayments.show', $repayment)
            ->with('success', 'Repayment installment created successfully.');
    }

    public function edit(Repayment $repayment)
    {
        return view('repayments.edit', compact('repayment'));
    }

    public function update(Request $request, Repayment $repayment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date|before_or_equal:today',
            'paid_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,partial,completed',
            'payment_method' => 'nullable|string|max:100',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $repayment->update($validated);

        return redirect()->route('repayments.show', $repayment)
            ->with('success', 'Repayment updated successfully.');
    }

    /**
     * Record a payment for a repayment installment
     */
    public function recordPayment(Request $request, Repayment $repayment): RedirectResponse
    {
        $user = Auth::guard('staff')->user();
        
        // Ensure user can only process payments for their loan products
        if ($repayment->loanApplication->product && $repayment->loanApplication->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized to process this payment.');
        }

        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0.01',
            'paid_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|string|max:100',
            'payment_reference' => 'nullable|string|max:100',
        ]);

        try {
            // Use the enhanced workflow service to process payment
            $updatedRepayment = \App\Services\RepaymentWorkflowService::processPayment(
                $repayment,
                $validated['paid_amount'],
                $validated['paid_date'],
                $validated['payment_method'],
                $validated['payment_reference'] ?? null
            );

            $message = 'Payment recorded successfully.';
            
            // Add late fee information if applicable
            if ($updatedRepayment->late_fee > 0) {
                $message .= ' Late fee of UGX ' . number_format($updatedRepayment->late_fee) . ' has been applied.';
            }
            
            // Add overpayment information if applicable
            $totalDue = $updatedRepayment->amount + $updatedRepayment->late_fee;
            if ($validated['paid_amount'] > $totalDue) {
                $overpayment = $validated['paid_amount'] - $totalDue;
                $message .= ' Overpayment of UGX ' . number_format($overpayment) . ' has been applied to future installments.';
            }

            return redirect()->route('repayments.show', $repayment)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Payment processing failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Repayment $repayment)
    {
        $repayment->delete();

        return redirect()->route('repayments.index')
            ->with('success', 'Repayment schedule deleted.');
    }
}
