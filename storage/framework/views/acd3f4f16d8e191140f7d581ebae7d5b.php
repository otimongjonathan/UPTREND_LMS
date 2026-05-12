<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?> - Customer Portal</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff8f3',
                            100: '#ffeddc',
                            200: '#ffd3b5',
                            300: '#ffb27f',
                            400: '#f48a47',
                            500: '#d96a2b',
                            600: '#b4531f',
                            700: '#8f3f16',
                            800: '#742f12',
                            900: '#5a240e',
                        },
                    },
                },
            },
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
</head>
<body class="customer-portal-body flex h-screen max-h-screen flex-col overflow-hidden bg-gradient-to-br from-orange-50 via-white to-orange-100 text-gray-900 font-sans antialiased">
    <script>
        // Apply saved theme immediately to prevent flash
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.body.classList.add('dark-theme');
            }
        })();
    </script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.55s ease-out both;
        }
        .card-hover {
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 20px 34px rgba(116, 47, 18, 0.18);
        }
        @supports (height: 100dvh) {
            .customer-portal-body {
                height: 100dvh;
                max-height: 100dvh;
            }
        }

        /* Dark Theme Styles */
        .dark-theme {
            background: linear-gradient(to bottom right, #1a1a1a, #2d2d2d, #1a1a1a) !important;
        }

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

        .dark-theme .text-gray-800 {
            color: #e5e7eb !important;
        }

        .dark-theme .text-gray-500 {
            color: #9ca3af !important;
        }

        .dark-theme .text-gray-700 {
            color: #d1d5db !important;
        }

        .dark-theme .text-gray-400 {
            color: #9ca3af !important;
        }

        .dark-theme .bg-gray-50 {
            background-color: #1f1f1f !important;
        }

        .dark-theme .bg-gray-100 {
            background-color: #374151 !important;
        }

        .dark-theme .bg-gray-200 {
            background-color: #4b5563 !important;
        }

        .dark-theme .border-gray-200 {
            border-color: #404040 !important;
        }

        .dark-theme .border-gray-100 {
            border-color: #374151 !important;
        }

        .dark-theme .bg-blue-50 {
            background-color: #1e3a5f !important;
        }

        .dark-theme .text-blue-800 {
            color: #93c5fd !important;
        }

        .dark-theme .text-blue-600 {
            color: #60a5fa !important;
        }

        .dark-theme .border-blue-200 {
            border-color: #1e40af !important;
        }

        .dark-theme .bg-green-50 {
            background-color: #064e3b !important;
        }

        .dark-theme .text-green-800 {
            color: #6ee7b7 !important;
        }

        .dark-theme .text-green-600 {
            color: #34d399 !important;
        }

        .dark-theme .bg-green-100 {
            background-color: #065f46 !important;
        }

        .dark-theme .bg-red-50 {
            background-color: #7f1d1d !important;
        }

        .dark-theme .text-red-800 {
            color: #fca5a5 !important;
        }

        .dark-theme .text-red-600 {
            color: #f87171 !important;
        }

        .dark-theme .bg-red-100 {
            background-color: #991b1b !important;
        }

        .dark-theme .bg-purple-100 {
            background-color: #581c87 !important;
        }

        .dark-theme .text-purple-600 {
            color: #c084fc !important;
        }

        .dark-theme .bg-orange-50 {
            background-color: #431407 !important;
        }

        .dark-theme .bg-orange-100 {
            background-color: #7c2d12 !important;
        }

        .dark-theme .text-orange-700 {
            color: #fb923c !important;
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

        .dark-theme .bg-gradient-to-br {
            background: linear-gradient(to bottom right, #1a1a1a, #2d2d2d, #1a1a1a) !important;
        }

        .dark-theme .bg-gradient-to-b {
            background: linear-gradient(to bottom, #2d2d2d, #1a1a1a) !important;
        }

        .dark-theme nav {
            background: linear-gradient(to bottom, #2d2d2d, #1a1a1a) !important;
            border-color: #404040 !important;
        }

        .dark-theme .cp-sidebar-link {
            color: #e5e7eb !important;
        }

        .dark-theme .cp-sidebar-link:hover {
            background-color: rgba(217, 106, 43, 0.15) !important;
        }

        .dark-theme .cp-sidebar-link.active {
            background: linear-gradient(90deg, rgba(217, 106, 43, 0.3), rgba(217, 106, 43, 0.15)) !important;
        }

        .dark-theme .cp-sidebar-section-title {
            color: #9ca3af !important;
        }

        .dark-theme .bg-gradient-to-r.from-primary-50 {
            background: linear-gradient(to right, rgba(217, 106, 43, 0.2), rgba(244, 138, 71, 0.2)) !important;
        }

        .dark-theme .bg-gradient-to-t {
            background: linear-gradient(to top, #1a1a1a, transparent) !important;
        }

        .dark-theme .shadow-lg,
        .dark-theme .shadow-sm {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.3) !important;
        }

        .dark-theme .hover\:text-white:hover {
            color: #ffffff !important;
        }

        .dark-theme .text-white {
            color: #ffffff !important;
        }

        .dark-theme .cp-logo-container {
            background: #1a1a1a !important;
        }

        .dark-theme .cp-logo-text {
            background: linear-gradient(90deg, #f48a47, #d96a2b, #f48a47, #d96a2b) !important;
            background-size: 200% auto !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
        }

        .dark-theme footer {
            background: linear-gradient(to right, #0f0f0f, #1a1a1a, #0f0f0f) !important;
        }

        .dark-theme .border-t {
            border-color: #404040 !important;
        }

        .dark-theme .border-b {
            border-color: #404040 !important;
        }
    </style>
    <!-- Shell: sidebar column + one scroll column (sidebar does not move with page scroll) -->
    <div x-data="{ sidebarOpen: true }" class="flex min-h-0 w-full flex-1 flex-col overflow-hidden md:flex-row">
        <div class="fixed top-0 left-0 right-0 z-40 flex h-16 items-center border-b border-gray-100 bg-white/95 px-4 shadow-sm backdrop-blur-md md:hidden">
            <button type="button" @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:outline-none">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <span class="ms-4 text-sm font-semibold text-gray-800">Menu</span>
        </div>

        <nav :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }" class="fixed left-0 top-0 z-50 flex h-screen w-64 shrink-0 flex-col overflow-y-auto border-r border-gray-200 bg-gradient-to-b from-primary-50 to-white shadow-lg transition-transform duration-300 ease-in-out md:static md:z-auto md:h-screen md:max-h-none md:translate-x-0">
            <style>
                @keyframes cpSlideInLeft {
                    from { opacity: 0; transform: translateX(-20px); }
                    to { opacity: 1; transform: translateX(0); }
                }
                @keyframes cpFadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes cpPulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.8; }
                }
                @keyframes cpIconBounce {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-3px); }
                }
                .cp-sidebar-link {
                    position: relative;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    display: flex;
                    align-items: center;
                    padding: 0.875rem 1.5rem;
                    color: #1f2937;
                    text-decoration: none;
                    border-left: 3px solid transparent;
                    overflow: hidden;
                    animation: cpSlideInLeft 0.4s ease-out both;
                }
                .cp-sidebar-link::before {
                    content: '';
                    position: absolute;
                    left: 0;
                    top: 0;
                    height: 100%;
                    width: 0;
                    background: linear-gradient(90deg, rgba(217, 106, 43, 0.15), rgba(217, 106, 43, 0.05));
                    transition: width 0.3s ease;
                    z-index: 0;
                }
                .cp-sidebar-link:hover::before { width: 100%; }
                .cp-sidebar-link > * { position: relative; z-index: 1; }
                .cp-sidebar-link:hover {
                    background-color: rgba(217, 106, 43, 0.08);
                    color: #d96a2b;
                    border-left-color: #d96a2b;
                    transform: translateX(4px);
                    padding-left: 1.75rem;
                }
                .cp-sidebar-link:hover .cp-link-icon {
                    animation: cpIconBounce 0.6s ease-in-out;
                    transform: scale(1.15);
                }
                .cp-sidebar-link.active {
                    background: linear-gradient(90deg, rgba(217, 106, 43, 0.2), rgba(217, 106, 43, 0.08));
                    color: #b4531f;
                    border-left-color: #d96a2b;
                    font-weight: 600;
                    box-shadow: inset 0 1px 3px rgba(217, 106, 43, 0.1);
                }
                .cp-sidebar-link.active .cp-link-icon { animation: cpPulse 2s ease-in-out infinite; }
                .cp-link-icon {
                    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                    display: inline-block;
                }
                .cp-link-text {
                    font-size: 0.875rem;
                    font-weight: 600;
                    letter-spacing: 0.05em;
                    transition: all 0.3s ease;
                    color: inherit;
                }
                .cp-sidebar-link:hover .cp-link-text { letter-spacing: 0.08em; }
                @keyframes cpLogoGlow {
                    0%, 100% { box-shadow: 0 0 20px rgba(217, 106, 43, 0.3); }
                    50% { box-shadow: 0 0 30px rgba(217, 106, 43, 0.6); }
                }
                @keyframes cpTextShimmer {
                    0% { background-position: -200% center; }
                    100% { background-position: 200% center; }
                }
                .cp-sidebar-logo {
                    padding: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: linear-gradient(90deg, #d96a2b, #b4531f);
                    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                    margin-top: 4rem;
                    animation: cpFadeIn 0.6s ease-out;
                    min-height: 88px;
                }
                .cp-logo-container {
                    background: white;
                    width: 100%;
                    height: 100%;
                    min-height: 88px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 1.25rem 1.5rem;
                    animation: cpLogoGlow 3s ease-in-out infinite;
                    transition: all 0.3s ease;
                }
                .cp-logo-container:hover {
                    transform: scale(1.02);
                    box-shadow: 0 0 40px rgba(217, 106, 43, 0.8);
                }
                .cp-logo-text {
                    background: linear-gradient(90deg, #d96a2b, #f48a47, #d96a2b, #f48a47);
                    background-size: 200% auto;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    animation: cpTextShimmer 4s linear infinite;
                    font-weight: 800;
                    font-size: 1.25rem;
                    letter-spacing: 0.05em;
                }
                .cp-sidebar-section-title {
                    padding: 1rem 1.5rem 0.5rem;
                    font-size: 0.7rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.1em;
                    color: #6b7280;
                    animation: cpSlideInLeft 0.5s ease-out both;
                }
                .cp-user-section { animation: cpSlideInLeft 0.6s ease-out 0.5s both; }
                @media (min-width: 768px) {
                    .cp-sidebar-logo { margin-top: 0; }
                }
            </style>

            <div class="cp-sidebar-logo">
                <a href="<?php echo e(route('customer.home', absolute: false)); ?>" @click="sidebarOpen = false" class="cp-logo-container">
                    <span class="cp-logo-text">UPTREND</span>
                    <span class="mt-1 text-xs font-semibold uppercase tracking-wider text-gray-500">Customer Portal</span>
                </a>
            </div>

            <div class="flex flex-1 flex-col px-4 py-6">
                <div class="cp-sidebar-section-title">Main</div>
                <a href="<?php echo e(route('customer.home', absolute: false)); ?>" @click="sidebarOpen = false" class="cp-sidebar-link <?php echo e(request()->routeIs('customer.home') ? 'active' : ''); ?>">
                    <span class="cp-link-icon text-xl me-3">🏠</span>
                    <span class="cp-link-text">HOME</span>
                </a>
                <a href="<?php echo e(route('customer.loan-products', absolute: false)); ?>" @click="sidebarOpen = false" class="cp-sidebar-link <?php echo e(request()->routeIs('customer.loan-products') ? 'active' : ''); ?>">
                    <span class="cp-link-icon text-xl me-3">💼</span>
                    <span class="cp-link-text">PRODUCTS</span>
                </a>
                <a href="<?php echo e(route('customer.loans.index', absolute: false)); ?>" @click="sidebarOpen = false" class="cp-sidebar-link relative <?php echo e(request()->routeIs('customer.loans.*') ? 'active' : ''); ?>">
                    <span class="cp-link-icon text-xl me-3">💰</span>
                    <span class="cp-link-text">MY LOANS</span>
                    <?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->loanApplications()->where('status', 'pending')->count() > 0): ?>
                        <span class="absolute end-3 top-1/2 z-20 flex h-5 w-5 -translate-y-1/2 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white"><?php echo e(Auth::guard('customer')->user()->loanApplications()->where('status', 'pending')->count()); ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo e(route('customer.notifications', absolute: false)); ?>" @click="sidebarOpen = false" class="cp-sidebar-link relative <?php echo e(request()->routeIs('customer.notifications') ? 'active' : ''); ?>">
                    <span class="cp-link-icon text-xl me-3">🔔</span>
                    <span class="cp-link-text">ALERTS</span>
                    <?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->unreadNotifications->count() > 0): ?>
                        <span class="absolute end-3 top-1/2 z-20 flex h-5 w-5 -translate-y-1/2 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white"><?php echo e(Auth::guard('customer')->user()->unreadNotifications->count()); ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo e(route('customer.profile', absolute: false)); ?>" @click="sidebarOpen = false" class="cp-sidebar-link <?php echo e(request()->routeIs('customer.profile') ? 'active' : ''); ?>">
                    <span class="cp-link-icon text-xl me-3">👤</span>
                    <span class="cp-link-text">PROFILE</span>
                </a>
                <a href="<?php echo e(route('customer.settings', absolute: false)); ?>" @click="sidebarOpen = false" class="cp-sidebar-link <?php echo e(request()->routeIs('customer.settings') ? 'active' : ''); ?>">
                    <span class="cp-link-icon text-xl me-3">⚙️</span>
                    <span class="cp-link-text">SETTINGS</span>
                </a>
            </div>

            <div class="cp-user-section mt-auto border-t border-gray-200 bg-gradient-to-t from-white to-transparent">
                <div class="p-4">
                    <?php if(auth()->guard('customer')->check()): ?>
                        <div class="mb-4 rounded-lg border border-primary-100 bg-gradient-to-r from-primary-50 to-orange-50 p-3 transition-all duration-300 hover:shadow-md">
                            <div class="truncate text-sm font-semibold text-gray-800"><?php echo e(Auth::guard('customer')->user()->name); ?></div>
                            <div class="mt-1 truncate text-xs font-normal text-gray-500"><?php echo e(Auth::guard('customer')->user()->email); ?></div>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('customer.logout', absolute: false)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="cp-sidebar-link w-full text-left hover:bg-red-50 hover:text-red-600 hover:border-red-500">
                            <span class="cp-link-icon text-lg me-2">🚪</span>
                            <span class="cp-link-text">LOG OUT</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="flex min-h-0 w-full flex-1 flex-col overflow-y-auto overscroll-y-contain pt-16 md:pt-0">
    <main class="p-8 md:pt-8">
        <div class="max-w-7xl mx-auto animate-fade-in-up">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <!-- Customer Footer -->
    <footer class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 py-12">
                <!-- About Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-white">UPTREND LMS</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Your trusted partner for loan management. We make borrowing simple, transparent, and accessible.
                    </p>
                    <div class="flex gap-3">
                        <button type="button" data-footer-title="Facebook"
                            data-footer-description="Follow UPTREND LMS on Facebook for customer updates, loan tips, and financial education content."
                            class="h-9 w-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition"
                            aria-label="Learn more about Facebook">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </button>
                        <button type="button" data-footer-title="Twitter"
                            data-footer-description="Get quick updates about loan products, payment reminders, and customer service announcements."
                            class="h-9 w-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition"
                            aria-label="Learn more about Twitter">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </button>
                        <button type="button" data-footer-title="LinkedIn"
                            data-footer-description="Connect with UPTREND LMS for professional updates and business loan opportunities."
                            class="h-9 w-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition"
                            aria-label="Learn more about LinkedIn">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="<?php echo e(route('customer.home')); ?>" data-footer-title="Home"
                                data-footer-description="Return to your dashboard to view loan summaries, recent activity, and quick actions."
                                data-footer-href="<?php echo e(route('customer.home')); ?>"
                                class="text-sm text-gray-400 hover:text-white transition">Home</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('customer.loans.index')); ?>" data-footer-title="My Loans"
                                data-footer-description="View all your loan applications, active loans, and repayment schedules in one place."
                                data-footer-href="<?php echo e(route('customer.loans.index')); ?>"
                                class="text-sm text-gray-400 hover:text-white transition">My Loans</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('customer.profile')); ?>" data-footer-title="Profile"
                                data-footer-description="Update your personal information, contact details, and account preferences."
                                data-footer-href="<?php echo e(route('customer.profile')); ?>"
                                class="text-sm text-gray-400 hover:text-white transition">Profile</a>
                        </li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-4">Resources</h4>
                    <ul class="space-y-2">
                        <li>
                            <button type="button" data-footer-title="FAQs"
                                data-footer-description="Find answers to common questions about loan applications, repayments, and account management."
                                class="text-sm text-gray-400 hover:text-white transition text-left">FAQs</button>
                        </li>
                        <li>
                            <button type="button" data-footer-title="Loan Calculator"
                                data-footer-description="Calculate monthly payments, interest rates, and total loan costs before applying."
                                class="text-sm text-gray-400 hover:text-white transition text-left">Loan Calculator</button>
                        </li>
                        <li>
                            <button type="button" data-footer-title="Help Center"
                                data-footer-description="Access tutorials, guides, and step-by-step instructions for using the customer portal."
                                class="text-sm text-gray-400 hover:text-white transition text-left">Help Center</button>
                        </li>
                        <li>
                            <button type="button" data-footer-title="Terms & Conditions"
                                data-footer-description="Review the legal terms, conditions, and policies governing your loan agreements."
                                class="text-sm text-gray-400 hover:text-white transition text-left">Terms & Conditions</button>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-4">Contact Us</h4>
                    <ul class="space-y-3">
                        <li>
                            <button type="button" class="flex items-start gap-2 text-sm text-gray-400 text-left hover:text-white transition"
                                data-footer-title="Customer Support Email"
                                data-footer-description="Email us for loan inquiries, payment questions, or account assistance. We respond within 24 hours."
                                data-footer-href="mailto:support@uptrendlms.com">
                            <svg class="h-5 w-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>support@uptrendlms.com</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="flex items-start gap-2 text-sm text-gray-400 text-left hover:text-white transition"
                                data-footer-title="Customer Service Phone"
                                data-footer-description="Call during business hours for immediate assistance with urgent loan matters and account issues."
                                data-footer-href="tel:+15551234567">
                            <svg class="h-5 w-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>+1 (555) 123-4567</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="flex items-start gap-2 text-sm text-gray-400 text-left hover:text-white transition"
                                data-footer-title="Business Hours"
                                data-footer-description="Our customer service team is available Monday through Friday, 9 AM to 5 PM for phone and live chat support.">
                            <svg class="h-5 w-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Mon-Fri: 9AM - 5PM</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-700 py-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-400">
                        <p>&copy; <?php echo e(date('Y')); ?> UPTREND LMS. All rights reserved.</p>
                        <div class="flex gap-4">
                            <a href="<?php echo e(route('customer.privacy-policy')); ?>" data-footer-title="Privacy Policy"
                                data-footer-description="Learn how we protect your personal information and financial data in compliance with privacy regulations."
                                data-footer-href="<?php echo e(route('customer.privacy-policy')); ?>"
                                class="hover:text-white transition">Privacy Policy</a>
                            <span class="text-gray-600">|</span>
                            <a href="<?php echo e(route('customer.terms-of-service')); ?>" data-footer-title="Terms of Service"
                                data-footer-description="Review the terms and conditions that govern your use of UPTREND LMS customer services."
                                data-footer-href="<?php echo e(route('customer.terms-of-service')); ?>"
                                class="hover:text-white transition">Terms of Service</a>
                            <span class="text-gray-600">|</span>
                            <a href="<?php echo e(route('customer.cookie-policy')); ?>" data-footer-title="Cookie Policy"
                                data-footer-description="Understand how we use cookies to improve your experience and protect your privacy."
                                data-footer-href="<?php echo e(route('customer.cookie-policy')); ?>"
                                class="hover:text-white transition">Cookie Policy</a>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">Secured by</span>
                        <span class="text-sm font-semibold text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-orange-400">UPTREND Security</span>
                    </div>
                </div>
            </div>
        </div>

        <div id="footer-info-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-6">
            <div class="w-full max-w-lg rounded-2xl border border-white/10 bg-slate-900 text-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-white/10 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary-300">Footer Details</p>
                        <h3 id="footer-info-title" class="mt-2 text-2xl font-bold">Information</h3>
                    </div>
                    <button type="button" id="footer-info-close" class="rounded-full bg-white/10 px-3 py-1 text-sm font-semibold text-gray-200 hover:bg-white/20">
                        Close
                    </button>
                </div>
                <div class="px-6 py-5">
                    <p id="footer-info-body" class="text-sm leading-6 text-gray-300"></p>
                </div>
                <div class="flex flex-col gap-3 border-t border-white/10 px-6 py-5 sm:flex-row sm:justify-end">
                    <a id="footer-info-action" href="#" class="hidden rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 px-5 py-3 text-sm font-semibold text-white transition hover:from-primary-600 hover:to-primary-700">Open item</a>
                    <button type="button" id="footer-info-dismiss" class="rounded-xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-gray-200 transition hover:bg-white/10">
                        Got it
                    </button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.getElementById('footer-info-modal');
                if (!modal) {
                    return;
                }

                const title = document.getElementById('footer-info-title');
                const body = document.getElementById('footer-info-body');
                const action = document.getElementById('footer-info-action');
                const closeButtons = [
                    document.getElementById('footer-info-close'),
                    document.getElementById('footer-info-dismiss'),
                ];

                function hideModal() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                function showModal(trigger) {
                    title.textContent = trigger.getAttribute('data-footer-title') || 'Information';
                    body.textContent = trigger.getAttribute('data-footer-description') || 'More details are not available right now.';

                    const href = trigger.getAttribute('data-footer-href');
                    if (href) {
                        action.href = href;
                        action.classList.remove('hidden');
                    } else {
                        action.classList.add('hidden');
                    }

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                document.addEventListener('click', function (event) {
                    const trigger = event.target.closest('[data-footer-title]');
                    if (trigger) {
                        event.preventDefault();
                        showModal(trigger);
                        return;
                    }

                    if (event.target === modal) {
                        hideModal();
                    }
                });

                closeButtons.forEach(function (button) {
                    if (button) {
                        button.addEventListener('click', hideModal);
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        hideModal();
                    }
                });
            });
        </script>
    </footer>
    </div>
    </div>
</body>
</html>

<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/customer/layouts/app.blade.php ENDPATH**/ ?>