<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $applications = LoanApplication::where('user_id', $userId)->count();
        $pending = LoanApplication::where('user_id', $userId)->where('status', 'pending')->count();
        
        // Get recommended loan products (top 3 active products)
        $recommendedProducts = LoanProduct::with('provider')
            ->where('is_active', true)
            ->orderBy('interest_rate', 'asc') // Lowest interest rate first
            ->take(3)
            ->get();

        return view('customer.home', [
            'applications' => $applications,
            'pending' => $pending,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }
}

