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
    <div class="max-w-3xl mx-auto auth-enter">
        <div class="text-center mb-8">
            <p class="inline-flex items-center rounded-full bg-primary-50 text-primary-700 px-3 py-1 text-xs font-semibold tracking-wide uppercase">
                Create Account
            </p>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">Build your UPTREND LMS profile</h1>
            <p class="mt-2 text-sm text-gray-600">Complete the form below to register your institution account.</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white shadow-xl shadow-blue-100/40 p-7 sm:p-8">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" />
                        <x-text-input id="name" class="soft-input block mt-2 w-full rounded-xl border-gray-200" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="business_name" :value="__('Business Name')" />
                        <x-text-input id="business_name" class="soft-input block mt-2 w-full rounded-xl border-gray-200" type="text" name="business_name" :value="old('business_name')" required autocomplete="organization" />
                        <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="address" :value="__('Business Address')" />
                    <textarea id="address" class="soft-input block mt-2 w-full border-gray-200 rounded-xl shadow-sm" name="address" rows="3" required autocomplete="address">{{ old('address') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email" class="soft-input block mt-2 w-full rounded-xl border-gray-200" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tel_no" :value="__('Telephone Number')" />
                        <x-text-input id="tel_no" class="soft-input block mt-2 w-full rounded-xl border-gray-200" type="tel" name="tel_no" :value="old('tel_no')" required autocomplete="tel" />
                        <x-input-error :messages="$errors->get('tel_no')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="financial_compliance_statement" :value="__('Financial Compliance Statement (PDF)')" />
                    <input id="financial_compliance_statement" class="soft-input block mt-2 w-full border-gray-200 rounded-xl shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold hover:file:bg-primary-100" type="file" name="financial_compliance_statement" accept=".pdf" required />
                    <p class="mt-2 text-sm text-gray-500">Upload a PDF document (maximum 5MB).</p>
                    <x-input-error :messages="$errors->get('financial_compliance_statement')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="soft-input block mt-2 w-full rounded-xl border-gray-200" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="soft-input block mt-2 w-full rounded-xl border-gray-200" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                    <a class="text-sm text-gray-600 hover:text-primary-700 font-medium" href="{{ route('login') }}">
                        {{ __('Already registered? Log in') }}
                    </a>

                    <x-primary-button class="w-full sm:w-auto justify-center rounded-xl py-3 px-8 text-sm font-semibold tracking-wide uppercase">
                        {{ __('Create Account') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
