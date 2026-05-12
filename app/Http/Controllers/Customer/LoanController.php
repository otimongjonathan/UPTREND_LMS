<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class LoanController extends Controller
{
    public function index(Request $request): View
    {
        $query = LoanApplication::where('user_id', auth()->id());

        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'issued', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $loans = $query->latest()->paginate(10);

        return view('customer.loans.index', [
            'loans' => $loans,
        ]);
    }

    public function show(LoanApplication $loan): View
    {
        $this->authorize('view', $loan);
        $loan->load('repayments');
        return view('customer.loans.show', compact('loan'));
    }

    public function create(Request $request): View
    {
        $product = null;
        
        if ($request->has('product_id')) {
            $product = LoanProduct::findOrFail($request->product_id);
        }
        
        return view('customer.loans.apply', compact('product'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'loan_product_id' => ['nullable', 'exists:loan_products,id'],
            'applicant_full_name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date', 'before:today'],
            'district_city' => ['required', 'string', 'max:255'],
            'county' => ['required', 'string', 'max:255'],
            'sub_county' => ['required', 'string', 'max:255'],
            'parish' => ['required', 'string', 'max:255'],
            'village' => ['required', 'string', 'max:255'],
            'residence_status' => ['required', Rule::in(['permanent', 'temporary'])],
            'po_box' => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'marital_status' => ['required', Rule::in(['single', 'married', 'divorced', 'widowed'])],
            'employment_status' => ['required', Rule::in(['employed', 'self_employed', 'unemployed', 'student', 'retired'])],
            'occupation' => ['required', 'string', 'max:255'],
            'loan_type' => ['required', Rule::in(['salary_loan', 'business_loan', 'education_loan'])],
            'amount' => ['required', 'numeric', 'min:1'],
            'repayment_schedule' => ['required', Rule::in(['weekly', 'bi_weekly', 'monthly', 'quarterly'])],
            'purpose' => ['required', 'string', 'max:500'],
            'police_letter' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'financial_statement' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'national_id' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'loan_guarantee_one' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'loan_guarantee_two' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'proof_of_residence' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $policeLetterPath = $request->file('police_letter')->store('loan-documents', 'public');
        $financialStatementPath = $request->file('financial_statement')->store('loan-documents', 'public');
        $nationalIdPath = $request->file('national_id')->store('loan-documents', 'public');
        $loanGuaranteeOnePath = $request->file('loan_guarantee_one')->store('loan-documents', 'public');
        $loanGuaranteeTwoPath = $request->file('loan_guarantee_two')->store('loan-documents', 'public');
        $proofOfResidencePath = $request->file('proof_of_residence')->store('loan-documents', 'public');

        $application = LoanApplication::create([
            'user_id' => auth()->id(),
            'loan_product_id' => $data['loan_product_id'],
            'applicant_full_name' => $data['applicant_full_name'],
            'dob' => $data['dob'],
            'application_date' => now(),
            'district_city' => $data['district_city'],
            'county' => $data['county'],
            'sub_county' => $data['sub_county'],
            'parish' => $data['parish'],
            'village' => $data['village'],
            'residence_status' => $data['residence_status'],
            'po_box' => $data['po_box'],
            'gender' => $data['gender'],
            'marital_status' => $data['marital_status'],
            'employment_status' => $data['employment_status'],
            'occupation' => $data['occupation'],
            'loan_type' => $data['loan_type'],
            'repayment_schedule' => $data['repayment_schedule'],
            'police_letter_path' => $policeLetterPath,
            'financial_statement_path' => $financialStatementPath,
            'national_id_path' => $nationalIdPath,
            'loan_guarantee_one_path' => $loanGuaranteeOnePath,
            'loan_guarantee_two_path' => $loanGuaranteeTwoPath,
            'proof_of_residence_path' => $proofOfResidencePath,
            'amount' => $data['amount'],
            'term_months' => 1,
            'purpose' => $data['purpose'],
            'monthly_income' => 0,
            'notes' => null,
            'status' => 'pending',
        ]);

        // Notify staff about new application
        \App\Services\ComprehensiveNotificationService::notifyStaffNewApplication($application);

        return redirect()->route('customer.loans.index')->with('status', 'Application received, We will get back to you within 3 business days');
    }

    public function edit(LoanApplication $loan): View
    {
        $this->authorize('update', $loan);
        
        if ($loan->status !== 'pending') {
            abort(403, 'Can only edit pending loan applications.');
        }

        return view('customer.loans.edit', compact('loan'));
    }

    public function update(Request $request, LoanApplication $loan): RedirectResponse
    {
        $this->authorize('update', $loan);

        if ($loan->status !== 'pending') {
            abort(403, 'Can only edit pending loan applications.');
        }

        $data = $request->validate([
            'applicant_full_name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date', 'before:today'],
            'district_city' => ['required', 'string', 'max:255'],
            'county' => ['required', 'string', 'max:255'],
            'sub_county' => ['required', 'string', 'max:255'],
            'parish' => ['required', 'string', 'max:255'],
            'village' => ['required', 'string', 'max:255'],
            'residence_status' => ['required', Rule::in(['permanent', 'temporary'])],
            'po_box' => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'marital_status' => ['required', Rule::in(['single', 'married', 'divorced', 'widowed'])],
            'employment_status' => ['required', Rule::in(['employed', 'self_employed', 'unemployed', 'student', 'retired'])],
            'occupation' => ['required', 'string', 'max:255'],
            'loan_type' => ['required', Rule::in(['salary_loan', 'business_loan', 'education_loan'])],
            'amount' => ['required', 'numeric', 'min:1'],
            'repayment_schedule' => ['required', Rule::in(['weekly', 'bi_weekly', 'monthly', 'quarterly'])],
            'purpose' => ['required', 'string', 'max:500'],
            'police_letter' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'financial_statement' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'national_id' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'loan_guarantee_one' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'loan_guarantee_two' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'proof_of_residence' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        // Update document paths if provided
        if ($request->hasFile('police_letter')) {
            if ($loan->police_letter_path) Storage::disk('public')->delete($loan->police_letter_path);
            $data['police_letter_path'] = $request->file('police_letter')->store('loan-documents', 'public');
        }

        if ($request->hasFile('financial_statement')) {
            if ($loan->financial_statement_path) Storage::disk('public')->delete($loan->financial_statement_path);
            $data['financial_statement_path'] = $request->file('financial_statement')->store('loan-documents', 'public');
        }

        if ($request->hasFile('national_id')) {
            if ($loan->national_id_path) Storage::disk('public')->delete($loan->national_id_path);
            $data['national_id_path'] = $request->file('national_id')->store('loan-documents', 'public');
        }

        if ($request->hasFile('loan_guarantee_one')) {
            if ($loan->loan_guarantee_one_path) Storage::disk('public')->delete($loan->loan_guarantee_one_path);
            $data['loan_guarantee_one_path'] = $request->file('loan_guarantee_one')->store('loan-documents', 'public');
        }

        if ($request->hasFile('loan_guarantee_two')) {
            if ($loan->loan_guarantee_two_path) Storage::disk('public')->delete($loan->loan_guarantee_two_path);
            $data['loan_guarantee_two_path'] = $request->file('loan_guarantee_two')->store('loan-documents', 'public');
        }

        if ($request->hasFile('proof_of_residence')) {
            if ($loan->proof_of_residence_path) Storage::disk('public')->delete($loan->proof_of_residence_path);
            $data['proof_of_residence_path'] = $request->file('proof_of_residence')->store('loan-documents', 'public');
        }

        // Remove file keys if they weren't provided
        if (!$request->hasFile('police_letter')) unset($data['police_letter']);
        if (!$request->hasFile('financial_statement')) unset($data['financial_statement']);
        if (!$request->hasFile('national_id')) unset($data['national_id']);
        if (!$request->hasFile('loan_guarantee_one')) unset($data['loan_guarantee_one']);
        if (!$request->hasFile('loan_guarantee_two')) unset($data['loan_guarantee_two']);
        if (!$request->hasFile('proof_of_residence')) unset($data['proof_of_residence']);

        $loan->update($data);

        return redirect()->route('customer.loans.show', $loan)->with('success', 'Application updated successfully.');
    }

    public function downloadDocument(LoanApplication $loan, $field)
    {
        $this->authorize('view', $loan);

        $allowedFields = [
            'police_letter_path',
            'financial_statement_path',
            'national_id_path',
            'loan_guarantee_one_path',
            'loan_guarantee_two_path',
            'proof_of_residence_path',
        ];

        if (!in_array($field, $allowedFields) || !$loan->$field) {
            abort(404, 'Document not found.');
        }

        $path = $loan->$field;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found on disk.');
        }

        return Storage::disk('public')->download($path);
    }
}


