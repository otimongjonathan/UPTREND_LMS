<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Guarantor - Loan #{{ $loan->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-width mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('guarantors.update', [$loan, $guarantor]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Full Name -->
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $guarantor->full_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('full_name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Relationship -->
                        <div>
                            <label for="relationship" class="block text-sm font-medium text-gray-700">Relationship</label>
                            <input type="text" id="relationship" name="relationship" value="{{ old('relationship', $guarantor->relationship) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('relationship')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Phone -->
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                            <input type="tel" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $guarantor->contact_phone) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('contact_phone')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $guarantor->contact_email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('contact_email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID Number -->
                        <div>
                            <label for="id_number" class="block text-sm font-medium text-gray-700">ID Number</label>
                            <input type="text" id="id_number" name="id_number" value="{{ old('id_number', $guarantor->id_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('id_number')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea id="address" name="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('address', $guarantor->address) }}</textarea>
                            @error('address')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Occupation -->
                        <div>
                            <label for="occupation" class="block text-sm font-medium text-gray-700">Occupation</label>
                            <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $guarantor->occupation) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('occupation')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Monthly Income -->
                        <div>
                            <label for="monthly_income" class="block text-sm font-medium text-gray-700">Monthly Income</label>
                            <input type="number" id="monthly_income" name="monthly_income" value="{{ old('monthly_income', $guarantor->monthly_income) }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('monthly_income')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document -->
                        <div>
                            <label for="guarantor_document" class="block text-sm font-medium text-gray-700">ID Document (Optional)</label>
                            <input type="file" id="guarantor_document" name="guarantor_document" class="mt-1 block w-full" accept=".pdf,.jpg,.jpeg,.png">
                            <p class="text-gray-500 text-xs mt-1">Max 5MB - PDF, JPG, or PNG</p>
                            @error('guarantor_document')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Update Guarantor
                            </button>
                            <a href="{{ route('guarantors.index', $loan) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
