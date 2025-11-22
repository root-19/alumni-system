{{-- 
    DEPRECATED: This file is no longer used.
    Assistant now uses the same layout as user/admin: x-layouts.app
    The app layout includes:
    - Sidebar on left (with mobile overlay)
    - Header on top
    - Same responsive behavior as user/admin
    
    All assistant views should use: <x-layouts.app :title="...">
    See: resources/views/components/layouts/app.blade.php
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white">
        {{-- Assistant now uses x-layouts.app instead of this file --}}
        <x-layouts.app :title="$title ?? null">
            {{ $slot }}
        </x-layouts.app>
    </body>
</html>
