<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'tel_no' => ['required', 'string', 'max:20'],
            'financial_compliance_statement' => ['required', 'file', 'mimes:pdf', 'max:5120'], // 5MB max
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Handle file upload
        $complianceFilePath = null;
        if ($request->hasFile('financial_compliance_statement')) {
            $complianceFilePath = $request->file('financial_compliance_statement')->store('compliance-statements', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'business_name' => $request->business_name,
            'address' => $request->address,
            'email' => $request->email,
            'role' => 'staff',
            'tel_no' => $request->tel_no,
            'financial_compliance_statement' => $complianceFilePath,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::guard('staff')->login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
