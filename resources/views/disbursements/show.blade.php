@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Disbursement #{{ $disbursement->id }}
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Loan: {{ $disbursement->loanApplication->user->name }} | Amount: UGX {{ number_format($disbursement->disbursement_amount, 0) }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('disbursements.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    ← Back
                </a>
            </div>
        </div>

        <!-- Status Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <!-- Current Status -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-600 mb-2">Current Status</p>
                <div class="flex items-center gap-2">
                    @php
                        $statusColor = match($disbursement->status) {
                            'pending' => 'yellow',
                            'approved' => 'blue',
                            'disbursed' => 'green',
                            'cancelled' => 'red',
                        };
                        $statusIcon = match($disbursement->status) {
                            'pending' => '⏳',
                            'approved' => '✓',
                            'disbursed' => '✓✓',
                            'cancelled' => '✕',
                        };
                    @endphp
                    <span class="text-2xl">{{ $statusIcon }}</span>
                    <span class="text-xl font-bold capitalize text-{{ $statusColor }}-600">
                        {{ $disbursement->status }}
                    </span>
                </div>
            </div>

            <!-- Loan Supervisor -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
                <p class="text-sm text-gray-600 mb-2">Loan Supervisor</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $disbursement->loanSupervisor->name ?? 'Not Assigned' }}
                </p>
                @if($disbursement->loanSupervisor)
                    <p class="text-xs text-gray-500 mt-1">{{ $disbursement->loanSupervisor->email }}</p>
                @endif
            </div>

            <!-- Transaction Status -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-600 mb-2">Transaction Status</p>
                <p class="text-xl font-bold capitalize text-purple-600">
                    {{ $disbursement->transaction_status ?? 'Not recorded' }}
                </p>
            </div>

            <!-- Approved -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-600 mb-2">Approved By</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $disbursement->approver->name ?? 'Pending' }}
                </p>
                @if($disbursement->approved_at)
                    <p class="text-xs text-gray-500 mt-1">{{ $disbursement->approved_at->format('M d, Y') }}</p>
                @endif
            </div>

            <!-- Disbursed -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <p class="text-sm text-gray-600 mb-2">Disbursed By</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $disbursement->disburser->name ?? 'Pending' }}
                </p>
                @if($disbursement->disbursed_at)
                    <p class="text-xs text-gray-500 mt-1">{{ $disbursement->disbursed_at->format('M d, Y') }}</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Disbursement Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Disbursement Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Gross Amount</p>
                            <p class="text-2xl font-bold text-gray-900">
                                UGX {{ number_format($disbursement->disbursement_amount, 0) }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Date</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $disbursement->disbursement_date->format('M d, Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Method</p>
                            <p class="text-lg font-semibold text-gray-900 capitalize">
                                {{ str_replace('_', ' ', $disbursement->disbursement_method) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Reference Number</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $disbursement->reference_number ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <!-- Fee Breakdown -->
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="text-md font-bold text-gray-900 mb-4">Fee Breakdown</h4>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Processing Fee ({{ $disbursement->processing_fee_percent }}%)</span>
                                <span class="text-sm font-semibold text-red-600">- UGX {{ number_format($disbursement->processing_fee_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Insurance Premium ({{ $disbursement->insurance_premium_percent }}%)</span>
                                <span class="text-sm font-semibold text-red-600">- UGX {{ number_format($disbursement->insurance_premium_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Tax ({{ $disbursement->tax_percent }}%)</span>
                                <span class="text-sm font-semibold text-red-600">- UGX {{ number_format($disbursement->tax_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t border-gray-300">
                                <span class="text-sm font-bold text-gray-900">Total Deductions</span>
                                <span class="text-sm font-bold text-red-600">- UGX {{ number_format($disbursement->total_deductions, 2) }}</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t-2 border-gray-400">
                                <span class="text-base font-bold text-gray-900">Net Disbursement</span>
                                <span class="text-lg font-bold text-green-600">UGX {{ number_format($disbursement->net_disbursement_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($disbursement->notes)
                        <div class="mt-6 pt-6 border-t">
                            <p class="text-sm text-gray-600">Notes</p>
                            <p class="text-gray-900 mt-2">{{ $disbursement->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Transaction Details -->
                @if($disbursement->disbursement_method === 'bank_transfer' || $disbursement->bank_name)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Bank Transfer Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600">Bank Name</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $disbursement->bank_name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Account Holder</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $disbursement->account_holder_name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Account Number</p>
                                <p class="text-lg font-semibold text-gray-900 font-mono">
                                    {{ $disbursement->account_number ? substr($disbursement->account_number, -4) : 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Routing Number</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $disbursement->routing_number ?? 'N/A' }}</p>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600">Transaction ID</p>
                                <p class="text-lg font-semibold text-gray-900 font-mono">{{ $disbursement->transaction_id ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if($disbursement->transaction_recorded_at)
                            <div class="mt-6 pt-6 border-t">
                                <p class="text-sm text-gray-600">Recorded At</p>
                                <p class="text-gray-900">{{ $disbursement->transaction_recorded_at->format('M d, Y H:i A') }}</p>
                            </div>
                        @endif
                    </div>
                @elseif($disbursement->disbursement_method === 'cash' || $disbursement->cash_received_by)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Cash Disbursement Details</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Received By</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $disbursement->cash_received_by ?? 'N/A' }}</p>
                            </div>

                            @if($disbursement->cash_notes)
                                <div class="pt-4 border-t">
                                    <p class="text-sm text-gray-600">Notes</p>
                                    <p class="text-gray-900 mt-2">{{ $disbursement->cash_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Repayment Schedule -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Repayment Schedule</h3>
                        <span class="text-sm font-semibold text-gray-600">
                            {{ $disbursement->number_of_installments ?? 0 }} installments
                        </span>
                    </div>

                    @if($disbursement->payment_frequency)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-sm text-gray-600">Payment Frequency</p>
                                <p class="text-lg font-semibold capitalize">{{ str_replace('-', ' ', $disbursement->payment_frequency) }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-sm text-gray-600">First Payment Date</p>
                                <p class="text-lg font-semibold">{{ $disbursement->first_payment_date->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-sm text-gray-600">Total Installments</p>
                                <p class="text-lg font-semibold">{{ $disbursement->number_of_installments ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if($disbursement->repaymentSchedules->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Due Date</th>
                                            <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700">Principal</th>
                                            <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700">Interest</th>
                                            <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700">Total</th>
                                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($disbursement->repaymentSchedules as $schedule)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-sm text-gray-900">{{ $schedule->installment_number }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-900">{{ $schedule->due_date->format('M d, Y') }}</td>
                                                <td class="px-4 py-3 text-sm text-right text-gray-900">UGX {{ number_format($schedule->principal_amount, 0) }}</td>
                                                <td class="px-4 py-3 text-sm text-right text-gray-900">UGX {{ number_format($schedule->interest_amount, 0) }}</td>
                                                <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900">UGX {{ number_format($schedule->total_amount, 0) }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="px-2 py-1 text-xs font-bold rounded
                                                        @if($schedule->status === 'paid') bg-green-100 text-green-800
                                                        @elseif($schedule->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($schedule->status === 'overdue') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif
                                                    ">
                                                        {{ ucfirst(str_replace('_', ' ', $schedule->status)) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded">
                                <p class="text-gray-500">Schedule will be generated when disbursement is completed</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded">
                            <p class="text-gray-500">Schedule configuration pending</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                <!-- Action Buttons -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                    
                    <div class="space-y-3">
                        @if($disbursement->status === 'pending')
                            <a href="{{ route('disbursements.edit', $disbursement) }}" 
                               class="block w-full text-center px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-semibold">
                                Configure Schedule
                            </a>
                        @endif

                        @if($disbursement->status === 'pending' && $disbursement->payment_frequency)
                            <form action="{{ route('disbursements.approve', $disbursement) }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 font-semibold">
                                    Approve Disbursement
                                </button>
                            </form>
                        @endif

                        @if($disbursement->status === 'approved')
                            <form action="{{ route('disbursements.disburse', $disbursement) }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                                    Complete Disbursement
                                </button>
                            </form>
                        @endif

                        @if($disbursement->status === 'disbursed' && !$disbursement->verified_at)
                            <form action="{{ route('disbursements.verify', $disbursement) }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-purple-500 text-white rounded-lg hover:bg-purple-600 font-semibold">
                                    Verify & Record
                                </button>
                            </form>
                        @endif

                        @if($disbursement->status !== 'disbursed')
                            <form action="{{ route('disbursements.cancel', $disbursement) }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold"
                                        onclick="return confirm('Are you sure?')">
                                    Cancel Disbursement
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Loan Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Loan Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Loan Amount:</span>
                            <span class="font-semibold">UGX {{ number_format($disbursement->loanApplication->amount, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Interest:</span>
                            <span class="font-semibold">UGX {{ number_format($disbursement->loanApplication->total_interest_amount ?? 0, 0) }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-gray-600 font-semibold">Total Repay:</span>
                            <span class="font-bold text-lg">
                                UGX {{ number_format(($disbursement->loanApplication->amount ?? 0) + ($disbursement->loanApplication->total_interest_amount ?? 0), 0) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Document -->
                @if($disbursement->disbursement_document_path)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Supporting Document</h3>
                        <a href="{{ asset('storage/' . $disbursement->disbursement_document_path) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
                            📄 View Document
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
