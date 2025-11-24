<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public bool $showPassword = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        // Role-based redirection
        $user = Auth::user();
        if ($user->isAssistant()) {
            $this->redirectIntended(default: route('assistant.dashboard', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>
<div class="flex flex-col items-center justify-center min-h-screen p-4 ">
    <x-auth-header  :title="__('Log in to your account')" class="text-black"  :description="__('Enter your email and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

  <form wire:submit="login" class="w-full bg-white text-black p-8 rounded-xl shadow-lg flex flex-col gap-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-black">{{ __('Email address') }}</label>
            <input
                wire:model="email"
                id="email"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
                class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400"
            />
        </div>

        <!-- Password -->
        <div class="relative">
            <label for="password" class="block text-sm font-medium text-black">{{ __('Password') }}</label>
            <div class="relative" x-data="{ showPassword: @entangle('showPassword') }">
                <input
                    wire:model="password"
                    id="password"
                    x-bind:type="showPassword ? 'text' : 'password'"
                    required
                    autocomplete="current-password"
                    placeholder="{{ __('Password') }}"
                    class="mt-1 block w-full px-4 py-2 pr-10 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400"
                />
                <button
                    type="button"
                    x-on:click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 mt-1 text-gray-500 hover:text-gray-700 focus:outline-none"
                >
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m13.42 13.42l-3.29-3.29M3 3l18 18"></path>
                    </svg>
                </button>
            </div>

            @if (Route::has('password.request'))
                <!-- <flux:link class="absolute end-0 top-8 text-sm" :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link> -->
            @endif
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input type="checkbox" wire:model="remember" id="remember" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" />
            <label for="remember" class="ms-2 text-sm text-black">{{ __('Remember me') }}</label>
        </div>

        <div class="flex items-center justify-end">
            <flux:button  type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 ">{{ __('Log in') }}</flux:button>
        </div>
    </form>


</div>
