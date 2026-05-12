<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                💰 Disburse Loan #{{ $loan->id }}
            </h2>
            <a href="{{ route('loans.show', $loan) }}" class="text-blue-600 hover:text-blue-900">
                ← Back to Loan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Loan Summary -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-600 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Loan Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Borrower</p>
                        <p class="font-semibold text-gray-900">{{ $loan->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Loan Amount</p>
                        <p class="font-semibold text-gray-900">UGX {{ number_format($loan->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Loan Product</p>
                        <p class="font-semibold text-gray-900">{{ $loan->product->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Interest Rate</p>
                        <p class="font-semibold text-gray-900">{{ $loan->product->interest_rate ?? 0 }}% per annum</p>
                    </div>
                </div>
            </div>

            <!-- Collateral Check -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-5 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">🔒 Collateral Security</h3>
                        <p class="text-sm text-gray-700 mt-1">
                            At least one collateral record is required before disbursement.
                        </p>
                        <p class="text-sm text-gray-700 mt-1">
                            Current records: <span class="font-semibold">{{ $loan->collaterals->count() }}</span>
                        </p>
                    </div>
                    <a href="{{ route('collaterals.create', $loan) }}" class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-white font-semibold hover:bg-amber-700">
                        + Add Collateral
                    </a>
                </div>

                @if($loan->collaterals->count())
                    <div class="mt-4 overflow-x-auto bg-white rounded-lg border border-amber-100">
                        <table class="min-w-full text-sm">
                            <thead class="bg-amber-50 text-amber-900">
                                <tr>
                                    <th class="px-4 py-3 text-left">Type</th>
                                    <th class="px-4 py-3 text-left">Value</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Document</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->collaterals as $collateral)
                                    <tr class="border-t">
                                        <td class="px-4 py-3">{{ $collateral->collateral_type }}</td>
                                        <td class="px-4 py-3">UGX {{ number_format($collateral->estimated_value, 2) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $collateral->status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($collateral->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($collateral->collateral_document_path)
                                                <a href="{{ asset('storage/' . $collateral->collateral_document_path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Guarantor Check -->
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-5 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">👥 Guarantor Details</h3>
                        <p class="text-sm text-gray-700 mt-1">
                            At least one guarantor is required before disbursement.
                        </p>
                        <p class="text-sm text-gray-700 mt-1">
                            Current records: <span class="font-semibold">{{ $loan->guarantors->count() }}</span>
                        </p>
                    </div>
                    <a href="{{ route('guarantors.create', $loan) }}" class="inline-flex items-center justify-center rounded-lg bg-purple-600 px-4 py-2 text-white font-semibold hover:bg-purple-700">
                        + Add Guarantor
                    </a>
                </div>

                @if($loan->guarantors->count())
                    <div class="mt-4 overflow-x-auto bg-white rounded-lg border border-purple-100">
                        <table class="min-w-full text-sm">
                            <thead class="bg-purple-50 text-purple-900">
                                <tr>
                                    <th class="px-4 py-3 text-left">Name</th>
                                    <th class="px-4 py-3 text-left">Relationship</th>
                                    <th class="px-4 py-3 text-left">Phone</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->guarantors as $guarantor)
                                    <tr class="border-t">
                                        <td class="px-4 py-3">{{ $guarantor->full_name }}</td>
                                        <td class="px-4 py-3">{{ $guarantor->relationship }}</td>
                                        <td class="px-4 py-3">{{ $guarantor->contact_phone }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $guarantor->status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($guarantor->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Disbursement & Repayment Configuration</h3>
                    
                    <!-- Fee Breakdown Preview -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-600 p-6 rounded-lg mb-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">💰 Fee Breakdown (Auto-calculated)</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Loan Amount</p>
                                <p class="text-lg font-bold text-gray-900" id="preview_loan_amount">UGX {{ number_format($loan->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Processing Fee ({{ $loan->product->processing_fee_percent ?? 0 }}%)</p>
                                <p class="text-lg font-bold text-red-600" id="preview_processing_fee">UGX 0.00</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Insurance ({{ $loan->product->insurance_premium_percent ?? 0 }}%)</p>
                                <p class="text-lg font-bold text-red-600" id="preview_insurance">UGX 0.00</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tax (10%)</p>
                                <p class="text-lg font-bold text-red-600" id="preview_tax">UGX 0.00</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-green-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-600">Total Deductions</p>
                                    <p class="text-xl font-bold text-red-600" id="preview_total_deductions">UGX 0.00</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Net Disbursement</p>
                                    <p class="text-2xl font-bold text-green-600" id="preview_net_amount">UGX {{ number_format($loan->amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('disbursements.store', $loan) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Disbursement Details -->
                        <div class="border-b pb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Disbursement Details</h4>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="loan_supervisor_id" class="block text-sm font-medium text-gray-700">Assign Loan Supervisor *</label>
                                    <select id="loan_supervisor_id" name="loan_supervisor_id" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">-- Select Supervisor --</option>
                                        @foreach($staffMembers as $staff)
                                            <option value="{{ $staff->id }}" {{ old('loan_supervisor_id') == $staff->id ? 'selected' : '' }}>
                                                {{ $staff->name }} ({{ $staff->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">This staff member will oversee the loan lifecycle</p>
                                    @error('loan_supervisor_id')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="disbursement_amount" class="block text-sm font-medium text-gray-700">Disbursement Amount *</label>
                                    <input type="number" id="disbursement_amount" name="disbursement_amount" 
                                           value="{{ old('disbursement_amount', $loan->amount) }}" 
                                           step="0.01" max="{{ $loan->amount }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    @error('disbursement_amount')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="disbursement_date" class="block text-sm font-medium text-gray-700">Disbursement Date *</label>
                                    <input type="date" id="disbursement_date" name="disbursement_date" 
                                           value="{{ old('disbursement_date', now()->format('Y-m-d')) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    @error('disbursement_date')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-2">
                                    <label for="disbursement_method" class="block text-sm font-medium text-gray-700">Disbursement Method *</label>
                                    <select id="disbursement_method" name="disbursement_method" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                            onchange="toggleMethodFields(this.value)" required>
                                        <option value="">-- Select Method --</option>
                                        <option value="bank_transfer" {{ old('disbursement_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="mobile_money" {{ old('disbursement_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                        <option value="cash" {{ old('disbursement_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="cheque" {{ old('disbursement_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    </select>
                                    @error('disbursement_method')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Bank Transfer Fields -->
                            <div id="bank_fields" class="mt-4 grid grid-cols-2 gap-4 hidden">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                                    <input type="text" name="account_holder_name" value="{{ old('account_holder_name', $loan->user->name) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Account Number</label>
                                    <input type="text" name="account_number" value="{{ old('account_number') }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
                                    <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>

                            <!-- Cash Fields -->
                            <div id="cash_fields" class="mt-4 hidden">
                                <label class="block text-sm font-medium text-gray-700">Cash Received By</label>
                                <input type="text" name="cash_received_by" value="{{ old('cash_received_by') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <!-- Repayment Schedule Configuration -->
                        <div class="border-b pb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Repayment Schedule Configuration</h4>
                            
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                                <p class="text-sm text-yellow-800">
                                    <strong>Grace Period:</strong> 2 months from disbursement date. First payment will be due 2 months after disbursement on the same day of the month.
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="payment_frequency" class="block text-sm font-medium text-gray-700">Payment Frequency *</label>
                                    <select id="payment_frequency" name="payment_frequency" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                            onchange="calculateInstallments()" required>
                                        <option value="">-- Select Frequency --</option>
                                        <option value="weekly" {{ old('payment_frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="bi-weekly" {{ old('payment_frequency') == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                        <option value="monthly" {{ old('payment_frequency', 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('payment_frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    </select>
                                    @error('payment_frequency')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="number_of_installments" class="block text-sm font-medium text-gray-700">Number of Installments *</label>
                                    <input type="number" id="number_of_installments" name="number_of_installments" 
                                           value="{{ old('number_of_installments', $loan->term_months) }}" 
                                           min="1" max="360"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    @error('number_of_installments')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" 
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl">
                                💰 Disburse Loan & Generate Schedule
                            </button>
                            <a href="{{ route('loans.show', $loan) }}" 
                               class="px-6 py-3 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-semibold text-sm transition-all duration-200">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const processingFeePercent = {{ $loan->product->processing_fee_percent ?? 0 }};
        const insurancePremiumPercent = {{ $loan->product->insurance_premium_percent ?? 0 }};
        const taxPercent = 10;

        function updateFeeBreakdown() {
            const amount = parseFloat(document.getElementById('disbursement_amount').value) || 0;
            
            const processingFee = (amount * processingFeePercent) / 100;
            const insurance = (amount * insurancePremiumPercent) / 100;
            const tax = ((processingFee + insurance) * taxPercent) / 100;
            const totalDeductions = processingFee + insurance + tax;
            const netAmount = amount - totalDeductions;
            
            document.getElementById('preview_loan_amount').textContent = 'UGX ' + amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_processing_fee').textContent = 'UGX ' + processingFee.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_insurance').textContent = 'UGX ' + insurance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_tax').textContent = 'UGX ' + tax.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_total_deductions').textContent = 'UGX ' + totalDeductions.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('preview_net_amount').textContent = 'UGX ' + netAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        function toggleMethodFields(method) {
            document.getElementById('bank_fields').classList.add('hidden');
            document.getElementById('cash_fields').classList.add('hidden');
            
            if (method === 'bank_transfer') {
                document.getElementById('bank_fields').classList.remove('hidden');
            } else if (method === 'cash') {
                document.getElementById('cash_fields').classList.remove('hidden');
            }
        }

        function calculateInstallments() {
            const frequency = document.getElementById('payment_frequency').value;
            const termMonths = {{ $loan->term_months }};
            let installments = termMonths;

            if (frequency === 'weekly') {
                installments = termMonths * 4;
            } else if (frequency === 'bi-weekly') {
                installments = termMonths * 2;
            } else if (frequency === 'quarterly') {
                installments = Math.max(1, Math.floor(termMonths / 3));
            }

            document.getElementById('number_of_installments').value = installments;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const method = document.getElementById('disbursement_method').value;
            if (method) toggleMethodFields(method);
            
            // Update fee breakdown on amount change
            document.getElementById('disbursement_amount').addEventListener('input', updateFeeBreakdown);
            updateFeeBreakdown();
        });
    </script>
</x-app-layout>