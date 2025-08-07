<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('Confirm password')"
        :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="confirmPassword" class="flex flex-col gap-6">
        <!-- Password -->
        <div class="relative">
            <label for="password" class="block text-sm font-medium text-black">{{ __('Password') }}</label>
            <input
                wire:model="password"
                id="password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="{{ __('Password') }}"
                class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400"
            />
        </div>

        <flux:button variant="primary" type="submit" class="w-full">{{ __('Confirm') }}</flux:button>
    </form>
</div>
