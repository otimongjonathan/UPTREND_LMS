@extends('customer.layouts.app')

@section('content')
    <style>
        .product-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(217, 106, 43, 0.2);
        }
    </style>

    <!-- Header -->
    <div class="mb-8 bg-gradient-to-r from-orange-600 via-orange-700 to-orange-800 rounded-3xl p-8 text-white shadow-2xl">
        <h1 class="text-3xl font-extrabold uppercase tracking-wide">Available Loan Products</h1>
        <p class="text-orange-100 mt-2">Browse and apply for loans from various providers</p>
    </div>

    @if($products->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center shadow-lg border border-orange-100">
            <div class="h-20 w-20 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center mx-auto mb-4">
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Loan Products Available</h3>
            <p class="text-gray-600">No loan products are currently available. Please check back later.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="product-card bg-white rounded-2xl shadow-lg border border-orange-100 overflow-hidden">
                    <!-- Provider Badge -->
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4 text-white">
                        <div class="flex items-center gap-2">
                            <div class="h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-orange-100">Provided by</p>
                                <p class="font-bold text-sm">{{ $product->provider_company }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Product Header -->
                        <div class="mb-4 pb-4 border-b border-orange-100">
                            <h3 class="text-xl font-bold text-gray-900">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 mt-2">{{ $product->description }}</p>
                        </div>

                        <!-- Amount Range -->
                        <div class="mb-4 p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border border-orange-200">
                            <p class="text-xs text-gray-600 mb-1">Loan Amount Range</p>
                            <p class="text-2xl font-bold text-orange-600">
                                UGX {{ number_format($product->min_amount, 0) }} - UGX {{ number_format($product->max_amount, 0) }}
                            </p>
                        </div>

                        <!-- Key Features -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center text-sm p-2 rounded-lg hover:bg-orange-50 transition">
                                <span class="text-gray-600 flex items-center gap-2">
                                    <span>📅</span> Term Duration
                                </span>
                                <span class="font-semibold text-gray-900">{{ $product->min_term }}-{{ $product->max_term }} months</span>
                            </div>
                            <div class="flex justify-between items-center text-sm p-2 rounded-lg hover:bg-orange-50 transition">
                                <span class="text-gray-600 flex items-center gap-2">
                                    <span>📊</span> Interest Rate
                                </span>
                                <span class="font-semibold text-orange-600">{{ number_format($product->interest_rate, 2) }}%</span>
                            </div>
                            <div class="flex justify-between items-center text-sm p-2 rounded-lg hover:bg-orange-50 transition">
                                <span class="text-gray-600 flex items-center gap-2">
                                    <span>⚙️</span> Processing Fee
                                </span>
                                <span class="font-semibold text-gray-900">{{ number_format($product->processing_fee_percent, 2) }}%</span>
                            </div>
                            <div class="flex justify-between items-center text-sm p-2 rounded-lg hover:bg-orange-50 transition">
                                <span class="text-gray-600 flex items-center gap-2">
                                    <span>🛡️</span> Insurance
                                </span>
                                <span class="font-semibold text-gray-900">{{ number_format($product->insurance_premium_percent, 2) }}%</span>
                            </div>
                            <div class="flex justify-between items-center text-sm p-2 rounded-lg hover:bg-orange-50 transition">
                                <span class="text-gray-600 flex items-center gap-2">
                                    <span>⏰</span> Late Fee
                                </span>
                                <span class="font-semibold text-red-600">{{ number_format($product->late_payment_fee_percent, 2) }}%</span>
                            </div>
                        </div>

                        <!-- Provider Contact Details -->
                        @if($product->provider)
                        <div class="mb-6 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                            <p class="text-xs font-semibold text-blue-700 mb-2 uppercase tracking-wide">Provider Contact</p>
                            <div class="space-y-2 text-sm">
                                @if($product->provider->email)
                                <div class="flex items-center gap-2 text-gray-700">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $product->provider->email }}</span>
                                </div>
                                @endif
                                @if($product->provider->tel_no)
                                <div class="flex items-center gap-2 text-gray-700">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $product->provider->tel_no }}</span>
                                </div>
                                @endif
                                @if($product->provider->address)
                                <div class="flex items-start gap-2 text-gray-700">
                                    <svg class="h-4 w-4 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $product->provider->address }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Action Button -->
                        <a href="{{ route('customer.loans.apply') }}?product_id={{ $product->id }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Apply for This Loan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    @endif
@endsection