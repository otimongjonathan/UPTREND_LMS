<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Customer Portal</title>
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
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-primary-100 min-h-screen text-gray-900 font-sans antialiased">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 15px rgba(217, 106, 43, 0.3); }
            50% { box-shadow: 0 0 25px rgba(217, 106, 43, 0.5); }
        }
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.55s ease-out both;
        }
        .animate-slide-down {
            animation: slideDown 0.5s ease-out both;
        }
        .nav-link {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link::before {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 80%;
            height: 3px;
            background: linear-gradient(90deg, #d96a2b, #f48a47);
            border-radius: 999px;
            transition: transform 0.3s ease;
        }
        .nav-link:hover::before {
            transform: translateX(-50%) scaleX(1);
        }
        .nav-link:hover {
            transform: translateY(-2px);
            color: #d96a2b;
        }
        .nav-link-active {
            background: linear-gradient(135deg, #d96a2b 0%, #f48a47 100%);
            color: white !important;
            box-shadow: 0 4px 12px rgba(217, 106, 43, 0.3);
        }
        .nav-link-active::before {
            display: none;
        }
        .nav-link-active:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 16px rgba(217, 106, 43, 0.4);
        }
        .card-hover {
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 20px 34px rgba(116, 47, 18, 0.18);
        }
        .logo-glow {
            animation: glow 3s ease-in-out infinite;
        }
        .shimmer-text {
            background: linear-gradient(90deg, #d96a2b 0%, #f48a47 50%, #d96a2b 100%);
            background-size: 1000px 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite;
        }
    </style>
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-b border-primary-200 shadow-lg animate-slide-down">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-20 flex items-center justify-between">
                <!-- Logo Section -->
                <a href="{{ route('customer.home', absolute: false) }}" class="flex items-center gap-3 group">
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-lg logo-glow group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold shimmer-text uppercase tracking-wider">UPTREND</h1>
                        <p class="text-xs text-gray-500 font-semibold">Customer Portal</p>
                    </div>
                </a>

                <!-- Navigation Links -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('customer.home', absolute: false) }}" @class([
                        'nav-link font-bold uppercase tracking-wide px-5 py-2.5 rounded-xl text-sm',
                        'nav-link-active' => request()->routeIs('customer.home'),
                        'text-gray-700 hover:bg-primary-50' => !request()->routeIs('customer.home'),
                    ])>
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Home
                        </span>
                    </a>
                    <a href="{{ route('customer.loan-products', absolute: false) }}" @class([
                        'nav-link font-bold uppercase tracking-wide px-5 py-2.5 rounded-xl text-sm',
                        'nav-link-active' => request()->routeIs('customer.loan-products'),
                        'text-gray-700 hover:bg-primary-50' => !request()->routeIs('customer.loan-products'),
                    ])>
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Loan Products
                        </span>
                    </a>
                    <a href="{{ route('customer.loans.index', absolute: false) }}" @class([
                        'nav-link font-bold uppercase tracking-wide px-5 py-2.5 rounded-xl text-sm',
                        'nav-link-active' => request()->routeIs('customer.loans.*'),
                        'text-gray-700 hover:bg-primary-50' => !request()->routeIs('customer.loans.*'),
                    ])>
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            My Loans
                        </span>
                    </a>
                    <a href="{{ route('customer.profile', absolute: false) }}" @class([
                        'nav-link font-bold uppercase tracking-wide px-5 py-2.5 rounded-xl text-sm',
                        'nav-link-active' => request()->routeIs('customer.profile'),
                        'text-gray-700 hover:bg-primary-50' => !request()->routeIs('customer.profile'),
                    ])>
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile
                        </span>
                    </a>
                    <form method="POST" action="{{ route('customer.logout', absolute: false) }}">
                        @csrf
                        <button type="submit" class="ml-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-500 to-red-600 text-white hover:from-red-600 hover:to-red-700 transition-all duration-300 font-bold uppercase tracking-wide text-sm shadow-lg hover:shadow-xl hover:scale-105 flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-28 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-fade-in-up">
            @yield('content')
        </div>
    </main>

    <!-- Customer Footer -->
    <footer class="mt-16 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white">
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
                            <a href="{{ route('customer.home') }}" data-footer-title="Home"
                                data-footer-description="Return to your dashboard to view loan summaries, recent activity, and quick actions."
                                data-footer-href="{{ route('customer.home') }}"
                                class="text-sm text-gray-400 hover:text-white transition">Home</a>
                        </li>
                        <li>
                            <a href="{{ route('customer.loans.index') }}" data-footer-title="My Loans"
                                data-footer-description="View all your loan applications, active loans, and repayment schedules in one place."
                                data-footer-href="{{ route('customer.loans.index') }}"
                                class="text-sm text-gray-400 hover:text-white transition">My Loans</a>
                        </li>
                        <li>
                            <a href="{{ route('customer.profile') }}" data-footer-title="Profile"
                                data-footer-description="Update your personal information, contact details, and account preferences."
                                data-footer-href="{{ route('customer.profile') }}"
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
                        <p>&copy; {{ date('Y') }} UPTREND LMS. All rights reserved.</p>
                        <div class="flex gap-4">
                            <a href="{{ route('customer.privacy-policy') }}" data-footer-title="Privacy Policy"
                                data-footer-description="Learn how we protect your personal information and financial data in compliance with privacy regulations."
                                data-footer-href="{{ route('customer.privacy-policy') }}"
                                class="hover:text-white transition">Privacy Policy</a>
                            <span class="text-gray-600">|</span>
                            <a href="{{ route('customer.terms-of-service') }}" data-footer-title="Terms of Service"
                                data-footer-description="Review the terms and conditions that govern your use of UPTREND LMS customer services."
                                data-footer-href="{{ route('customer.terms-of-service') }}"
                                class="hover:text-white transition">Terms of Service</a>
                            <span class="text-gray-600">|</span>
                            <a href="{{ route('customer.cookie-policy') }}" data-footer-title="Cookie Policy"
                                data-footer-description="Understand how we use cookies to improve your experience and protect your privacy."
                                data-footer-href="{{ route('customer.cookie-policy') }}"
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
</body>
</html>

