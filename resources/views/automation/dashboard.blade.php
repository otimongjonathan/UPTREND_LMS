<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                🤖 Automated Loan Management
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('loans.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    View All Loans
                </a>
                <a href="{{ route('repayments.index') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    View Repayments
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Automation Status -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg shadow p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">✅ Automated Processes Active</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-green-600">🔄 Auto Schedule Generation</h4>
                        <p class="text-sm text-gray-600 mt-1">Complete repayment schedules created automatically when loans are issued</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-blue-600">📊 Smart Calculations</h4>
                        <p class="text-sm text-gray-600 mt-1">Both simple and compound interest methods supported with proper amortization</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-orange-600">⚡ Instant Processing</h4>
                        <p class="text-sm text-gray-600 mt-1">No manual installment creation needed - everything happens automatically</p>
                    </div>
                </div>
            </div>

            <!-- Process Flow -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">🔄 Automated Process Flow</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="text-blue-600 font-bold">1</span>
                            </div>
                            <p class="text-sm font-semibold">Loan Approved</p>
                            <p class="text-xs text-gray-500">Staff approves application</p>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="text-green-600 font-bold">2</span>
                            </div>
                            <p class="text-sm font-semibold">Auto Issue</p>
                            <p class="text-xs text-gray-500">System calculates terms</p>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="text-orange-600 font-bold">3</span>
                            </div>
                            <p class="text-sm font-semibold">Generate Schedule</p>
                            <p class="text-xs text-gray-500">All installments created</p>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <span class="text-purple-600 font-bold">4</span>
                            </div>
                            <p class="text-sm font-semibold">Customer Notified</p>
                            <p class="text-xs text-gray-500">Auto reminders sent</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Testing -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">📱 Notification Testing</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <form action="{{ route('notifications.send-reminders') }}" method="POST">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Send All Reminders</label>
                                <p class="text-xs text-gray-500 mb-2">Send due today, tomorrow, 3-day and overdue notifications</p>
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    📤 Send Reminder Notifications
                                </button>
                            </div>
                        </form>
                        
                        <form action="{{ route('notifications.test') }}" method="POST">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Test Loan Notification</label>
                                <p class="text-xs text-gray-500 mb-2">Test loan issued notification for specific loan</p>
                                <div class="flex gap-2">
                                    <input type="number" name="loan_id" placeholder="Loan ID" required 
                                           class="flex-1 px-3 py-2 border rounded focus:border-blue-500">
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        🧪 Test
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">📊 Automation Stats</h3>
                    </div>
                    <div class="p-6">
                        @php
                            $stats = [
                                'total_loans' => \App\Models\LoanApplication::where('status', 'issued')->count(),
                                'total_installments' => \App\Models\Repayment::count(),
                                'pending_payments' => \App\Models\Repayment::where('status', 'pending')->count(),
                                'overdue_payments' => \App\Models\Repayment::where('status', '!=', 'completed')
                                    ->where('due_date', '<', now())->count(),
                            ];
                        @endphp
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Active Loans:</span>
                                <span class="font-semibold">{{ $stats['total_loans'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Auto-Generated Installments:</span>
                                <span class="font-semibold">{{ $stats['total_installments'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pending Payments:</span>
                                <span class="font-semibold text-yellow-600">{{ $stats['pending_payments'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Need Reminders:</span>
                                <span class="font-semibold text-red-600">{{ $stats['overdue_payments'] }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t text-center">
                            <p class="text-sm text-gray-600">Automation Rate</p>
                            <p class="text-2xl font-bold text-green-600">100%</p>
                            <p class="text-xs text-gray-500">Fully automated system</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>