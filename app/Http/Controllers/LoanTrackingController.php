<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\Repayment;
use App\Models\PaymentReceipt;
use App\Services\LoanTrackingService;
use App\Services\RepaymentWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanTrackingController extends Controller
{
    /**
     * Show active loans dashboard with tracking
     */
    public function activeLoans()
    {
        $user = Auth::user();
        
        $loans = LoanTrackingService::getActiveLoanTracking($user->id);
        $overdueLoans = LoanTrackingService::getOverdueLoans($user->id);
        $repaymentSummary = LoanTrackingService::getRepaymentSummary($user->id);

        return view('tracking.active-loans', [
            'loans' => $loans,
            'overdueLoans' => $overdueLoans,
            'repaymentSummary' => $repaymentSummary,
            'paymentDistribution' => LoanTrackingService::getPaymentDistribution($user->id),
        ]);
    }

    /**
     * Show detailed tracking for a single loan
     */
    public function trackLoan(LoanApplication $loan)
    {
        return redirect()->route('loans.show', $loan);
    }

    /**
     * Show repayment schedule
     */
    public function repaymentSchedule(LoanApplication $loan)
    {
        $user = Auth::user();
        
        if (!$loan->product || $loan->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized access to this loan.');
        }

        $schedule = RepaymentWorkflowService::getRepaymentSchedule($loan);

        return view('tracking.repayment-schedule', compact('schedule', 'loan'));
    }

    /**
     * Record a payment
     */
    public function recordPayment(Repayment $repayment)
    {
        $user = Auth::user();
        
        if (!$repayment->loanApplication->product || 
            $repayment->loanApplication->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized to record payment for this repayment.');
        }

        return view('tracking.record-payment', compact('repayment'));
    }

    /**
     * Store payment record
     */
    public function storePayment(Request $request, Repayment $repayment)
    {
        $user = Auth::user();
        
        if (!$repayment->loanApplication->product || 
            $repayment->loanApplication->product->provider_id !== $user->id) {
            abort(403, 'Unauthorized to record payment for this repayment.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,check,other',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $receipt = RepaymentWorkflowService::recordPayment($repayment, $validated);

        return redirect()
            ->route('tracking.loan-detail', $repayment->loanApplication)
            ->with('success', "Payment recorded successfully. Receipt: {$receipt->receipt_number}");
    }

    /**
     * Show repayment analytics dashboard
     */
    public function analytics()
    {
        $user = Auth::user();
        
        $analytics = RepaymentWorkflowService::getRepaymentAnalytics($user->id);
        $monthlySummary = LoanTrackingService::getRepaymentSummary($user->id, 'monthly');
        $annualSummary = LoanTrackingService::getRepaymentSummary($user->id, 'yearly');
        $paymentDistribution = LoanTrackingService::getPaymentDistribution($user->id);

        return view('tracking.analytics', [
            'analytics' => $analytics,
            'monthlySummary' => $monthlySummary,
            'annualSummary' => $annualSummary,
            'paymentDistribution' => $paymentDistribution,
        ]);
    }

    /**
     * Show overdue loans
     */
    public function overdueLoans()
    {
        $user = Auth::user();
        
        $overdueLoans = LoanTrackingService::getOverdueLoans($user->id);
        $overdueRepayments = Repayment::whereHas('loanApplication.product', function ($query) use ($user) {
            $query->where('provider_id', $user->id);
        })->where('status', 'pending')
            ->where('due_date', '<', now()->toDateString())
            ->with(['loanApplication.user'])
            ->orderBy('days_overdue', 'desc')
            ->paginate(15);

        return view('tracking.overdue-loans', [
            'overdueLoans' => $overdueLoans,
            'overdueRepayments' => $overdueRepayments,
        ]);
    }

    /**
     * Export payment report
     */
    public function exportPaymentReport(Request $request)
    {
        $user = Auth::user();
        
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        
        $query = PaymentReceipt::whereHas('loanApplication.product', function ($q) use ($user) {
            $q->where('provider_id', $user->id);
        });

        if ($fromDate) {
            $query->where('payment_date', '>=', $fromDate);
        }
        
        if ($toDate) {
            $query->where('payment_date', '<=', $toDate);
        }

        $payments = $query->with(['loanApplication.user', 'repayment'])
            ->orderBy('payment_date', 'desc')
            ->get();

        // Generate CSV
        $csv = "Receipt Number,Payment Date,Customer Name,Loan ID,Amount Paid,Payment Method,Reference\n";
        
        foreach ($payments as $payment) {
            $csv .= "\"{$payment->receipt_number}\"," .
                    "\"{$payment->payment_date->format('Y-m-d')}\"," .
                    "\"{$payment->loanApplication->user->name}\"," .
                    "\"{$payment->loan_application_id}\"," .
                    "{$payment->amount_paid}," .
                    "\"{$payment->payment_method}\"," .
                    "\"{$payment->reference_number}\"\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="payment-report-' . date('Y-m-d') . '.csv"');
    }

    /**
     * Export loan tracking report
     */
    public function exportLoanReport(Request $request)
    {
        $user = Auth::user();
        
        $loans = LoanApplication::with(['user', 'product', 'repayments'])
            ->whereHas('product', function ($query) use ($user) {
                $query->where('provider_id', $user->id);
            })
            ->where('status', '!=', 'pending')
            ->get();

        $csv = "Loan ID,Customer,Loan Amount,Total Repayable,Paid,Outstanding,Status,Progress\n";
        
        foreach ($loans as $loan) {
            $repayments = $loan->repayments;
            $paidAmount = $repayments->where('status', 'completed')->sum('paid_amount') +
                         $repayments->where('status', 'partial')->sum('paid_amount');
            $totalRepayable = $loan->total_repayable ?? $loan->amount;
            $outstanding = $totalRepayable - $paidAmount;
            $progress = $repayments->count() > 0 
                ? round(($repayments->where('status', 'completed')->count() / $repayments->count()) * 100, 2)
                : 0;

            $csv .= "\"{$loan->id}\"," .
                    "\"{$loan->user->name}\"," .
                    "{$loan->amount}," .
                    "{$totalRepayable}," .
                    "{$paidAmount}," .
                    "{$outstanding}," .
                    "\"{$loan->status}\"," .
                    "{$progress}%\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="loan-report-' . date('Y-m-d') . '.csv"');
    }

    /**
     * Send payment reminders
     */
    public function sendReminders()
    {
        $sentCount = RepaymentWorkflowService::sendPaymentReminders();

        return redirect()->back()
            ->with('success', "Payment reminders sent to {$sentCount} borrowers.");
    }
}
