<?php

namespace App\Http\Controllers;

use App\Models\LoanGuarantor;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoanGuarantorController extends Controller
{
    public function index(LoanApplication $loan): View
    {
        $guarantors = $loan->guarantors()->paginate(10);
        return view('guarantors.index', compact('loan', 'guarantors'));
    }

    public function create(LoanApplication $loan): View
    {
        return view('guarantors.create', compact('loan'));
    }

    public function store(Request $request, LoanApplication $loan): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email',
            'id_number' => 'required|string|max:50',
            'address' => 'required|string',
            'occupation' => 'required|string|max:100',
            'monthly_income' => 'required|numeric|min:0',
            'guarantor_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('guarantor_document')) {
            $validated['guarantor_document_path'] = $request->file('guarantor_document')
                ->store('loan-guarantors', 'public');
        }

        $loan->guarantors()->create($validated);

        return redirect()->route('loans.show', $loan)->with('success', 'Guarantor added successfully.');
    }

    public function edit(LoanGuarantor $guarantor): View
    {
        return view('guarantors.edit', compact('guarantor'));
    }

    public function update(Request $request, LoanGuarantor $guarantor): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email',
            'id_number' => 'required|string|max:50',
            'address' => 'required|string',
            'occupation' => 'required|string|max:100',
            'monthly_income' => 'required|numeric|min:0',
            'guarantor_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('guarantor_document')) {
            $validated['guarantor_document_path'] = $request->file('guarantor_document')
                ->store('loan-guarantors', 'public');
        }

        $guarantor->update($validated);

        return redirect()->route('loans.show', $guarantor->loanApplication)->with('success', 'Guarantor updated successfully.');
    }

    public function destroy(LoanGuarantor $guarantor): RedirectResponse
    {
        $loanId = $guarantor->loan_application_id;
        $guarantor->delete();
        return redirect()->route('loans.show', $loanId)->with('success', 'Guarantor removed.');
    }

    public function approve(LoanGuarantor $guarantor): RedirectResponse
    {
        $guarantor->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Guarantor approved.');
    }

    public function reject(LoanGuarantor $guarantor): RedirectResponse
    {
        $guarantor->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Guarantor rejected.');
    }
}
