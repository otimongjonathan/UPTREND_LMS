<x-app-layout>
    <style>
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .content-animate {
            animation: slideUp 0.6s ease-out;
        }
        .table-row {
            transition: all 0.3s ease;
        }
        .table-row:hover {
            background-color: #f0f9ff;
            box-shadow: inset 0 0 10px rgba(37, 99, 235, 0.1);
        }
    </style>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                💰 {{ __('Loans') }}
            </h2>
            <a href="#" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-6 py-3 rounded-lg text-sm font-bold uppercase tracking-wide shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                ✨ Add New Loan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="content-animate bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-wide\">Loan Management</h3>
                        <div class=\"flex space-x-3 gap-2\">
                            <input type=\"text\" placeholder=\"🔍 Search loans...\" class=\"border-2 border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-200 rounded-lg px-4 py-2.5 text-sm font-medium focus:outline-none transition-all duration-300 bg-gray-50 focus:bg-white\">
                            <select class=\"border-2 border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-200 rounded-lg px-4 py-2.5 text-sm font-medium focus:outline-none transition-all duration-300 bg-gray-50 focus:bg-white\">
                                <option>All Status</option>
                                <option>✅ Active</option>
                                <option>📋 Completed</option>
                                <option>⚠️ Overdue</option>
                            </select>
                        </div>
                    </div>

                    <div class=\"overflow-x-auto\">
                        <table class=\"min-w-full divide-y divide-gray-200\">
                            <thead class=\"bg-gradient-to-r from-primary-50 to-primary-100 border-b-2 border-primary-300\">
                                <tr>
                                    <th class=\"px-6 py-4 text-left text-xs font-bold text-primary-800 uppercase tracking-wider\">Loan ID</th>
                                    <th class=\"px-6 py-4 text-left text-xs font-bold text-primary-800 uppercase tracking-wider\">Borrower</th>
                                    <th class=\"px-6 py-4 text-left text-xs font-bold text-primary-800 uppercase tracking-wider\">Amount</th>
                                    <th class=\"px-6 py-4 text-left text-xs font-bold text-primary-800 uppercase tracking-wider\">Status</th>
                                    <th class=\"px-6 py-4 text-left text-xs font-bold text-primary-800 uppercase tracking-wider\">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No loans found. <a href="#" class="text-primary-600 hover:text-primary-800">Create your first loan</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>