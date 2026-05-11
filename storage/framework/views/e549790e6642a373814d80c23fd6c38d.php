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
        .content-animate {
            animation: slideUp 0.6s ease-out;
        }
        .table-row {
            transition: all 0.3s ease;
        }
        .table-row:hover {
            background-color: #f0f9ff;
            box-shadow: inset 0 0 10px rgba(37, 99, 235, 0.1);
        }
    </style>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-bold text-3xl text-gray-800 leading-tight uppercase tracking-wide">
            💰 <?php echo e(__('Loans')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- PENDING ISSUE Section -->
            <div class="content-animate bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-wide mb-6">⏳ Pending Issue (<?php echo e($pendingIssue); ?>)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-b-2 border-yellow-300">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Borrower</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Loan Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-yellow-800 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $loans->where('status', 'approved'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($loan->id); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($loan->applicant_full_name ?? $loan->user?->name ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900">UGX <?php echo e(number_format($loan->amount, 2)); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e(str($loan->loan_type ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 uppercase">
                                                Approved
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex gap-2">
                                                <a href="<?php echo e(route('applications.show', $loan->id)); ?>" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                                    View
                                                </a>
                                                <form action="<?php echo e(route('loans.issue', $loan->id)); ?>" method="POST" class="inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition">Issue</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No pending issues.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ISSUED Section -->
            <div class="content-animate bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-wide mb-6">✅ Issued (<?php echo e($active); ?>)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-green-50 to-green-100 border-b-2 border-green-300">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Borrower</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Loan Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Issued Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-green-800 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $loans->where('status', 'active'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($loan->id); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($loan->applicant_full_name ?? $loan->user?->name ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900">UGX <?php echo e(number_format($loan->amount, 2)); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e(str($loan->loan_type ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($loan->disbursement_date?->format('M d, Y') ?? $loan->updated_at?->format('M d, Y') ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 uppercase">
                                                Active
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex gap-2">
                                                <a href="<?php echo e(route('loans.show', $loan->id)); ?>" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                                    View
                                                </a>
                                                <a href="<?php echo e(route('loans.show', $loan->id)); ?>" class="px-3 py-1 bg-purple-500 text-white text-xs rounded hover:bg-purple-600 transition">
                                                    Details
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No active loans.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- COMPLETED Section -->
            <div class="content-animate bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-wide mb-6">✔️ Completed (<?php echo e($completed); ?>)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-blue-50 to-blue-100 border-b-2 border-blue-300">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Borrower</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Loan Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Completed Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $loans->where('status', 'completed'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($loan->id); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($loan->applicant_full_name ?? $loan->user?->name ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900">UGX <?php echo e(number_format($loan->amount, 2)); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e(str($loan->loan_type ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($loan->completed_at?->format('M d, Y') ?? $loan->updated_at?->format('M d, Y') ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 uppercase">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="<?php echo e(route('loans.show', $loan->id)); ?>" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No completed loans.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- OVERDUE Section -->
            <div class="content-animate bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 uppercase tracking-wide mb-6">🚨 Overdue (<?php echo e($overdue); ?>)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-red-50 to-red-100 border-b-2 border-red-300">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Loan ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Borrower</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Loan Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Outstanding Balance</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-red-800 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $loans->where('status', 'overdue'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($loan->id); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e($loan->applicant_full_name ?? $loan->user?->name ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900">UGX <?php echo e(number_format($loan->amount, 2)); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo e(str($loan->loan_type ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900">UGX <?php echo e(number_format($loan->outstanding_balance ?? 0, 2)); ?></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 uppercase">
                                                Overdue
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex gap-2">
                                                <a href="<?php echo e(route('loans.show', $loan->id)); ?>" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition">
                                                    View
                                                </a>
                                                <a href="<?php echo e(route('loans.show', $loan->id)); ?>" class="px-3 py-1 bg-orange-500 text-white text-xs rounded hover:bg-orange-600 transition">
                                                    Details
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No overdue loans.</td>
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
<?php endif; ?><?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/loans/index.blade.php ENDPATH**/ ?>