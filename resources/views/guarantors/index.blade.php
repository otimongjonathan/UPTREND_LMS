<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Loan Guarantors - Loan #{{ $loan->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-width mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <a href="{{ route('guarantors.create', $loan) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Add Guarantor
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3">Name</th>
                                    <th class="px-6 py-3">Relationship</th>
                                    <th class="px-6 py-3">Phone</th>
                                    <th class="px-6 py-3">Monthly Income</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($guarantors as $guarantor)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-3">{{ $guarantor->full_name }}</td>
                                        <td class="px-6 py-3">{{ $guarantor->relationship }}</td>
                                        <td class="px-6 py-3">{{ $guarantor->contact_phone }}</td>
                                        <td class="px-6 py-3">{{ number_format($guarantor->monthly_income, 2) }}</td>
                                        <td class="px-6 py-3">
                                            <span class="px-3 py-1 rounded-full text-white text-xs font-bold
                                                @if($guarantor->status === 'pending') bg-yellow-500
                                                @elseif($guarantor->status === 'approved') bg-green-500
                                                @else bg-red-500
                                                @endif
                                            ">
                                                {{ ucfirst($guarantor->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 flex gap-2">
                                            <a href="{{ route('guarantors.edit', [$loan, $guarantor]) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                                            @if($guarantor->status === 'pending')
                                                <form action="{{ route('guarantors.approve', [$loan, $guarantor]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button class="text-green-600 hover:underline text-xs">Approve</button>
                                                </form>
                                                <form action="{{ route('guarantors.reject', [$loan, $guarantor]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button class="text-red-600 hover:underline text-xs">Reject</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('guarantors.destroy', [$loan, $guarantor]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this guarantor?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:underline text-xs">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-3 text-center text-gray-500">
                                            No guarantors added yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('applications.show', $loan) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Back to Application
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
