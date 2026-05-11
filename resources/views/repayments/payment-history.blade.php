@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Payment History</h1>
                <p class="mt-2 text-sm text-gray-600">
                    All payments for: {{ $loan->user->name }}
                </p>
            </div>
            <a href="{{ route('repayments.collection', $loan) }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                ← Back
            </a>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-600">Total Scheduled</p>
                <p class="text-3xl font-bold text-gray-900">UGX {{ number_format($totalScheduled, 0) }}</p>
            </div>

            <div class="bg-green-50 rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Total Paid</p>
                <p class="text-3xl font-bold text-green-600">UGX {{ number_format($totalPaid, 0) }}</p>
            </div>

            <div class="bg-blue-50 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Remaining</p>
                <p class="text-3xl font-bold text-blue-600">UGX {{ number_format($remaining, 0) }}</p>
            </div>

            <div class="bg-gray-100 rounded-lg shadow-md p-6 border-l-4 border-gray-400">
                <p class="text-sm text-gray-600">Progress</p>
                <p class="text-3xl font-bold text-gray-900">
                    {{ $totalScheduled > 0 ? round(($totalPaid / $totalScheduled) * 100) : 0 }}%
                </p>
            </div>
        </div>

        <!-- Payment Schedule Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h3 class="font-bold text-white text-lg">Complete Schedule with Payment Records</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">#</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Due Date</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-700">Principal</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-700">Interest</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-700">Total Due</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-700">Paid</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-700">Outstanding</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-center font-semibold text-gray-700">Payments</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($schedules as $schedule)
                            <tr class="hover:bg-gray-50">
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
                                <td class="px-6 py-4 text-sm text-right text-green-600 font-semibold">
                                    UGX {{ number_format($schedule->paid_amount, 0) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right font-semibold
                                    @if($schedule->total_amount == $schedule->paid_amount) text-gray-600
                                    @elseif($schedule->total_amount > $schedule->paid_amount) text-red-600
                                    @endif
                                ">
                                    UGX {{ number_format($schedule->total_amount - $schedule->paid_amount, 0) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 text-xs font-bold rounded
                                        @if($schedule->status === 'paid') bg-green-100 text-green-800
                                        @elseif($schedule->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($schedule->status === 'overdue') bg-red-100 text-red-800
                                        @elseif($schedule->status === 'partially_paid') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $schedule->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($schedule->paymentReceipts->count() > 0)
                                        <button onclick="togglePayments(this)" 
                                                class="px-2 py-1 bg-blue-100 text-blue-600 rounded text-xs font-semibold hover:bg-blue-200">
                                            {{ $schedule->paymentReceipts->count() }} payment(s)
                                        </button>
                                        <div class="hidden mt-2 space-y-2 text-left text-xs">
                                            @foreach($schedule->paymentReceipts as $receipt)
                                                <div class="bg-gray-50 p-2 rounded border">
                                                    <p class="font-semibold">UGX {{ number_format($receipt->amount_paid, 0) }}</p>
                                                    <p class="text-gray-600">{{ $receipt->payment_date->format('M d, Y') }} • {{ ucfirst(str_replace('_', ' ', $receipt->payment_method)) }}</p>
                                                    @if($receipt->payment_reference)
                                                        <p class="text-gray-500">Ref: {{ $receipt->payment_reference }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">No payments</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Total Installments</p>
                        <p class="text-lg font-bold text-gray-900">{{ $schedules->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Paid Installments</p>
                        <p class="text-lg font-bold text-green-600">{{ $schedules->where('status', 'paid')->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pending/Overdue</p>
                        <p class="text-lg font-bold text-red-600">{{ $schedules->whereIn('status', ['pending', 'overdue'])->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Completion Rate</p>
                        <p class="text-lg font-bold text-blue-600">
                            {{ $schedules->count() > 0 ? round(($schedules->where('status', 'paid')->count() / $schedules->count()) * 100) : 0 }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePayments(button) {
    const paymentDiv = button.nextElementSibling;
    paymentDiv.classList.toggle('hidden');
    button.classList.toggle('bg-blue-200');
}
</script>
@endsection
