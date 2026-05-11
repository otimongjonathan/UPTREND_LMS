<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateCustomer
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if customer is authenticated on the customer guard
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login');
        }

        // Ensure the authenticated user is actually a customer
        $user = Auth::guard('customer')->user();
        if ($user->role !== 'customer') {
            Auth::guard('customer')->logout();
            return redirect()->route('customer.login');
        }

        return $next($request);
    }
}
