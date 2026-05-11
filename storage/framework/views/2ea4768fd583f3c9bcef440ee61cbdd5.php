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
            <h2 class="font-bold text-2xl text-gray-800 leading-tight uppercase tracking-wide">
                <?php echo e($borrower->name); ?>

            </h2>
            <a href="<?php echo e(route('borrowers.index')); ?>" class="px-4 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                ← Back
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Borrower Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900 mb-6">Borrower Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Full Name</p>
                            <p class="text-lg font-semibold"><?php echo e($borrower->name); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-lg font-semibold"><?php echo e($borrower->email); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Business Name</p>
                            <p class="text-lg font-semibold"><?php echo e($borrower->business_name ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="text-lg font-semibold"><?php echo e($borrower->tel_no ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Address</p>
                            <p class="text-lg font-semibold"><?php echo e($borrower->address ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Applications & History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900 mb-6">Application & Loan History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-500 border-b bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 font-semibold">Application ID</th>
                                    <th class="py-3 px-4 font-semibold">Loan Type</th>
                                    <th class="py-3 px-4 font-semibold">Amount (UGX)</th>
                                    <th class="py-3 px-4 font-semibold">Term (Months)</th>
                                    <th class="py-3 px-4 font-semibold">Applied Date</th>
                                    <th class="py-3 px-4 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                        <td class="py-4 px-4"><?php echo e($loan->id); ?></td>
                                        <td class="py-4 px-4"><?php echo e(str($loan->loan_type ?? 'N/A')->replace('_', ' ')->title()); ?></td>
                                        <td class="py-4 px-4"><?php echo e(number_format($loan->amount, 2)); ?></td>
                                        <td class="py-4 px-4"><?php echo e($loan->term_months ?? 'N/A'); ?></td>
                                        <td class="py-4 px-4"><?php echo e($loan->created_at->format('M d, Y')); ?></td>
                                        <td class="py-4 px-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                                <?php if($loan->status === 'pending'): ?>
                                                    bg-yellow-100 text-yellow-700
                                                <?php elseif($loan->status === 'approved'): ?>
                                                    bg-blue-100 text-blue-700
                                                <?php elseif($loan->status === 'issued'): ?>
                                                    bg-green-100 text-green-700
                                                <?php elseif($loan->status === 'rejected'): ?>
                                                    bg-red-100 text-red-700
                                                <?php endif; ?>
                                            ">
                                                <?php echo e(ucfirst($loan->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-gray-500">No applications found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Loan Details Summary -->
            <?php
                $totalLoans = $loans->whereIn('status', ['approved', 'issued'])->count();
                $totalAmount = $loans->whereIn('status', ['approved', 'issued'])->sum('amount');
            ?>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold uppercase tracking-wide text-gray-900 mb-6">Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="text-sm text-gray-600">Total Active Loans</p>
                            <p class="text-3xl font-bold text-blue-600"><?php echo e($totalLoans); ?></p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4">
                            <p class="text-sm text-gray-600">Total Amount (UGX)</p>
                            <p class="text-3xl font-bold text-green-600"><?php echo e(number_format($totalAmount, 0)); ?></p>
                        </div>
                        <div class="border-l-4 border-gray-500 pl-4">
                            <p class="text-sm text-gray-600">All Applications</p>
                            <p class="text-3xl font-bold text-gray-600"><?php echo e($loans->count()); ?></p>
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
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/borrowers/show.blade.php ENDPATH**/ ?>