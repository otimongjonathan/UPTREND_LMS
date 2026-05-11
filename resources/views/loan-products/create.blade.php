<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Loan Product
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('loan-products.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Business Loan, Personal Loan" required>
                            @error('name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Min Amount -->
                            <div>
                                <label for="min_amount" class="block text-sm font-medium text-gray-700">Minimum Amount</label>
                                <input type="number" id="min_amount" name="min_amount" value="{{ old('min_amount') }}" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                @error('min_amount')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Amount -->
                            <div>
                                <label for="max_amount" class="block text-sm font-medium text-gray-700">Maximum Amount</label>
                                <input type="number" id="max_amount" name="max_amount" value="{{ old('max_amount') }}" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                @error('max_amount')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Min Term -->
                            <div>
                                <label for="min_term" class="block text-sm font-medium text-gray-700">Minimum Term (Months)</label>
                                <input type="number" id="min_term" name="min_term" value="{{ old('min_term') }}" step="1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                @error('min_term')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Term -->
                            <div>
                                <label for="max_term" class="block text-sm font-medium text-gray-700">Maximum Term (Months)</label>
                                <input type="number" id="max_term" name="max_term" value="{{ old('max_term') }}" step="1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                @error('max_term')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Interest Rate -->
                            <div>
                                <label for="interest_rate" class="block text-sm font-medium text-gray-700">Interest Rate (%)</label>
                                <input type="number" id="interest_rate" name="interest_rate" value="{{ old('interest_rate') }}" step="0.01" min="0" max="100" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                @error('interest_rate')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Processing Fee -->
                            <div>
                                <label for="processing_fee_percent" class="block text-sm font-medium text-gray-700">Processing Fee (%)</label>
                                <input type="number" id="processing_fee_percent" name="processing_fee_percent" value="{{ old('processing_fee_percent') }}" step="0.01" min="0" max="100" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                @error('processing_fee_percent')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Late Payment Fee -->
                            <div>
                                <label for="late_payment_fee_percent" class="block text-sm font-medium text-gray-700">Late Payment Fee (%)</label>
                                <input type="number" id="late_payment_fee_percent" name="late_payment_fee_percent" value="{{ old('late_payment_fee_percent') }}" step="0.01" min="0" max="100" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                @error('late_payment_fee_percent')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Insurance Premium -->
                            <div>
                                <label for="insurance_premium_percent" class="block text-sm font-medium text-gray-700">Insurance Premium (%)</label>
                                <input type="number" id="insurance_premium_percent" name="insurance_premium_percent" value="{{ old('insurance_premium_percent') }}" step="0.01" min="0" max="100" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                @error('insurance_premium_percent')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center gap-4">
                            <label for="is_active" class="flex items-center gap-2">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="rounded">
                                <span class="text-sm font-medium text-gray-700">Active (Available to customers)</span>
                            </label>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Create Product
                            </button>
                            <a href="{{ route('loan-products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
