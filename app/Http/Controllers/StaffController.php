<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staffMembers = User::query()
            ->get()
            ->filter(fn ($user) => User::isStaffRole($user->role))
            ->sortByDesc('created_at')
            ->values();

        return view('staff.index', compact('staffMembers'));
    }

    public function create(): View
    {
        return view('staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'tel_no' => ['required', 'string', 'max:20', 'unique:users,tel_no'],
            'role' => ['required', 'string', 'in:' . implode(',', User::STAFF_ROLES)],
        ]);

        $temporaryPassword = Str::random(32);

        $user = User::create([
            'name' => $validated['name'],
            'business_name' => null,
            'address' => $validated['address'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'tel_no' => $validated['tel_no'],
            'financial_compliance_statement' => null,
            'password' => Hash::make($temporaryPassword),
        ]);

        Password::sendResetLink(['email' => $user->email]);

        return redirect()->route('staff.index')->with('success', 'Staff member added successfully. A password setup email has been sent.');
    }
}