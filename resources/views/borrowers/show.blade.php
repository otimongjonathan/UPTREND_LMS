<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight uppercase tracking-wide">
                {{ $borrower->name }}
            </h2>
            <a href="{{ route('borrowers.index') }}" class="px-4 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Borrower Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900 mb-6">Borrower Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Full Name</p>
                            <p class="text-lg font-semibold">{{ $borrower->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-lg font-semibold">{{ $borrower->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Business Name</p>
                            <p class="text-lg font-semibold">{{ $borrower->business_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="text-lg font-semibold">{{ $borrower->tel_no ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Address</p>
                            <p class="text-lg font-semibold">{{ $borrower->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Applications & History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900 mb-6">Application & Loan History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500 border-b bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 font-semibold">Application ID</th>
                                    <th class="py-3 px-4 font-semibold">Loan Type</th>
                                    <th class="py-3 px-4 font-semibold">Amount (UGX)</th>
                                    <th class="py-3 px-4 font-semibold">Term (Months)</th>
                                    <th class="py-3 px-4 font-semibold">Applied Date</th>
                                    <th class="py-3 px-4 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($loans as $loan)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                        <td class="py-4 px-4">{{ $loan->id }}</td>
                                        <td class="py-4 px-4">{{ str($loan->loan_type ?? 'N/A')->replace('_', ' ')->title() }}</td>
                                        <td class="py-4 px-4">{{ number_format($loan->amount, 2) }}</td>
                                        <td class="py-4 px-4">{{ $loan->term_months ?? 'N/A' }}</td>
                                        <td class="py-4 px-4">{{ $loan->created_at->format('M d, Y') }}</td>
                                        <td class="py-4 px-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                                @if ($loan->status === 'pending')
                                                    bg-yellow-100 text-yellow-700
                                                @elseif ($loan->status === 'approved')
                                                    bg-blue-100 text-blue-700
                                                @elseif ($loan->status === 'issued')
                                                    bg-green-100 text-green-700
                                                @elseif ($loan->status === 'rejected')
                                                    bg-red-100 text-red-700
                                                @endif
                                            ">
                                                {{ ucfirst($loan->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-gray-500">No applications found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Loan Details Summary -->
            @php
                $totalLoans = $loans->whereIn('status', ['approved', 'issued'])->count();
                $totalAmount = $loans->whereIn('status', ['approved', 'issued'])->sum('amount');
            @endphp
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900 mb-6">Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="text-sm text-gray-600">Total Active Loans</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $totalLoans }}</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4">
                            <p class="text-sm text-gray-600">Total Amount (UGX)</p>
                            <p class="text-3xl font-bold text-green-600">{{ number_format($totalAmount, 0) }}</p>
                        </div>
                        <div class="border-l-4 border-gray-500 pl-4">
                            <p class="text-sm text-gray-600">All Applications</p>
                            <p class="text-3xl font-bold text-gray-600">{{ $loans->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
