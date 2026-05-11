<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                💰 Loan Disbursements
            </h2>
            <div class="text-sm text-gray-600">
                Total: {{ $disbursements->total() }} records
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg border border-yellow-200">
                    <p class="text-yellow-600 text-xs font-semibold uppercase tracking-wide">Pending</p>
                    <p class="text-2xl font-bold text-yellow-700">{{ $disbursements->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                    <p class="text-blue-600 text-xs font-semibold uppercase tracking-wide">Approved</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $disbursements->where('status', 'approved')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                    <p class="text-green-600 text-xs font-semibold uppercase tracking-wide">Disbursed</p>
                    <p class="text-2xl font-bold text-green-700">{{ $disbursements->where('status', 'disbursed')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-lg border border-red-200">
                    <p class="text-red-600 text-xs font-semibold uppercase tracking-wide">Cancelled</p>
                    <p class="text-2xl font-bold text-red-700">{{ $disbursements->where('status', 'cancelled')->count() }}</p>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('disbursements.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Borrower -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search Borrower</label>
                            <input type="text" name="search" placeholder="Search by name..." 
                                value="{{ request('search') }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="disbursed" {{ request('status') === 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <!-- Method Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Method</label>
                            <select name="method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Methods</option>
                                <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="check" {{ request('method') === 'check' ? 'selected' : '' }}>Check</option>
                                <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                                🔍 Search
                            </button>
                            <a href="{{ route('disbursements.index') }}" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition font-medium text-center">
                                ↺ Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Disbursements Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold">Loan ID</th>
                                <th class="px-6 py-4 text-left font-semibold">Borrower</th>
                                <th class="px-6 py-4 text-left font-semibold">Amount</th>
                                <th class="px-6 py-4 text-left font-semibold">Method</th>
                                <th class="px-6 py-4 text-left font-semibold">Status</th>
                                <th class="px-6 py-4 text-left font-semibold">Date</th>
                                <th class="px-6 py-4 text-left font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($disbursements as $disburse)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-4 font-semibold text-blue-600">#{{ $disburse->loan_application_id }}</td>
                                    <td class="px-6 py-4 text-gray-800">{{ $disburse->loanApplication->user->name }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">UGX {{ number_format($disburse->disbursement_amount, 0) }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ ucfirst(str_replace('_', ' ', $disburse->disbursement_method)) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-white text-xs font-bold
                                            @if($disburse->status === 'pending') bg-yellow-500
                                            @elseif($disburse->status === 'approved') bg-blue-500
                                            @elseif($disburse->status === 'disbursed') bg-green-500
                                            @else bg-red-500
                                            @endif
                                        ">
                                            {{ ucfirst($disburse->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $disburse->disbursement_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 flex gap-3">
                                        @if($disburse->status === 'pending')
                                            <form action="{{ route('disbursements.approve', $disburse) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-blue-700 transition">✓ Approve</button>
                                            </form>
                                        @elseif($disburse->status === 'approved')
                                            <form action="{{ route('disbursements.disburse', $disburse) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button class="bg-green-600 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-green-700 transition">💳 Disburse</button>
                                            </form>
                                        @endif
                                        @if($disburse->status !== 'disbursed' && $disburse->status !== 'cancelled')
                                            <form action="{{ route('disbursements.cancel', $disburse) }}" method="POST" style="display:inline;" onsubmit="return confirm('Cancel this disbursement?');">
                                                @csrf
                                                <button class="bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-red-700 transition">✕ Cancel</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <p class="text-lg font-semibold mb-2">📭 No disbursements found</p>
                                            <p class="text-sm">No records match your current filters</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $disbursements->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
