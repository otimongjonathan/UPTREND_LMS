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
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 Credit Scores
            </h2>
            <div class="text-sm text-gray-600">
                Total: <?php echo e($creditScores->total()); ?> customers
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Risk Distribution Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                    <p class="text-green-600 text-xs font-semibold uppercase tracking-wide">Low Risk</p>
                    <p class="text-2xl font-bold text-green-700"><?php echo e($creditScores->where('risk_level', 'low')->count()); ?></p>
                </div>
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg border border-yellow-200">
                    <p class="text-yellow-600 text-xs font-semibold uppercase tracking-wide">Medium Risk</p>
                    <p class="text-2xl font-bold text-yellow-700"><?php echo e($creditScores->where('risk_level', 'medium')->count()); ?></p>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200">
                    <p class="text-orange-600 text-xs font-semibold uppercase tracking-wide">High Risk</p>
                    <p class="text-2xl font-bold text-orange-700"><?php echo e($creditScores->where('risk_level', 'high')->count()); ?></p>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-lg border border-red-200">
                    <p class="text-red-600 text-xs font-semibold uppercase tracking-wide">Critical Risk</p>
                    <p class="text-2xl font-bold text-red-700"><?php echo e($creditScores->where('risk_level', 'critical')->count()); ?></p>
                </div>
            </div>

            <!-- Filters & Actions Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex flex-col md:flex-row gap-4 mb-6 justify-between items-start md:items-end">
                    <div class="flex gap-3 flex-1">
                        <button onclick="event.preventDefault(); document.getElementById('recalc-form').submit();" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-semibold text-sm flex items-center gap-2">
                            🔄 Recalculate All
                        </button>
                        <form id="recalc-form" action="<?php echo e(route('credit-scores.recalculate-all')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                        <a href="<?php echo e(route('credit-scores.export')); ?>" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold text-sm flex items-center gap-2">
                            📥 Export CSV
                        </a>
                    </div>
                </div>

                <form method="GET" action="<?php echo e(route('credit-scores.index')); ?>" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <!-- Search by Name/Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" placeholder="Name or email..." 
                                value="<?php echo e(request('search')); ?>" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Risk Level Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Risk Level</label>
                            <select name="risk_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Levels</option>
                                <option value="low" <?php echo e(request('risk_level') === 'low' ? 'selected' : ''); ?>>Low</option>
                                <option value="medium" <?php echo e(request('risk_level') === 'medium' ? 'selected' : ''); ?>>Medium</option>
                                <option value="high" <?php echo e(request('risk_level') === 'high' ? 'selected' : ''); ?>>High</option>
                                <option value="critical" <?php echo e(request('risk_level') === 'critical' ? 'selected' : ''); ?>>Critical</option>
                            </select>
                        </div>

                        <!-- Score Range Min -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Score Min</label>
                            <input type="number" name="score_min" placeholder="0" 
                                value="<?php echo e(request('score_min')); ?>" min="0" max="1000"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Score Range Max -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Score Max</label>
                            <input type="number" name="score_max" placeholder="1000" 
                                value="<?php echo e(request('score_max')); ?>" min="0" max="1000"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                                🔍 Filter
                            </button>
                            <a href="<?php echo e(route('credit-scores.index')); ?>" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition font-medium text-center">
                                ↺ Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Credit Scores Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-purple-600 to-purple-700 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold">Name</th>
                                <th class="px-6 py-4 text-left font-semibold">Email</th>
                                <th class="px-6 py-4 text-left font-semibold">Score</th>
                                <th class="px-6 py-4 text-left font-semibold">Risk Level</th>
                                <th class="px-6 py-4 text-left font-semibold">Loans</th>
                                <th class="px-6 py-4 text-left font-semibold">Default %</th>
                                <th class="px-6 py-4 text-left font-semibold">On-Time %</th>
                                <th class="px-6 py-4 text-left font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php $__empty_1 = true; $__currentLoopData = $creditScores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-purple-50 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-900"><?php echo e($score->user->name); ?></td>
                                    <td class="px-6 py-4 text-gray-700"><?php echo e($score->user->email); ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 text-white flex items-center justify-center font-bold">
                                                <?php echo e($score->score); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-white text-xs font-bold
                                            <?php if($score->risk_level === 'low'): ?> bg-green-500
                                            <?php elseif($score->risk_level === 'medium'): ?> bg-yellow-500
                                            <?php elseif($score->risk_level === 'high'): ?> bg-orange-500
                                            <?php else: ?> bg-red-500
                                            <?php endif; ?>
                                        ">
                                            <?php echo e(ucfirst($score->risk_level)); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900"><?php echo e($score->completed_loans); ?></td>
                                    <td class="px-6 py-4 text-red-600 font-semibold"><?php echo e(number_format($score->default_rate, 1)); ?>%</td>
                                    <td class="px-6 py-4 text-green-600 font-semibold"><?php echo e(number_format($score->on_time_payment_rate, 1)); ?>%</td>
                                    <td class="px-6 py-4">
                                        <a href="<?php echo e(route('credit-scores.show', $score)); ?>" class="bg-purple-600 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-purple-700 transition inline-block">
                                            👁 View Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <p class="text-lg font-semibold mb-2">📭 No credit scores found</p>
                                            <p class="text-sm">No records match your current filters</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <?php echo e($creditScores->links()); ?>

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
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/credit-scores/index.blade.php ENDPATH**/ ?>