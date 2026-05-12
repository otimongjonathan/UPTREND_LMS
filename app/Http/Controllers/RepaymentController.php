<?php

namespace App\Http\Controllers;

use App\Models\LoanRepaymentSchedule;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('staff')->user();
        
        $query = LoanRepaymentSchedule::with('loanApplication.user', 'loanApplication.product')
            ->whereHas('loanApplication.product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            });

        if ($request->filled('loan_id')) {
            $query->where('loan_application_id', $request->loan_id);
        }

        $schedules = $query->latest('created_at')->paginate(15);

        $summary = [
            'total_due' => LoanRepaymentSchedule::whereHas('loanApplication.product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            })->sum('total_outstanding'),
            'total_paid' => LoanRepaymentSchedule::whereHas('loanApplication.product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            })->sum('total_paid'),
            'overdue' => LoanRepaymentSchedule::whereHas('loanApplication.product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            })->where('installments_overdue', '>', 0)->count(),
            'pending' => LoanRepaymentSchedule::whereHas('loanApplication.product', function ($q) use ($user) {
                $q->where('provider_id', $user->id);
            })->where('installments_pending', '>', 0)->count(),
        ];

        return view('repayments.index', compact('schedules', 'summary'));
    }


}
