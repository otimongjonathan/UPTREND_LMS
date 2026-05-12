<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateStaff
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if staff is authenticated on the staff guard
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        // Ensure the authenticated user is actually a staff member
        $user = Auth::guard('staff')->user();
        if (! User::isStaffRole($user?->role)) {
            Auth::guard('staff')->logout();
            return redirect()->route('login');
        }

        return $next($request);
    }
}
