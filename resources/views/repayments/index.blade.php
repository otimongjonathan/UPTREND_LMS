<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                💳 {{ __('Loan Repayments') }} - UPDATED VERSION
            </h2>
            <a href="{{ route('repayments.create') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                + Create Repayment
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <h3 class="text-gray-600 text-sm font-semibold">Total Due</h3>
                    <p class="text-2xl font-bold text-blue-600 mt-2">{{ number_format($summary['total_due'], 2) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <h3 class="text-gray-600 text-sm font-semibold">Total Paid</h3>
                    <p class="text-2xl font-bold text-green-600 mt-2">{{ number_format($summary['total_paid'], 2) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <h3 class="text-gray-600 text-sm font-semibold">Overdue</h3>
                    <p class="text-2xl font-bold text-red-600 mt-2">{{ $summary['overdue'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <h3 class="text-gray-600 text-sm font-semibold">Pending</h3>
                    <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $summary['pending'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <form method="GET" action="{{ route('repayments.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                    </div>
                    <div>
                        <label for="loan_id" class="block text-sm font-medium text-gray-700">Loan ID</label>
                        <input type="number" name="loan_id" id="loan_id" value="{{ request('loan_id') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                               placeholder="Enter loan ID">
                    </div>
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ request('due_date') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Repayments Table -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">All Repayments</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Loan ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Installment</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Borrower</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Due Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Principal</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Interest</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Total Due</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($repayments as $repayment)
                            <tr class="hover:bg-gray-50 {{ $repayment->isOverdue() ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 text-sm text-gray-900">#{{ $repayment->loan_application_id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                        #{{ $repayment->installment_number ?? 1 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $repayment->loanApplication?->user?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $repayment->due_date?->format('M d, Y') }}
                                    @if($repayment->isOverdue())
                                        <span class="block text-xs text-red-600 font-semibold">{{ $repayment->getDaysOverdue() }} days overdue</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">UGX {{ number_format($repayment->principal_amount ?? 0, 2) }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">UGX {{ number_format($repayment->interest_amount ?? 0, 2) }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                    UGX {{ number_format($repayment->amount + ($repayment->late_fee ?? 0), 2) }}
                                    @if($repayment->late_fee > 0)
                                        <span class="block text-xs text-red-600">+{{ number_format($repayment->late_fee, 2) }} late fee</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-bold
                                        @if ($repayment->status === 'completed') bg-green-100 text-green-700
                                        @elseif ($repayment->status === 'partial') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ str($repayment->status)->title() }}
                                    </span>
                                    @if($repayment->paid_amount > 0 && $repayment->status !== 'completed')
                                        <span class="block text-xs text-gray-600 mt-1">Paid: UGX {{ number_format($repayment->paid_amount, 2) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('repayments.show', $repayment->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold">View</a>
                                        @if ($repayment->status !== 'completed')
                                        <a href="{{ route('repayments.edit', $repayment->id) }}" class="text-green-600 hover:text-green-900 font-semibold">Record Payment</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-gray-500">No repayments found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $repayments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>