<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetGuardSessionCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        // Set session cookie name based on route guard
        if ($request->is('customer/*')) {
            config(['session.cookie' => 'customer_session']);
        } elseif ($request->is('login') || $request->is('register') || $request->is('dashboard*') || $request->is('applications*') || $request->is('loans*') || $request->is('repayments*') || $request->is('borrowers*') || $request->is('loan-products*') || $request->is('guarantors*') || $request->is('collaterals*') || $request->is('disbursements*') || $request->is('credit-scores*') || $request->is('tracking*') || $request->is('reports*') || $request->is('automation*') || $request->is('profile*')) {
            config(['session.cookie' => 'staff_session']);
        }

        return $next($request);
    }
}
