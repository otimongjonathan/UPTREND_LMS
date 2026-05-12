@extends('customer.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🔔 Notifications</h1>
            <p class="text-gray-600 mt-1">Stay updated with your loan activities</p>
        </div>
        @if($notifications->where('read_at', null)->count() > 0)
            <form action="{{ route('customer.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition font-semibold text-sm">
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-primary-500' }}">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            @php
                                $iconBg = match($notification->type) {
                                    'App\\Notifications\\LoanApplicationApproved' => 'bg-green-100',
                                    'App\\Notifications\\LoanApplicationRejected' => 'bg-red-100',
                                    'App\\Notifications\\LoanDisbursed' => 'bg-blue-100',
                                    'App\\Notifications\\RepaymentDueReminder' => 'bg-yellow-100',
                                    'App\\Notifications\\RepaymentOverdue' => 'bg-red-100',
                                    'App\\Notifications\\RepaymentReceived' => 'bg-green-100',
                                    default => 'bg-gray-100',
                                };
                                $iconColor = match($notification->type) {
                                    'App\\Notifications\\LoanApplicationApproved' => 'text-green-600',
                                    'App\\Notifications\\LoanApplicationRejected' => 'text-red-600',
                                    'App\\Notifications\\LoanDisbursed' => 'text-blue-600',
                                    'App\\Notifications\\RepaymentDueReminder' => 'text-yellow-600',
                                    'App\\Notifications\\RepaymentOverdue' => 'text-red-600',
                                    'App\\Notifications\\RepaymentReceived' => 'text-green-600',
                                    default => 'text-gray-600',
                                };
                            @endphp
                            <div class="{{ $iconBg }} {{ $iconColor }} h-12 w-12 rounded-full flex items-center justify-center">
                                @if(str_contains($notification->type, 'Approved'))
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif(str_contains($notification->type, 'Rejected'))
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif(str_contains($notification->type, 'Disbursed'))
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif(str_contains($notification->type, 'Repayment'))
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                @else
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h3>
                                    <p class="text-gray-600 mt-1">
                                        {{ $notification->data['message'] ?? 'You have a new notification' }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if(!$notification->read_at)
                                    <form action="{{ route('customer.notifications.mark-read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-primary-600 hover:text-primary-700 font-semibold text-sm whitespace-nowrap">
                                            Mark as Read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Notifications</h3>
                <p class="text-gray-600">You're all caught up! Check back later for updates.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
