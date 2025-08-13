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
    public string $middle_name = '';
public string $suffix = '';
public string $year_graduated = '';
public string $program = '';
public string $gender = '';
public string $status = '';
public string $contact_number = '';
public string $address = '';
public $profile_image; 

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
    $validated = $this->validate([
    'name' => ['required', 'string', 'max:255'],
    'middle_name' => ['nullable', 'string', 'max:255'],
    'suffix' => ['nullable', 'string', 'max:50'],
    'year_graduated' => ['required', 'digits:4', 'integer', 'min:1900', 'max:' . date('Y')],
    'program' => ['required', 'string', 'max:255'],
    'gender' => ['required', 'in:male,female,other'],
    'status' => ['required', 'string', 'max:255'],
    'contact_number' => ['required', 'string', 'max:20'],
    'address' => ['required', 'string', 'max:500'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
    'profile_image' => ['nullable', 'image', 'max:1024'], // max 1MB
]);

if ($this->profile_image) {
    $validated['profile_image_path'] = $this->profile_image->store('profile-images', 'public');
}


        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col w-screen items-center justify-center  max-w-[90rem] mx-auto px-8">
{{-- <div class="flex flex-col items-center justify-center min-h-screen p-4 "> --}}
{{-- <div class="flex flex-col gap-6 w-full max-w-[90rem] mx-auto px-8"> --}}
{{--  --}}

    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />
  <form wire:submit="register" enctype="multipart/form-data"
          class="w-full max-w-6xl mx-auto bg-white text-black p-8 rounded-xl shadow-lg
                 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

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
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            />
        </div>

        <!-- Middle Name -->
        <div>
            <label for="middle_name" class="block text-sm font-medium text-black">{{ __('Middle Name') }}</label>
            <input
                wire:model="middle_name"
                id="middle_name"
                type="text"
                autocomplete="middle_name"
                placeholder="{{ __('Middle name') }}"
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            />
        </div>

        <!-- Suffix -->
        <div>
            <label for="suffix" class="block text-sm font-medium text-black">{{ __('Suffix') }}</label>
            <input
                wire:model="suffix"
                id="suffix"
                type="text"
                autocomplete="suffix"
                placeholder="{{ __('Suffix (e.g., Jr., Sr.)') }}"
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            />
        </div>

        <!-- Year Graduated -->
        <div>
            <label for="year_graduated" class="block text-sm font-medium text-black">{{ __('Year Graduated') }}</label>
            <input
                wire:model="year_graduated"
                id="year_graduated"
                type="number"
                min="1900"
                max="{{ date('Y') }}"
                placeholder="{{ __('e.g. 2023') }}"
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            />
        </div>

        <!-- Program -->
        <div>
            <label for="program" class="block text-sm font-medium text-black">{{ __('Program') }}</label>
            <input
                wire:model="program"
                id="program"
                type="text"
                placeholder="{{ __('Your program or course') }}"
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            />
        </div>

        <!-- Gender -->
        <div>
            <label for="gender" class="block text-sm font-medium text-black">{{ __('Gender') }}</label>
            <select wire:model="gender" id="gender" required
                    class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                           focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black text-sm">
                <option value="" disabled>{{ __('Select gender') }}</option>
                <option value="male">{{ __('Male') }}</option>
                <option value="female">{{ __('Female') }}</option>
                <option value="other">{{ __('Other') }}</option>
            </select>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-black">{{ __('Status') }}</label>
            <input
                wire:model="status"
                id="status"
                type="text"
                placeholder="{{ __('Your current status (e.g., Student, Alumni)') }}"
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            />
        </div>

        <!-- Contact Number -->
        <div>
            <label for="contact_number" class="block text-sm font-medium text-black">{{ __('Contact Number') }}</label>
            <input
                wire:model="contact_number"
                id="contact_number"
                type="tel"
                placeholder="{{ __('e.g. +639123456789') }}"
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            />
        </div>

        <!-- Address (span 4 columns) -->
        <div class="md:col-span-4">
            <label for="address" class="block text-sm font-medium text-black">{{ __('Address') }}</label>
            <textarea
                wire:model="address"
                id="address"
                rows="2"
                placeholder="{{ __('Your full address') }}"
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm resize-none
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            ></textarea>
        </div>

        <!-- Email Address (span 4 columns) -->
        <div class="md:col-span-4">
            <label for="email" class="block text-sm font-medium text-black">{{ __('Email address') }}</label>
            <input
                wire:model="email"
                id="email"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-black">{{ __('Password') }}</label>
            <input
                wire:model="password"
                id="password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="{{ __('Password') }}"
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-black">{{ __('Confirm password') }}</label>
            <input
                wire:model="password_confirmation"
                id="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="{{ __('Confirm password') }}"
                class="mt-1 block w-full px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-black placeholder-gray-400 text-sm"
            />
        </div>

        <!-- Profile Image (span 4 columns) -->
        <div class="md:col-span-4">
            <label for="profile_image" class="block text-sm font-medium text-black">{{ __('Profile Image') }}</label>
            <input
                wire:model="profile_image"
                id="profile_image"
                type="file"
                accept="image/*"
                class="mt-1 block w-full text-black"
            />
            @error('profile_image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Submit button (span 4 columns) -->
        <div class="md:col-span-4 flex items-center justify-end">
            <flux:button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-sm py-2">
                {{ __('Create account') }}
            </flux:button>
        </div>

    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-green-600 mt-4">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" class="text-sm text-green-600" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>