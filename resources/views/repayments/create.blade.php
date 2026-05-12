<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                💳 Create Repayment Schedule
            </h2>
            <a href="{{ route('repayments.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Create Manual Repayment Installment</h3>
                    <p class="text-sm text-gray-600 mt-1">Create a single manual repayment installment. Note: Repayment schedules are automatically generated when loans are issued.</p>
                </div>

                <div class="px-6 py-6">
                    @if ($errors->any())
                        <div class="mb-6 rounded-lg bg-red-50 text-red-700 px-4 py-3 text-sm">
                            <p class="font-semibold">Please fix the following errors:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('repayments.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Loan Selection -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Loan Application</label>
                            <select name="loan_application_id" id="loan_select" required class="w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                <option value="">Choose a borrower and their loan...</option>
                                @foreach ($loans as $loan)
                                    <option value="{{ $loan->id }}" 
                                            data-amount="{{ $loan->amount }}"
                                            data-rate="{{ $loan->applied_interest_rate ?? 15 }}"
                                            data-term="{{ $loan->repayment_term ?? 12 }}"
                                            @selected(old('loan_application_id') == $loan->id)>
                                        {{ $loan->applicant_full_name ?? $loan->user?->name }} - Loan #{{ $loan->id }} 
                                        (UGX {{ number_format($loan->amount, 0) }} @ {{ $loan->applied_interest_rate ?? 15 }}%)
                                    </option>
                                @endforeach
                            </select>
                            @error('loan_application_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Installment Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Installment Number</label>
                                <input type="number" name="installment_number" value="{{ old('installment_number', 1) }}" min="1" required 
                                       class="mt-1 w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('installment_number') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Due Date
                                    <span class="text-xs text-gray-500 font-normal ml-1">(When borrower must pay this installment)</span>
                                </label>
                                <input type="date" name="due_date" value="{{ old('due_date') }}" required 
                                       class="mt-1 w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                @error('due_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Loan Terms Display -->
                        <div id="loan-terms" class="bg-blue-50 p-4 rounded-lg" style="display: none;">
                            <h4 class="font-semibold text-gray-900 mb-3">📋 Confirmed Loan Terms</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div><strong>Principal:</strong> <span id="display-principal"></span></div>
                                <div><strong>Interest Rate:</strong> <span id="display-rate"></span></div>
                                <div><strong>Term:</strong> <span id="display-term"></span></div>
                                <div><strong>Interest Method:</strong> 
                                    <select id="interest-method" class="text-xs border rounded px-1">
                                        <option value="simple">Simple Interest</option>
                                        <option value="compound" selected>Compound Interest</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 p-3 bg-white rounded border">
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div><strong>Total Interest:</strong> <span id="total-interest" class="text-blue-600"></span></div>
                                    <div><strong>Total Amount:</strong> <span id="total-amount-due" class="text-green-600"></span></div>
                                    <div><strong>Per Installment:</strong> <span id="per-installment" class="text-orange-600"></span></div>
                                </div>
                            </div>
                            <button type="button" id="confirm-terms" class="mt-3 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                ✓ Confirm Terms & Create Schedule
                            </button>
                        </div>

                        <!-- Payment Breakdown -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-semibold text-gray-900">Payment Breakdown</h4>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Reducing Balance Method</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Principal Amount</label>
                                    <input type="number" name="principal_amount" value="{{ old('principal_amount') }}" step="0.01" min="0" required 
                                           class="mt-1 w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                    <p class="text-xs text-gray-500 mt-1">Increases each month</p>
                                    @error('principal_amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Interest Amount</label>
                                    <input type="number" name="interest_amount" value="{{ old('interest_amount') }}" step="0.01" min="0" required 
                                           class="mt-1 w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                    <p class="text-xs text-gray-500 mt-1">Decreases each month</p>
                                    @error('interest_amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Total Amount Due (EMI)</label>
                                    <input type="number" name="amount" id="total_amount" value="{{ old('amount') }}" step="0.01" min="0.01" required readonly
                                           class="mt-1 w-full rounded-lg border border-gray-300 bg-gray-100 focus:border-orange-500 focus:ring-orange-500">
                                    <p class="text-xs text-gray-500 mt-1">Fixed monthly payment</p>
                                    @error('amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Remaining Balance After Payment
                                    <span class="text-xs text-gray-500 font-normal ml-1">(Auto-calculated)</span>
                                </label>
                                <input type="number" name="remaining_balance" id="remaining_balance" value="{{ old('remaining_balance', '') }}" step="0.01" min="0" readonly
                                       class="mt-1 w-full rounded-lg border border-gray-300 bg-gray-100 focus:border-orange-500 focus:ring-orange-500"
                                       placeholder="Will be calculated automatically">
                                <p class="text-xs text-gray-500 mt-1">Outstanding loan balance after this payment</p>
                                @error('remaining_balance') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Frequency</label>
                                <select name="payment_frequency" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                    <option value="">Select frequency...</option>
                                    <option value="weekly" @selected(old('payment_frequency') === 'weekly')>Weekly</option>
                                    <option value="bi_weekly" @selected(old('payment_frequency') === 'bi_weekly')>Bi-Weekly</option>
                                    <option value="monthly" @selected(old('payment_frequency') === 'monthly')>Monthly</option>
                                    <option value="quarterly" @selected(old('payment_frequency') === 'quarterly')>Quarterly</option>
                                </select>
                                @error('payment_frequency') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Status and Payment Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                    <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                                    <option value="partial" @selected(old('status') === 'partial')>Partial</option>
                                    <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                                </select>
                                @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Method (Optional)</label>
                                <select name="payment_method" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                    <option value="">Select method...</option>
                                    <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                                    <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Bank Transfer</option>
                                    <option value="mobile_money" @selected(old('payment_method') === 'mobile_money')>Mobile Money</option>
                                    <option value="check" @selected(old('payment_method') === 'check')>Check</option>
                                </select>
                                @error('payment_method') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                            <textarea name="notes" rows="3" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-orange-500" 
                                      placeholder="Add any additional notes about this repayment installment...">{{ old('notes') }}</textarea>
                            @error('notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-6 border-t">
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                                ✓ Create Installment
                            </button>
                            <a href="{{ route('repayments.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition">
                                ✗ Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let loanTerms = {};
        
        // Auto-calculate total amount
        function calculateTotal() {
            const principal = parseFloat(document.querySelector('input[name="principal_amount"]').value) || 0;
            const interest = parseFloat(document.querySelector('input[name="interest_amount"]').value) || 0;
            const total = principal + interest;
            document.getElementById('total_amount').value = total.toFixed(2);
        }

        // Add event listeners
        document.querySelector('input[name="principal_amount"]').addEventListener('input', calculateTotal);
        document.querySelector('input[name="interest_amount"]').addEventListener('input', calculateTotal);

        // Step 1: Show loan terms for confirmation
        document.getElementById('loan_select').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.value) {
                const principal = parseFloat(option.dataset.amount);
                const annualRate = parseFloat(option.dataset.rate);
                const termMonths = parseInt(option.dataset.term);
                
                // Store loan terms
                loanTerms = { principal, annualRate, termMonths };
                
                // Display loan terms
                document.getElementById('display-principal').textContent = `UGX ${principal.toLocaleString()}`;
                document.getElementById('display-rate').textContent = `${annualRate}% per annum`;
                document.getElementById('display-term').textContent = `${termMonths} months`;
                
                // Calculate and show total interest options
                calculateLoanTotals();
                
                // Show terms section
                document.getElementById('loan-terms').style.display = 'block';
                
                // Hide payment breakdown until terms confirmed
                document.querySelector('.bg-gray-50').style.display = 'none';
            }
        });
        
        // Calculate total loan cost
        function calculateLoanTotals() {
            const { principal, annualRate, termMonths } = loanTerms;
            const method = document.getElementById('interest-method').value;
            
            let totalInterest, totalAmount;
            
            if (method === 'simple') {
                // Simple Interest: P × R × T
                totalInterest = principal * (annualRate / 100) * (termMonths / 12);
                totalAmount = principal + totalInterest;
            } else {
                // Compound Interest (EMI method)
                const monthlyRate = annualRate / 100 / 12;
                const emi = principal * (monthlyRate * Math.pow(1 + monthlyRate, termMonths)) / (Math.pow(1 + monthlyRate, termMonths) - 1);
                totalAmount = emi * termMonths;
                totalInterest = totalAmount - principal;
            }
            
            const perInstallment = totalAmount / termMonths;
            
            // Update display
            document.getElementById('total-interest').textContent = `UGX ${totalInterest.toLocaleString()}`;
            document.getElementById('total-amount-due').textContent = `UGX ${totalAmount.toLocaleString()}`;
            document.getElementById('per-installment').textContent = `UGX ${perInstallment.toLocaleString()}`;
            
            // Store for later use
            loanTerms.totalInterest = totalInterest;
            loanTerms.totalAmount = totalAmount;
            loanTerms.perInstallment = perInstallment;
        }
        
        // Interest method change
        document.getElementById('interest-method').addEventListener('change', calculateLoanTotals);
        
        // Step 2: Confirm terms and show installment breakdown
        document.getElementById('confirm-terms').addEventListener('click', function() {
            // Show payment breakdown section
            document.querySelector('.bg-gray-50').style.display = 'block';
            
            // Calculate this specific installment
            calculateInstallmentBreakdown();
            
            // Scroll to payment breakdown
            document.querySelector('.bg-gray-50').scrollIntoView({ behavior: 'smooth' });
        });
        
        // Calculate specific installment breakdown
        function calculateInstallmentBreakdown() {
            const installmentNumber = parseInt(document.querySelector('input[name="installment_number"]').value) || 1;
            const { principal, annualRate, termMonths, totalAmount, totalInterest } = loanTerms;
            const method = document.getElementById('interest-method').value;
            
            if (method === 'simple') {
                // Simple interest: equal installments
                const principalPortion = principal / termMonths;
                const interestPortion = totalInterest / termMonths;
                const remainingBalance = principal - (principalPortion * installmentNumber);
                
                document.querySelector('input[name="principal_amount"]').value = principalPortion.toFixed(2);
                document.querySelector('input[name="interest_amount"]').value = interestPortion.toFixed(2);
                document.querySelector('input[name="remaining_balance"]').value = Math.max(0, remainingBalance).toFixed(2);
            } else {
                // Compound interest: reducing balance
                const monthlyRate = annualRate / 100 / 12;
                const emi = loanTerms.perInstallment;
                
                // Calculate remaining balance before this installment
                let remainingBalance = principal;
                for (let i = 1; i < installmentNumber; i++) {
                    const interestForMonth = remainingBalance * monthlyRate;
                    const principalForMonth = emi - interestForMonth;
                    remainingBalance -= principalForMonth;
                }
                
                // Calculate for current installment
                const interestPortion = remainingBalance * monthlyRate;
                const principalPortion = emi - interestPortion;
                const newRemainingBalance = remainingBalance - principalPortion;
                
                document.querySelector('input[name="principal_amount"]').value = principalPortion.toFixed(2);
                document.querySelector('input[name="interest_amount"]').value = interestPortion.toFixed(2);
                document.querySelector('input[name="remaining_balance"]').value = Math.max(0, newRemainingBalance).toFixed(2);
            }
            
            calculateTotal();
            showInstallmentDetails();
        }
        
        // Update when installment number changes
        document.querySelector('input[name="installment_number"]').addEventListener('input', function() {
            if (Object.keys(loanTerms).length > 0) {
                calculateInstallmentBreakdown();
            }
        });
        
        function showInstallmentDetails() {
            const installmentNumber = parseInt(document.querySelector('input[name="installment_number"]').value) || 1;
            const method = document.getElementById('interest-method').value;
            
            // Remove existing details
            const existing = document.getElementById('installment-details');
            if (existing) existing.remove();
            
            // Create details
            const detailsDiv = document.createElement('div');
            detailsDiv.id = 'installment-details';
            detailsDiv.className = 'mt-4 p-4 bg-green-50 border border-green-200 rounded-lg';
            detailsDiv.innerHTML = `
                <h5 class="font-semibold text-gray-900 mb-2">📊 Installment #${installmentNumber} Breakdown</h5>
                <div class="text-sm">
                    <p><strong>Method:</strong> ${method === 'simple' ? 'Simple Interest (Equal Principal + Interest)' : 'Compound Interest (Reducing Balance)'}</p>
                    <p><strong>Total Loan Interest:</strong> UGX ${loanTerms.totalInterest?.toLocaleString()}</p>
                    <p><strong>Total Loan Amount:</strong> UGX ${loanTerms.totalAmount?.toLocaleString()}</p>
                    <p class="text-xs text-gray-600 mt-2">
                        ${method === 'simple' ? 
                            'Simple: Same principal and interest every month' : 
                            'Compound: Interest decreases, principal increases each month'
                        }
                    </p>
                </div>
            `;
            
            document.querySelector('.bg-gray-50').appendChild(detailsDiv);
        }
    </script>
</x-app-layout>