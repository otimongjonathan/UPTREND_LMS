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
        <script src="<?php echo e(asset('js/scroll-animations.js')); ?>" defer></script>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-orange-50 via-white to-orange-100">
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

            /* Force uniform header styling */
            header h2, header h1, header h3 {
                color: white !important;
                font-size: 1.875rem !important; /* text-3xl */
                font-weight: 700 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            }

            header p {
                color: rgba(255, 255, 255, 0.9) !important;
            }

            /* Subtle Page Loading Bar */
            #page-loader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 3px;
                background: rgba(217, 106, 43, 0.1);
                z-index: 9999;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            #page-loader.active {
                opacity: 1;
            }

            #page-loader::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 0;
                background: linear-gradient(90deg, 
                    rgba(217, 106, 43, 0.3), 
                    rgba(217, 106, 43, 0.5), 
                    rgba(217, 106, 43, 0.3)
                );
                animation: loadingBar 1.5s ease-in-out infinite;
            }

            #page-loader.active::before {
                animation: loadingBar 1.5s ease-in-out infinite;
            }

            @keyframes loadingBar {
                0% {
                    width: 0;
                    left: 0;
                }
                50% {
                    width: 70%;
                    left: 15%;
                }
                100% {
                    width: 0;
                    left: 100%;
                }
            }

            /* Subtle backdrop blur */
            #page-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(2px);
                z-index: 9998;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }

            #page-backdrop.active {
                opacity: 1;
                pointer-events: all;
            }

            /* Page fade-in animation */
            @keyframes pageContentFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Fill body exactly — fixed 100vh/100dvh can leave a strip above the taskbar on some setups */
            .page-content {
                animation: pageContentFadeIn 0.5s ease-out;
                flex: 1 1 auto;
                min-height: 0;
            }

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

            /* Fill viewport; scroll only inside <main> — avoids extra document scroll */
            /* Same horizontal blend as layouts/footer: from-gray-900 via-gray-800 to-gray-900 */
            html {
                background-color: #111827;
                background-image: linear-gradient(to right, #111827, #1f2937, #111827);
            }
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }
            body {
                display: flex;
                flex-direction: column;
                min-height: 100dvh;
                background-color: transparent;
            }
            /* Cover gaps inside the scrollport below the footer (flex / subpixel) */
            #app-main-scroll {
                overflow-x: hidden;
            }
            #app-main-scroll .app-main-stack > footer {
                margin-bottom: 0;
                position: relative;
            }
            /* Short strip only — a tall ::after was still in the scroll overflow and felt like a huge footer */
            #app-main-scroll .app-main-stack > footer::after {
                content: '';
                position: absolute;
                left: 0;
                right: 0;
                top: 100%;
                height: 3rem;
                background-image: linear-gradient(to right, #111827, #1f2937, #111827);
                pointer-events: none;
            }
        </style>

        <!-- Subtle Page Loading Indicator -->
        <div id="page-loader"></div>
        <div id="page-backdrop"></div>
        
        <div class="page-content flex min-h-0 overflow-hidden">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- Main Content Area -->
            <div class="flex min-h-0 min-w-0 flex-1 flex-col overflow-hidden">
                <!-- Page Header -->
                <?php if(isset($header)): ?>
                    <header class="bg-gradient-to-r from-primary-500 via-primary-600 to-primary-700 header-shadow border-b border-primary-800 mt-16 md:mt-0">
                        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                            <?php echo e($header); ?>

                        </div>
                    </header>
                <?php endif; ?>

                <!-- Footer scrolls with content; app-main-stack + flex fills short pages; footer shadow masks any residual gap -->
                <main id="app-main-scroll" class="min-h-0 flex-1 overflow-y-auto overscroll-y-contain bg-gradient-to-br from-orange-50 via-white to-orange-100">
                    <div class="app-main-stack flex min-h-full w-full flex-col">
                        <div class="min-w-0 flex-[1_0_auto]">
                            <?php echo e($slot); ?>

                        </div>
                        <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </main>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loader = document.getElementById('page-loader');
                const backdrop = document.getElementById('page-backdrop');
                const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="mailto:"]):not([href^="tel:"])');
                const forms = document.querySelectorAll('form');

                function showLoader() {
                    loader.classList.add('active');
                    backdrop.classList.add('active');
                }

                function hideLoader() {
                    loader.classList.remove('active');
                    backdrop.classList.remove('active');
                }

                // Show loader on link click
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        const href = this.getAttribute('href');
                        if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                            if (!this.closest('form') && href !== window.location.href) {
                                showLoader();
                            }
                        }
                    });
                });

                // Show loader on form submit
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        if (!loader.classList.contains('active')) {
                            showLoader();
                        }
                    });
                });

                // Hide loader when page is fully loaded
                window.addEventListener('load', hideLoader);

                // Hide loader on page show (back/forward navigation)
                window.addEventListener('pageshow', function(event) {
                    if (event.persisted) {
                        hideLoader();
                    }
                });

                // Fallback: hide loader after 5 seconds
                setTimeout(hideLoader, 5000);
            });
        </script>
    </body>
</html>
<?php /**PATH D:\xampp\htdocs\UPTREND LMS\resources\views/layouts/app.blade.php ENDPATH**/ ?>