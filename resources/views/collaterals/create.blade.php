<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Collateral to Loan #{{ $loan->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-width mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('collaterals.store', $loan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-900">
                            <p class="font-semibold">Collateral capture is required before disbursement.</p>
                            <p class="mt-1">You may add multiple collateral records for the same loan. Use one record per document.</p>
                        </div>

                        <!-- Collateral Type -->
                        <div>
                            <label for="collateral_type" class="block text-sm font-medium text-gray-700">Collateral Type</label>
                            <select id="collateral_type" name="collateral_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select collateral type</option>
                                @foreach($collateralTypes as $type)
                                    <option value="{{ $type }}" {{ old('collateral_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('collateral_type')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="loan_supervisor_id" class="block text-sm font-medium text-gray-700">Loan Supervisor</label>
                            <select id="loan_supervisor_id" name="loan_supervisor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select supervising staff member</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('loan_supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                        {{ $supervisor->name }}{{ $supervisor->business_name ? ' - ' . $supervisor->business_name : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('loan_supervisor_id')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estimated Value -->
                        <div>
                            <label for="estimated_value" class="block text-sm font-medium text-gray-700">Estimated Value</label>
                            <input type="number" id="estimated_value" name="estimated_value" value="{{ old('estimated_value') }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('estimated_value')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Valuation Date -->
                        <div>
                            <label for="valuation_date" class="block text-sm font-medium text-gray-700">Valuation Date</label>
                            <input type="date" id="valuation_date" name="valuation_date" value="{{ old('valuation_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('valuation_date')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document -->
                        <div>
                            <label for="collateral_document" class="block text-sm font-medium text-gray-700">Proof of Collateral (PDF)</label>
                            <input type="file" id="collateral_document" name="collateral_document" class="mt-1 block w-full" accept=".pdf" required>
                            <p class="text-gray-500 text-xs mt-1">Upload a PDF proof document. Max 5MB.</p>
                            @error('collateral_document')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Add Collateral
                            </button>
                            <a href="{{ route('collaterals.index', $loan) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
