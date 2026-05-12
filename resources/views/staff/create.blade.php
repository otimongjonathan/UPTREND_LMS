<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Staff Member</h2>
                <p class="mt-1 text-sm text-gray-600">Create a staff account that can supervise collateral records.</p>
            </div>
            <a href="{{ route('staff.index') }}" class="text-sm font-medium text-primary-700 hover:text-primary-800">Back to staff list</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('staff.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="name" :value="__('Full Name')" />
                                <x-text-input id="name" class="block mt-2 w-full rounded-xl border-gray-200" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="tel_no" :value="__('Telephone Number')" />
                                <x-text-input id="tel_no" class="block mt-2 w-full rounded-xl border-gray-200" type="tel" name="tel_no" :value="old('tel_no')" required />
                                <x-input-error :messages="$errors->get('tel_no')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="address" :value="__('Office Address')" />
                            <textarea id="address" name="address" rows="3" class="block mt-2 w-full rounded-xl border-gray-200 shadow-sm" required>{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" class="block mt-2 w-full rounded-xl border-gray-200" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="role" :value="__('Role')" />
                                <select id="role" name="role" class="block mt-2 w-full rounded-xl border-gray-200 shadow-sm" required>
                                    <option value="">Select role</option>
                                    <option value="loan_supervisor" {{ old('role') === 'loan_supervisor' ? 'selected' : '' }}>Loan Supervisor</option>
                                    <option value="accountant" {{ old('role') === 'accountant' ? 'selected' : '' }}>Accountant</option>
                                    <option value="marketer" {{ old('role') === 'marketer' ? 'selected' : '' }}>Marketer</option>
                                    <option value="operations" {{ old('role') === 'operations' ? 'selected' : '' }}>Operations</option>
                                    <option value="other" {{ old('role') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <p class="mt-2 text-xs text-gray-500">All roles created here can sign in to the staff dashboard.</p>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <a href="{{ route('staff.index') }}" class="rounded-xl px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-100">Cancel</a>
                            <x-primary-button class="rounded-xl px-6 py-3 text-sm font-semibold">Create Staff & Send Invite</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>