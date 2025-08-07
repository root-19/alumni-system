<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-white">
      <flux:sidebar sticky stashable class="bg-gradient-to-b from-green-700 to-green-500 text-white">

            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

             @auth
        <div class="flex items-center gap-5 px-4 py-2 text-white">
        <div class="text-left leading-tight">
            <div class="font-semibold text-sm">
                {{ auth()->user()->name }}
            </div>
            <div class="text-ml text-gray-400 capitalize">
                {{ auth()->user()->role }}
            </div>
              </div>
              </div>
            @endauth

<flux:navlist variant="outline">
    <flux:navlist.group :heading="__('Platform')" class="grid gap-5 ">

        {{-- Dashboard for USER --}}
        @if(auth()->user()->role === 'user')
            <flux:navlist.item :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('User Dashboard') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate>
                {{ __('Contact') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('room')" :current="request()->routeIs('room')" wire:navigate>
                {{ __('Room') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('view')" :current="request()->routeIs('view')" wire:navigate>
                {{ __('View') }}
            </flux:navlist.item>
        @endif

        {{-- Dashboard for ASSISTANT --}}
        @if(auth()->user()->role === 'assistant')
            <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Assistant Dashboard') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('givingBack')" :current="request()->routeIs('givingBack')" wire:navigate>
                {{ __('Giving') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('accounts')" :current="request()->routeIs('accounts')" wire:navigate>
                {{ __('acounts') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('news')" :current="request()->routeIs('news')" wire:navigate>
                {{ __('news  and updates') }}
            </flux:navlist.item>
        @endif

        {{-- Dashboard for ADMIN --}}
        @if(auth()->user()->role === 'admin')
              <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('givingBack')" :current="request()->routeIs('giving')" wire:navigate>
                {{ __('Giving') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('accounts')" :current="request()->routeIs('accounts')" wire:navigate>
                {{ __('Acounts') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('news')" :current="request()->routeIs('news')" wire:navigate>
                {{ __('Eews  and updates') }}
            </flux:navlist.item>
              <flux:navlist.item :href="route('events')" :current="request()->routeIs('events')" wire:navigate>
                {{ __('Events') }}
            </flux:navlist.item>
              <flux:navlist.item :href="route('resume')" :current="request()->routeIs('resume')" wire:navigate>
                {{ __('Training') }}
            </flux:navlist.item>
              <flux:navlist.item :href="route('reports')" :current="request()->routeIs('reports')" wire:navigate>
                {{ __('Reports') }}
            </flux:navlist.item>
        @endif

    </flux:navlist.group>
</flux:navlist>


            <flux:spacer />

     

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
