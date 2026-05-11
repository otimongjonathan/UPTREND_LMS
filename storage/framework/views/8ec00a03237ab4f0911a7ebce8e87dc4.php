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
        <h2 class="font-bold text-2xl text-gray-800 leading-tight uppercase tracking-wide">
            <?php echo e(__('Applications')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- PENDING Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900">⏳ Pending Applications (<?php echo e($statusCounts['pending']); ?>)</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500 border-b">
                                <tr>
                                    <th class="py-2 pe-4">Date</th>
                                    <th class="py-2 pe-4">Applicant</th>
                                    <th class="py-2 pe-4">Loan Type</th>
                                    <th class="py-2 pe-4">Amount (UGX)</th>
                                    <th class="py-2 pe-4">Repayment</th>
                                    <th class="py-2 pe-4">Status</th>
                                    <th class="py-2 pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $applications->where('status', 'pending'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pe-4"><?php echo e($application->created_at->format('M d, Y H:i')); ?></td>
                                        <td class="py-3 pe-4"><?php echo e($application->applicant_full_name ?? $application->user?->name ?? 'N/A'); ?></td>
                                        <td class="py-3 pe-4"><?php echo e(str($application->loan_type ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="py-3 pe-4"><?php echo e(number_format($application->amount, 2)); ?></td>
                                        <td class="py-3 pe-4"><?php echo e(str($application->repayment_schedule ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="py-3 pe-4">
                                            <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700 uppercase">
                                                <?php echo e($application->status); ?>

                                            </span>
                                        </td>
                                        <td class="py-3 pe-4 flex gap-2">
                                            <a href="<?php echo e(route('applications.show', $application->id)); ?>" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 font-semibold">
                                                👁️ View Details
                                            </a>
                                            <form action="<?php echo e(route('applications.approve', $application->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">✓ Approve</button>
                                            </form>
                                            <form action="<?php echo e(route('applications.reject', $application->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">✗ Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">No pending applications.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- APPROVED Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900">✅ Approved Applications (<?php echo e($statusCounts['approved']); ?>)</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500 border-b">
                                <tr>
                                    <th class="py-2 pe-4">Date</th>
                                    <th class="py-2 pe-4">Applicant</th>
                                    <th class="py-2 pe-4">Loan Type</th>
                                    <th class="py-2 pe-4">Amount (UGX)</th>
                                    <th class="py-2 pe-4">Repayment</th>
                                    <th class="py-2 pe-4">Status</th>
                                    <th class="py-2 pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $applications->where('status', 'approved'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pe-4"><?php echo e($application->created_at->format('M d, Y H:i')); ?></td>
                                        <td class="py-3 pe-4"><?php echo e($application->applicant_full_name ?? $application->user?->name ?? 'N/A'); ?></td>
                                        <td class="py-3 pe-4"><?php echo e(str($application->loan_type ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="py-3 pe-4"><?php echo e(number_format($application->amount, 2)); ?></td>
                                        <td class="py-3 pe-4"><?php echo e(str($application->repayment_schedule ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="py-3 pe-4">
                                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700 uppercase">
                                                <?php echo e($application->status); ?>

                                            </span>
                                        </td>
                                        <td class="py-3 pe-4">
                                            <a href="<?php echo e(route('applications.show', $application->id)); ?>" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 font-semibold">
                                                👁️ View Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">No approved applications.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- REJECTED Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900">❌ Rejected Applications (<?php echo e($statusCounts['rejected']); ?>)</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500 border-b">
                                <tr>
                                    <th class="py-2 pe-4">Date</th>
                                    <th class="py-2 pe-4">Applicant</th>
                                    <th class="py-2 pe-4">Loan Type</th>
                                    <th class="py-2 pe-4">Amount (UGX)</th>
                                    <th class="py-2 pe-4">Repayment</th>
                                    <th class="py-2 pe-4">Status</th>
                                    <th class="py-2 pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $applications->where('status', 'rejected'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pe-4"><?php echo e($application->created_at->format('M d, Y H:i')); ?></td>
                                        <td class="py-3 pe-4"><?php echo e($application->applicant_full_name ?? $application->user?->name ?? 'N/A'); ?></td>
                                        <td class="py-3 pe-4"><?php echo e(str($application->loan_type ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="py-3 pe-4"><?php echo e(number_format($application->amount, 2)); ?></td>
                                        <td class="py-3 pe-4"><?php echo e(str($application->repayment_schedule ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="py-3 pe-4">
                                            <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700 uppercase">
                                                <?php echo e($application->status); ?>

                                            </span>
                                        </td>
                                        <td class="py-3 pe-4">
                                            <a href="<?php echo e(route('applications.show', $application->id)); ?>" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 font-semibold">
                                                👁️ View Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">No rejected applications.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
<?php endif; ?><?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/applications/index.blade.php ENDPATH**/ ?>