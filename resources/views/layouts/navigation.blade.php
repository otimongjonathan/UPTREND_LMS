<!-- Sidebar Navigation -->
<div x-data="{ sidebarOpen: true }" class="flex">
    <!-- Mobile hamburger toggle -->
    <div class="fixed top-0 left-0 right-0 h-16 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm md:hidden z-40 flex items-center px-4">
        <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{ 'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{ 'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <span class="ms-4 text-sm font-semibold text-gray-800">Menu</span>
    </div>

    <!-- Sidebar -->
    <nav :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }" class="fixed md:static md:translate-x-0 left-0 top-0 h-screen w-64 bg-gradient-to-b from-primary-50 to-white border-r border-gray-200 shadow-lg transition-transform duration-300 ease-in-out z-50 md:z-auto flex flex-col overflow-y-auto">
        <style>
            @keyframes slideInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.8; }
            }

            @keyframes iconBounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-3px); }
            }

            .sidebar-link {
                position: relative;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                align-items: center;
                padding: 0.875rem 1.5rem;
                color: #1f2937;
                text-decoration: none;
                border-left: 3px solid transparent;
                overflow: hidden;
                animation: slideInLeft 0.4s ease-out both;
            }

            .sidebar-link::before {
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

            .sidebar-link:hover::before {
                width: 100%;
            }

            .sidebar-link > * {
                position: relative;
                z-index: 1;
            }

            .sidebar-link:hover {
                background-color: rgba(217, 106, 43, 0.08);
                color: #d96a2b;
                border-left-color: #d96a2b;
                transform: translateX(4px);
                padding-left: 1.75rem;
            }

            .sidebar-link:hover .link-icon {
                animation: iconBounce 0.6s ease-in-out;
                transform: scale(1.15);
            }

            .sidebar-link.active {
                background: linear-gradient(90deg, rgba(217, 106, 43, 0.2), rgba(217, 106, 43, 0.08));
                color: #b4531f;
                border-left-color: #d96a2b;
                font-weight: 600;
                box-shadow: inset 0 1px 3px rgba(217, 106, 43, 0.1);
            }

            .sidebar-link.active .link-icon {
                animation: pulse 2s ease-in-out infinite;
            }

            .sidebar-link:nth-child(1) { animation-delay: 0.05s; }
            .sidebar-link:nth-child(2) { animation-delay: 0.1s; }
            .sidebar-link:nth-child(3) { animation-delay: 0.15s; }
            .sidebar-link:nth-child(4) { animation-delay: 0.2s; }
            .sidebar-link:nth-child(5) { animation-delay: 0.25s; }
            .sidebar-link:nth-child(6) { animation-delay: 0.3s; }
            .sidebar-link:nth-child(7) { animation-delay: 0.35s; }
            .sidebar-link:nth-child(8) { animation-delay: 0.4s; }

            .link-icon {
                transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                display: inline-block;
            }

            .link-text {
                font-size: 0.875rem;
                font-weight: 600;
                letter-spacing: 0.05em;
                transition: all 0.3s ease;
                color: #1f2937;
            }

            .sidebar-link:hover .link-text {
                letter-spacing: 0.08em;
            }

            @keyframes logoGlow {
                0%, 100% {
                    box-shadow: 0 0 20px rgba(217, 106, 43, 0.3);
                }
                50% {
                    box-shadow: 0 0 30px rgba(217, 106, 43, 0.6);
                }
            }

            @keyframes textShimmer {
                0% {
                    background-position: -200% center;
                }
                100% {
                    background-position: 200% center;
                }
            }

            @keyframes logoFloat {
                0%, 100% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-5px);
                }
            }

            .sidebar-logo {
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(90deg, #d96a2b, #b4531f);
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                margin-top: 4rem;
                animation: fadeIn 0.6s ease-out;
                min-height: 88px;
            }

            .logo-container {
                background: white;
                width: 100%;
                height: 100%;
                min-height: 88px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1.5rem;
                animation: logoGlow 3s ease-in-out infinite;
                transition: all 0.3s ease;
            }

            .logo-container:hover {
                transform: scale(1.02);
                box-shadow: 0 0 40px rgba(217, 106, 43, 0.8);
            }

            .logo-text {
                background: linear-gradient(90deg, #d96a2b, #f48a47, #d96a2b, #f48a47);
                background-size: 200% auto;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                animation: textShimmer 4s linear infinite;
                font-weight: 800;
                font-size: 1.5rem;
                letter-spacing: 0.05em;
            }

            .sidebar-section-title {
                padding: 1rem 1.5rem 0.5rem;
                font-size: 0.7rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: #6b7280;
                animation: slideInLeft 0.5s ease-out both;
            }

            .user-section {
                animation: slideInLeft 0.6s ease-out 0.5s both;
            }

            @media (min-width: 768px) {
                .sidebar-logo {
                    margin-top: 0;
                }
            }
        </style>

        <!-- Logo -->
        <div class="sidebar-logo">
            <a href="{{ route('dashboard') }}" class="logo-container">
                <span class="logo-text">UPTREND LMS</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="px-4 py-6 space-y-1 flex-1">
            <!-- Main Section -->
            <div class="sidebar-section-title">Main</div>
            <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="link-icon text-xl me-3">📊</span>
                <span class="link-text">DASHBOARD</span>
            </a>

            <!-- Loan Management Section -->
            <div class="sidebar-section-title mt-6">Loan Management</div>
            <a href="{{ route('applications.index') }}" @click="sidebarOpen = false" class="sidebar-link {{ request()->routeIs('applications.*') ? 'active' : '' }}">
                <span class="link-icon text-xl me-3">📋</span>
                <span class="link-text">APPLICATIONS</span>
            </a>
            <a href="{{ route('loans.index') }}" @click="sidebarOpen = false" class="sidebar-link {{ request()->routeIs('loans.*') ? 'active' : '' }}">
                <span class="link-icon text-xl me-3">💰</span>
                <span class="link-text">LOANS</span>
            </a>
            <a href="{{ route('loan-products.index') }}" @click="sidebarOpen = false" class="sidebar-link {{ request()->routeIs('loan-products.*') ? 'active' : '' }}">
                <span class="link-icon text-xl me-3">💼</span>
                <span class="link-text">PRODUCTS</span>
            </a>
            <a href="{{ route('repayments.index') }}" @click="sidebarOpen = false" class="sidebar-link {{ request()->routeIs('repayments.*') ? 'active' : '' }}">
                <span class="link-icon text-xl me-3">💳</span>
                <span class="link-text">REPAYMENTS</span>
            </a>

            <!-- User Management Section -->
            <div class="sidebar-section-title mt-6">User Management</div>
            <a href="{{ route('borrowers.index') }}" @click="sidebarOpen = false" class="sidebar-link {{ request()->routeIs('borrowers.*') ? 'active' : '' }}">
                <span class="link-icon text-xl me-3">👥</span>
                <span class="link-text">BORROWERS</span>
            </a>
            <a href="{{ route('staff.index') }}" @click="sidebarOpen = false" class="sidebar-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                <span class="link-icon text-xl me-3">👔</span>
                <span class="link-text">STAFF</span>
            </a>
        </div>

        <!-- User Section -->
        <div class="user-section bg-gradient-to-t from-white to-transparent border-t border-gray-200 mt-auto">
            <div class="p-4">
                <div class="mb-4 p-3 bg-gradient-to-r from-primary-50 to-orange-50 rounded-lg border border-primary-100 transition-all duration-300 hover:shadow-md">
                    <div class="font-semibold text-sm text-gray-800 truncate">{{ Auth::guard('staff')->user()->business_name }}</div>
                    <div class="font-normal text-xs text-gray-500 truncate mt-1">{{ Auth::guard('staff')->user()->email }}</div>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('profile.show') }}" @click="sidebarOpen = false" class="sidebar-link text-sm py-2">
                        <span class="link-icon text-lg me-2">👁️</span>
                        <span class="link-text">VIEW PROFILE</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false" class="sidebar-link text-sm py-2">
                        <span class="link-icon text-lg me-2">✏️</span>
                        <span class="link-text">EDIT PROFILE</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="sidebar-link text-sm py-2 hover:bg-red-50 hover:text-red-600 hover:border-red-500 w-full text-left">
                            <span class="link-icon text-lg me-2">🚪</span>
                            <span class="link-text">LOG OUT</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</div>
