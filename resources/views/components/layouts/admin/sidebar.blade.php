<flux:sidebar sticky stashable class="bg-gradient-to-b from-blue-700 to-blue-500 text-white" mobile>

    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

     @auth
<div class="flex items-center gap-5 px-4 py-2 text-white">
<div class="text-left leading-tight">
    <div class="font-semibold text-sm">
        {{ auth()->user()->name }} {{ auth()->user()->last_name }}
    </div>
    <div class="text-ml text-white capitalize">
        {{ auth()->user()->role }}
    </div>
      </div>
      </div>
    @endauth

<flux:navlist variant="outline">
<flux:navlist.group :heading="__('Admin Platform')" class="grid gap-2 text-white">

    {{-- Dashboard for ADMIN --}}
    @if(auth()->user()->role === 'admin')
        {{-- 1. DASHBOARD --}}
        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="text-white">
            {{ __('Dashboard') }}
        </flux:navlist.item>

        {{-- 2. ANNOUNCEMENT (Collapsible Group) --}}
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition rounded-md text-white">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                    </svg>
                    {{ __('Announcement') }}
                </span>
                <svg x-show="!open" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                <svg x-show="open" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
            <div x-show="open" x-collapse class="ml-4 space-y-1">
                <flux:navlist.item
                    icon="newspaper"
                    :href="route('admin.news')"
                    :current="request()->routeIs('admin.news')"
                    wire:navigate
                    class="rounded-md px-3 py-2 text-sm text-white font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition flex items-center gap-2">
                    {{ __('Create News & Events') }}
                </flux:navlist.item>
                <flux:navlist.item
                    icon="academic-cap"
                    :href="route('admin.trainings.create')"
                    :current="request()->routeIs('admin.trainings.create')"
                    wire:navigate
                    class="rounded-md px-3 py-2 text-sm text-white font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition flex items-center gap-2">
                    {{ __('Create Trainings') }}
                </flux:navlist.item>
                <flux:navlist.item
                    icon="newspaper"
                    :href="route('admin.news')"
                    :current="request()->routeIs('admin.news')"
                    wire:navigate
                    class="rounded-md px-3 py-2 text-sm text-white font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition flex items-center gap-2">
                    {{ __('News Management') }}
                </flux:navlist.item>
                <flux:navlist.item
                    icon="eye"
                    :href="route('admin.newsdisplay')"
                    :current="request()->routeIs('admin.newsdisplay')"
                    wire:navigate
                    class="rounded-md px-3 py-2 text-sm text-white font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition flex items-center gap-2">
                    {{ __('News Display') }}
                </flux:navlist.item>
                <flux:navlist.item
                    icon="calendar-days"
                    :href="route('admin.events.index')"
                    :current="request()->routeIs('admin.events.*')"
                    wire:navigate
                    class="rounded-md px-3 py-2 text-sm text-white font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition flex items-center gap-2">
                    {{ __('Event Management') }}
                </flux:navlist.item>
                <flux:navlist.item
                    icon="rectangle-group"
                    :href="route('admin.trainings.index')"
                    :current="request()->routeIs('admin.trainings.index')"
                    wire:navigate
                    class="rounded-md px-3 py-2 text-sm text-white font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition flex items-center gap-2">
                    {{ __('Training Management') }}
                </flux:navlist.item>
            </div>
        </div>

        {{-- 3. GIVING BACK (PREVIOUSLY CONTRIBUTOR) --}}
        <flux:navlist.item 
            icon="heart" 
            :href="route('givingBack')" 
            :current="request()->routeIs('givingBack') || request()->routeIs('admin.contributor')" 
            wire:navigate 
            class="text-white">
            {{ __('Giving Back') }}
        </flux:navlist.item>

        {{-- 4. DOCUMENT REQUESTS --}}
        <flux:navlist.item 
            icon="inbox" 
            :href="route('admin.document-requests.index')" 
            :current="request()->routeIs('admin.document-requests.*')" 
            wire:navigate 
            class="text-white">
            {{ __('Document Requests') }}
        </flux:navlist.item>

        {{-- 5. USER MANAGEMENT (Collapsible Group) --}}
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition rounded-md text-white">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                    </svg>
                    {{ __('User Management') }}
                </span>
                <svg x-show="!open" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                <svg x-show="open" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
            <div x-show="open" x-collapse class="ml-4 space-y-1">
                <flux:navlist.item
                    icon="user-plus"
                    :href="route('admin.register.form')"
                    :current="request()->routeIs('admin.register.*')"
                    wire:navigate
                    class="rounded-md px-3 py-2 text-sm text-white font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition flex items-center gap-2">
                    {{ __('Create New Accounts') }}
                </flux:navlist.item>
                <flux:navlist.item
                    icon="users"
                    :href="route('accounts')"
                    :current="request()->routeIs('accounts')"
                    wire:navigate
                    class="rounded-md px-3 py-2 text-sm text-white font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition flex items-center gap-2">
                    {{ __('Manage Accounts') }}
                </flux:navlist.item>
            </div>
        </div>
    @endif

</flux:navlist.group>
</flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name . ' ' . auth()->user()->last_name"
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
                                    <span class="truncate font-semibold">{{ auth()->user()->name }} {{ auth()->user()->last_name }}</span>
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