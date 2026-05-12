<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('customer.profile.index', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'business_name' => ['required', 'string', 'max:255', Rule::unique('users', 'business_name')->ignore($user->id)],
            'tel_no' => ['required', 'string', 'max:20', Rule::unique('users', 'tel_no')->ignore($user->id)],
            'address' => ['required', 'string', 'max:500'],
        ]);

        $user->update($data);

        return redirect()->route('customer.profile')->with('status', 'Profile updated successfully.');
    }
}

