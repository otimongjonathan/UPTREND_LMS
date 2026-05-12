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
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Staff List</h2>
                <p class="mt-1 text-sm text-gray-600">Manage staff accounts and keep loan supervision ready for collateral capture.</p>
            </div>
            <a href="<?php echo e(route('staff.create')); ?>" class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                Add Staff
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-2xl bg-white border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Total Staff</p>
                    <p class="mt-3 text-4xl font-bold text-gray-900"><?php echo e($staffMembers->count()); ?></p>
                </div>
                <div class="rounded-2xl bg-white border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500">Loan Supervisors</p>
                    <p class="mt-3 text-4xl font-bold text-gray-900"><?php echo e($staffMembers->where('role', 'loan_supervisor')->count()); ?></p>
                </div>
                <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg p-6 text-white">
                    <p class="text-sm text-blue-100">Quick Action</p>
                    <p class="mt-3 text-xl font-semibold">Add a staff member before capturing collateral</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wide">
                                <tr>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Address</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Phone</th>
                                    <th class="px-4 py-3">Role</th>
                                    <th class="px-4 py-3">Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $staffMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($staff->name); ?></td>
                                        <td class="px-4 py-3 text-gray-700"><?php echo e($staff->address); ?></td>
                                        <td class="px-4 py-3 text-gray-700"><?php echo e($staff->email); ?></td>
                                        <td class="px-4 py-3 text-gray-700"><?php echo e($staff->tel_no); ?></td>
                                        <td class="px-4 py-3 text-gray-700"><?php echo e(ucwords(str_replace('_', ' ', $staff->role))); ?></td>
                                        <td class="px-4 py-3 text-gray-700"><?php echo e($staff->created_at?->format('M d, Y')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No staff records found.</td>
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
<?php endif; ?><?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/staff/index.blade.php ENDPATH**/ ?>