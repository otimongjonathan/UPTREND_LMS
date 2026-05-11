<?php

namespace App\Http\Controllers;

use App\Models\RepaymentSchedule;
use App\Models\LoanApplication;
use App\Services\RepaymentCollectionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RepaymentCollectionController extends Controller
{
    protected RepaymentCollectionService $collectionService;

    public function __construct(RepaymentCollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * View outstanding repayments for a loan
     */
    public function index(LoanApplication $loan): View
    {
        $pendingSchedules = $this->collectionService->getPendingInstallments($loan);
        $overdueInstallments = $this->collectionService->getOverdueInstallments($loan);
        $arrears = $this->collectionService->calculateArrears($loan);
        $paymentHistory = [];

        foreach ($pendingSchedules as $schedule) {
            $paymentHistory[$schedule->id] = $this->collectionService->getPaymentHistory($schedule);
        }

        return view('repayments.collection', compact(
            'loan',
            'pendingSchedules',
            'overdueInstallments',
            'arrears',
            'paymentHistory'
        ));
    }

    /**
     * Show payment form for a specific installment
     */
    public function create(RepaymentSchedule $schedule): View
    {
        $schedule->load('loanDisbursement', 'loanApplication');
        $paymentHistory = $this->collectionService->getPaymentHistory($schedule);

        return view('repayments.pay', compact('schedule', 'paymentHistory'));
    }

    /**
     * Record a payment against a schedule
     */
    public function store(Request $request, RepaymentSchedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0.01|max:' . ($schedule->total_amount - $schedule->paid_amount),
            'payment_method' => 'required|in:bank_transfer,check,cash,mobile_money',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $this->collectionService->recordPayment(
            $schedule,
            $validated['amount_paid'],
            $validated['payment_method'],
            $validated['payment_reference'] ?? null,
            $validated['notes'] ?? null
        );

        return redirect()->route('repayments.collection', $schedule->loanApplication)
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Edit repayment schedule (adjust dates/amounts)
     */
    public function edit(RepaymentSchedule $schedule): View
    {
        $schedule->load('loanApplication', 'loanDisbursement');

        return view('repayments.schedule-edit', compact('schedule'));
    }

    /**
     * Update repayment schedule
     */
    public function update(Request $request, RepaymentSchedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'due_date' => 'required|date',
            'principal_amount' => 'required|numeric|min:0',
            'interest_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $schedule->update([
            'due_date' => $validated['due_date'],
            'principal_amount' => $validated['principal_amount'],
            'interest_amount' => $validated['interest_amount'],
            'total_amount' => $validated['principal_amount'] + $validated['interest_amount'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('repayments.collection', $schedule->loanApplication)
            ->with('success', 'Schedule updated successfully!');
    }

    /**
     * Show all payment history for a loan
     */
    public function paymentHistory(LoanApplication $loan): View
    {
        $schedules = $loan->repaymentSchedules()
            ->with('paymentReceipts')
            ->orderBy('due_date')
            ->get();

        $totalScheduled = $schedules->sum('total_amount');
        $totalPaid = $schedules->sum('paid_amount');
        $remaining = $totalScheduled - $totalPaid;

        return view('repayments.payment-history', compact(
            'loan',
            'schedules',
            'totalScheduled',
            'totalPaid',
            'remaining'
        ));
    }

    /**
     * View a specific payment receipt
     */
    public function viewReceipt(\App\Models\PaymentReceipt $receipt): View
    {
        $receipt->load(['repaymentSchedule', 'loanApplication', 'user']);

        return view('repayments.receipt', compact('receipt'));
    }

    /**
     * Send payment reminder for upcoming installment
     */
    public function sendReminder(RepaymentSchedule $schedule): RedirectResponse
    {
        // TODO: Implement actual email/SMS sending
        // For now, just mark that reminder was triggered

        $schedule->reminders()->create([
            'loan_application_id' => $schedule->loan_application_id,
            'user_id' => $schedule->loanApplication->user_id,
            'days_before_due' => $schedule->due_date->diffInDays(today()),
            'reminded' => true,
            'reminder_sent_at' => now(),
            'reminder_type' => 'email',
            'reminder_message' => "Payment reminder: UGX " . number_format($schedule->total_amount, 0) . 
                " due on " . $schedule->due_date->format('M d, Y'),
        ]);

        return redirect()->back()->with('success', 'Reminder sent to borrower!');
    }

    /**
     * Generate bulk payment report
     */
    public function bulkPaymentReport(Request $request): View
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $payments = \App\Models\PaymentReceipt::whereDate('payment_date', '>=', $startDate)
            ->whereDate('payment_date', '<=', $endDate)
            ->with('loanApplication', 'repaymentSchedule')
            ->orderByDesc('payment_date')
            ->get();

        $summary = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount_paid'),
            'total_principal' => $payments->sum('principal_paid'),
            'total_interest' => $payments->sum('interest_paid'),
        ];

        return view('repayments.bulk-report', compact('payments', 'summary', 'startDate', 'endDate'));
    }
}
