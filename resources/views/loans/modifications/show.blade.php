<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                🔄 Loan Modifications - #{{ $loan->id }}
            </h2>
            <a href="{{ route('loans.show', $loan) }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                ← Back to Loan Details
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Loan Status Overview -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Current Loan Status</h3>
                </div>
                <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold">Original Amount</p>
                        <p class="text-xl font-bold text-blue-600">UGX {{ number_format($loan->amount, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold">Total Paid</p>
                        <p class="text-xl font-bold text-green-600">UGX {{ number_format($loan->total_paid, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold">Outstanding Balance</p>
                        <p class="text-xl font-bold text-orange-600">UGX {{ number_format($loan->outstanding_balance, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold">Status</p>
                        <p class="text-lg font-bold text-purple-600">{{ str($loan->status)->title() }}</p>
                    </div>
                </div>
            </div>

            <!-- Modification Options -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Early Completion -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                        <h4 class="text-lg font-bold text-green-800">💰 Early Loan Completion</h4>
                        <p class="text-sm text-green-600">Client wants to pay off loan early</p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Amount</label>
                                <input type="number" step="0.01" name="payment_amount" 
                                       value="{{ $loan->outstanding_balance }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                                <input type="date" name="payment_date" 
                                       value="{{ now()->format('Y-m-d') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="check">Check</option>
                                </select>
                            </div>
                            <button type="button" class="w-full px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                                Complete Loan Early
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Loan Restructuring -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
                        <h4 class="text-lg font-bold text-yellow-800">🔄 Loan Restructuring</h4>
                        <p class="text-sm text-yellow-600">Extend loan term due to financial difficulties</p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">New Term (Months)</label>
                                <input type="number" name="new_term_months" min="1" max="60" 
                                       value="{{ ($loan->actual_term_months ?? $loan->term_months) + 6 }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Frequency</label>
                                <select name="new_payment_frequency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500" required>
                                    <option value="weekly" {{ $loan->repayment_schedule === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="bi_weekly" {{ $loan->repayment_schedule === 'bi_weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                    <option value="monthly" {{ $loan->repayment_schedule === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ $loan->repayment_schedule === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Restructuring Fee</label>
                                <input type="number" step="0.01" name="restructuring_fee" 
                                       value="{{ $loan->amount * 0.05 }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500" required>
                            </div>
                            <button type="button" class="w-full px-4 py-2 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition">
                                Restructure Loan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Default Recovery Plan -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                        <h4 class="text-lg font-bold text-red-800">⚠️ Default Recovery Plan</h4>
                        <p class="text-sm text-red-600">Client has missed payments - create recovery plan</p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="mb-4">
                            <h5 class="font-semibold text-gray-900 mb-2">Overdue Payments:</h5>
                            @foreach($loan->repayments()->where('status', 'pending')->where('due_date', '<', now())->get() as $overdue)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" name="missed_payments[]" value="{{ $overdue->id }}" 
                                       class="rounded border-gray-300 text-red-600 shadow-sm">
                                <label class="ml-2 text-sm">
                                    Installment #{{ $overdue->installment_number }} - UGX {{ number_format($overdue->amount, 2) }} 
                                    (Due: {{ $overdue->due_date->format('M d, Y') }})
                                </label>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h5 class="font-semibold text-gray-900 mb-2">Recovery Payment Schedule:</h5>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <input type="number" step="0.01" placeholder="Payment Amount" 
                                           class="rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <input type="date" class="rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                </div>
                            </div>
                            <button type="button" class="w-full px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                                Create Recovery Plan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Overpayment Processing -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                        <h4 class="text-lg font-bold text-blue-800">💎 Overpayment Processing</h4>
                        <p class="text-sm text-blue-600">Client paid more than scheduled amount</p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Overpayment Amount</label>
                                <input type="number" step="0.01" name="overpayment_amount" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                                <input type="date" name="payment_date" 
                                       value="{{ now()->format('Y-m-d') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="apply_to_future" id="apply_future" checked 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <label for="apply_future" class="ml-2 text-sm text-gray-600">Apply to future installments</label>
                            </div>
                            <button type="button" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                Process Overpayment
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modification History -->
            @if(!empty($modificationHistory['adjustments']))
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Modification History</h3>
                </div>
                <div class="px-6 py-6">
                    <div class="space-y-4">
                        @foreach($modificationHistory['adjustments'] as $adjustment)
                        <div class="border-l-4 border-orange-400 pl-4 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ str($adjustment['type'])->title()->replace('_', ' ') }}</p>
                                    <p class="text-sm text-gray-600">{{ $adjustment['description'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-orange-600">UGX {{ number_format($adjustment['amount'], 2) }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($adjustment['date'])->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>