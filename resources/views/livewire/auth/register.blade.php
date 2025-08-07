<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>



<div class="flex flex-col gap-6">



    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center " :status="session('status')" />

    <form wire:submit="register" class="w-full max-w-md mx-auto bg-white text-black p-8 rounded-xl shadow-lg flex flex-col gap-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-black">{{ __('Name') }}</label>
            <input
                wire:model="name"
                id="name"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="{{ __('Full name') }}"
                class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400"
            />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-black">{{ __('Email address') }}</label>
            <input
                wire:model="email"
                id="email"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
              class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400"
        </div>

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

        <!-- Confirm Password -->
        <div class="relative">
            <label for="password_confirmation" class="block text-sm font-medium text-black">{{ __('Confirm password') }}</label>
            <input
                wire:model="password_confirmation"
                id="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="{{ __('Confirm password') }}"
                class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400"
            />
        </div>
        <br>

        <div class="flex items-center justify-end">
            <flux:button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800  ">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-green-600">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" class=" text-sm text-green-600" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
