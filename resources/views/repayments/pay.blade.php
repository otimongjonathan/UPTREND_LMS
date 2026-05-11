@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Record Payment</h1>
            <p class="mt-2 text-sm text-gray-600">
                Installment #{{ $schedule->installment_number }} | Due: {{ $schedule->due_date->format('M d, Y') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('repayments.store', $schedule) }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
                    @csrf

                    <!-- Installment Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg border-2 border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Installment Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Principal</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    UGX {{ number_format($schedule->principal_amount, 0) }}
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Interest</p>
                                <p class="text-2xl font-bold text-blue-600">
                                    UGX {{ number_format($schedule->interest_amount, 0) }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Total Due</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    UGX {{ number_format($schedule->total_amount, 0) }}
                                </p>
                            </div>
                        </div>

                        @if($schedule->paid_amount > 0)
                            <div class="mt-4 pt-4 border-t space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Already Paid</span>
                                    <span class="font-semibold">UGX {{ number_format($schedule->paid_amount, 0) }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold">
                                    <span class="text-gray-900">Still Outstanding</span>
                                    <span class="text-red-600">UGX {{ number_format($schedule->total_amount - $schedule->paid_amount, 0) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Status Alert -->
                    @if($schedule->status === 'overdue')
                        <div class="p-4 bg-red-50 border-l-4 border-red-600 rounded">
                            <p class="text-sm text-red-800">
                                <strong>⚠️ This installment is overdue by {{ now()->diffInDays($schedule->due_date) }} days</strong>
                            </p>
                        </div>
                    @endif

                    <!-- Payment Amount -->
                    <div>
                        <label for="amount_paid" class="block text-sm font-medium text-gray-700">Payment Amount</label>
                        <div class="mt-1 relative">
                            <span class="absolute left-4 top-3 text-gray-500">UGX</span>
                            <input type="number" 
                                   id="amount_paid" 
                                   name="amount_paid" 
                                   value="{{ old('amount_paid', $schedule->total_amount - $schedule->paid_amount) }}"
                                   step="0.01"
                                   min="0.01"
                                   max="{{ $schedule->total_amount - $schedule->paid_amount }}"
                                   class="mt-1 block w-full pl-12 pr-4 py-2 rounded-lg border-gray-300 shadow-sm"
                                   required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Maximum: UGX {{ number_format($schedule->total_amount - $schedule->paid_amount, 0) }}
                        </p>
                        @error('amount_paid')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select id="payment_method" 
                                name="payment_method"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                                required>
                            <option value="">-- Select method --</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="check" {{ old('payment_method') === 'check' ? 'selected' : '' }}>Check</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="mobile_money" {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Reference -->
                    <div>
                        <label for="payment_reference" class="block text-sm font-medium text-gray-700">Payment Reference (Optional)</label>
                        <input type="text" 
                               id="payment_reference" 
                               name="payment_reference" 
                               value="{{ old('payment_reference') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                               placeholder="e.g., Check number, transaction ID, receipt number">
                        @error('payment_reference')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3"
                                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                                  placeholder="Any additional notes about this payment">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-4 pt-6 border-t">
                        <button type="submit" class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                            Record Payment
                        </button>
                        <a href="{{ route('repayments.collection', $schedule->loanApplication) }}" 
                           class="flex-1 px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-semibold text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Payment History -->
                @if($paymentHistory->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Payment History</h3>
                        
                        <div class="space-y-3">
                            @foreach($paymentHistory as $payment)
                                <div class="pb-3 border-b last:border-0">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-semibold text-gray-900">
                                            UGX {{ number_format($payment->amount_paid, 0) }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                        @if($payment->payment_reference)
                                            ({{ $payment->payment_reference }})
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-4 border-t font-bold text-gray-900 flex justify-between">
                            <span>Total Paid</span>
                            <span>UGX {{ number_format($paymentHistory->sum('amount_paid'), 0) }}</span>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 rounded-lg p-6 text-center border border-blue-200">
                        <p class="text-sm text-blue-800">No payments recorded yet</p>
                    </div>
                @endif

                <!-- Borrower Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Borrower</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-semibold text-gray-900">{{ $schedule->loanApplication->user->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-sm text-blue-600">{{ $schedule->loanApplication->user->email }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="text-sm text-gray-900">{{ $schedule->loanApplication->user->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                    <h4 class="font-semibold text-yellow-900 mb-2">💡 Payment Tip</h4>
                    <p class="text-xs text-yellow-800">
                        Record the full outstanding amount to mark this installment as paid, or any partial amount to keep it open.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
