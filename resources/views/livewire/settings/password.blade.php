<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <div>
                <label for="current_password" class="block text-sm font-medium text-black">{{ __('Current password') }}</label>
                <input
                    wire:model="current_password"
                    id="current_password"
                    type="password"
                    required
                    autocomplete="current-password"
                    class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400"
                />
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-black">{{ __('New password') }}</label>
                <input
                    wire:model="password"
                    id="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400"
                />
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-black">{{ __('Confirm Password') }}</label>
                <input
                    wire:model="password_confirmation"
                    id="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400"
                />
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
