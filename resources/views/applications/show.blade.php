<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                📋 Application Details
            </h2>
            <a href="{{ route('applications.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                ← Back to Applications
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with Status -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 overflow-hidden shadow-2xl sm:rounded-2xl mb-8">
                <div class="px-8 py-8 text-white">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="font-bold text-4xl mb-2">
                                Application #{{ $application->id }}
                            </h2>
                            <p class="text-blue-100 text-lg">{{ $application->applicant_full_name }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-6 py-3 rounded-full text-lg font-bold shadow-lg
                                @if ($application->status === 'pending') bg-yellow-400 text-yellow-900
                                @elseif ($application->status === 'approved') bg-blue-400 text-blue-900
                                @elseif ($application->status === 'issued') bg-green-400 text-green-900
                                @elseif ($application->status === 'rejected') bg-red-400 text-red-900
                                @endif">
                                {{ str($application->status)->title() }}
                            </span>
                            <p class="text-blue-100 mt-2">{{ $application->application_date?->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                        <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                            <div class="text-blue-100 text-sm font-medium">Loan Amount</div>
                            <div class="text-2xl font-bold">UGX {{ number_format($application->amount, 0) }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                            <div class="text-blue-100 text-sm font-medium">Term</div>
                            <div class="text-2xl font-bold">{{ $application->confirmed_term_months ?? $application->term_months ?? 'N/A' }} months</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                            <div class="text-blue-100 text-sm font-medium">Frequency</div>
                            <div class="text-2xl font-bold">{{ str($application->confirmed_payment_frequency ?? $application->repayment_schedule)->title()->replace('_', ' ') }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                            <div class="text-blue-100 text-sm font-medium">Monthly Income</div>
                            <div class="text-2xl font-bold">UGX {{ number_format($application->monthly_income, 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Loan Product Assignment -->
                    @if($application->status !== 'rejected')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="bg-gradient-to-r from-cyan-500 to-blue-500 px-8 py-6">
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                Loan Product Assignment
                            </h3>
                        </div>
                        <div class="px-8 py-8">
                            @if($application->loan_product_id)
                                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
                                    <p class="text-sm text-green-600 font-semibold mb-2">✓ Loan Product Assigned</p>
                                    <p class="text-lg font-bold text-green-900">{{ $application->product?->name ?? 'Unknown Product' }}</p>
                                    <p class="text-sm text-green-700 mt-2">Interest Rate: {{ $application->product?->interest_rate ?? 'N/A' }}%</p>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
                                    <p class="text-sm text-yellow-600 font-semibold mb-2">⚠ No Loan Product Assigned</p>
                                    <p class="text-sm text-yellow-700">A loan product must be assigned before this application can be issued.</p>
                                </div>
                            @endif
                            
                            @if($application->status === 'pending' || $application->status === 'approved')
                            <form action="{{ route('applications.assign-product', $application->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700">Select Loan Product</label>
                                    <select name="loan_product_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all duration-200" required>
                                        <option value="">-- Choose a Loan Product --</option>
                                        @forelse($loanProducts ?? [] as $product)
                                            <option value="{{ $product->id }}" {{ $application->loan_product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} ({{ $product->interest_rate }}% - UGX {{ number_format($product->min_amount, 0) }} to {{ number_format($product->max_amount, 0) }})
                                            </option>
                                        @empty
                                            <option value="" disabled>No loan products available</option>
                                        @endforelse
                                    </select>
                                </div>
                                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Assign Product
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Loan Terms Management -->
                    @if($application->status !== 'rejected')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="bg-gradient-to-r from-orange-500 to-red-500 px-8 py-6">
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Confirmed Loan Terms
                            </h3>
                            @if($application->hasConfirmedTerms())
                                <p class="text-orange-100 mt-2">
                                    Last modified: {{ $application->terms_last_modified?->format('M d, Y \a\t h:i A') }}
                                </p>
                            @endif
                        </div>
                        <div class="px-8 py-8">
                            @if($application->hasConfirmedTerms())
                                @php $terms = $application->getEffectiveTerms(); @endphp
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-blue-600 font-semibold">Payment Frequency</p>
                                                <p class="text-xl font-bold text-blue-900">{{ str($terms['payment_frequency'])->title()->replace('_', ' ') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-green-600 font-semibold">Term Duration</p>
                                                <p class="text-xl font-bold text-green-900">{{ $terms['term_months'] }} Months</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 110 2h-1v10a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM9 6v8a1 1 0 102 0V6a1 1 0 10-2 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-purple-600 font-semibold">Total Installments</p>
                                                <p class="text-xl font-bold text-purple-900">{{ $terms['installment_count'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl border border-orange-200">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-orange-600 font-semibold">Installment Amount</p>
                                                <p class="text-xl font-bold text-orange-900">UGX {{ number_format($terms['installment_amount'], 0) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl border border-indigo-200">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-indigo-600 font-semibold">First Due Date</p>
                                                <p class="text-xl font-bold text-indigo-900">{{ $terms['first_due_date']?->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl border border-red-200">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-red-600 font-semibold">Final Due Date</p>
                                                <p class="text-xl font-bold text-red-900">{{ $terms['final_due_date']?->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($application->status === 'pending' || $application->status === 'approved')
                            <div class="border-t border-gray-200 pt-8 mt-8">
                                <h4 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                    {{ $application->hasConfirmedTerms() ? 'Modify Terms' : 'Set Confirmed Terms' }}
                                </h4>
                                <form action="{{ route('applications.update-terms', $application->id) }}" method="POST" class="space-y-6">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-bold text-gray-700">Payment Frequency</label>
                                            <select name="payment_frequency" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-200" required>
                                                <option value="weekly" {{ ($application->confirmed_payment_frequency ?? $application->repayment_schedule) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                                <option value="bi_weekly" {{ ($application->confirmed_payment_frequency ?? $application->repayment_schedule) === 'bi_weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                                <option value="monthly" {{ ($application->confirmed_payment_frequency ?? $application->repayment_schedule) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                <option value="quarterly" {{ ($application->confirmed_payment_frequency ?? $application->repayment_schedule) === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-bold text-gray-700">First Due Date</label>
                                            <input type="date" name="first_due_date" 
                                                   value="{{ $application->confirmed_first_due_date?->format('Y-m-d') ?? now()->addMonth()->format('Y-m-d') }}"
                                                   min="{{ now()->addMonth()->format('Y-m-d') }}"
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-200" required>
                                            <p class="text-xs text-gray-500 mt-1">Must be at least one month from today</p>
                                        </div>
                                    </div>
                                    
                                    @error('terms')
                                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    
                                    <div class="flex gap-4">
                                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                            {{ $application->hasConfirmedTerms() ? 'Update Terms' : 'Confirm Terms' }}
                                        </button>
                                        <button type="button" onclick="document.getElementById('preview-terms').classList.toggle('hidden')" 
                                                class="px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-bold transition-all duration-200">
                                            Preview Changes
                                        </button>
                                    </div>
                                </form>
                                
                                <!-- Preview Section -->
                                <div id="preview-terms" class="hidden mt-6 p-6 bg-yellow-50 border-2 border-yellow-200 rounded-xl">
                                    <h5 class="font-bold text-yellow-800 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Preview: Changes will be calculated automatically
                                    </h5>
                                    <p class="text-sm text-yellow-700 leading-relaxed">
                                        When you change the payment frequency or due date, the system will automatically:
                                        <br>• Recalculate the loan term in months
                                        <br>• Determine the number of installments
                                        <br>• Update installment amounts
                                        <br>• Regenerate the repayment schedule (if loan is approved)
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Fee & Tax Calculation -->
                    @if($application->status !== 'rejected')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="bg-gradient-to-r from-green-500 to-teal-500 px-8 py-6">
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                Fees & Tax Calculation
                            </h3>
                            @if($application->hasCalculatedFees())
                                <p class="text-green-100 mt-2">
                                    Calculated: {{ $application->fees_calculated_at?->format('M d, Y \a\t h:i A') }}
                                </p>
                            @endif
                        </div>
                        <div class="px-8 py-8" data-loan-amount="{{ $application->amount }}">
                            @if($application->hasCalculatedFees())
                                @php $feeBreakdown = $application->getFeeBreakdown(); @endphp
                                
                                <!-- Fee Summary Cards -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                                </svg>
                                            </div>
                                            <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded-full font-semibold">{{ $feeBreakdown['fees']['processing']['percent'] }}%</span>
                                        </div>
                                        <p class="text-sm text-blue-600 font-semibold mb-1">Processing Fee</p>
                                        <p class="text-2xl font-bold text-blue-900">UGX {{ number_format($feeBreakdown['fees']['processing']['amount'], 0) }}</p>
                                    </div>
                                    
                                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 12a1 1 0 102 0V8a1 1 0 10-2 0v4zm0-6a1 1 0 112 0 1 1 0 01-2 0z"/>
                                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                                                </svg>
                                            </div>
                                            <span class="text-xs bg-purple-200 text-purple-800 px-2 py-1 rounded-full font-semibold">{{ $feeBreakdown['fees']['insurance']['percent'] }}%</span>
                                        </div>
                                        <p class="text-sm text-purple-600 font-semibold mb-1">Insurance Fee</p>
                                        <p class="text-2xl font-bold text-purple-900">UGX {{ number_format($feeBreakdown['fees']['insurance']['amount'], 0) }}</p>
                                    </div>
                                    
                                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl border border-orange-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                                                </svg>
                                            </div>
                                            <span class="text-xs bg-orange-200 text-orange-800 px-2 py-1 rounded-full font-semibold">{{ $feeBreakdown['taxes']['vat']['percent'] }}%</span>
                                        </div>
                                        <p class="text-sm text-orange-600 font-semibold mb-1">VAT</p>
                                        <p class="text-2xl font-bold text-orange-900">UGX {{ number_format($feeBreakdown['taxes']['vat']['amount'], 0) }}</p>
                                    </div>
                                    
                                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl border border-red-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                                </svg>
                                            </div>
                                            <span class="text-xs bg-red-200 text-red-800 px-2 py-1 rounded-full font-semibold">{{ $feeBreakdown['taxes']['withholding']['percent'] }}%</span>
                                        </div>
                                        <p class="text-sm text-red-600 font-semibold mb-1">Withholding Tax</p>
                                        <p class="text-2xl font-bold text-red-900">UGX {{ number_format($feeBreakdown['taxes']['withholding']['amount'], 0) }}</p>
                                    </div>
                                </div>
                                
                                <!-- Summary Section -->
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-8 rounded-2xl border-2 border-gray-200">
                                    <h4 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                        <svg class="w-6 h-6 mr-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Financial Summary
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600 font-semibold mb-2">Gross Loan Amount</p>
                                            <p class="text-3xl font-bold text-gray-900">UGX {{ number_format($feeBreakdown['summary']['gross_loan_amount'], 0) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-red-600 font-semibold mb-2">Total Deductions</p>
                                            <p class="text-3xl font-bold text-red-700">-UGX {{ number_format($feeBreakdown['summary']['total_deductions'], 0) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-green-600 font-semibold mb-2">Net Disbursement</p>
                                            <p class="text-3xl font-bold text-green-700">UGX {{ number_format($feeBreakdown['summary']['net_disbursement'], 0) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm text-blue-600 font-semibold mb-2">Total Repayment</p>
                                            <p class="text-3xl font-bold text-blue-700">UGX {{ number_format($feeBreakdown['summary']['total_repayment'], 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($application->status === 'pending' || $application->status === 'approved')
                            <div class="{{ $application->hasCalculatedFees() ? 'border-t border-gray-200 pt-8 mt-8' : '' }}">
                                <h4 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $application->hasCalculatedFees() ? 'Recalculate Fees' : 'Calculate Fees & Taxes' }}
                                </h4>
                                <form action="{{ route('applications.calculate-fees', $application->id) }}" method="POST" class="space-y-6">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-bold text-gray-700">Processing Fee (%)</label>
                                            <input type="number" name="processing_fee_percent" step="0.01" min="0" max="10"
                                                   value="{{ $application->processing_fee_percent ?? ($application->product?->processing_fee_percent ?? 2.5) }}"
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200" required>
                                            <p class="text-xs text-gray-500 mt-1">From loan product: {{ $application->product?->processing_fee_percent ?? 'N/A' }}%</p>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-bold text-gray-700">Insurance Fee (%)</label>
                                            <input type="number" name="insurance_fee_percent" step="0.01" min="0" max="5"
                                                   value="{{ $application->insurance_fee_percent ?? ($application->product?->insurance_premium_percent ?? 1.0) }}"
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200" required>
                                            <p class="text-xs text-gray-500 mt-1">From loan product: {{ $application->product?->insurance_premium_percent ?? 'N/A' }}%</p>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-bold text-gray-700">VAT (%)</label>
                                            <input type="number" name="vat_percent" step="0.01" min="0" max="25"
                                                   value="{{ $application->vat_percent ?? 18.0 }}"
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200" required>
                                            <p class="text-xs text-gray-500 mt-1">Standard VAT rate</p>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-bold text-gray-700">Withholding Tax (%)</label>
                                            <input type="number" name="withholding_tax_percent" step="0.01" min="0" max="10"
                                                   value="{{ $application->withholding_tax_percent ?? 0.0 }}"
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200" required>
                                            <p class="text-xs text-gray-500 mt-1">Optional withholding tax</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                            {{ $application->hasCalculatedFees() ? 'Recalculate Fees' : 'Calculate Fees' }}
                                        </button>
                                        <button type="button" onclick="document.getElementById('fee-preview').classList.toggle('hidden')" 
                                                class="px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-bold transition-all duration-200">
                                            Preview Calculation
                                        </button>
                                    </div>
                                </form>
                                
                                <!-- Fee Preview Section -->
                                <div id="fee-preview" class="hidden mt-6 p-6 bg-blue-50 border-2 border-blue-200 rounded-xl">
                                    <h5 class="font-bold text-blue-800 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Fee Calculation Preview
                                    </h5>
                                    <div id="fee-calculation-preview" class="text-sm text-blue-700">
                                        <!-- Dynamic content will be inserted here by JavaScript -->
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Right Column - Sidebar -->
                <div class="space-y-8">
                    <!-- Personal Information -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 px-6 py-4">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                                Personal Information
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Full Name</p>
                                    <p class="text-gray-900 font-medium">{{ $application->applicant_full_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Date of Birth</p>
                                    <p class="text-gray-900 font-medium">{{ $application->dob?->format('M d, Y') }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Gender</p>
                                        <p class="text-gray-900 font-medium">{{ str($application->gender)->title() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Marital Status</p>
                                        <p class="text-gray-900 font-medium">{{ str($application->marital_status)->title() }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Employment</p>
                                    <p class="text-gray-900 font-medium">{{ str($application->employment_status)->title()->replace('_', ' ') }}</p>
                                    <p class="text-sm text-gray-600">{{ $application->occupation }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-6 py-4">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                Address
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">District/City</p>
                                    <p class="text-gray-900 font-medium">{{ $application->district_city }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">County</p>
                                        <p class="text-gray-900 font-medium">{{ $application->county }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Sub-County</p>
                                        <p class="text-gray-900 font-medium">{{ $application->sub_county }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Parish</p>
                                        <p class="text-gray-900 font-medium">{{ $application->parish }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Village</p>
                                        <p class="text-gray-900 font-medium">{{ $application->village }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Residence Status</p>
                                    <p class="text-gray-900 font-medium">{{ str($application->residence_status)->title() }}</p>
                                </div>
                                @if($application->po_box)
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">P.O. Box</p>
                                    <p class="text-gray-900 font-medium">{{ $application->po_box }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Loan Details -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                Loan Details
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Loan Type</p>
                                    <p class="text-gray-900 font-medium">{{ str($application->loan_type)->title()->replace('_', ' ') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Purpose</p>
                                    <p class="text-gray-900 font-medium">{{ $application->purpose }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Original Schedule</p>
                                    <p class="text-gray-900 font-medium">{{ str($application->repayment_schedule)->title()->replace('_', ' ') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Original Term</p>
                                    <p class="text-gray-900 font-medium">{{ $application->term_months ?? 'N/A' }} months</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="bg-gradient-to-r from-rose-500 to-pink-500 px-6 py-4">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                    <path d="M3 8a2 2 0 012-2v9a2 2 0 01-2-2V8zM17 6a2 2 0 012 2v7a2 2 0 01-2 2V6z"/>
                                </svg>
                                Documents
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <div class="space-y-3">
                                @php
                                    $documents = [
                                        'police_letter_path' => 'Police Letter',
                                        'financial_statement_path' => 'Financial Statement',
                                        'national_id_path' => 'National ID',
                                        'loan_guarantee_one_path' => 'Loan Guarantee One',
                                        'loan_guarantee_two_path' => 'Loan Guarantee Two',
                                        'proof_of_residence_path' => 'Proof of Residence',
                                    ];
                                @endphp

                                @foreach ($documents as $field => $label)
                                    @if ($application->$field)
                                        <a href="{{ route('loans.download', [$application->id, $field]) }}" class="flex items-center px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition group">
                                            <svg class="w-5 h-5 text-green-600 mr-3 group-hover:text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                                <path d="M3 8a2 2 0 012-2v9a2 2 0 01-2-2V8zM17 6a2 2 0 012 2v7a2 2 0 01-2 2V6z"/>
                                            </svg>
                                            <span class="text-green-900 font-medium text-sm">{{ $label }}</span>
                                            <svg class="w-4 h-4 text-green-600 ml-auto group-hover:text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                                            </svg>
                                        </a>
                                    @else
                                        <div class="flex items-center px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 opacity-60">
                                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                                <path d="M3 8a2 2 0 012-2v9a2 2 0 01-2-2V8zM17 6a2 2 0 012 2v7a2 2 0 01-2 2V6z"/>
                                            </svg>
                                            <span class="text-gray-500 font-medium text-sm">{{ $label }}</span>
                                            <span class="text-xs text-gray-400 ml-auto">Not provided</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if ($application->status === 'pending')
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Application Review Actions
                    </h3>
                </div>
                <div class="px-8 py-8 bg-gray-50">
                    <p class="text-gray-700 mb-6 text-lg">Choose an action to process this application:</p>
                    <div class="flex gap-4">
                        <form action="{{ route('applications.approve', $application->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-xl font-bold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                APPROVE APPLICATION
                            </button>
                        </form>
                        <form action="{{ route('applications.reject', $application->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white rounded-xl font-bold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                </svg>
                                REJECT APPLICATION
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @elseif ($application->status === 'approved')
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100 mt-8">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        Issue Loan
                    </h3>
                </div>
                <div class="px-8 py-8 bg-gray-50">
                    @if(!$application->loan_product_id)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Loan product must be assigned first.</strong> Please scroll up to the "Loan Product Assignment" section and assign a product before issuing the loan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-700 mb-6 text-lg">Ready to issue this approved loan. Click the button below to proceed:</p>
                        <form action="{{ route('loans.issue', $application->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white rounded-xl font-bold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                                ISSUE LOAN
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 mt-8">
                <div class="px-8 py-6 bg-gray-50">
                    <p class="text-gray-700 text-lg flex items-center">
                        <span class="font-semibold">Application Status:</span>
                        <span class="px-4 py-2 rounded-full text-lg font-bold ml-4
                            @if ($application->status === 'issued') bg-green-100 text-green-700
                            @elseif ($application->status === 'rejected') bg-red-100 text-red-700
                            @endif">
                            {{ str($application->status)->title() }}
                        </span>
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

<script src="{{ asset('js/enhanced-fee-calculator.js') }}"></script>
