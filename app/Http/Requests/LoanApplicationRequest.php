<?php

namespace App\Http\Requests;

use App\Rules\LoanProductLimits;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoanApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->input('loan_product_id');
        
        return [
            'loan_product_id' => ['required', 'exists:loan_products,id'],
            'applicant_full_name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date', 'before:today'],
            'district_city' => ['required', 'string', 'max:255'],
            'county' => ['required', 'string', 'max:255'],
            'sub_county' => ['required', 'string', 'max:255'],
            'parish' => ['required', 'string', 'max:255'],
            'village' => ['required', 'string', 'max:255'],
            'residence_status' => ['required', Rule::in(['owner', 'tenant'])],
            'po_box' => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'marital_status' => ['required', Rule::in(['single', 'married', 'divorced', 'widowed'])],
            'employment_status' => ['required', Rule::in(['employed', 'self_employed', 'unemployed', 'student', 'retired'])],
            'occupation' => ['required', 'string', 'max:255'],
            'loan_type' => ['required', 'string', 'max:255'],
            'amount' => [
                'required', 
                'numeric', 
                'min:1',
                new LoanProductLimits($productId, 'amount')
            ],
            'term_months' => [
                'required',
                'integer',
                'min:1',
                new LoanProductLimits($productId, 'term')
            ],
            'repayment_schedule' => ['required', Rule::in(['weekly', 'bi_weekly', 'monthly', 'quarterly'])],
            'purpose' => ['required', 'string', 'max:500'],
            'monthly_income' => ['required', 'numeric', 'min:0'],
            'police_letter' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'financial_statement' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'national_id' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'loan_guarantee_one' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'loan_guarantee_two' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'proof_of_residence' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'loan_product_id.required' => 'Please select a loan product.',
            'loan_product_id.exists' => 'The selected loan product is invalid.',
            'amount.required' => 'Please enter the loan amount.',
            'amount.numeric' => 'The loan amount must be a valid number.',
            'term_months.required' => 'Please specify the loan term in months.',
            'term_months.integer' => 'The loan term must be a whole number of months.',
            'monthly_income.required' => 'Please enter your monthly income.',
            'monthly_income.numeric' => 'Monthly income must be a valid number.',
        ];
    }
}