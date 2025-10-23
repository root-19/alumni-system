<flux:sidebar sticky stashable class="bg-gradient-to-b from-blue-700 to-blue-500 text-white">

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
<flux:navlist.group :heading="__('Admin Platform')" class="grid gap-5 ">

    {{-- Dashboard for ADMIN --}}
    @if(auth()->user()->role === 'admin')
          <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
            {{ __('Dashboard') }}
        </flux:navlist.item>
        <flux:navlist.item :href="route('givingBack')" :current="request()->routeIs('givingBack')" wire:navigate>
            {{ __('Giving') }}
        </flux:navlist.item>
        <flux:navlist.item :href="route('accounts')" :current="request()->routeIs('accounts')" wire:navigate>
            {{ __('Acounts') }}
        </flux:navlist.item>
        <flux:navlist.item :href="route('admin.news')" :current="request()->routeIs('admin.news')" wire:navigate>
            {{ __('News and updates') }}
        </flux:navlist.item>
          <flux:navlist.item :href="route('events')" :current="request()->routeIs('events')" wire:navigate>
            {{ __('Events') }}
        </flux:navlist.item>
          <flux:navlist.item :href="route('resume')" :current="request()->routeIs('resume')" wire:navigate>
            {{ __('Training') }}
        </flux:navlist.item>
          <flux:navlist.item :href="route('report')" :current="request()->routeIs('report')" wire:navigate>
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