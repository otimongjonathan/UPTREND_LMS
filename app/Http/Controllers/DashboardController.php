<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\LoanRepaymentSchedule;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('staff')->user();
        
        // Get applications for current provider's loan products only
        $applicationsQuery = LoanApplication::whereHas('product', function ($query) use ($user) {
            $query->where('provider_id', $user->id);
        });
        
        $stats = [
            'total_applications' => $applicationsQuery->count(),
            'pending_applications' => (clone $applicationsQuery)->where('status', 'pending')->count(),
            'approved_applications' => (clone $applicationsQuery)->where('status', 'approved')->count(),
            'active_loans' => (clone $applicationsQuery)->where('status', 'active')->count(),
            'completed_loans' => (clone $applicationsQuery)->where('status', 'completed')->count(),
            'overdue_loans' => (clone $applicationsQuery)->where('status', 'overdue')->count(),
            'rejected_applications' => (clone $applicationsQuery)->where('status', 'rejected')->count(),
            'total_loan_amount' => (clone $applicationsQuery)->whereIn('status', ['approved', 'active', 'completed'])->sum('amount'),
            'total_borrowers' => User::whereHas('loanApplications', function ($query) use ($user) {
                $query->whereHas('product', function ($subQuery) use ($user) {
                    $subQuery->where('provider_id', $user->id);
                })->whereIn('status', ['approved', 'active', 'completed']);
            })->count(),
            'overdue_repayments' => LoanRepaymentSchedule::whereHas('loanApplication.product', function ($query) use ($user) {
                $query->where('provider_id', $user->id);
            })->where('installments_overdue', '>', 0)->count(),
            'total_repaid' => LoanRepaymentSchedule::whereHas('loanApplication.product', function ($query) use ($user) {
                $query->where('provider_id', $user->id);
            })->sum('total_paid'),
        ];

        return view('dashboard', compact('stats'));
    }
}

