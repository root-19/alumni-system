<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>[x-cloak] { display: none !important; }</style>
        @stack('scripts')
    </head>

    <body class="min-h-screen {{ auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'assistant') ? 'bg-white' : 'bg-white' }} ">
        <x-layouts.app.sidebar :title="$title ?? null"/>

        <flux:main>
            <x-layouts.app.header :title="$title ?? null" />
            {{ $slot }}
           

        </flux:main>
       
        @fluxScripts
    </body>
</html>
