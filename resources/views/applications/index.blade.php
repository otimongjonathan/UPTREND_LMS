<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight uppercase tracking-wide">
            {{ __('Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- PENDING Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900">⏳ Pending Applications ({{ $statusCounts['pending'] }})</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500 border-b">
                                <tr>
                                    <th class="py-2 pe-4">Date</th>
                                    <th class="py-2 pe-4">Applicant</th>
                                    <th class="py-2 pe-4">Loan Type</th>
                                    <th class="py-2 pe-4">Amount (UGX)</th>
                                    <th class="py-2 pe-4">Repayment</th>
                                    <th class="py-2 pe-4">Status</th>
                                    <th class="py-2 pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications->where('status', 'pending') as $application)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pe-4">{{ $application->created_at->format('M d, Y H:i') }}</td>
                                        <td class="py-3 pe-4">{{ $application->applicant_full_name ?? $application->user?->name ?? 'N/A' }}</td>
                                        <td class="py-3 pe-4">{{ str($application->loan_type ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="py-3 pe-4">{{ number_format($application->amount, 2) }}</td>
                                        <td class="py-3 pe-4">{{ str($application->repayment_schedule ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="py-3 pe-4">
                                            <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700 uppercase">
                                                {{ $application->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 pe-4 flex gap-2">
                                            <a href="{{ route('applications.show', $application->id) }}" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 font-semibold">
                                                👁️ View Details
                                            </a>
                                            <form action="{{ route('applications.approve', $application->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">✓ Approve</button>
                                            </form>
                                            <form action="{{ route('applications.reject', $application->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">✗ Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">No pending applications.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- APPROVED Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900">✅ Approved Applications ({{ $statusCounts['approved'] }})</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500 border-b">
                                <tr>
                                    <th class="py-2 pe-4">Date</th>
                                    <th class="py-2 pe-4">Applicant</th>
                                    <th class="py-2 pe-4">Loan Type</th>
                                    <th class="py-2 pe-4">Amount (UGX)</th>
                                    <th class="py-2 pe-4">Repayment</th>
                                    <th class="py-2 pe-4">Status</th>
                                    <th class="py-2 pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications->where('status', 'approved') as $application)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pe-4">{{ $application->created_at->format('M d, Y H:i') }}</td>
                                        <td class="py-3 pe-4">{{ $application->applicant_full_name ?? $application->user?->name ?? 'N/A' }}</td>
                                        <td class="py-3 pe-4">{{ str($application->loan_type ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="py-3 pe-4">{{ number_format($application->amount, 2) }}</td>
                                        <td class="py-3 pe-4">{{ str($application->repayment_schedule ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="py-3 pe-4">
                                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700 uppercase">
                                                {{ $application->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 pe-4">
                                            <a href="{{ route('applications.show', $application->id) }}" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 font-semibold">
                                                👁️ View Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">No approved applications.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- REJECTED Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900">❌ Rejected Applications ({{ $statusCounts['rejected'] }})</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500 border-b">
                                <tr>
                                    <th class="py-2 pe-4">Date</th>
                                    <th class="py-2 pe-4">Applicant</th>
                                    <th class="py-2 pe-4">Loan Type</th>
                                    <th class="py-2 pe-4">Amount (UGX)</th>
                                    <th class="py-2 pe-4">Repayment</th>
                                    <th class="py-2 pe-4">Status</th>
                                    <th class="py-2 pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications->where('status', 'rejected') as $application)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pe-4">{{ $application->created_at->format('M d, Y H:i') }}</td>
                                        <td class="py-3 pe-4">{{ $application->applicant_full_name ?? $application->user?->name ?? 'N/A' }}</td>
                                        <td class="py-3 pe-4">{{ str($application->loan_type ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="py-3 pe-4">{{ number_format($application->amount, 2) }}</td>
                                        <td class="py-3 pe-4">{{ str($application->repayment_schedule ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="py-3 pe-4">
                                            <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700 uppercase">
                                                {{ $application->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 pe-4">
                                            <a href="{{ route('applications.show', $application->id) }}" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 font-semibold">
                                                👁️ View Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">No rejected applications.</td>
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