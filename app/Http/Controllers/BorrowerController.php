<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BorrowerController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('staff')->user();
        
        // Get borrowers who have borrowed from this business's loan products
        $borrowers = User::whereHas('loanApplications', function ($query) use ($user) {
            $query->whereHas('product', function ($productQuery) use ($user) {
                $productQuery->where('provider_id', $user->id);
            })->whereIn('status', ['approved', 'active', 'completed']);
        })->with(['loanApplications' => function ($query) use ($user) {
            $query->whereHas('product', function ($productQuery) use ($user) {
                $productQuery->where('provider_id', $user->id);
            });
        }])->latest()
            ->paginate(15);

        return view('borrowers.index', compact('borrowers'));
    }

    public function show(User $borrower): View
    {
        $user = Auth::guard('staff')->user();
        
        // Get only this business's loans for this borrower
        $loans = LoanApplication::where('user_id', $borrower->id)
            ->whereHas('product', function ($query) use ($user) {
                $query->where('provider_id', $user->id);
            })->get();
        
        return view('borrowers.show', compact('borrower', 'loans'));
    }
}

