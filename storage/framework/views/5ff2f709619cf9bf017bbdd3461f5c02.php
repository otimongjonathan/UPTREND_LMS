<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% { 
                opacity: 1;
                transform: scale(1);
            }
            50% { 
                opacity: 0.8;
                transform: scale(1.05);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }

        @keyframes glow {
            0%, 100% { 
                box-shadow: 0 10px 40px rgba(217, 106, 43, 0.2);
            }
            50% { 
                box-shadow: 0 15px 60px rgba(217, 106, 43, 0.4);
            }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

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

        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }

        .widget-card {
            animation: fadeUp 0.6s ease-out both;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .widget-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
            pointer-events: none;
        }

        .widget-card:hover::before {
            left: 100%;
        }

        .widget-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s ease;
            pointer-events: none;
        }

        .widget-card:hover::after {
            opacity: 1;
        }

        .widget-card:hover {
            transform: translateY(-12px) scale(1.03) rotateX(5deg);
            box-shadow: 0 30px 60px rgba(217, 106, 43, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .widget-card:active {
            transform: translateY(-8px) scale(1.01);
            transition: all 0.1s ease;
        }

        .widget-card:nth-child(1) { animation-delay: 0.1s; }
        .widget-card:nth-child(2) { animation-delay: 0.2s; }
        .widget-card:nth-child(3) { animation-delay: 0.3s; }
        .widget-card:nth-child(4) { animation-delay: 0.4s; }

        .gradient-bg {
            background: linear-gradient(135deg, #d96a2b 0%, #b4531f 50%, #8f3f16 100%);
            animation: glow 3s ease-in-out infinite;
        }

        .stat-badge {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            display: inline-block;
        }

        .float-animation {
            animation: float 4s ease-in-out infinite;
        }

        .icon-bounce {
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: inline-block;
        }

        .icon-bounce:hover {
            animation: bounce 0.6s ease-in-out;
            transform: scale(1.2) rotate(10deg);
        }

        .gradient-text {
            background: linear-gradient(135deg, #d96a2b, #f48a47, #d96a2b);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite;
        }

        .shimmer {
            background: linear-gradient(90deg, #d96a2b 0%, #f48a47 50%, #d96a2b 100%);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        .hover-lift {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            cursor: pointer;
        }

        .hover-lift::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(135deg, rgba(217, 106, 43, 0.1), rgba(244, 138, 71, 0.1));
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
            z-index: 1;
        }

        .hover-lift:hover::before {
            opacity: 1;
        }

        .hover-lift:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 15px 35px rgba(217, 106, 43, 0.2);
        }

        .hover-lift:active {
            transform: translateY(-2px) scale(0.98);
        }

        .hover-lift > * {
            position: relative;
            z-index: 2;
        }

        .icon-bounce:hover {
            animation: wiggle 0.5s ease-in-out;
        }

        .border-gradient {
            border: 2px solid transparent;
            background-clip: padding-box;
            position: relative;
        }

        .border-gradient::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #d96a2b, #f48a47, #d96a2b);
            background-size: 200% 200%;
            border-radius: inherit;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.4s ease;
            animation: shimmer 3s ease-in-out infinite;
            pointer-events: none;
        }

        .border-gradient:hover::before {
            opacity: 1;
        }

        .quick-nav-item {
            animation: scaleIn 0.5s ease-out both;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .quick-nav-item:nth-child(1) { animation-delay: 0.5s; }
        .quick-nav-item:nth-child(2) { animation-delay: 0.6s; }
        .quick-nav-item:nth-child(3) { animation-delay: 0.7s; }
        .quick-nav-item:nth-child(4) { animation-delay: 0.8s; }
        .quick-nav-item:nth-child(5) { animation-delay: 0.9s; }
        .quick-nav-item:nth-child(6) { animation-delay: 1.0s; }
        .quick-nav-item:nth-child(7) { animation-delay: 1.1s; }
        .quick-nav-item:nth-child(8) { animation-delay: 1.2s; }

        .workspace-card {
            animation: slideUp 0.8s ease-out 0.3s both;
        }

        .performance-card {
            animation: slideIn 0.8s ease-out 0.4s both;
        }
    </style>

     <?php $__env->slot('header', null, []); ?> 
        <div class="space-y-1">
            <h2 class="font-bold text-3xl text-white leading-tight uppercase tracking-wide drop-shadow-lg">
                Welcome back, <?php echo e(Auth::user()->business_name); ?>

            </h2>
            <p class="text-sm text-white/90">Here is an overview of your loan management system.</p>
        </div>
     <?php $__env->endSlot(); ?>

    <!-- Quick Navigation Widget - Below Navbar -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
            <div class="widget-card bg-white rounded-2xl border border-orange-100 shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 gradient-text">📍 Quick Navigation</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="<?php echo e(route('applications.index')); ?>" class="quick-nav-item group p-4 rounded-xl border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-all duration-300 hover-lift block">
                        <div class="relative z-10">
                            <div class="text-2xl mb-2 icon-bounce">📋</div>
                            <div class="text-sm font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Applications</div>
                            <div class="text-xs text-gray-500 mt-1">Review & approve</div>
                        </div>
                    </a>
                    <a href="<?php echo e(route('loans.index')); ?>" class="quick-nav-item group p-4 rounded-xl border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-all duration-300 hover-lift block">
                        <div class="relative z-10">
                            <div class="text-2xl mb-2 icon-bounce">💰</div>
                            <div class="text-sm font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Loans</div>
                            <div class="text-xs text-gray-500 mt-1">View & manage</div>
                        </div>
                    </a>
                    <a href="<?php echo e(route('loan-products.index')); ?>" class="quick-nav-item group p-4 rounded-xl border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-all duration-300 hover-lift block">
                        <div class="relative z-10">
                            <div class="text-2xl mb-2 icon-bounce">💼</div>
                            <div class="text-sm font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Products</div>
                            <div class="text-xs text-gray-500 mt-1">Create & manage</div>
                        </div>
                    </a>
                    <a href="<?php echo e(route('borrowers.index')); ?>" class="quick-nav-item group p-4 rounded-xl border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-all duration-300 hover-lift block">
                        <div class="relative z-10">
                            <div class="text-2xl mb-2 icon-bounce">👥</div>
                            <div class="text-sm font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Borrowers</div>
                            <div class="text-xs text-gray-500 mt-1">Manage customers</div>
                        </div>
                    </a>
                    <a href="<?php echo e(route('repayments.index')); ?>" class="quick-nav-item group p-4 rounded-xl border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-all duration-300 hover-lift block">
                        <div class="relative z-10">
                            <div class="text-2xl mb-2 icon-bounce">💳</div>
                            <div class="text-sm font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Repayments</div>
                            <div class="text-xs text-gray-500 mt-1">Track payments</div>
                        </div>
                    </a>
                    <a href="<?php echo e(route('disbursements.index')); ?>" class="quick-nav-item group p-4 rounded-xl border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-all duration-300 hover-lift block">
                        <div class="relative z-10">
                            <div class="text-2xl mb-2 icon-bounce">💸</div>
                            <div class="text-sm font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Disbursements</div>
                            <div class="text-xs text-gray-500 mt-1">Approve & disburse</div>
                        </div>
                    </a>
                    <a href="<?php echo e(route('credit-scores.index')); ?>" class="quick-nav-item group p-4 rounded-xl border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-all duration-300 hover-lift block">
                        <div class="relative z-10">
                            <div class="text-2xl mb-2 icon-bounce">⭐</div>
                            <div class="text-sm font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Credit Scores</div>
                            <div class="text-xs text-gray-500 mt-1">View & calculate</div>
                        </div>
                    </a>
                    <a href="<?php echo e(route('reports.index')); ?>" class="quick-nav-item group p-4 rounded-xl border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-all duration-300 hover-lift block">
                        <div class="relative z-10">
                            <div class="text-2xl mb-2 icon-bounce">📈</div>
                            <div class="text-sm font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Reports</div>
                            <div class="text-xs text-gray-500 mt-1">Analytics & data</div>
                        </div>
                    </a>
                </div>
                <div class="mt-4 p-3 bg-orange-50 border border-orange-200 rounded-lg shimmer">
                    <p class="text-xs text-gray-600">💡 <span class="font-medium">Tip:</span> Hover over any module to see a quick description</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-7">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <a href="<?php echo e(route('applications.index')); ?>" class="widget-card block bg-gradient-to-br from-amber-400 to-orange-400 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden border-gradient">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 float-animation"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-amber-100">Applications</p>
                                <p class="mt-3 text-4xl font-bold stat-badge"><?php echo e($stats['total_applications']); ?></p>
                                <p class="mt-2 text-sm font-medium text-amber-100"><?php echo e($stats['pending_applications']); ?> pending</p>
                            </div>
                            <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center icon-bounce">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="<?php echo e(route('loans.index')); ?>" class="widget-card block bg-gradient-to-br from-rose-400 to-pink-400 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden border-gradient">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 float-animation"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-orange-100">Active Loans</p>
                                <p class="mt-3 text-4xl font-bold stat-badge"><?php echo e($stats['active_loans']); ?></p>
                                <p class="mt-2 text-sm font-medium text-orange-100">UGX <?php echo e(number_format($stats['total_loan_amount'], 2)); ?></p>
                            </div>
                            <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center icon-bounce">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="<?php echo e(route('borrowers.index')); ?>" class="widget-card block bg-gradient-to-br from-teal-400 to-cyan-400 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden border-gradient">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 float-animation"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-blue-100">Borrowers</p>
                                <p class="mt-3 text-4xl font-bold stat-badge"><?php echo e($stats['total_borrowers']); ?></p>
                                <p class="mt-2 text-sm font-medium text-blue-100">Active accounts</p>
                            </div>
                            <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center icon-bounce">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="<?php echo e(route('repayments.index')); ?>" class="widget-card block bg-gradient-to-br from-green-400 to-emerald-400 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden border-gradient">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 float-animation"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-100">Repayments</p>
                                <p class="mt-3 text-4xl font-bold stat-badge">UGX <?php echo e(number_format($stats['total_repaid'], 2)); ?></p>
                                <p class="mt-2 text-sm font-medium text-emerald-100"><?php echo e($stats['overdue_repayments']); ?> overdue</p>
                            </div>
                            <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center icon-bounce">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 workspace-card widget-card rounded-2xl overflow-hidden shadow-xl bg-gradient-to-br from-orange-400 to-red-400 text-white p-8 relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="h-12 w-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold">UPTREND LMS Workspace</h3>
                        </div>
                        <p class="mt-3 text-orange-50 max-w-2xl text-lg">
                            Start your daily workflow quickly with shortcuts to core modules and monitor your institution performance in one place.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="<?php echo e(route('loans.index')); ?>" class="inline-flex items-center gap-2 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-sm px-5 py-3 text-sm font-semibold transition shadow-lg">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                View Loans
                            </a>
                            <a href="<?php echo e(route('applications.index')); ?>" class="inline-flex items-center gap-2 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-sm px-5 py-3 text-sm font-semibold transition shadow-lg">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Manage Applications
                            </a>
                            <a href="<?php echo e(route('repayments.index')); ?>" class="inline-flex items-center gap-2 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-sm px-5 py-3 text-sm font-semibold transition shadow-lg">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Track Repayments
                            </a>
                        </div>
                    </div>
                </div>

                <div class="performance-card widget-card bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/dashboard.blade.php ENDPATH**/ ?>