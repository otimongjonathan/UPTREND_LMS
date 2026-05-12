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
            <?php echo e(__('View Profile')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('Business Name')); ?></label>
                        <div class="font-medium text-base text-gray-800"><?php echo e($user->business_name); ?></div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('Contact Person')); ?></label>
                        <div class="font-medium text-base text-gray-800"><?php echo e($user->name); ?></div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('Email')); ?></label>
                        <div class="font-medium text-base text-gray-800"><?php echo e($user->email); ?></div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('Phone Number')); ?></label>
                        <div class="font-medium text-base text-gray-800"><?php echo e($user->tel_no); ?></div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('Address')); ?></label>
                        <div class="font-medium text-base text-gray-800"><?php echo e($user->address); ?></div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('Member Since')); ?></label>
                        <div class="font-medium text-base text-gray-800"><?php echo e($user->created_at->format('M d, Y')); ?></div>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold">
                            <?php echo e(__('Edit Profile')); ?>

                        </a>

                        <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center px-4 py-2 bg-primary-50 text-primary-700 rounded-lg hover:bg-primary-100 transition font-semibold">
                            <?php echo e(__('Back to Dashboard')); ?>

                        </a>
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
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/profile/show.blade.php ENDPATH**/ ?>