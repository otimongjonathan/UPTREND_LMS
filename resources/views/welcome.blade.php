<x-guest-layout>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(37, 99, 235, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(37, 99, 235, 0.6);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .hover-lift {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
    </style>
    <div class="w-full">
        <div class="text-center mb-24 animate-fade-in-up px-4 sm:px-6 lg:px-8 rounded-full" >
            <div class="mb-6">
                <span class="inline-block bg-primary-100 text-primary-700 text-sm font-bold px-4 py-2 rounded-full mb-4 animate-fade-in-up">🚀 The Modern Loan Management</span>
            </div>
            <h1 class="text-6xl sm:text-7xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-800 mb-6 uppercase tracking-wider leading-tight">Welcome to UPTREND LMS</h1>
            <p class="text-xl text-gray-600 mb-8 max-w-4xl mx-auto leading-relaxed">
                The premier Loan Management System for modern financial institutions.
                Manage loans, track applications, monitor repayments, and oversee borrowers all in one place.
            </p>
            <div class="flex justify-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-2 text-sm text-gray-500">✨ Efficient</span>
                <span class="inline-flex items-center gap-2 text-sm text-gray-500">⚡ Fast</span>
                <span class="inline-flex items-center gap-2 text-sm text-gray-500">🔒 Secure</span>
            </div>
        </div>

        
        <div class="px-4 sm:px-6 lg:px-8 mb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <div class="bg-white p-8 rounded-xl shadow-lg border-l-4 border-primary-500 hover-lift group relative overflow-hidden" style="animation: fadeInUp 0.6s ease-out 0.1s both;">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-gradient-to-br from-primary-400 to-primary-600 p-4 rounded-full mb-4 transform group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 uppercase mb-3 group-hover:text-primary-700 transition-colors duration-300">Loan Management</h3>
                        <p class="text-gray-600 text-sm leading-relaxed group-hover:text-gray-700 transition-colors duration-300">Comprehensive loan tracking and management system with advanced analytics</p>
                    </div>
                </div>
            </div>

            
            <div class="bg-white p-8 rounded-xl shadow-lg border-l-4 border-primary-500 hover-lift group relative overflow-hidden" style="animation: fadeInUp 0.6s ease-out 0.2s both;">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-gradient-to-br from-blue-400 to-primary-600 p-4 rounded-full mb-4 transform group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 uppercase mb-3 group-hover:text-primary-700 transition-colors duration-300">Applications</h3>
                        <p class="text-gray-600 text-sm leading-relaxed group-hover:text-gray-700 transition-colors duration-300">Streamlined application processing and approval workflow automation</p>
                    </div>
                </div>
            </div>

            <!-- Repayments Card -->
            <div class="bg-white p-8 rounded-xl shadow-lg border-l-4 border-primary-500 hover-lift group relative overflow-hidden" style="animation: fadeInUp 0.6s ease-out 0.3s both;">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-gradient-to-br from-green-400 to-primary-600 p-4 rounded-full mb-4 transform group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 uppercase mb-3 group-hover:text-primary-700 transition-colors duration-300">Repayments</h3>
                        <p class="text-gray-600 text-sm leading-relaxed group-hover:text-gray-700 transition-colors duration-300">Automated repayment tracking and payment processing system</p>
                    </div>
                </div>
            </div>

            <!-- Borrowers Card -->
            <div class="bg-white p-8 rounded-xl shadow-lg border-l-4 border-primary-500 hover-lift group relative overflow-hidden" style="animation: fadeInUp 0.6s ease-out 0.4s both;">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-gradient-to-br from-purple-400 to-primary-600 p-4 rounded-full mb-4 transform group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 uppercase mb-3 group-hover:text-primary-700 transition-colors duration-300">Borrowers</h3>
                        <p class="text-gray-600 text-sm leading-relaxed group-hover:text-gray-700 transition-colors duration-300">Complete borrower management and profile tracking solution.</p>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Call to Action Section -->
        <div class="w-full bg-gradient-to-br from-primary-600 to-primary-800 shadow-2xl border-l-4 border-primary-400 relative overflow-hidden mb-24" style="animation: fadeInUp 0.8s ease-out 0.5s both;">
        <div class="px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-80 h-80 bg-white rounded-full -mr-40 -mt-40"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-white rounded-full -ml-40 -mb-40"></div>
            </div>
            <div class="text-center relative z-10 max-w-5xl mx-auto">
                <h2 class="text-4xl font-bold text-white uppercase mb-6">🚀 join us Today</h2>
                <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
                    Join thousands of financial institutions already using UPTREND LMS to streamline their loan management processes.
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto bg-white hover:bg-blue-50 active:bg-blue-100 text-primary-700 px-12 py-4 rounded-lg text-lg font-semibold uppercase tracking-wide transition duration-300 transform hover:scale-105 active:scale-95 shadow-xl cursor-pointer focus:outline-none focus:ring-4 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-600 font-bold">
                        ➜ Login
                    </a>
                    <a href="{{ route('register') }}" class="w-full sm:w-auto bg-primary-400 hover:bg-primary-300 active:bg-primary-500 text-white border-2 border-white px-12 py-4 rounded-lg text-lg font-semibold uppercase tracking-wide transition duration-300 transform hover:scale-105 active:scale-95 shadow-xl cursor-pointer focus:outline-none focus:ring-4 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-600 font-bold">
                        ✨ Create Account
                    </a>
                </div>
            </div>
        </div>
        </div>

        <!-- Trust Indicators -->
        <div class="text-center px-4 sm:px-6 lg:px-8" style="animation: fadeInUp 1s ease-out 0.6s both;">
            <p class="text-gray-500 mb-12 text-lg font-semibold">Trusted by financial institutions worldwide</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-6xl mx-auto">
                <div class="text-center bg-white rounded-xl p-6 shadow-md hover-lift transition-all duration-300">
                    <div class="text-4xl mb-3 transform group-hover:scale-125 transition-transform duration-300">🔒</div>
                    <div class="text-lg font-semibold text-primary-700 uppercase">Secure</div>
                    <p class="text-xs text-gray-500 mt-2">Bank-level encryption</p>
                </div>
                <div class="text-center bg-white rounded-xl p-6 shadow-md hover-lift transition-all duration-300">
                    <div class="text-4xl mb-3 transform group-hover:scale-125 transition-transform duration-300">⚡</div>
                    <div class="text-lg font-semibold text-primary-700 uppercase">Reliable</div>
                    <p class="text-xs text-gray-500 mt-2">99.9% uptime</p>
                </div>
                <div class="text-center bg-white rounded-xl p-6 shadow-md hover-lift transition-all duration-300">
                    <div class="text-4xl mb-3 transform group-hover:scale-125 transition-transform duration-300">🚀</div>
                    <div class="text-lg font-semibold text-primary-700 uppercase">Efficient</div>
                    <p class="text-xs text-gray-500 mt-2">Lightning fast</p>
                </div>
                <div class="text-center bg-white rounded-xl p-6 shadow-md hover-lift transition-all duration-300">
                    <div class="text-4xl mb-3 transform group-hover:scale-125 transition-transform duration-300">📊</div>
                    <div class="text-lg font-semibold text-primary-700 uppercase">Analytics</div>
                    <p class="text-xs text-gray-500 mt-2">Real-time insights</p>
                </div>
            </div>
        </div>
        </div>
    </div>
</x-guest-layout>