@extends('customer.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Help & Settings</h1>
        <p class="text-gray-600 mt-2">Manage your preferences and get help</p>
    </div>

    <!-- Theme Settings -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6 scroll-animate">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Theme Settings</h2>
                <p class="text-sm text-gray-600">Customize your viewing experience</p>
            </div>
        </div>

        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
            <div>
                <h3 class="font-semibold text-gray-900">Dark Mode</h3>
                <p class="text-sm text-gray-600">Switch to dark theme for better viewing at night</p>
            </div>
            <button id="theme-toggle" class="relative inline-flex h-12 w-24 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 bg-gray-300">
                <span id="theme-toggle-dot" class="inline-block h-10 w-10 transform rounded-full bg-white shadow-lg transition-transform translate-x-1"></span>
            </button>
        </div>

        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>Note:</strong> Your theme preference will be saved and applied across all pages.
            </p>
        </div>
    </div>

    <!-- Help Resources -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6 scroll-animate">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Help Resources</h2>
                <p class="text-sm text-gray-600">Find answers and get support</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="#" class="p-4 border border-gray-200 rounded-xl hover:border-primary-300 hover:bg-primary-50 transition-all group">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">User Guide</h3>
                        <p class="text-sm text-gray-600 mt-1">Learn how to use the portal</p>
                    </div>
                </div>
            </a>

            <a href="#" class="p-4 border border-gray-200 rounded-xl hover:border-primary-300 hover:bg-primary-50 transition-all group">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">FAQs</h3>
                        <p class="text-sm text-gray-600 mt-1">Frequently asked questions</p>
                    </div>
                </div>
            </a>

            <a href="#" class="p-4 border border-gray-200 rounded-xl hover:border-primary-300 hover:bg-primary-50 transition-all group">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">Contact Support</h3>
                        <p class="text-sm text-gray-600 mt-1">Get help from our team</p>
                    </div>
                </div>
            </a>

            <a href="#" class="p-4 border border-gray-200 rounded-xl hover:border-primary-300 hover:bg-primary-50 transition-all group">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">Report Issue</h3>
                        <p class="text-sm text-gray-600 mt-1">Report a problem or bug</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Account Settings -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 scroll-animate">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Account Settings</h2>
                <p class="text-sm text-gray-600">Manage your account preferences</p>
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('customer.profile') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="font-semibold text-gray-900">Edit Profile</span>
                </div>
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>

            <a href="#" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <span class="font-semibold text-gray-900">Change Password</span>
                </div>
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>

            <a href="{{ route('customer.notifications') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="font-semibold text-gray-900">Notification Settings</span>
                </div>
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const themeToggleDot = document.getElementById('theme-toggle-dot');
    const body = document.body;
    
    // Check for saved theme preference
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Apply saved theme
    if (currentTheme === 'dark') {
        body.classList.add('dark-theme');
        themeToggle.classList.remove('bg-gray-300');
        themeToggle.classList.add('bg-primary-600');
        themeToggleDot.classList.remove('translate-x-1');
        themeToggleDot.classList.add('translate-x-12');
    }
    
    // Toggle theme
    themeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-theme');
        
        if (body.classList.contains('dark-theme')) {
            localStorage.setItem('theme', 'dark');
            themeToggle.classList.remove('bg-gray-300');
            themeToggle.classList.add('bg-primary-600');
            themeToggleDot.classList.remove('translate-x-1');
            themeToggleDot.classList.add('translate-x-12');
        } else {
            localStorage.setItem('theme', 'light');
            themeToggle.classList.add('bg-gray-300');
            themeToggle.classList.remove('bg-primary-600');
            themeToggleDot.classList.add('translate-x-1');
            themeToggleDot.classList.remove('translate-x-12');
        }
    });
});
</script>

<style>
/* Dark Theme Styles for Settings Page */
.dark-theme .bg-white {
    background-color: #2d2d2d !important;
    border-color: #404040 !important;
}

.dark-theme .text-gray-900 {
    color: #f3f4f6 !important;
}

.dark-theme .text-gray-600 {
    color: #9ca3af !important;
}

.dark-theme .bg-gray-50 {
    background-color: #1f1f1f !important;
}

.dark-theme .border-gray-200 {
    border-color: #404040 !important;
}

.dark-theme .bg-blue-50 {
    background-color: #1e3a5f !important;
}

.dark-theme .text-blue-800 {
    color: #93c5fd !important;
}

.dark-theme .border-blue-200 {
    border-color: #1e40af !important;
}

.dark-theme .hover\:bg-gray-100:hover {
    background-color: #374151 !important;
}

.dark-theme .hover\:border-primary-300:hover {
    border-color: #d96a2b !important;
}

.dark-theme .hover\:bg-primary-50:hover {
    background-color: rgba(217, 106, 43, 0.1) !important;
}
</style>
@endsection
