<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                Reports
            </h2>
            <p class="text-sm text-gray-600">Track performance summaries and export-ready insights.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
                <h3 class="text-xl font-bold text-gray-900">Reports Dashboard</h3>
                <p class="mt-2 text-gray-600">
                    This page is ready for your reporting modules. You can add loan trends, repayment summaries,
                    borrower analytics, and export options here.
                </p>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-xl border border-blue-100 bg-blue-50/60 p-4">
                        <p class="text-sm font-semibold text-blue-700">Loan Summary</p>
                        <p class="text-sm text-blue-600 mt-1">Monthly totals and disbursement trends.</p>
                    </div>
                    <div class="rounded-xl border border-green-100 bg-green-50/60 p-4">
                        <p class="text-sm font-semibold text-green-700">Repayment Health</p>
                        <p class="text-sm text-green-600 mt-1">On-time rates, arrears, and aging overview.</p>
                    </div>
                    <div class="rounded-xl border border-purple-100 bg-purple-50/60 p-4">
                        <p class="text-sm font-semibold text-purple-700">Borrower Insights</p>
                        <p class="text-sm text-purple-600 mt-1">Profiles, growth, and account activity snapshots.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
