<x-guest-layout>
    <style>
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-enter {
            animation: slideUp 0.5s ease-out;
        }

        .soft-input {
            transition: all 0.2s ease;
        }

        .soft-input:focus {
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
            border-color: rgb(37 99 235);
        }
    </style>

    <div class="max-w-xl mx-auto auth-enter">
        <div class="text-center mb-8">
            <p class="inline-flex items-center rounded-full bg-primary-50 text-primary-700 px-3 py-1 text-xs font-semibold tracking-wide uppercase">
                Secure Access
            </p>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">Welcome back</h1>
            <p class="mt-2 text-sm text-gray-600">Sign in to continue managing your loans and operations.</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white shadow-xl shadow-blue-100/40 p-7 sm:p-8">
            <x-auth-session-status class="mb-5" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="soft-input block mt-2 w-full rounded-xl border-gray-200" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <x-input-label for="password" :value="__('Password')" />
                        @if (Route::has('password.request'))
                            <a class="text-sm text-primary-600 hover:text-primary-700 font-medium" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <x-text-input id="password" class="soft-input block mt-2 w-full rounded-xl border-gray-200"
                        type="password"
                        name="password"
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <label for="remember_me" class="inline-flex items-center gap-2">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500" name="remember">
                    <span class="text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                <div class="pt-2">
                    <x-primary-button class="w-full justify-center rounded-xl py-3 text-sm font-semibold tracking-wide uppercase">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <p class="mt-5 text-center text-sm text-gray-600">
            New to UPTREND LMS?
            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">Create an account</a>
        </p>
    </div>
</x-guest-layout>
