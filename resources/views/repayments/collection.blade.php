@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Repayment Collection</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Loan: {{ $loan->user->name }} | Amount: UGX {{ number_format($loan->amount, 0) }}
                    </p>
                </div>
                <a href="{{ route('loans.show', $loan) }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    ← Back
                </a>
            </div>
        </div>

        <!-- Arrears Summary -->
        @if($arrears['total_arrears'] > 0)
            <div class="mb-8 p-6 bg-red-50 border-2 border-red-300 rounded-lg">
                <h3 class="text-xl font-bold text-red-900 mb-4">⚠️ Outstanding Arrears</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-red-700">Total Arrears</p>
                        <p class="text-2xl font-bold text-red-900">UGX {{ number_format($arrears['total_arrears'], 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-red-700">Principal Arrears</p>
                        <p class="text-2xl font-bold text-red-900">UGX {{ number_format($arrears['principal_arrears'], 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-red-700">Interest Arrears</p>
                        <p class="text-2xl font-bold text-red-900">UGX {{ number_format($arrears['interest_arrears'], 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-red-700">Overdue Installments</p>
                        <p class="text-2xl font-bold text-red-900">{{ $arrears['overdue_count'] }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Overdue Installments -->
                @if($overdueInstallments->count() > 0)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                            <h3 class="font-bold text-white text-lg">
                                🔴 Overdue Installments ({{ $overdueInstallments->count() }})
                            </h3>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-red-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-700">#</th>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Due Date</th>
                                        <th class="px-6 py-3 text-right font-semibold text-gray-700">Amount Due</th>
                                        <th class="px-6 py-3 text-right font-semibold text-gray-700">Paid</th>
                                        <th class="px-6 py-3 text-right font-semibold text-gray-700">Outstanding</th>
                                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Days Overdue</th>
                                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($overdueInstallments as $schedule)
                                        <tr class="hover:bg-red-50 border-l-4 border-red-600">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $schedule->installment_number }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $schedule->due_date->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-right font-semibold text-gray-900">
                                                UGX {{ number_format($schedule->total_amount, 0) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-900">
                                                UGX {{ number_format($schedule->paid_amount, 0) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right font-bold text-red-600">
                                                UGX {{ number_format($schedule->total_amount - $schedule->paid_amount, 0) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-center font-bold text-red-600">
                                                {{ $schedule->days_overdue ?? now()->diffInDays($schedule->due_date) }} days
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('repayments.pay', $schedule) }}" 
                                                   class="inline-block px-3 py-1 bg-green-600 text-white rounded text-xs font-semibold hover:bg-green-700">
                                                    Pay Now
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Pending Installments -->
                @if($pendingSchedules->count() > 0)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h3 class="font-bold text-white text-lg">
                                📋 Pending/Current Installments ({{ $pendingSchedules->count() }})
                            </h3>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-blue-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-700">#</th>
                                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Due Date</th>
                                        <th class="px-6 py-3 text-right font-semibold text-gray-700">Principal</th>
                                        <th class="px-6 py-3 text-right font-semibold text-gray-700">Interest</th>
                                        <th class="px-6 py-3 text-right font-semibold text-gray-700">Total</th>
                                        <th class="px-6 py-3 text-right font-semibold text-gray-700">Paid</th>
                                        <th class="px-6 py-3 text-right font-semibold text-gray-700">Outstanding</th>
                                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                                        <th class="px-6 py-3 text-center font-semibold text-gray-700">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($pendingSchedules as $schedule)
                                        <tr class="hover:bg-blue-50">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $schedule->installment_number }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $schedule->due_date->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-900">
                                                UGX {{ number_format($schedule->principal_amount, 0) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-900">
                                                UGX {{ number_format($schedule->interest_amount, 0) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right font-semibold text-gray-900">
                                                UGX {{ number_format($schedule->total_amount, 0) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-900">
                                                UGX {{ number_format($schedule->paid_amount, 0) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right font-semibold text-blue-600">
                                                UGX {{ number_format($schedule->total_amount - $schedule->paid_amount, 0) }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-2 py-1 text-xs font-bold rounded
                                                    @if($schedule->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($schedule->status === 'partially_paid') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif
                                                ">
                                                    {{ ucfirst(str_replace('_', ' ', $schedule->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('repayments.pay', $schedule) }}" 
                                                   class="inline-block px-3 py-1 bg-blue-600 text-white rounded text-xs font-semibold hover:bg-blue-700">
                                                    Pay
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($pendingSchedules->count() === 0 && $overdueInstallments->count() === 0)
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <p class="text-xl text-gray-600">✓ All installments have been paid!</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Loan Summary</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Total Loan Amount</p>
                            <p class="text-2xl font-bold text-gray-900">
                                UGX {{ number_format($loan->amount, 0) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Total Interest</p>
                            <p class="text-2xl font-bold text-blue-600">
                                UGX {{ number_format($loan->total_interest_amount ?? 0, 0) }}
                            </p>
                        </div>

                        <div class="pt-3 border-t">
                            <p class="text-sm text-gray-600">Total to Repay</p>
                            <p class="text-2xl font-bold text-gray-900">
                                UGX {{ number_format(($loan->amount ?? 0) + ($loan->total_interest_amount ?? 0), 0) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <h4 class="font-semibold text-blue-900 mb-3">💳 Accepted Payment Methods</h4>
                    <ul class="text-sm text-blue-800 space-y-2">
                        <li>✓ Bank Transfer</li>
                        <li>✓ Check</li>
                        <li>✓ Cash</li>
                        <li>✓ Mobile Money</li>
                    </ul>
                </div>

                <!-- Links -->
                <div class="bg-white rounded-lg shadow-md p-6 space-y-3">
                    <a href="{{ route('repayments.payment-history', $loan) }}" 
                       class="block w-full px-4 py-2 text-center bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">
                        View Payment History
                    </a>
                    <a href="{{ route('repayments.bulk-report') }}" 
                       class="block w-full px-4 py-2 text-center bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-semibold">
                        Payment Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
