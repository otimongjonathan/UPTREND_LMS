<?php

namespace App\Http\Controllers;

use App\Models\CreditScore;
use App\Models\User;
use App\Services\CreditScoringService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CreditScoringController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        
        // Get credit scores for users who have borrowed from this business
        $query = CreditScore::whereHas('user', function ($userQuery) use ($user) {
            $userQuery->whereHas('loanApplications', function ($appQuery) use ($user) {
                $appQuery->whereHas('product', function ($productQuery) use ($user) {
                    $productQuery->where('provider_id', $user->id);
                });
            });
        })->with('user');
        
        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by risk level
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }
        
        // Filter by score range
        if ($request->filled('score_min')) {
            $query->where('score', '>=', $request->score_min);
        }
        if ($request->filled('score_max')) {
            $query->where('score', '<=', $request->score_max);
        }
        
        $creditScores = $query->latest()->paginate(15)->appends($request->query());
        
        return view('credit-scores.index', compact('creditScores'));
    }

    public function show(CreditScore $creditScore): View
    {
        $user = Auth::user();
        $borrower = $creditScore->user;
        
        // Get only this business's loans for this borrower
        $loans = $borrower->loanApplications()
            ->whereHas('product', function ($query) use ($user) {
                $query->where('provider_id', $user->id);
            })->get();
        
        return view('credit-scores.show', compact('creditScore', 'borrower', 'loans'));
    }

    public function calculate(User $user): RedirectResponse
    {
        $authUser = Auth::user();
        
        // Only calculate credit score for customers of this business
        $hasLoan = $user->loanApplications()
            ->whereHas('product', function ($query) use ($authUser) {
                $query->where('provider_id', $authUser->id);
            })->exists();
        
        if (!$hasLoan) {
            return redirect()->back()->with('error', 'This customer has not borrowed from your business.');
        }
        
        CreditScoringService::calculateCreditScore($user);
        
        return redirect()->route('credit-scores.show', $user->creditScore)
            ->with('success', 'Credit score calculated successfully.');
    }

    public function recalculateAll(): RedirectResponse
    {
        $authUser = Auth::user();
        
        // Get customers who have borrowed from this business
        $users = User::where('role', 'customer')
            ->whereHas('loanApplications', function ($query) use ($authUser) {
                $query->whereHas('product', function ($productQuery) use ($authUser) {
                    $productQuery->where('provider_id', $authUser->id);
                });
            })->get();
        
        foreach ($users as $user) {
            CreditScoringService::calculateCreditScore($user);
        }
        
        return redirect()->back()->with('success', 'All credit scores for your customers recalculated.');
    }

    public function export()
    {
        $user = Auth::user();
        
        // Get credit scores for customers of this business
        $creditScores = CreditScore::whereHas('user', function ($query) use ($user) {
            $query->whereHas('loanApplications', function ($appQuery) use ($user) {
                $appQuery->whereHas('product', function ($productQuery) use ($user) {
                    $productQuery->where('provider_id', $user->id);
                });
            });
        })->with('user')
            ->orderBy('score', 'desc')
            ->get();
        
        $csv = "User ID,Name,Email,Score,Risk Level,Completed Loans,Defaulted Loans,Default Rate,On-Time Rate\n";
        
        foreach ($creditScores as $score) {
            $csv .= "{$score->user_id}," .
                   "{$score->user->name}," .
                   "{$score->user->email}," .
                   "{$score->score}," .
                   "{$score->risk_level}," .
                   "{$score->completed_loans}," .
                   "{$score->defaulted_loans}," .
                   "{$score->default_rate}," .
                   "{$score->on_time_payment_rate}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="credit-scores-' . date('Y-m-d') . '.csv"');
    }
}

