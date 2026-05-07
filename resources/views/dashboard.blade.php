<x-app-layout>
    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .widget-card {
            animation: fadeUp 0.5s ease-out both;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .widget-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px rgba(15, 23, 42, 0.08);
        }

        .widget-card:nth-child(1) { animation-delay: 0.05s; }
        .widget-card:nth-child(2) { animation-delay: 0.1s; }
        .widget-card:nth-child(3) { animation-delay: 0.15s; }
        .widget-card:nth-child(4) { animation-delay: 0.2s; }
    </style>

    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                Welcome back, {{ Auth::user()->name }}
            </h2>
            <p class="text-sm text-gray-600">Here is an overview of your loan management system.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-7">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <a href="{{ route('loans.index') }}" class="widget-card block bg-white rounded-2xl border border-blue-100 shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Loans</p>
                            <p class="mt-3 text-3xl font-bold text-gray-900">0</p>
                            <p class="mt-2 text-xs font-medium text-green-600">+12% from last month</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('applications.index') }}" class="widget-card block bg-white rounded-2xl border border-orange-100 shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Applications</p>
                            <p class="mt-3 text-3xl font-bold text-gray-900">0</p>
                            <p class="mt-2 text-xs font-medium text-orange-600">0 pending review</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('repayments.index') }}" class="widget-card block bg-white rounded-2xl border border-green-100 shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Repayments</p>
                            <p class="mt-3 text-3xl font-bold text-gray-900">$0</p>
                            <p class="mt-2 text-xs font-medium text-green-600">All repayments current</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('borrowers.index') }}" class="widget-card block bg-white rounded-2xl border border-purple-100 shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Borrowers</p>
                            <p class="mt-3 text-3xl font-bold text-gray-900">0</p>
                            <p class="mt-2 text-xs font-medium text-purple-600">Active customer accounts</p>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 widget-card rounded-2xl overflow-hidden shadow-sm bg-gradient-to-br from-primary-700 to-primary-900 text-white p-8">
                    <h3 class="text-2xl font-bold">UPTREND LMS Workspace</h3>
                    <p class="mt-2 text-blue-100 max-w-2xl">
                        Start your daily workflow quickly with shortcuts to core modules and monitor your institution performance in one place.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('loans.index') }}" class="inline-flex items-center rounded-lg bg-white/20 hover:bg-white/30 px-4 py-2 text-sm font-semibold transition">
                            View Loans
                        </a>
                        <a href="{{ route('applications.index') }}" class="inline-flex items-center rounded-lg bg-white/20 hover:bg-white/30 px-4 py-2 text-sm font-semibold transition">
                            Manage Applications
                        </a>
                        <a href="{{ route('repayments.index') }}" class="inline-flex items-center rounded-lg bg-white/20 hover:bg-white/30 px-4 py-2 text-sm font-semibold transition">
                            Track Repayments
                        </a>
                    </div>
                </div>

                <div class="widget-card bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900">Performance Snapshot</h3>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-600">Success Rate</span>
                            <span class="font-semibold text-primary-700">98.5%</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-600">Avg. Processing Time</span>
                            <span class="font-semibold text-primary-700">2.3 days</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Client Satisfaction</span>
                            <span class="font-semibold text-primary-700">4.8/5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
