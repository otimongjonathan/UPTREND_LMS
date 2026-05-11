<x-app-layout>
    <style>
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .content-animate {
            animation: slideUp 0.6s ease-out;
        }
        .table-row {
            transition: all 0.3s ease;
        }
        .table-row:hover {
            background-color: #f0f9ff;
            box-shadow: inset 0 0 10px rgba(37, 99, 235, 0.1);
        }
    </style>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
            💰 {{ __('Loans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- PENDING ISSUE Section -->
            <div class="content-animate bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-wide mb-6">⏳ Pending Issue ({{ $pendingIssue }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-b-2 border-yellow-300">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Borrower</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Loan Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($loans->where('status', 'approved') as $loan)
                                    <tr class="table-row">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loan->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loan->applicant_full_name ?? $loan->user?->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">UGX {{ number_format($loan->amount, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ str($loan->loan_type ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 uppercase">
                                                Approved
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex gap-2">
                                                <a href="{{ route('applications.show', $loan->id) }}" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                                    View
                                                </a>
                                                <form action="{{ route('loans.issue', $loan->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition">Issue</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No pending issues.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ISSUED Section -->
            <div class="content-animate bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-wide mb-6">✅ Issued ({{ $active }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-green-50 to-green-100 border-b-2 border-green-300">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Borrower</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Loan Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Issued Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($loans->where('status', 'active') as $loan)
                                    <tr class="table-row">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loan->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loan->applicant_full_name ?? $loan->user?->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">UGX {{ number_format($loan->amount, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ str($loan->loan_type ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loan->disbursement_date?->format('M d, Y') ?? $loan->updated_at?->format('M d, Y') ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 uppercase">
                                                Active
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex gap-2">
                                                <a href="{{ route('loans.show', $loan->id) }}" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                                    View
                                                </a>
                                                <a href="{{ route('loans.show', $loan->id) }}" class="px-3 py-1 bg-purple-500 text-white text-xs rounded hover:bg-purple-600 transition">
                                                    Details
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No active loans.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- COMPLETED Section -->
            <div class="content-animate bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-wide mb-6">✔️ Completed ({{ $completed }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-blue-50 to-blue-100 border-b-2 border-blue-300">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Borrower</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Loan Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Completed Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($loans->where('status', 'completed') as $loan)
                                    <tr class="table-row">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loan->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loan->applicant_full_name ?? $loan->user?->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">UGX {{ number_format($loan->amount, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ str($loan->loan_type ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loan->completed_at?->format('M d, Y') ?? $loan->updated_at?->format('M d, Y') ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 uppercase">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="{{ route('loans.show', $loan->id) }}" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No completed loans.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- OVERDUE Section -->
            <div class="content-animate bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-wide mb-6">🚨 Overdue ({{ $overdue }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-red-50 to-red-100 border-b-2 border-red-300">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Borrower</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Loan Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Outstanding Balance</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($loans->where('status', 'overdue') as $loan)
                                    <tr class="table-row">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loan->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loan->applicant_full_name ?? $loan->user?->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">UGX {{ number_format($loan->amount, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ str($loan->loan_type ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">UGX {{ number_format($loan->outstanding_balance ?? 0, 2) }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 uppercase">
                                                Overdue
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex gap-2">
                                                <a href="{{ route('loans.show', $loan->id) }}" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                                    View
                                                </a>
                                                <a href="{{ route('loans.show', $loan->id) }}" class="px-3 py-1 bg-orange-500 text-white text-xs rounded hover:bg-orange-600 transition">
                                                    Details
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No overdue loans.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>