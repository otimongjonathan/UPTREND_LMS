@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Payment Receipt</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Receipt #{{ $receipt->receipt_number ?? 'N/A' }}
                </p>
            </div>
            <a href="{{ route('repayments.payment-history', $receipt->repaymentSchedule->loanDisbursement->loanApplication->id) }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                ← Back to History
            </a>
        </div>

        <!-- Receipt Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Payment Received</h2>
                        <p class="text-green-100 mt-1">{{ $receipt->payment_date->format('F d, Y \a\t H:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">UGX {{ number_format($receipt->amount_paid, 0) }}</div>
                        <span class="inline-block mt-2 px-3 py-1 bg-green-500 text-white rounded text-sm font-semibold">
                            Completed
                        </span>
                    </div>
                </div>
            </div>

            <!-- Receipt Content -->
            <div class="p-8">
                <!-- Loan & Borrower Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Left Column: Borrower Info -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">Borrower Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-semibold text-gray-900">{{ $receipt->repaymentSchedule->loanDisbursement->loanApplication->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold text-gray-900">{{ $receipt->repaymentSchedule->loanDisbursement->loanApplication->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-semibold text-gray-900">{{ $receipt->repaymentSchedule->loanDisbursement->loanApplication->user->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Receipt Info -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">Receipt Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Receipt Number</p>
                                <p class="font-semibold text-gray-900 font-mono">{{ $receipt->receipt_number ?? $receipt->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Payment Method</p>
                                <p class="font-semibold text-gray-900">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                                        {{ ucfirst(str_replace('_', ' ', $receipt->payment_method)) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Reference</p>
                                <p class="font-semibold text-gray-900">{{ $receipt->payment_reference ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loan & Installment Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Loan Details -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">Loan Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Loan ID</p>
                                <p class="font-semibold text-gray-900">{{ $receipt->repaymentSchedule->loanDisbursement->loanApplication->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Loan Product</p>
                                <p class="font-semibold text-gray-900">
                                    {{ $receipt->repaymentSchedule->loanDisbursement->loanApplication->loanProduct->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Original Amount</p>
                                <p class="font-semibold text-gray-900">
                                    UGX {{ number_format($receipt->repaymentSchedule->loanDisbursement->loanApplication->amount_requested, 0) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Installment Details -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">Installment Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Installment #</p>
                                <p class="font-semibold text-gray-900">{{ $receipt->repaymentSchedule->installment_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Due Date</p>
                                <p class="font-semibold text-gray-900">{{ $receipt->repaymentSchedule->due_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Payment Date</p>
                                <p class="font-semibold text-gray-900">{{ $receipt->payment_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Breakdown -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h3 class="font-bold text-gray-900 mb-4">Payment Breakdown</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <p class="text-gray-600">Installment Total Due</p>
                            <p class="font-semibold text-gray-900">UGX {{ number_format($receipt->repaymentSchedule->total_amount, 0) }}</p>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <p class="text-gray-600">Principal</p>
                            <p class="text-gray-900">UGX {{ number_format($receipt->repaymentSchedule->principal_amount, 0) }}</p>
                        </div>
                        <div class="flex justify-between pb-3">
                            <p class="text-gray-600">Interest</p>
                            <p class="text-gray-900">UGX {{ number_format($receipt->repaymentSchedule->interest_amount, 0) }}</p>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-3 border-t-2">
                            <p class="text-gray-900">Amount Paid This Transaction</p>
                            <p class="text-green-600">UGX {{ number_format($receipt->amount_paid, 0) }}</p>
                        </div>
                        @if($receipt->amount_paid < $receipt->repaymentSchedule->total_amount)
                            <div class="flex justify-between text-lg font-bold pt-2">
                                <p class="text-gray-900">Remaining Balance</p>
                                <p class="text-red-600">
                                    UGX {{ number_format($receipt->repaymentSchedule->total_amount - $receipt->amount_paid, 0) }}
                                </p>
                            </div>
                        @else
                            <div class="flex justify-between text-lg font-bold pt-2">
                                <p class="text-gray-900">Fully Paid</p>
                                <p class="text-green-600">✓</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Notes -->
                @if($receipt->notes)
                    <div class="mb-8">
                        <h3 class="font-bold text-gray-900 mb-3">Notes</h3>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                            <p class="text-gray-700">{{ $receipt->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Processed By -->
                <div class="mb-8 p-4 bg-gray-50 rounded">
                    <h3 class="font-bold text-gray-900 mb-3">Processing Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Processed By</p>
                            <p class="font-semibold text-gray-900">
                                {{ $receipt->user->name ?? 'System' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Processed At</p>
                            <p class="font-semibold text-gray-900">
                                {{ $receipt->created_at->format('M d, Y \a\t H:i A') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 justify-end pt-8 border-t">
                    <button onclick="window.print()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">
                        🖨️ Print
                    </button>
                    <a href="{{ route('repayments.payment-history', $receipt->repaymentSchedule->loanDisbursement->loanApplication->id) }}" 
                       class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                        Back to History
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-6 border-t text-center text-xs text-gray-600">
                <p>This is a computer-generated receipt. No signature is required.</p>
                <p class="mt-2">For inquiries, contact your loan officer or visit the office.</p>
            </div>
        </div>
    </div>
</div>

<style media="print">
    .hidden { display: none; }
    button { display: none; }
    a { display: none; }
</style>
@endsection
