<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CUSTOMER LOGIN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff8f3',
                            100: '#ffeddc',
                            200: '#ffd3b5',
                            300: '#ffb27f',
                            400: '#f48a47',
                            500: '#d96a2b',
                            600: '#b4531f',
                            700: '#8f3f16',
                            800: '#742f12',
                            900: '#5a240e',
                        },
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-primary-100 min-h-screen flex items-center justify-center px-4 font-sans antialiased">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out both;
        }
    </style>
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-primary-100 p-8 animate-fade-in-up">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary-600">UPTREND LMS</p>
        <h1 class="text-2xl font-extrabold uppercase tracking-wide text-gray-900 mt-2">CUSTOMER LOGIN</h1>
        <p class="text-sm text-gray-600 mt-1">Sign in to access your customer portal.</p>

        <form method="POST" action="<?php echo e(route('customer.login.store', absolute: false)); ?>" class="mt-6 space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
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
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border border-gray-300 text-primary-600">
                Remember me
            </label>
            <button class="w-full py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition duration-300 transform hover:scale-[1.01] shadow-sm">Login</button>
        </form>

        <p class="text-sm text-gray-600 mt-5">
            Don't have an account?
            <a href="<?php echo e(route('customer.register', absolute: false)); ?>" class="font-semibold text-primary-700 hover:text-primary-800 transition">Register</a>
        </p>
    </div>
</body>
</html>

<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/customer/auth/login.blade.php ENDPATH**/ ?>