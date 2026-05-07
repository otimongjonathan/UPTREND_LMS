<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
        @vite(['resources/js/app.js'])
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
        <div class="min-h-screen pt-16">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gradient-to-r from-white to-primary-50 header-shadow border-b border-gray-100">
                    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
