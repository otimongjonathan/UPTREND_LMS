<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Disbursement
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-width mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6 p-4 bg-blue-50 rounded">
                        <p class="text-sm"><strong>Loan:</strong> #{{ $loan->id }} - {{ $loan->user->name }}</p>
                        <p class="text-sm"><strong>Loan Amount:</strong> UGX {{ number_format($loan->amount, 0) }}</p>
                    </div>

                    <form action="{{ route('disbursements.store', $loan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Amount -->
                        <div>
                            <label for="disbursement_amount" class="block text-sm font-medium text-gray-700">Disbursement Amount</label>
                            <input type="number" id="disbursement_amount" name="disbursement_amount" value="{{ old('disbursement_amount') }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('disbursement_amount')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="disbursement_date" class="block text-sm font-medium text-gray-700">Disbursement Date</label>
                            <input type="date" id="disbursement_date" name="disbursement_date" value="{{ old('disbursement_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('disbursement_date')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Method -->
                        <div>
                            <label for="disbursement_method" class="block text-sm font-medium text-gray-700">Disbursement Method</label>
                            <select id="disbursement_method" name="disbursement_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">-- Select method --</option>
                                <option value="bank_transfer" {{ old('disbursement_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="check" {{ old('disbursement_method') == 'check' ? 'selected' : '' }}>Check</option>
                                <option value="cash" {{ old('disbursement_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                            @error('disbursement_method')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank Account -->
                        <div>
                            <label for="bank_account" class="block text-sm font-medium text-gray-700">Bank Account (Optional)</label>
                            <input type="text" id="bank_account" name="bank_account" value="{{ old('bank_account') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Account number or IBAN">
                            @error('bank_account')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reference Number -->
                        <div>
                            <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference Number (Optional)</label>
                            <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g. Check number, transaction ID">
                            @error('reference_number')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document -->
                        <div>
                            <label for="disbursement_document" class="block text-sm font-medium text-gray-700">Supporting Document (Optional)</label>
                            <input type="file" id="disbursement_document" name="disbursement_document" class="mt-1 block w-full" accept=".pdf,.jpg,.jpeg,.png">
                            <p class="text-gray-500 text-xs mt-1">Max 5MB - PDF, JPG, or PNG</p>
                            @error('disbursement_document')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Create Disbursement
                            </button>
                            <a href="{{ route('disbursements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
