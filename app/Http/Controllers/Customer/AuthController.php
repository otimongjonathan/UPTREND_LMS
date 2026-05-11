<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        // If customer already logged in, redirect to home
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.home');
        }

        return view('customer.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        // If already logged in as customer, redirect to home
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.home');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Invalid login credentials.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('customer.home');
    }

    public function showRegister(): View|RedirectResponse
    {
        // If customer already logged in, redirect to home
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.home');
        }

        return view('customer.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        // If already logged in as customer, redirect to home
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.home');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'business_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'tel_no' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'customer',
            'business_name' => $data['business_name'],
            'address' => $data['address'],
            'tel_no' => $data['tel_no'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::guard('customer')->login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

