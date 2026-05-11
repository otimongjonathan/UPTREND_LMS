<x-app-layout>
    <x-slot name="header">
        <div class=\"flex justify-between items-center\">
            <h2 class=\"font-semibold text-xl text-gray-800 leading-tight\">
                📊 {{ $borrower->name }}'s Credit Profile
            </h2>
            <a href=\"{{ route('credit-scores.index') }}\" class=\"text-blue-600 hover:text-blue-800 font-semibold\">
                ← Back to Scores
            </a>
        </div>
    </x-slot>

    <div class=\"py-12\">
        <div class=\"max-w-7xl mx-auto sm:px-6 lg:px-8\">
            <!-- Borrower Info -->
            <div class=\"bg-white rounded-lg shadow-md p-6 mb-6\">
                <div class=\"flex justify-between items-start\">
                    <div>
                        <h3 class=\"text-3xl font-bold text-gray-900 mb-2\">{{ $borrower->name }}</h3>
                        <p class=\"text-gray-600 text-lg\">{{ $borrower->email }}</p>
                    </div>
                    <div class=\"text-right\">
                        <p class=\"text-sm text-gray-600 mb-1\">Member Since</p>
                        <p class=\"text-lg font-semibold text-gray-900\">{{ $borrower->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Score & Risk Cards -->
            <div class=\"grid grid-cols-1 md:grid-cols-3 gap-6 mb-6\">
                <!-- Credit Score -->
                <div class=\"bg-gradient-to-br from-purple-500 to-purple-600 text-white p-8 rounded-lg shadow-lg\">
                    <p class=\"text-purple-100 text-sm font-semibold mb-2\">CREDIT SCORE</p>
                    <div class=\"text-5xl font-bold mb-2\">{{ $creditScore->score }}</div>
                    <div class=\"flex items-center gap-2\">
                        <div class=\"w-3 h-3 rounded-full
                            @if($creditScore->score >= 750) bg-green-400
                            @elseif($creditScore->score >= 600) bg-yellow-400
                            @else bg-red-400
                            @endif
                        \"></div>
                        <span class=\"text-sm\">Out of 1000 points</span>
                    </div>
                </div>

                <!-- Risk Level -->
                <div class=\"bg-gradient-to-br 
                    @if($creditScore->risk_level === 'low') from-green-500 to-green-600
                    @elseif($creditScore->risk_level === 'medium') from-yellow-500 to-yellow-600
                    @elseif($creditScore->risk_level === 'high') from-orange-500 to-orange-600
                    @else from-red-500 to-red-600
                    @endif
                    text-white p-8 rounded-lg shadow-lg\">
                    <p class=\"opacity-90 text-sm font-semibold mb-2\">RISK ASSESSMENT</p>
                    <div class=\"text-4xl font-bold mb-2\">{{ ucfirst($creditScore->risk_level) }}</div>
                    <p class=\"text-sm opacity-90\">Based on payment history</p>
                </div>

                <!-- Loan History -->
                <div class=\"bg-gradient-to-br from-blue-500 to-blue-600 text-white p-8 rounded-lg shadow-lg\">
                    <p class=\"text-blue-100 text-sm font-semibold mb-2\">CREDIT HISTORY</p>
                    <div class=\"text-4xl font-bold mb-2\">{{ $creditScore->credit_history_months }}</div>
                    <p class=\"text-sm\">Months of history</p>
                </div>
            </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg">
                            <div class="text-sm opacity-80">Credit Score</div>
                            <div class="text-4xl font-bold">{{ $creditScore->score }}</div>
                            <div class="text-sm mt-2">out of 1000</div>
                        </div>
                        
                        <div class="bg-gradient-to-br 
                            @if($creditScore->risk_level === 'low') from-green-500 to-green-600
                            @elseif($creditScore->risk_level === 'medium') from-yellow-500 to-yellow-600
                            @elseif($creditScore->risk_level === 'high') from-orange-500 to-orange-600
                            @else from-red-500 to-red-600
                            @endif
                            text-white p-6 rounded-lg">
                            <div class="text-sm opacity-80">Risk Level</div>
                            <div class="text-3xl font-bold">{{ ucfirst($creditScore->risk_level) }}</div>
                            <div class="text-sm mt-2">Risk Assessment</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="text-gray-600 text-sm">Completed Loans</div>
                            <div class="text-2xl font-bold">{{ $creditScore->completed_loans }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="text-gray-600 text-sm">Defaulted Loans</div>
                            <div class="text-2xl font-bold text-red-600">{{ $creditScore->defaulted_loans }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="text-gray-600 text-sm">Default Rate</div>
                            <div class="text-2xl font-bold">{{ number_format($creditScore->default_rate, 2) }}%</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="text-gray-600 text-sm">On-Time Payment Rate</div>
                            <div class="text-2xl font-bold text-green-600">{{ number_format($creditScore->on_time_payment_rate, 2) }}%</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="text-gray-600 text-sm">Average Loan Amount</div>
                            <div class="text-2xl font-bold">{{ number_format($creditScore->average_loan_amount, 0) }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="text-gray-600 text-sm">Credit History (Months)</div>
                            <div class="text-2xl font-bold">{{ $creditScore->credit_history_months }}</div>
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <h4 class="font-bold mb-4">Loan History</h4>
                        @if($loans->count())
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100 border-b">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Amount</th>
                                            <th class="px-4 py-2 text-left">Status</th>
                                            <th class="px-4 py-2 text-left">Date Applied</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($loans as $loan)
                                            <tr class="border-b">
                                                <td class="px-4 py-2">{{ number_format($loan->amount, 2) }}</td>
                                                <td class="px-4 py-2">
                                                    <span class="px-2 py-1 rounded text-xs font-bold text-white
                                                        @if($loan->status === 'approved') bg-green-500
                                                        @elseif($loan->status === 'issued') bg-blue-500
                                                        @elseif($loan->status === 'rejected') bg-red-500
                                                        @else bg-yellow-500
                                                        @endif
                                                    ">{{ ucfirst($loan->status) }}</span>
                                                </td>
                                                <td class="px-4 py-2">{{ $loan->application_date->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No loan history</p>
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('credit-scores.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Back to Scores
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
