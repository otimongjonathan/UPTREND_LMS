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
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-primary-50 via-white to-primary-100">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-7xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border-l-4 border-primary-500">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
