<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\LoanRepaymentSchedule;
use App\Services\RepaymentScheduleService;
use Illuminate\Http\Request;

class RepaymentScheduleController extends Controller
{
    public function index()
    {
        $schedules = LoanRepaymentSchedule::with('loanApplication.user')
            ->where('status', '!=', 'completed')
            ->orderBy('first_payment_date')
            ->paginate(20);
        
        return view('repayment-schedules.index', compact('schedules'));
    }

    public function show(LoanRepaymentSchedule $schedule)
    {
        $schedule->load('loanApplication.user', 'loanDisbursement');
        
        return view('repayment-schedules.show', compact('schedule'));
    }

    public function recordPayment(Request $request, LoanRepaymentSchedule $schedule)
    {
        $validated = $request->validate([
            'installment_number' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'payment_reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        RepaymentScheduleService::recordPayment(
            $schedule, 
            $validated['installment_number'],
            $validated['amount'], 
            $validated
        );

        // Send notification to customer
        $installments = $schedule->installments;
        $installment = collect($installments)->firstWhere('installment_number', $validated['installment_number']);
        
        if ($installment) {
            $schedule->loanApplication->user->notify(
                new \App\Notifications\RepaymentReceived(
                    $schedule->loan_application_id,
                    $validated['installment_number'],
                    $validated['amount'],
                    $schedule->total_outstanding,
                    now()->format('M d, Y')
                )
            );
        }

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    public function regenerate(LoanApplication $loan)
    {
        if ($loan->disbursements()->where('status', 'disbursed')->exists()) {
            $disbursement = $loan->disbursements()->where('status', 'disbursed')->first();
            RepaymentScheduleService::generateSchedule($disbursement);
            return redirect()->back()->with('success', 'Repayment schedule regenerated successfully!');
        }
        
        return redirect()->back()->with('error', 'No disbursed loan found.');
    }
}
