<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                💳 Repayment Details - Installment #{{ $repayment->installment_number }}
            </h2>
            <a href="{{ route('repayments.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                ← Back to Repayments
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Repayment Status Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900">Payment Status</h3>
                        <span class="px-4 py-2 rounded-full text-lg font-bold
                            @if ($repayment->status === 'completed') bg-green-100 text-green-700
                            @elseif ($repayment->status === 'partial') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ str($repayment->status)->title() }}
                        </span>
                    </div>
                </div>

                <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold">Total Due</p>
                        <p class="text-2xl font-bold text-blue-600">UGX {{ number_format($repayment->amount + $repayment->late_fee, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold">Amount Paid</p>
                        <p class="text-2xl font-bold text-green-600">UGX {{ number_format($repayment->paid_amount ?? 0, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <p class="text-sm text-gray-600 font-semibold">Remaining</p>
                        <p class="text-2xl font-bold text-orange-600">UGX {{ number_format($repayment->getRemainingAmount(), 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Breakdown -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Payment Breakdown</h3>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-4">Installment Details</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Installment Number:</span>
                                    <span class="font-semibold">#{{ $repayment->installment_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Principal Amount:</span>
                                    <span class="font-semibold">UGX {{ number_format($repayment->principal_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Interest Amount:</span>
                                    <span class="font-semibold">UGX {{ number_format($repayment->interest_amount, 2) }}</span>
                                </div>
                                @if($repayment->late_fee > 0)
                                <div class="flex justify-between text-red-600">
                                    <span>Late Fee:</span>
                                    <span class="font-semibold">UGX {{ number_format($repayment->late_fee, 2) }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between border-t pt-2">
                                    <span class="font-semibold">Total Due:</span>
                                    <span class="font-bold">UGX {{ number_format($repayment->amount + $repayment->late_fee, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-4">Payment Information</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Due Date:</span>
                                    <span class="font-semibold">{{ $repayment->due_date->format('M d, Y') }}</span>
                                </div>
                                @if($repayment->paid_date)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Paid Date:</span>
                                    <span class="font-semibold">{{ $repayment->paid_date->format('M d, Y') }}</span>
                                </div>
                                @endif
                                @if($repayment->isOverdue())
                                <div class="flex justify-between text-red-600">
                                    <span>Days Overdue:</span>
                                    <span class="font-semibold">{{ $repayment->getDaysOverdue() }} days</span>
                                </div>
                                @endif
                                @if($repayment->payment_method)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Method:</span>
                                    <span class="font-semibold">{{ str($repayment->payment_method)->title() }}</span>
                                </div>
                                @endif
                                @if($repayment->payment_reference)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Reference:</span>
                                    <span class="font-semibold">{{ $repayment->payment_reference }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Remaining Balance:</span>
                                    <span class="font-semibold">UGX {{ number_format($repayment->remaining_balance, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Information -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Loan Information</h3>
                </div>

                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Borrower</p>
                            <p class="text-lg text-gray-900">{{ $repayment->loanApplication->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Loan Amount</p>
                            <p class="text-lg font-bold text-gray-900">UGX {{ number_format($repayment->loanApplication->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Interest Rate</p>
                            <p class="text-lg text-gray-900">{{ $repayment->loanApplication->applied_interest_rate ?? 'N/A' }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Repayment Schedule</p>
                            <p class="text-lg text-gray-900">{{ str($repayment->loanApplication->repayment_schedule)->replace('_', ' ')->title() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Record Payment Form -->
            @if($repayment->status !== 'completed')
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Record Payment</h3>
                </div>

                <form action="{{ route('repayments.record', $repayment) }}" method="POST" class="px-6 py-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="paid_amount" class="block text-sm font-medium text-gray-700">Payment Amount</label>
                            <input type="number" step="0.01" name="paid_amount" id="paid_amount" 
                                   value="{{ old('paid_amount', $repayment->getRemainingAmount()) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                            @error('paid_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="paid_date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                            <input type="date" name="paid_date" id="paid_date" 
                                   value="{{ old('paid_date', now()->format('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                            @error('paid_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select name="payment_method" id="payment_method" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                                <option value="">Select Method</option>
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="mobile_money" {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                <option value="check" {{ old('payment_method') === 'check' ? 'selected' : '' }}>Check</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_reference" class="block text-sm font-medium text-gray-700">Reference Number</label>
                            <input type="text" name="payment_reference" id="payment_reference" 
                                   value="{{ old('payment_reference') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                   placeholder="Transaction ID, Check Number, etc.">
                            @error('payment_reference')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>