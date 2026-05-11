<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                Edit Repayment #{{ $repayment->id }}
            </h2>
            <a href="{{ route('repayments.show', $repayment->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Edit Repayment Details</h3>
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

                    <form action="{{ route('repayments.update', $repayment->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 font-semibold">Amount Due</label>
                                <input type="number" name="amount" value="{{ old('amount', $repayment->amount) }}" step="0.01" min="0.01" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-lg">
                                @error('amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 font-semibold">Due Date</label>
                                <input type="date" name="due_date" value="{{ old('due_date', $repayment->due_date?->format('Y-m-d')) }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @error('due_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 font-semibold">Paid Amount</label>
                                <input type="number" name="paid_amount" value="{{ old('paid_amount', $repayment->paid_amount) }}" step="0.01" min="0" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @error('paid_amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 font-semibold">Paid Date</label>
                                <input type="date" name="paid_date" value="{{ old('paid_date', $repayment->paid_date?->format('Y-m-d')) }}" max="{{ now()->toDateString() }}" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @error('paid_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 font-semibold">Status</label>
                                <select name="status" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="pending" @selected(old('status', $repayment->status) === 'pending')>Pending</option>
                                    <option value="partial" @selected(old('status', $repayment->status) === 'partial')>Partial</option>
                                    <option value="completed" @selected(old('status', $repayment->status) === 'completed')>Completed</option>
                                </select>
                                @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 font-semibold">Payment Method</label>
                                <input type="text" name="payment_method" value="{{ old('payment_method', $repayment->payment_method) }}" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @error('payment_method') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 font-semibold">Payment Reference</label>
                                <input type="text" name="payment_reference" value="{{ old('payment_reference', $repayment->payment_reference) }}" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Transaction ID or receipt number">
                                @error('payment_reference') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 font-semibold">Notes</label>
                                <textarea name="notes" rows="4" class="mt-1 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $repayment->notes) }}</textarea>
                                @error('notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition">
                                ✓ Update Repayment
                            </button>
                            <a href="{{ route('repayments.show', $repayment->id) }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg font-bold hover:bg-gray-600 transition">
                                ✗ Cancel
                            </a>
                            <form action="{{ route('repayments.destroy', $repayment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
