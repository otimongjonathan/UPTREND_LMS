<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight uppercase tracking-wide">
            {{ __('Borrower Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900 mb-4">Active Borrowers</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500 border-b bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 font-semibold">Name</th>
                                    <th class="py-3 px-4 font-semibold">Email</th>
                                    <th class="py-3 px-4 font-semibold">Business Name</th>
                                    <th class="py-3 px-4 font-semibold">Active Loans</th>
                                    <th class="py-3 px-4 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($borrowers as $borrower)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                        <td class="py-4 px-4">{{ $borrower->name }}</td>
                                        <td class="py-4 px-4">{{ $borrower->email }}</td>
                                        <td class="py-4 px-4">{{ $borrower->business_name ?? 'N/A' }}</td>
                                        <td class="py-4 px-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                                {{ $borrower->loanApplications->whereIn('status', ['approved', 'issued'])->count() }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <a href="{{ route('borrowers.show', $borrower->id) }}" class="px-4 py-2 bg-primary-600 text-white text-xs rounded hover:bg-primary-700 transition">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-gray-500">No borrowers found.</td>
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