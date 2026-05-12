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
            <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
                💳 <?php echo e(__('Loan Repayments')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <h3 class="text-gray-600 text-sm font-semibold">Total Due</h3>
                    <p class="text-2xl font-bold text-blue-600 mt-2"><?php echo e(number_format($summary['total_due'], 2)); ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <h3 class="text-gray-600 text-sm font-semibold">Total Paid</h3>
                    <p class="text-2xl font-bold text-green-600 mt-2"><?php echo e(number_format($summary['total_paid'], 2)); ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <h3 class="text-gray-600 text-sm font-semibold">Overdue</h3>
                    <p class="text-2xl font-bold text-red-600 mt-2"><?php echo e($summary['overdue']); ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <h3 class="text-gray-600 text-sm font-semibold">Pending</h3>
                    <p class="text-2xl font-bold text-yellow-600 mt-2"><?php echo e($summary['pending']); ?></p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <form method="GET" action="<?php echo e(route('repayments.index')); ?>" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="loan_id" class="block text-sm font-medium text-gray-700">Loan ID</label>
                        <input type="number" name="loan_id" id="loan_id" value="<?php echo e(request('loan_id')); ?>" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                               placeholder="Enter loan ID">
                    </div>
                    <div class="flex items-end col-span-2">
                        <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition">
                            Filter
                        </button>
                        <a href="<?php echo e(route('repayments.index')); ?>" class="ml-2 px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Repayment Schedules Table -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="px-6 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">All Repayment Schedules</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Loan ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Borrower</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Total Repayable</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Total Paid</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Outstanding</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Installments</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 <?php echo e($schedule->installments_overdue > 0 ? 'bg-red-50' : ''); ?>">
                                <td class="px-6 py-4 text-sm text-gray-900">#<?php echo e($schedule->loan_application_id); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($schedule->loanApplication?->applicant_full_name ?? $schedule->loanApplication?->user?->name ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">UGX <?php echo e(number_format($schedule->total_repayable, 2)); ?></td>
                                <td class="px-6 py-4 text-sm font-semibold text-green-600">UGX <?php echo e(number_format($schedule->total_paid, 2)); ?></td>
                                <td class="px-6 py-4 text-sm font-semibold text-orange-600">UGX <?php echo e(number_format($schedule->total_outstanding, 2)); ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2 text-xs">
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded font-semibold"><?php echo e($schedule->installments_paid); ?> Paid</span>
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded font-semibold"><?php echo e($schedule->installments_pending); ?> Pending</span>
                                        <?php if($schedule->installments_overdue > 0): ?>
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded font-semibold"><?php echo e($schedule->installments_overdue); ?> Overdue</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <?php if($schedule->installments_overdue > 0): ?>
                                        <span class="px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-700">Overdue</span>
                                    <?php elseif($schedule->installments_pending > 0): ?>
                                        <span class="px-2 py-1 rounded text-xs font-bold bg-yellow-100 text-yellow-700">Active</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-700">Completed</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="<?php echo e(route('schedules.show', $schedule->id)); ?>" class="text-blue-600 hover:text-blue-900 font-semibold">View Details</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No repayment schedules found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    <?php echo e($schedules->links()); ?>

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
<?php endif; ?><?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/repayments/index.blade.php ENDPATH**/ ?>