<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CUSTOMER REGISTRATION</title>
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
<body class="bg-gradient-to-br from-primary-50 via-white to-primary-100 min-h-screen py-10 px-4 font-sans antialiased">
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out both;
        }
    </style>
    <div class="w-full max-w-2xl mx-auto bg-white rounded-2xl shadow-xl border border-primary-100 p-8 animate-fade-in-up">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary-600">UPTREND LMS</p>
        <h1 class="text-2xl font-extrabold uppercase tracking-wide text-gray-900 mt-2">CUSTOMER REGISTRATION</h1>
        <p class="text-sm text-gray-600 mt-1">Create your account to apply for loans online.</p>

        <form method="POST" action="{{ route('customer.register.store', absolute: false) }}" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Business Name</label>
                <input type="text" name="business_name" value="{{ old('business_name') }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                @error('business_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Telephone</label>
                <input type="text" name="tel_no" value="{{ old('tel_no') }}" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                @error('tel_no') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <textarea name="address" rows="3" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('address') }}</textarea>
                @error('address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="mt-1 w-full rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
            <div class="md:col-span-2">
                <button class="w-full py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition duration-300 transform hover:scale-[1.01]">Create Customer Account</button>
            </div>
        </form>

        <p class="text-sm text-gray-600 mt-5">
            Already registered?
            <a href="{{ route('customer.login', absolute: false) }}" class="font-semibold text-primary-700 hover:text-primary-800 transition">Login</a>
        </p>
    </div>
</body>
</html>

