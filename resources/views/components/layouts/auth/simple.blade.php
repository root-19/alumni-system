<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-900 text-emerald-50">

    @if (Route::currentRouteName() === 'login')
        {{-- LOGIN LAYOUT --}}
        <div class="bg-emerald-950/70 border border-emerald-800 text-emerald-50 flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10 lg:flex-row lg:gap-10 shadow-2xl shadow-black/30 rounded-3xl">
         <div class="hidden lg:flex lg:w-1/2 lg:flex-col lg:items-center lg:justify-center">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" class="mx-auto h-auto drop-shadow-lg">
            </div> 
            <div class="flex w-full max-w-md flex-col gap-2 lg:w-1/2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6 w-full">
                    {{ $slot }}
                </div>
            </div>
        </div>
    @else
        {{-- REGISTER LAYOUT --}}
        {{-- <div class="bg-white flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10"> --}}
            <div class="flex w-full max-w-md flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6 w-full">
                    {{ $slot }}
                </div>
            </div>
        </div>
    @endif

    @fluxScripts
</body>
</html>


