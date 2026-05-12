<?php

namespace App\Http\Controllers;

use App\Models\Collateral;
use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class CollateralController extends Controller
{
    public function index(LoanApplication $loan): View
    {
        $collaterals = $loan->collaterals()->with('loanSupervisor')->latest()->paginate(10);
        $totalValue = $loan->collaterals()->sum('estimated_value');
        
        return view('collaterals.index', compact('loan', 'collaterals', 'totalValue'));
    }

    public function create(LoanApplication $loan): View
    {
        $collateralTypes = Collateral::COLLATERAL_TYPES;
        $supervisors = User::query()
            ->get()
            ->filter(fn ($user) => User::isStaffRole($user->role))
            ->sortBy('name')
            ->values();

        return view('collaterals.create', compact('loan', 'collateralTypes', 'supervisors'));
    }

    public function store(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'collateral_type' => ['required', Rule::in(Collateral::COLLATERAL_TYPES)],
            'loan_supervisor_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'staff')),
            ],
            'description' => 'required|string',
            'estimated_value' => 'required|numeric|min:0',
            'valuation_date' => 'required|date',
            'collateral_document' => 'required|file|mimes:pdf|max:5120',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('collateral_document')) {
            $validated['collateral_document_path'] = $request->file('collateral_document')
                ->store('collateral-proofs', 'public');
        }

        $loan->collaterals()->create($validated);

        return redirect()->route('loans.show', $loan)->with('success', 'Collateral added successfully.');
    }

    public function edit(Collateral $collateral): View
    {
        $loan = $collateral->loanApplication;
        $collateralTypes = Collateral::COLLATERAL_TYPES;
        $supervisors = User::query()
            ->get()
            ->filter(fn ($user) => User::isStaffRole($user->role))
            ->sortBy('name')
            ->values();

        return view('collaterals.edit', compact('loan', 'collateral', 'collateralTypes', 'supervisors'));
    }

    public function update(Request $request, Collateral $collateral): RedirectResponse
    {
        $validated = $request->validate([
            'collateral_type' => ['required', Rule::in(Collateral::COLLATERAL_TYPES)],
            'loan_supervisor_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'staff')),
            ],
            'description' => 'required|string',
            'estimated_value' => 'required|numeric|min:0',
            'valuation_date' => 'required|date',
            'collateral_document' => 'nullable|file|mimes:pdf|max:5120',
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('collateral_document')) {
            $validated['collateral_document_path'] = $request->file('collateral_document')
                ->store('collateral-proofs', 'public');
        }

        $collateral->update($validated);

        return redirect()->route('loans.show', $collateral->loanApplication)->with('success', 'Collateral updated successfully.');
    }

    public function destroy(Collateral $collateral): RedirectResponse
    {
        $loan = $collateral->loanApplication;
        Collateral::query()->whereKey($collateral->getKey())->delete();
        return redirect()->route('collaterals.index', $loan)->with('success', 'Collateral removed.');
    }

    public function verify(Collateral $collateral): RedirectResponse
    {
        $collateral->update(['status' => 'verified']);
        return redirect()->back()->with('success', 'Collateral verified.');
    }

    public function reject(Collateral $collateral): RedirectResponse
    {
        $collateral->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Collateral rejected.');
    }
}
