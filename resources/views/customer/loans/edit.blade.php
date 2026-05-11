@extends('customer.layouts.app')

@section('content')
    <div class="mb-6 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow-lg card-hover">
        <h1 class="text-2xl font-extrabold uppercase tracking-wide">EDIT LOAN APPLICATION</h1>
        <p class="text-blue-100 mt-1">Update your pending loan application details. Once approved, you can no longer make changes.</p>
    </div>

    <div class="bg-white border border-blue-100 rounded-2xl p-6 shadow-sm card-hover">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-extrabold uppercase tracking-wide text-gray-900">EDIT APPLICATION #{{ $loan->id }}</h1>
                <p class="text-sm text-gray-600 mt-1">Update the information below and upload new PDFs if needed.</p>
            </div>
            <a href="{{ route('customer.loans.show', $loan->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                ← Back
            </a>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-lg bg-red-50 text-red-700 px-4 py-3 text-sm">
                <p class="font-semibold">Please fix the following errors:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.loans.update', $loan->id) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @method('PATCH')

            <!-- Applicant Details -->
            <div class="rounded-xl border border-blue-100 p-4">
                <h3 class="text-sm font-bold uppercase tracking-wide text-blue-700">Applicant Details</h3>
                <div class="mt-4 grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Applicant Full Name (As on ID)</label>
                        <input type="text" name="applicant_full_name" value="{{ old('applicant_full_name', $loan->applicant_full_name) }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('applicant_full_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date of Birth (DOB)</label>
                        <input type="date" name="dob" value="{{ old('dob', $loan->dob?->format('Y-m-d')) }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('dob') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <select name="gender" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="male" @selected(old('gender', $loan->gender) === 'male')>Male</option>
                            <option value="female" @selected(old('gender', $loan->gender) === 'female')>Female</option>
                        </select>
                        @error('gender') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Marital Status</label>
                        <select name="marital_status" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="single" @selected(old('marital_status', $loan->marital_status) === 'single')>Single</option>
                            <option value="married" @selected(old('marital_status', $loan->marital_status) === 'married')>Married</option>
                            <option value="divorced" @selected(old('marital_status', $loan->marital_status) === 'divorced')>Divorced</option>
                            <option value="widowed" @selected(old('marital_status', $loan->marital_status) === 'widowed')>Widowed</option>
                        </select>
                        @error('marital_status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Employment Status</label>
                        <select name="employment_status" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="employed" @selected(old('employment_status', $loan->employment_status) === 'employed')>Employed</option>
                            <option value="self_employed" @selected(old('employment_status', $loan->employment_status) === 'self_employed')>Self Employed</option>
                            <option value="unemployed" @selected(old('employment_status', $loan->employment_status) === 'unemployed')>Unemployed</option>
                            <option value="student" @selected(old('employment_status', $loan->employment_status) === 'student')>Student</option>
                            <option value="retired" @selected(old('employment_status', $loan->employment_status) === 'retired')>Retired</option>
                        </select>
                        @error('employment_status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Occupation</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $loan->occupation) }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('occupation') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Residence Address -->
            <div class="rounded-xl border border-blue-100 p-4">
                <h3 class="text-sm font-bold uppercase tracking-wide text-blue-700">Residence Address</h3>
                <div class="mt-4 grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">District/City</label>
                        <input type="text" name="district_city" value="{{ old('district_city', $loan->district_city) }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('district_city') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">County</label>
                        <input type="text" name="county" value="{{ old('county', $loan->county) }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('county') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sub County</label>
                        <input type="text" name="sub_county" value="{{ old('sub_county', $loan->sub_county) }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('sub_county') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Parish</label>
                        <input type="text" name="parish" value="{{ old('parish', $loan->parish) }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('parish') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Village</label>
                        <input type="text" name="village" value="{{ old('village', $loan->village) }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('village') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">P.O. Box</label>
                        <input type="text" name="po_box" value="{{ old('po_box', $loan->po_box) }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('po_box') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Residence Status</label>
                        <select name="residence_status" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="permanent" @selected(old('residence_status', $loan->residence_status) === 'permanent')>Permanent</option>
                            <option value="temporary" @selected(old('residence_status', $loan->residence_status) === 'temporary')>Temporary</option>
                        </select>
                        @error('residence_status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Loan Details -->
            <div class="rounded-xl border border-blue-100 p-4">
                <h3 class="text-sm font-bold uppercase tracking-wide text-blue-700">Loan Details</h3>
                <div class="mt-4 grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Loan Type</label>
                        <select name="loan_type" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="salary_loan" @selected(old('loan_type', $loan->loan_type) === 'salary_loan')>Salary Loan</option>
                            <option value="business_loan" @selected(old('loan_type', $loan->loan_type) === 'business_loan')>Business Loan</option>
                            <option value="education_loan" @selected(old('loan_type', $loan->loan_type) === 'education_loan')>Education Loan</option>
                        </select>
                        @error('loan_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Loan Amount (UGX)</label>
                        <input type="number" name="amount" value="{{ old('amount', $loan->amount) }}" step="0.01" min="0" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Repayment Schedule</label>
                        <select name="repayment_schedule" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="weekly" @selected(old('repayment_schedule', $loan->repayment_schedule) === 'weekly')>Weekly</option>
                            <option value="bi_weekly" @selected(old('repayment_schedule', $loan->repayment_schedule) === 'bi_weekly')>Bi-Weekly</option>
                            <option value="monthly" @selected(old('repayment_schedule', $loan->repayment_schedule) === 'monthly')>Monthly</option>
                            <option value="quarterly" @selected(old('repayment_schedule', $loan->repayment_schedule) === 'quarterly')>Quarterly</option>
                        </select>
                        @error('repayment_schedule') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Loan Purpose</label>
                        <textarea name="purpose" rows="3" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('purpose', $loan->purpose) }}</textarea>
                        @error('purpose') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="rounded-xl border border-blue-100 p-4">
                <h3 class="text-sm font-bold uppercase tracking-wide text-blue-700">Upload Documents (PDF Only, Max 5MB each)</h3>
                <p class="text-xs text-gray-600 mt-2">Leave blank to keep existing documents</p>
                <div class="mt-4 grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Police Letter (Optional)</label>
                        <input type="file" name="police_letter" accept=".pdf" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @if($loan->police_letter_path)
                            <p class="text-xs text-green-600 mt-1">✓ Current document uploaded</p>
                        @endif
                        @error('police_letter') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Financial Statement (Optional)</label>
                        <input type="file" name="financial_statement" accept=".pdf" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @if($loan->financial_statement_path)
                            <p class="text-xs text-green-600 mt-1">✓ Current document uploaded</p>
                        @endif
                        @error('financial_statement') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">National ID (Optional)</label>
                        <input type="file" name="national_id" accept=".pdf" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @if($loan->national_id_path)
                            <p class="text-xs text-green-600 mt-1">✓ Current document uploaded</p>
                        @endif
                        @error('national_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Loan Guarantee One (Optional)</label>
                        <input type="file" name="loan_guarantee_one" accept=".pdf" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @if($loan->loan_guarantee_one_path)
                            <p class="text-xs text-green-600 mt-1">✓ Current document uploaded</p>
                        @endif
                        @error('loan_guarantee_one') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Loan Guarantee Two (Optional)</label>
                        <input type="file" name="loan_guarantee_two" accept=".pdf" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @if($loan->loan_guarantee_two_path)
                            <p class="text-xs text-green-600 mt-1">✓ Current document uploaded</p>
                        @endif
                        @error('loan_guarantee_two') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Proof of Residence (Optional)</label>
                        <input type="file" name="proof_of_residence" accept=".pdf" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @if($loan->proof_of_residence_path)
                            <p class="text-xs text-green-600 mt-1">✓ Current document uploaded</p>
                        @endif
                        @error('proof_of_residence') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-bold uppercase tracking-wide hover:bg-blue-700 transition">
                    ✓ Update Application
                </button>
                <a href="{{ route('customer.loans.show', $loan->id) }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg font-bold uppercase tracking-wide hover:bg-gray-600 transition">
                    ✗ Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
