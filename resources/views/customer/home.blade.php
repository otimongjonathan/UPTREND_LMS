@extends('customer.layouts.app')

@section('content')
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(217, 106, 43, 0.3); }
            50% { box-shadow: 0 0 40px rgba(217, 106, 43, 0.6); }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .8; transform: scale(1.05); }
        }

        .stat-card {
            animation: slideIn 0.6s ease-out both;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .stat-card:hover::before {
            left: 100%;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 20px 40px rgba(217, 106, 43, 0.25);
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .glow-animation {
            animation: glow 3s ease-in-out infinite;
        }

        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }

        .icon-bounce:hover {
            animation: float 0.6s ease-in-out;
        }

        .gradient-text {
            background: linear-gradient(135deg, #d96a2b, #f48a47);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .shimmer-bg {
            background: linear-gradient(90deg, #d96a2b 0%, #f48a47 50%, #d96a2b 100%);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite;
        }

        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(217, 106, 43, 0.2);
        }
    </style>

    <!-- Welcome Banner -->
    <div class="mb-8 bg-gradient-to-r from-orange-600 via-orange-700 to-orange-800 rounded-3xl p-8 text-white shadow-2xl hover-lift glow-animation relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 float-animation"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24 float-animation" style="animation-delay: 1s;"></div>
        <div class="relative">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center icon-bounce">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold uppercase tracking-wide">Welcome Back, {{ Auth::user()->name }}!</h1>
                    <p class="text-orange-100 mt-1 text-lg">Track your loans, submit applications, and manage your profile in one place.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12 float-animation"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold uppercase tracking-wide text-orange-100">Total Applications</p>
                    <div class="h-10 w-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center icon-bounce">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold pulse-animation">{{ $applications }}</p>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12 float-animation"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold uppercase tracking-wide text-amber-100">Pending Reviews</p>
                    <div class="h-10 w-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center icon-bounce">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold pulse-animation">{{ $pending }}</p>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12 float-animation"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold uppercase tracking-wide text-emerald-100">Approved Loans</p>
                    <div class="h-10 w-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center icon-bounce">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold pulse-animation">{{ $applications - $pending }}</p>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12 float-animation"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold uppercase tracking-wide text-rose-100">Quick Actions</p>
                    <div class="h-10 w-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center icon-bounce">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('customer.loans.index') }}" class="inline-block mt-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold transition-all duration-300 hover-lift">
                    Apply Now →
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('customer.loans.index') }}" class="bg-white rounded-2xl p-6 shadow-lg hover-lift border border-orange-100 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center icon-bounce">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 gradient-text">My Loans</h3>
                    <p class="text-sm text-gray-600">View & manage applications</p>
                </div>
            </div>
        </a>

        <a href="{{ route('customer.profile') }}" class="bg-white rounded-2xl p-6 shadow-lg hover-lift border border-orange-100 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center icon-bounce">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 gradient-text">My Profile</h3>
                    <p class="text-sm text-gray-600">Update your information</p>
                </div>
            </div>
        </a>

        <a href="{{ route('customer.home') }}" class="bg-white rounded-2xl p-6 shadow-lg hover-lift border border-orange-100 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center icon-bounce">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 gradient-text">Support</h3>
                    <p class="text-sm text-gray-600">Get help & assistance</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Information Section -->
    <div class="bg-gradient-to-br from-orange-50 to-white rounded-2xl p-8 shadow-lg border border-orange-100 hover-lift">
        <div class="flex items-start gap-4">
            <div class="h-12 w-12 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center flex-shrink-0 icon-bounce">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold uppercase tracking-wide text-gray-900 mb-2 gradient-text">Getting Started</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Welcome to your customer portal! Here you can submit new loan applications, track the status of existing applications, 
                    and manage your profile information. Use the <span class="font-semibold text-orange-700">MY LOANS</span> section to get started.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div class="flex items-start gap-3 hover-lift p-3 rounded-lg transition-all duration-300">
                        <div class="h-8 w-8 rounded-lg bg-orange-100 text-orange-700 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-sm">1</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Submit Application</h4>
                            <p class="text-sm text-gray-600">Fill out the loan application form with your details</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 hover-lift p-3 rounded-lg transition-all duration-300">
                        <div class="h-8 w-8 rounded-lg bg-orange-100 text-orange-700 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-sm">2</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Track Progress</h4>
                            <p class="text-sm text-gray-600">Monitor your application status in real-time</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 hover-lift p-3 rounded-lg transition-all duration-300">
                        <div class="h-8 w-8 rounded-lg bg-orange-100 text-orange-700 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-sm">3</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Get Approved</h4>
                            <p class="text-sm text-gray-600">Receive notification once your loan is approved</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 hover-lift p-3 rounded-lg transition-all duration-300">
                        <div class="h-8 w-8 rounded-lg bg-orange-100 text-orange-700 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-sm">4</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Manage Repayments</h4>
                            <p class="text-sm text-gray-600">View and track your repayment schedule</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
