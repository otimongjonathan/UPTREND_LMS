@extends('customer.layouts.app')

@section('content')
    <!-- Application Form Header -->
    <div class="mb-8 bg-gradient-to-r from-orange-600 via-orange-700 to-orange-800 rounded-3xl p-8 text-white shadow-2xl">
        <div class="flex items-center gap-3 mb-3">
            <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold uppercase tracking-wide">New Loan Application</h1>
                <p class="text-orange-100 mt-1 text-lg">Complete the form below to apply for a loan</p>
            </div>
        </div>
    </div>

    {{-- Loan Product Information Section --}}
    @if($product)
        <div class="mb-8 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border-2 border-orange-300 p-6 shadow-lg">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-orange-700 mb-1">Selected Loan Product</p>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $product->name }}</h2>
                    <p class="text-gray-700 mb-4">{{ $product->description }}</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Amount Range</p>
                            <p class="text-lg font-bold text-orange-600">UGX {{ number_format($product->min_amount, 0) }} - {{ number_format($product->max_amount, 0) }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Term Duration</p>
                            <p class="text-lg font-bold text-gray-900">{{ $product->min_term }}-{{ $product->max_term }} months</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Interest Rate</p>
                            <p class="text-lg font-bold text-orange-600">{{ number_format($product->interest_rate, 2) }}%</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Processing Fee</p>
                            <p class="text-lg font-bold text-gray-900">{{ number_format($product->processing_fee_percent, 2) }}%</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Insurance Premium</p>
                            <p class="text-lg font-bold text-gray-900">{{ number_format($product->insurance_premium_percent, 2) }}%</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-orange-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Late Payment Fee</p>
                            <p class="text-lg font-bold text-red-600">{{ number_format($product->late_payment_fee_percent, 2) }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 text-green-700 px-6 py-4 flex items-center gap-3">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-semibold">{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.loans.store', absolute: false) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Hidden field for loan product if coming from loan products page -->
        @if($product)
            <input type="hidden" name="loan_product_id" value="{{ $product->id }}">
        @elseif(request('product_id'))
            <input type="hidden" name="loan_product_id" value="{{ request('product_id') }}">
        @endif

        <!-- Applicant Details -->
        <div class="bg-white rounded-2xl border border-orange-100 p-6 shadow-lg">
            <h3 class="text-lg font-bold uppercase tracking-wide text-orange-700 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Applicant Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name (As on ID)</label>
                    <input type="text" name="applicant_full_name" value="{{ old('applicant_full_name') }}" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    @error('applicant_full_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob') }}" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    @error('dob') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                    <select name="gender" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select gender</option>
                        <option value="male" @selected(old('gender') === 'male')>Male</option>
                        <option value="female" @selected(old('gender') === 'female')>Female</option>
                    </select>
                    @error('gender') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Marital Status</label>
                    <select name="marital_status" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select status</option>
                        <option value="single" @selected(old('marital_status') === 'single')>Single</option>
                        <option value="married" @selected(old('marital_status') === 'married')>Married</option>
                        <option value="divorced" @selected(old('marital_status') === 'divorced')>Divorced</option>
                        <option value="widowed" @selected(old('marital_status') === 'widowed')>Widowed</option>
                    </select>
                    @error('marital_status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Employment Status</label>
                    <select name="employment_status" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select status</option>
                        <option value="employed" @selected(old('employment_status') === 'employed')>Employed</option>
                        <option value="self_employed" @selected(old('employment_status') === 'self_employed')>Self Employed</option>
                        <option value="unemployed" @selected(old('employment_status') === 'unemployed')>Unemployed</option>
                        <option value="student" @selected(old('employment_status') === 'student')>Student</option>
                        <option value="retired" @selected(old('employment_status') === 'retired')>Retired</option>
                    </select>
                    @error('employment_status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation') }}" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    @error('occupation') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Residence Address -->
        <div class="bg-white rounded-2xl border border-orange-100 p-6 shadow-lg">
            <h3 class="text-lg font-bold uppercase tracking-wide text-orange-700 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Residence Address
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">District/City</label>
                    <input type="text" name="district_city" value="{{ old('district_city') }}" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    @error('district_city') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">County</label>
                    <input type="text" name="county" value="{{ old('county') }}" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    @error('county') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sub County</label>
                    <input type="text" name="sub_county" value="{{ old('sub_county') }}" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    @error('sub_county') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Parish</label>
                    <input type="text" name="parish" value="{{ old('parish') }}" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    @error('parish') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Village</label>
                    <input type="text" name="village" value="{{ old('village') }}" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    @error('village') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Residence Status</label>
                    <select name="residence_status" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select status</option>
                        <option value="permanent" @selected(old('residence_status') === 'permanent')>Permanent</option>
                        <option value="temporary" @selected(old('residence_status') === 'temporary')>Temporary</option>
                    </select>
                    @error('residence_status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">PO Box</label>
                    <input type="text" name="po_box" value="{{ old('po_box') }}" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    @error('po_box') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Loan Request Details -->
        <div class="bg-white rounded-2xl border border-orange-100 p-6 shadow-lg">
            <h3 class="text-lg font-bold uppercase tracking-wide text-orange-700 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Loan Request Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Type</label>
                    <select name="loan_type" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select loan type</option>
                        @php
                            $preselectedType = '';
                            if($product) {
                                $productName = strtolower($product->name);
                                if(str_contains($productName, 'salary')) {
                                    $preselectedType = 'salary_loan';
                                } elseif(str_contains($productName, 'business')) {
                                    $preselectedType = 'business_loan';
                                } elseif(str_contains($productName, 'education')) {
                                    $preselectedType = 'education_loan';
                                }
                            }
                        @endphp
                        <option value="salary_loan" @selected(old('loan_type') === 'salary_loan' || $preselectedType === 'salary_loan')>Salary Loan</option>
                        <option value="business_loan" @selected(old('loan_type') === 'business_loan' || $preselectedType === 'business_loan')>Business Loan</option>
                        <option value="education_loan" @selected(old('loan_type') === 'education_loan' || $preselectedType === 'education_loan')>Education Loan</option>
                    </select>
                    @error('loan_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Loan Amount (UGX)
                        @if($product)
                            <span class="text-xs text-orange-600 ml-2">(Min: {{ number_format($product->min_amount, 0) }} - Max: {{ number_format($product->max_amount, 0) }})</span>
                        @endif
                    </label>
                    @php
                        $minAmount = $product ? $product->min_amount : 1;
                        $maxAmount = $product ? $product->max_amount : 999999;
                        $defaultAmount = ($product && !old('amount')) ? $product->min_amount : old('amount', '');
                    @endphp
                    <input type="number" step="0.01" min="{{ $minAmount }}" max="{{ $maxAmount }}" name="amount" value="{{ $defaultAmount }}" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                    @error('amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    @if($product)
                        <p class="text-xs text-gray-500 mt-1">Amount must be between UGX {{ number_format($product->min_amount, 0) }} and {{ number_format($product->max_amount, 0) }}</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Repayment Schedule</label>
                    <select name="repayment_schedule" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">
                        <option value="">Select schedule</option>
                        <option value="weekly" @selected(old('repayment_schedule') === 'weekly')>Weekly</option>
                        <option value="bi_weekly" @selected(old('repayment_schedule') === 'bi_weekly')>Bi-Weekly</option>
                        <option value="monthly" @selected(old('repayment_schedule') === 'monthly')>Monthly</option>
                        <option value="quarterly" @selected(old('repayment_schedule') === 'quarterly')>Quarterly</option>
                    </select>
                    @error('repayment_schedule') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Purpose</label>
                    <textarea name="purpose" rows="3" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-4 py-2">{{ old('purpose') }}</textarea>
                    @error('purpose') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Required Documents -->
        <div class="bg-white rounded-2xl border border-orange-100 p-6 shadow-lg">
            <h3 class="text-lg font-bold uppercase tracking-wide text-orange-700 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Required Documents (PDF Only)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Police Letter</label>
                    <input type="file" name="police_letter" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    @error('police_letter') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Financial Statement</label>
                    <input type="file" name="financial_statement" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    @error('financial_statement') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">National ID Copy</label>
                    <input type="file" name="national_id" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    @error('national_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Guarantee 1</label>
                    <input type="file" name="loan_guarantee_one" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    @error('loan_guarantee_one') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Loan Guarantee 2</label>
                    <input type="file" name="loan_guarantee_two" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    @error('loan_guarantee_two') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Proof of Residence (LC1 Letter)</label>
                    <input type="file" name="proof_of_residence" accept="application/pdf" required class="w-full rounded-lg border border-gray-300 bg-white file:mr-4 file:rounded-md file:border-0 file:bg-orange-100 file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-200">
                    @error('proof_of_residence') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4">
            <a href="{{ route('customer.loans.index') }}" class="px-6 py-3 rounded-xl border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold hover:from-orange-600 hover:to-orange-700 transition shadow-lg hover:shadow-xl flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Submit Application
            </button>
        </div>
    </form>
@endsection
