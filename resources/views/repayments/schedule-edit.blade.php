@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Repayment Schedule</h1>
            <p class="mt-2 text-sm text-gray-600">
                Installment #{{ $schedule->installment_number }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('repayments.update-schedule', $schedule) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Current Details -->
                <div class="bg-gray-50 p-6 rounded-lg border-2 border-gray-200 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Current Details</h3>
                    
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
                            <p class="text-sm text-gray-600">Total</p>
                            <p class="text-2xl font-bold text-gray-900">
                                UGX {{ number_format($schedule->total_amount, 0) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Edit Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" 
                               id="due_date" 
                               name="due_date" 
                               value="{{ old('due_date', $schedule->due_date->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                               required>
                        @error('due_date')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Principal Amount -->
                    <div>
                        <label for="principal_amount" class="block text-sm font-medium text-gray-700">Principal Amount</label>
                        <div class="mt-1 relative">
                            <span class="absolute left-4 top-3 text-gray-500">UGX</span>
                            <input type="number" 
                                   id="principal_amount" 
                                   name="principal_amount" 
                                   value="{{ old('principal_amount', $schedule->principal_amount) }}"
                                   step="0.01"
                                   min="0"
                                   class="block w-full pl-12 pr-4 py-2 rounded-lg border-gray-300 shadow-sm"
                                   required>
                        </div>
                        @error('principal_amount')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Interest Amount -->
                    <div>
                        <label for="interest_amount" class="block text-sm font-medium text-gray-700">Interest Amount</label>
                        <div class="mt-1 relative">
                            <span class="absolute left-4 top-3 text-gray-500">UGX</span>
                            <input type="number" 
                                   id="interest_amount" 
                                   name="interest_amount" 
                                   value="{{ old('interest_amount', $schedule->interest_amount) }}"
                                   step="0.01"
                                   min="0"
                                   class="block w-full pl-12 pr-4 py-2 rounded-lg border-gray-300 shadow-sm"
                                   required>
                        </div>
                        @error('interest_amount')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                              placeholder="Reason for schedule change, etc.">{{ old('notes', $schedule->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                        Save Changes
                    </button>
                    <a href="{{ route('repayments.collection', $schedule->loanApplication) }}" 
                       class="flex-1 px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-semibold text-center">
                        Cancel
                    </a>
                </div>
            </form>

            <!-- Info Box -->
            <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-semibold text-blue-900 mb-2">ℹ️ About Schedule Editing</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Use this to adjust due dates for loan restructuring</li>
                    <li>• Modify amounts if there are penalties or adjustments</li>
                    <li>• Changes are recorded in the notes section</li>
                    <li>• This affects future repayment calculations</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
