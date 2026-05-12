

<?php $__env->startSection('content'); ?>
    <div class="mb-6 bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl p-6 text-white shadow-lg card-hover">
        <h1 class="text-2xl font-extrabold uppercase tracking-wide">MY PROFILE</h1>
        <p class="text-primary-100 mt-1">Review your account and business information.</p>
    </div>

    <div class="bg-white border border-primary-100 rounded-2xl p-6 shadow-sm card-hover">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold uppercase tracking-wide text-gray-900">MY PROFILE</h1>
                <p class="text-sm text-gray-600 mt-1">Your customer account details.</p>
            </div>
            <a href="#edit-profile" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-primary-600 text-white text-sm font-bold uppercase tracking-wide hover:bg-primary-700 transition duration-300 transform hover:scale-[1.02]">
                Edit Profile
            </a>
        </div>

        <?php if(session('status')): ?>
            <div class="mt-4 rounded-lg bg-green-50 text-green-700 px-4 py-3 text-sm border border-green-100">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Full Name</p>
                <p class="font-semibold text-gray-900"><?php echo e($user->name); ?></p>
            </div>
            <div>
                <p class="text-gray-500">Email</p>
                <p class="font-semibold text-gray-900"><?php echo e($user->email); ?></p>
            </div>
            <div>
                <p class="text-gray-500">Business Name</p>
                <p class="font-semibold text-gray-900"><?php echo e($user->business_name); ?></p>
            </div>
            <div>
                <p class="text-gray-500">Telephone</p>
                <p class="font-semibold text-gray-900"><?php echo e($user->tel_no); ?></p>
            </div>
            <div class="md:col-span-2">
                <p class="text-gray-500">Address</p>
                <p class="font-semibold text-gray-900"><?php echo e($user->address); ?></p>
            </div>
        </div>
    </div>

    <div id="edit-profile" class="mt-6 bg-white border border-primary-100 rounded-2xl p-6 shadow-sm card-hover">
        <h2 class="text-xl font-extrabold uppercase tracking-wide text-gray-900">EDIT PROFILE</h2>
        <p class="text-sm text-gray-600 mt-1">Update your details below and save changes.</p>

        <form method="POST" action="<?php echo e(route('customer.profile.update', absolute: false)); ?>" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input id="name" type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="business_name" class="block text-sm font-medium text-gray-700">Business Name</label>
                <input id="business_name" type="text" name="business_name" value="<?php echo e(old('business_name', $user->business_name)); ?>" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <?php $__errorArgs = ['business_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="tel_no" class="block text-sm font-medium text-gray-700">Telephone</label>
                <input id="tel_no" type="text" name="tel_no" value="<?php echo e(old('tel_no', $user->tel_no)); ?>" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <?php $__errorArgs = ['tel_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea id="address" name="address" rows="3" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500"><?php echo e(old('address', $user->address)); ?></textarea>
                <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="w-full md:w-auto px-6 py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition duration-300 transform hover:scale-[1.01]">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('customer.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/customer/profile/index.blade.php ENDPATH**/ ?>