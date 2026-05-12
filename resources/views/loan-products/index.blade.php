<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            💼 Loan Products
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        ✅ {{ session('success') }}
                                    </p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex text-green-500 hover:text-green-700">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <style>
                            @keyframes fade-in {
                                from {
                                    opacity: 0;
                                    transform: translateY(-10px);
                                }
                                to {
                                    opacity: 1;
                                    transform: translateY(0);
                                }
                            }
                            .animate-fade-in {
                                animation: fade-in 0.3s ease-out;
                            }
                        </style>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm animate-fade-in">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">
                                        ❌ {{ session('error') }}
                                    </p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Available Loan Products</h3>
                        <a href="{{ route('loan-products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            ➕ Create New Product
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3">Product Name</th>
                                    <th class="px-6 py-3">Provider Company</th>
                                    <th class="px-6 py-3">Amount Range</th>
                                    <th class="px-6 py-3">Term (Months)</th>
                                    <th class="px-6 py-3">Interest Rate</th>
                                    <th class="px-6 py-3">Processing Fee</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-3 font-semibold">{{ $product->name }}</td>
                                        <td class="px-6 py-3">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                {{ $product->provider_company }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3">
                                            UGX {{ number_format($product->min_amount, 0) }} - UGX {{ number_format($product->max_amount, 0) }}
                                        </td>
                                        <td class="px-6 py-3">
                                            {{ $product->min_term }} - {{ $product->max_term }}
                                        </td>
                                        <td class="px-6 py-3">{{ number_format($product->interest_rate, 2) }}%</td>
                                        <td class="px-6 py-3">{{ number_format($product->processing_fee_percent, 2) }}%</td>
                                        <td class="px-6 py-3">
                                            <form action="{{ route('loan-products.toggle', $product) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 rounded-full text-white text-xs font-bold cursor-pointer
                                                    @if($product->is_active) bg-green-500 hover:bg-green-600
                                                    @else bg-gray-400 hover:bg-gray-500
                                                    @endif
                                                ">
                                                    {{ $product->is_active ? '✓ Active' : '✗ Inactive' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-3 flex gap-2">
                                            <a href="{{ route('loan-products.edit', $product) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                                            <form action="{{ route('loan-products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-xs">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-3 text-center text-gray-500">
                                            No loan products created yet. <a href="{{ route('loan-products.create') }}" class="text-blue-600">Create one now</a>
                                        </td>
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
