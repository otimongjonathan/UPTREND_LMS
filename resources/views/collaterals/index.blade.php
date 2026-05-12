<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Collateral - Loan #{{ $loan->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-width mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <a href="{{ route('collaterals.create', $loan) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Add Collateral
                        </a>
                        <p class="mt-3 text-sm text-gray-600">You can add multiple collateral records for this loan. Keep one record per document proof.</p>
                        @if($collaterals->count())
                            <div class="mt-4 p-4 bg-blue-50 rounded">
                                <p class="text-sm font-semibold">Total Collateral Value: <span class="text-lg">{{ number_format($collaterals->sum('estimated_value'), 2) }}</span></p>
                            </div>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3">Type</th>
                                    <th class="px-6 py-3">Supervisor</th>
                                    <th class="px-6 py-3">Description</th>
                                    <th class="px-6 py-3">Estimated Value</th>
                                    <th class="px-6 py-3">Proof</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collaterals as $collateral)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-3">{{ $collateral->collateral_type }}</td>
                                        <td class="px-6 py-3">{{ $collateral->loanSupervisor->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-3">{{ \Illuminate\Support\Str::limit($collateral->description, 50) }}</td>
                                        <td class="px-6 py-3">{{ number_format($collateral->estimated_value, 2) }}</td>
                                        <td class="px-6 py-3">
                                            @if($collateral->collateral_document_path)
                                                <a href="{{ asset('storage/' . $collateral->collateral_document_path) }}" target="_blank" class="text-blue-600 hover:underline text-xs">View PDF</a>
                                            @else
                                                <span class="text-gray-400 text-xs">No file</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3">
                                            <span class="px-3 py-1 rounded-full text-white text-xs font-bold
                                                @if($collateral->status === 'pending') bg-yellow-500
                                                @elseif($collateral->status === 'verified') bg-green-500
                                                @else bg-red-500
                                                @endif
                                            ">
                                                {{ ucfirst($collateral->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 flex gap-2">
                                            <a href="{{ route('collaterals.edit', $collateral) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                                            @if($collateral->status === 'pending')
                                                <form action="{{ route('collaterals.verify', $collateral) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button class="text-green-600 hover:underline text-xs">Verify</button>
                                                </form>
                                                <form action="{{ route('collaterals.reject', $collateral) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button class="text-red-600 hover:underline text-xs">Reject</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('collaterals.destroy', $collateral) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this collateral?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:underline text-xs">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-3 text-center text-gray-500">
                                            No collaterals added yet
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
