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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            💼 Loan Products
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <?php if(session('success')): ?>
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Available Loan Products</h3>
                        <a href="<?php echo e(route('loan-products.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            ➕ Create New Product
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3">Product Name</th>
                                    <th class="px-6 py-3">Provider Company</th>
                                    <th class="px-6 py-3">Amount Range</th>
                                    <th class="px-6 py-3">Term (Months)</th>
                                    <th class="px-6 py-3">Interest Rate</th>
                                    <th class="px-6 py-3">Processing Fee</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-3 font-semibold"><?php echo e($product->name); ?></td>
                                        <td class="px-6 py-3">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                <?php echo e($product->provider_company); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-3">
                                            UGX <?php echo e(number_format($product->min_amount, 0)); ?> - UGX <?php echo e(number_format($product->max_amount, 0)); ?>

                                        </td>
                                        <td class="px-6 py-3">
                                            <?php echo e($product->min_term); ?> - <?php echo e($product->max_term); ?>

                                        </td>
                                        <td class="px-6 py-3"><?php echo e(number_format($product->interest_rate, 2)); ?>%</td>
                                        <td class="px-6 py-3"><?php echo e(number_format($product->processing_fee_percent, 2)); ?>%</td>
                                        <td class="px-6 py-3">
                                            <form action="<?php echo e(route('loan-products.toggle', $product)); ?>" method="POST" style="display:inline;">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="px-3 py-1 rounded-full text-white text-xs font-bold cursor-pointer
                                                    <?php if($product->is_active): ?> bg-green-500 hover:bg-green-600
                                                    <?php else: ?> bg-gray-400 hover:bg-gray-500
                                                    <?php endif; ?>
                                                ">
                                                    <?php echo e($product->is_active ? '✓ Active' : '✗ Inactive'); ?>

                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-3 flex gap-2">
                                            <a href="<?php echo e(route('loan-products.edit', $product)); ?>" class="text-blue-600 hover:underline text-xs">Edit</a>
                                            <form action="<?php echo e(route('loan-products.destroy', $product)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:underline text-xs">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="px-6 py-3 text-center text-gray-500">
                                            No loan products created yet. <a href="<?php echo e(route('loan-products.create')); ?>" class="text-blue-600">Create one now</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <?php echo e($products->links()); ?>

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
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/loan-products/index.blade.php ENDPATH**/ ?>