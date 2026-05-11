@extends('customer.layouts.app')

@section('content')
    <style>
        .loan-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .loan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(217, 106, 43, 0.15);
        }
        .filter-btn {
            transition: all 0.2s;
        }
        .filter-btn.active {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
        }
    </style>

    <!-- My Loans Header -->
    <div class="mb-8 bg-gradient-to-r from-orange-600 via-orange-700 to-orange-800 rounded-3xl p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold uppercase tracking-wide">My Loans</h1>
                    <p class="text-orange-100 mt-1">Track your loan applications and their status</p>
                </div>
            </div>
            <a href="{{ route('customer.loan-products') }}" class="px-6 py-3 bg-white text-orange-700 font-bold rounded-xl shadow-lg hover:bg-orange-50 transition flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Apply for Loans
            </a>
        </div>
    </div>

    <!-- Status Filters -->
    <div class="mb-6 flex items-center gap-3">
        <span class="text-sm font-semibold text-gray-700">Filter by Status:</span>
        <div class="flex gap-2">
            <a href="{{ route('customer.loans.index') }}" class="filter-btn px-4 py-2 rounded-lg font-semibold text-sm {{ !request('status') ? 'active' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All
            </a>
            <a href="{{ route('customer.loans.index', ['status' => 'pending']) }}" class="filter-btn px-4 py-2 rounded-lg font-semibold text-sm {{ request('status') === 'pending' ? 'active' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Pending
            </a>
            <a href="{{ route('customer.loans.index', ['status' => 'approved']) }}" class="filter-btn px-4 py-2 rounded-lg font-semibold text-sm {{ request('status') === 'approved' ? 'active' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Approved
            </a>
            <a href="{{ route('customer.loans.index', ['status' => 'rejected']) }}" class="filter-btn px-4 py-2 rounded-lg font-semibold text-sm {{ request('status') === 'rejected' ? 'active' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Rejected
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 text-green-700 px-6 py-4 flex items-center gap-3">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-semibold">{{ session('status') }}</span>
        </div>
    @endif

    <!-- Loans Grid -->
    @forelse ($loans as $loan)
        <div class="loan-card bg-white rounded-2xl border border-orange-100 p-6 shadow-lg mb-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-4 py-1.5 rounded-full text-xs font-bold
                            @if ($loan->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif ($loan->status === 'approved') bg-blue-100 text-blue-700
                            @elseif ($loan->status === 'issued') bg-green-100 text-green-700
                            @elseif ($loan->status === 'rejected') bg-red-100 text-red-700
                            @endif uppercase">
                            {{ $loan->status }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $loan->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Loan Amount</p>
                            <p class="text-xl font-bold text-orange-600">UGX {{ number_format($loan->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Repayment Schedule</p>
                            <p class="text-sm font-semibold text-gray-900">{{ str($loan->repayment_schedule ?? 'N/A')->replace('_', ' ')->title() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Purpose</p>
                            <p class="text-sm font-semibold text-gray-900">{{ Str::limit($loan->purpose, 40) }}</p>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('customer.loans.show', $loan->id) }}" class="ml-4 px-4 py-2 bg-orange-100 text-orange-700 font-semibold rounded-lg hover:bg-orange-200 transition flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Details
                </a>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl p-12 text-center shadow-lg border border-orange-100">
            <div class="h-20 w-20 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center mx-auto mb-4">
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Loan Applications Yet</h3>
            <p class="text-gray-600 mb-6">You haven't submitted any loan applications. Browse our loan products and apply today!</p>
            <a href="{{ route('customer.loan-products') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold rounded-xl hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Browse Loan Products
            </a>
        </div>
    @endforelse
@endsection

