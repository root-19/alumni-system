<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')

    <body class="min-h-screen {{ auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'assistant') ? 'bg-white' : 'bg-white' }} ">
        <x-layouts.app.sidebar :title="$title ?? null"/>

        <flux:main>
            <x-layouts.app.header :title="$title ?? null" />
            {{ $slot }}
        </flux:main>

        @fluxScripts
    </body>
</html>
