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

        <style>
            /* Scroll Animations */
            .scroll-animate {
                opacity: 0;
                transform: translateY(30px);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
                transition-delay: calc(var(--animation-order) * 0.05s);
            }

            .scroll-visible {
                opacity: 1;
                transform: translateY(0);
            }

            /* Remove extra space below footer */
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
        </style>
        <script src="<?php echo e(asset('js/scroll-animations.js')); ?>" defer></script>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-orange-50 via-white to-orange-100">
        <div class="flex flex-col pt-6 sm:pt-0 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-7xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border-l-4 border-primary-500">
                <?php echo e($slot); ?>

            </div>

            <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </body>
</html>
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/layouts/guest.blade.php ENDPATH**/ ?>