@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Configure Disbursement</h1>
            <p class="mt-2 text-sm text-gray-600">
                Set up transaction details and repayment schedule
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('disbursements.update', $disbursement) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <!-- Transaction Details Section -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Transaction Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Disbursement Method (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Disbursement Method</label>
                                <input type="text" 
                                       value="{{ ucfirst(str_replace('_', ' ', $disbursement->disbursement_method)) }}" 
                                       disabled
                                       class="mt-1 w-full rounded-lg bg-gray-100 px-4 py-2 text-gray-900">
                            </div>

                            <!-- Amount (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="text" 
                                       value="UGX {{ number_format($disbursement->disbursement_amount, 0) }}" 
                                       disabled
                                       class="mt-1 w-full rounded-lg bg-gray-100 px-4 py-2 text-gray-900">
                            </div>
                        </div>

                        <!-- Bank Transfer Details -->
                        @if($disbursement->disbursement_method === 'bank_transfer')
                            <div class="mt-6 pt-6 border-t space-y-6">
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                                    <input type="text" 
                                           id="bank_name" 
                                           name="bank_name" 
                                           value="{{ old('bank_name', $disbursement->bank_name) }}"
                                           class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                           placeholder="e.g., CRDB Bank">
                                    @error('bank_name')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="account_holder_name" class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                                        <input type="text" 
                                               id="account_holder_name" 
                                               name="account_holder_name" 
                                               value="{{ old('account_holder_name', $disbursement->account_holder_name) }}"
                                               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                               placeholder="Full name on account">
                                        @error('account_holder_name')
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                                        <input type="text" 
                                               id="account_number" 
                                               name="account_number" 
                                               value="{{ old('account_number', $disbursement->account_number) }}"
                                               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                               placeholder="Account number or IBAN">
                                        @error('account_number')
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="routing_number" class="block text-sm font-medium text-gray-700">Routing Number</label>
                                        <input type="text" 
                                               id="routing_number" 
                                               name="routing_number" 
                                               value="{{ old('routing_number', $disbursement->routing_number) }}"
                                               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                               placeholder="Bank routing code">
                                        @error('routing_number')
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="transaction_id" class="block text-sm font-medium text-gray-700">Transaction ID</label>
                                        <input type="text" 
                                               id="transaction_id" 
                                               name="transaction_id" 
                                               value="{{ old('transaction_id', $disbursement->transaction_id) }}"
                                               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                               placeholder="Reference/Transaction ID">
                                        @error('transaction_id')
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @elseif($disbursement->disbursement_method === 'cash')
                            <div class="mt-6 pt-6 border-t space-y-6">
                                <div>
                                    <label for="cash_received_by" class="block text-sm font-medium text-gray-700">Received By (Name)</label>
                                    <input type="text" 
                                           id="cash_received_by" 
                                           name="cash_received_by" 
                                           value="{{ old('cash_received_by', $disbursement->cash_received_by) }}"
                                           class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                           placeholder="Name of person receiving cash">
                                    @error('cash_received_by')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="cash_notes" class="block text-sm font-medium text-gray-700">Cash Handover Notes</label>
                                    <textarea id="cash_notes" 
                                              name="cash_notes" 
                                              rows="4"
                                              class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                              placeholder="e.g., Date of receipt, condition of cash, witnesses, etc.">{{ old('cash_notes', $disbursement->cash_notes) }}</textarea>
                                    @error('cash_notes')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="mt-6 pt-6 border-t">
                            <label for="transaction_notes" class="block text-sm font-medium text-gray-700">Additional Transaction Notes</label>
                            <textarea id="transaction_notes" 
                                      name="transaction_notes" 
                                      rows="3"
                                      class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                      placeholder="Any additional details about this transaction">{{ old('transaction_notes', $disbursement->transaction_notes) }}</textarea>
                            @error('transaction_notes')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Repayment Schedule Section -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Repayment Schedule Configuration</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Payment Frequency -->
                            <div>
                                <label for="payment_frequency" class="block text-sm font-medium text-gray-700">Payment Frequency</label>
                                <select id="payment_frequency" 
                                        name="payment_frequency"
                                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                        required>
                                    <option value="">-- Select frequency --</option>
                                    <option value="daily" {{ old('payment_frequency', $disbursement->payment_frequency) === 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ old('payment_frequency', $disbursement->payment_frequency) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="bi-weekly" {{ old('payment_frequency', $disbursement->payment_frequency) === 'bi-weekly' ? 'selected' : '' }}>Bi-weekly</option>
                                    <option value="monthly" {{ old('payment_frequency', $disbursement->payment_frequency) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="bi-monthly" {{ old('payment_frequency', $disbursement->payment_frequency) === 'bi-monthly' ? 'selected' : '' }}>Bi-monthly</option>
                                    <option value="quarterly" {{ old('payment_frequency', $disbursement->payment_frequency) === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="semi-annual" {{ old('payment_frequency', $disbursement->payment_frequency) === 'semi-annual' ? 'selected' : '' }}>Semi-annual</option>
                                    <option value="annual" {{ old('payment_frequency', $disbursement->payment_frequency) === 'annual' ? 'selected' : '' }}>Annual</option>
                                </select>
                                @error('payment_frequency')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Number of Installments -->
                            <div>
                                <label for="number_of_installments" class="block text-sm font-medium text-gray-700">Number of Installments</label>
                                <input type="number" 
                                       id="number_of_installments" 
                                       name="number_of_installments" 
                                       value="{{ old('number_of_installments', $disbursement->number_of_installments) }}"
                                       min="1"
                                       max="360"
                                       class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                       required>
                                @error('number_of_installments')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- First Payment Date -->
                            <div>
                                <label for="first_payment_date" class="block text-sm font-medium text-gray-700">First Payment Date</label>
                                <input type="date" 
                                       id="first_payment_date" 
                                       name="first_payment_date" 
                                       value="{{ old('first_payment_date', $disbursement->first_payment_date) }}"
                                       min="{{ now()->addDay()->format('Y-m-d') }}"
                                       class="mt-1 w-full rounded-lg border-gray-300 shadow-sm px-4 py-2"
                                       required>
                                @error('first_payment_date')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Schedule Preview -->
                        <div class="mt-8 pt-8 border-t">
                            <h4 class="font-semibold text-gray-900 mb-4">Schedule Preview</h4>
                            <div id="schedulePreview" class="bg-gray-50 p-4 rounded-lg text-sm text-gray-600">
                                <p>Configure the fields above to preview the repayment schedule</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                            Save Configuration
                        </button>
                        <a href="{{ route('disbursements.show', $disbursement) }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-semibold text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Loan Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Loan Information</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Borrower</p>
                            <p class="font-semibold text-gray-900">{{ $disbursement->loanApplication->user->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Loan Amount</p>
                            <p class="font-semibold text-gray-900">
                                UGX {{ number_format($disbursement->loanApplication->amount, 0) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Interest</p>
                            <p class="font-semibold text-gray-900">
                                UGX {{ number_format($disbursement->loanApplication->total_interest_amount ?? 0, 0) }}
                            </p>
                        </div>

                        <div class="pt-3 border-t">
                            <p class="text-sm text-gray-600">Total Repay</p>
                            <p class="text-lg font-bold text-gray-900">
                                UGX {{ number_format(($disbursement->loanApplication->amount ?? 0) + ($disbursement->loanApplication->total_interest_amount ?? 0), 0) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <h4 class="font-semibold text-blue-900 mb-3">💡 Payment Frequency Guide</h4>
                    <div class="text-sm text-blue-800 space-y-2">
                        <p><strong>Daily:</strong> Payment every day</p>
                        <p><strong>Weekly:</strong> Payment every 7 days</p>
                        <p><strong>Monthly:</strong> Most common, payment each month</p>
                        <p><strong>Quarterly:</strong> Every 3 months</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
