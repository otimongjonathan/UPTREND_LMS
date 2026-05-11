<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $applications = LoanApplication::where('user_id', $userId)->count();
        $pending = LoanApplication::where('user_id', $userId)->where('status', 'pending')->count();

        return view('customer.home', [
            'applications' => $applications,
            'pending' => $pending,
        ]);
    }
}

