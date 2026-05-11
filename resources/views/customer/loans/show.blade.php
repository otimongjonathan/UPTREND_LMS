@extends('customer.layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('customer.loans.index') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
            ← Back to My Loans
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100 mb-6">
        <div class="px-6 py-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-bold text-3xl text-gray-800">
                    Loan Application #{{ $loan->id }}
                </h2>
                <span class="px-4 py-2 rounded-full text-lg font-bold
                    @if ($loan->status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif ($loan->status === 'approved') bg-blue-100 text-blue-700
                    @elseif ($loan->status === 'issued') bg-green-100 text-green-700
                    @elseif ($loan->status === 'rejected') bg-red-100 text-red-700
                    @endif">
                    {{ str($loan->status)->title() }}
                </span>
            </div>
            <p class="text-gray-600">Submitted: {{ $loan->application_date?->format('M d, Y \a\t h:i A') }}</p>
        </div>
            </div>

            <!-- Loan Details -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Loan Details</h3>
                </div>

                <div class="px-6 py-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Loan Amount</p>
                        <p class="text-2xl font-bold text-green-600">UGX {{ number_format($loan->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Loan Type</p>
                        <p class="text-lg text-gray-900">{{ str($loan->loan_type)->replace('_', ' ')->title() }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Purpose</p>
                        <p class="text-gray-900">{{ $loan->purpose }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Repayment Schedule</p>
                        <p class="text-gray-900">{{ str($loan->repayment_schedule)->replace('_', ' ')->title() }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Term (Months)</p>
                        <p class="text-gray-900">{{ $loan->term_months }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Monthly Income</p>
                        <p class="text-gray-900">UGX {{ number_format($loan->monthly_income, 2) }}</p>
                    </div>
                </div>

                @if ($loan->notes)
                <div class="px-6 py-6 border-t border-gray-200 bg-gray-50">
                    <p class="text-sm text-gray-600 font-semibold mb-2">Staff Notes</p>
                    <p class="text-gray-900">{{ $loan->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Personal Information -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                </div>

                <div class="px-6 py-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Full Name</p>
                        <p class="text-gray-900">{{ $loan->applicant_full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Date of Birth</p>
                        <p class="text-gray-900">{{ $loan->dob?->format('M d, Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Gender</p>
                        <p class="text-gray-900">{{ str($loan->gender)->title() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Marital Status</p>
                        <p class="text-gray-900">{{ str($loan->marital_status)->replace('_', ' ')->title() }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Employment Status</p>
                        <p class="text-gray-900">{{ str($loan->employment_status)->replace('_', ' ')->title() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Occupation</p>
                        <p class="text-gray-900">{{ $loan->occupation }}</p>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Address</h3>
                </div>

                <div class="px-6 py-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">District/City</p>
                        <p class="text-gray-900">{{ $loan->district_city }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">County</p>
                        <p class="text-gray-900">{{ $loan->county }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Sub-County</p>
                        <p class="text-gray-900">{{ $loan->sub_county }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Parish</p>
                        <p class="text-gray-900">{{ $loan->parish }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Village</p>
                        <p class="text-gray-900">{{ $loan->village }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">P.O. Box</p>
                        <p class="text-gray-900">{{ $loan->po_box }}</p>
                    </div>

                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 font-semibold">Residence Status</p>
                        <p class="text-gray-900">{{ str($loan->residence_status)->title() }}</p>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Attached Documents</h3>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $documents = [
                                'police_letter_path' => 'Police Letter',
                                'financial_statement_path' => 'Financial Statement',
                                'national_id_path' => 'National ID',
                                'loan_guarantee_one_path' => 'Loan Guarantee One',
                                'loan_guarantee_two_path' => 'Loan Guarantee Two',
                                'proof_of_residence_path' => 'Proof of Residence',
                            ];
                        @endphp

                        @foreach ($documents as $field => $label)
                            @if ($loan->$field)
                                <a href="{{ route('customer.loans.download', [$loan->id, $field]) }}" class="flex items-center px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 16.5a1 1 0 11-2 0 1 1 0 012 0zM15 16.5a1 1 0 11-2 0 1 1 0 012 0z"/>
                                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                                    </svg>
                                    <span class="text-blue-900 font-semibold text-sm">{{ $label }}</span>
                                </a>
                            @else
                                <div class="flex items-center px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 opacity-50">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 16.5a1 1 0 11-2 0 1 1 0 012 0zM15 16.5a1 1 0 11-2 0 1 1 0 012 0z"/>
                                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                                    </svg>
                                    <span class="text-gray-500 font-semibold text-sm">{{ $label }} (Not provided)</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Repayments (if issued) -->
            @if ($loan->status === 'issued' && $loan->repayments->count() > 0)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Repayment Schedule</h3>
                </div>

                <div class="px-6 py-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Due Date</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Amount Due</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Paid Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700">Paid Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($loan->repayments as $repayment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $repayment->due_date?->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">UGX {{ number_format($repayment->amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-bold
                                        @if ($repayment->status === 'completed') bg-green-100 text-green-700
                                        @elseif ($repayment->status === 'partial') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ str($repayment->status)->title() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">UGX {{ number_format($repayment->paid_amount ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $repayment->paid_date?->format('M d, Y') ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Actions -->
            @if ($loan->status === 'pending')
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="px-6 py-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Actions</h3>
                    <div class="flex gap-3">
                        <a href="{{ route('customer.loans.edit', $loan->id) }}" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 font-semibold">
                            ✏️ Edit Application
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
