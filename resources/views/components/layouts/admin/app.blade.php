<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="min-h-screen bg-white ">
        <x-layouts.admin.sidebar :title="$title ?? null">
            <x-layouts.admin.header :title="$title ?? null" />

            <flux:main>
                {{ $slot }}
            </flux:main>
        </x-layouts.admin.sidebar>

        @fluxScripts
    </body>
</html>