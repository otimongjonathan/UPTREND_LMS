<?php

namespace App\Http\Controllers;

use App\Models\Collateral;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CollateralController extends Controller
{
    public function index(LoanApplication $loan): View
    {
        $collaterals = $loan->collaterals()->paginate(10);
        $totalValue = $loan->collaterals()->sum('estimated_value');
        
        return view('collaterals.index', compact('loan', 'collaterals', 'totalValue'));
    }

    public function create(LoanApplication $loan): View
    {
        return view('collaterals.create', compact('loan'));
    }

    public function store(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'collateral_type' => 'required|string|max:100',
            'description' => 'required|string',
            'estimated_value' => 'required|numeric|min:0',
            'valuation_date' => 'required|date',
            'collateral_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('collateral_document')) {
            $validated['collateral_document_path'] = $request->file('collateral_document')
                ->store('loan-collaterals', 'public');
        }

        $loan->collaterals()->create($validated);

        return redirect()->route('loans.show', $loan)->with('success', 'Collateral added successfully.');
    }

    public function edit(Collateral $collateral): View
    {
        return view('collaterals.edit', compact('collateral'));
    }

    public function update(Request $request, Collateral $collateral): RedirectResponse
    {
        $validated = $request->validate([
            'collateral_type' => 'required|string|max:100',
            'description' => 'required|string',
            'estimated_value' => 'required|numeric|min:0',
            'valuation_date' => 'required|date',
            'collateral_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('collateral_document')) {
            $validated['collateral_document_path'] = $request->file('collateral_document')
                ->store('loan-collaterals', 'public');
        }

        $collateral->update($validated);

        return redirect()->route('loans.show', $collateral->loanApplication)->with('success', 'Collateral updated successfully.');
    }

    public function destroy(Collateral $collateral): RedirectResponse
    {
        $loanId = $collateral->loan_application_id;
        $collateral->delete();
        return redirect()->route('loans.show', $loanId)->with('success', 'Collateral removed.');
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
