<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS CDN -->
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

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-primary-50 via-white to-primary-100 min-h-screen">
        <style>
            @keyframes slideDownHeader {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .header-shadow {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            }
            header {
                animation: slideDownHeader 0.4s ease-out;
            }
        </style>
        <div class="min-h-screen pt-16 flex flex-col">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-gradient-to-r from-white to-primary-50 header-shadow border-b border-gray-100">
                    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main class="flex-1">
                <?php echo e($slot); ?>

            </main>

            <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </body>
</html>
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/layouts/app.blade.php ENDPATH**/ ?>