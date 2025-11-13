<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-white">

<flux:sidebar sticky stashable class="bg-gradient-to-b from-green-700 to-green-500 text-white">

    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />


    

    {{-- User Info --}}
    @auth
    <div class="flex items-center gap-5 px-4 py-2 text-white">
        <div class="text-left leading-tight">
            <div class="font-semibold text-sm">
                {{ auth()->user()?->name }}
            </div>
            <div class="text-ml text-gray-300 capitalize">
                @php
                    $role = auth()->user()?->role;
                    $isAlumni = auth()->user()?->is_alumni;
                @endphp
                @if($role === 'user')
                    {{ $isAlumni ? 'Alumni' : 'Student' }}
                @else
                    {{ $role }}
                @endif
            </div>
        </div>
    </div>
    @endauth

    {{-- Navigation --}}
    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Platform')" class="flex flex-col text-2xl gap-1">
            @auth
                @php
                    $role = auth()->user()?->role;

                    $menus = [
                        'user' => [
                            ['route' => 'dashboard', 'label' => __('User Dashboard'), 'icon' => 'home', 'active' => 'dashboard'],
                            ['route' => 'events', 'label' => __('Events'), 'icon' => 'calendar-days', 'active' => 'events'],
                            ['route' => 'news', 'label' => __('Events & Updates'), 'icon' => 'newspaper', 'active' => 'news'],
                            ['route' => 'message', 'label' => __('Message'), 'icon' => 'chat-bubble-left-right', 'active' => 'message'],
                            ['route' => 'profile.edit', 'label' => __('Profile'), 'icon' => 'user-circle', 'active' => 'profile.edit'],
                            ['route' => 'donations', 'label' => __('Giving Back'), 'icon' => 'heart', 'active' => 'donations'],
                            ['route' => 'resume-view', 'label' => __('Resume'), 'icon' => 'document-text', 'active' => 'resume-view'],
                            ['route' => 'trainings.index', 'label' => __('Training'), 'icon' => 'academic-cap', 'active' => 'trainings.index'],
                            ['route' => 'documents.index', 'label' => __('Documents'), 'icon' => 'document-duplicate', 'active' => 'documents.index'],

                        ],
                        'assistant' => [
                            ['route' => 'dashboard', 'label' => __('Assistant Dashboard'), 'icon' => 'home', 'active' => 'dashboard'],
                            ['route' => 'givingBack', 'label' => __('Giving'), 'icon' => 'heart', 'active' => 'givingBack'],
                            ['route' => 'accounts', 'label' => __('Accounts'), 'icon' => 'users', 'active' => 'accounts'],
                            ['route' => 'news', 'label' => __('News & Updates'), 'icon' => 'newspaper', 'active' => 'news'],
                            ['route' => 'events', 'label' => __('Events'), 'icon' => 'calendar-days', 'active' => 'events'],
                        ],
                        'admin' => [
                            ['route' => 'dashboard', 'label' => __('Dashboard'), 'icon' => 'home', 'active' => 'dashboard'],

                            ['route' => 'accounts', 'label' => __('Accounts'), 'icon' => 'users', 'active' => 'accounts'],
                            ['group' => 'Announcements', 'items' => [
                                ['route' => 'admin.news', 'label' => __('Events & Updates'), 'icon' => 'newspaper', 'active' => 'admin.news'],
                                ['route' => 'admin.events.index', 'label' => __('Events'), 'icon' => 'calendar-days', 'active' => 'admin.events.*'],
                                ['route' => 'admin.registrations.index', 'label' => __('Registrations'), 'icon' => 'clipboard-document-list', 'active' => 'admin.registrations.*'],
                                ['route' => 'admin.attendance.index', 'label' => __('Attendance'), 'icon' => 'check-circle', 'active' => 'admin.attendance.*'],
                                ['route' => 'admin.event-logs.index', 'label' => __('Event Logs'), 'icon' => 'archive-box', 'active' => 'admin.event-logs.*'],
                            ]],
                            ['route' => 'resume', 'label' => __('Resume'), 'icon' => 'document-text', 'active' => 'resume'],
                            ['route' => 'report', 'label' => __('Reports'), 'icon' => 'chat-bubble', 'active' => 'messages'],
                            ['route' => 'admin.contributor', 'label' => __('Contributor'), 'icon' => 'users', 'active' => 'admin.contributor'],
                            ['route' => 'admin.register.form', 'label' => __('Create Account'), 'icon' => 'user-plus', 'active' => 'admin.register.form'],
                            ['route' => 'admin.trainings.create', 'label' => __('Training Ground'), 'icon' => 'academic-cap', 'active' => 'admin.trainings.create'],
                            ['route' => 'admin.trainings.index', 'label' => __('Training Data'), 'icon' => 'rectangle-group', 'active' => 'admin.trainings.index'],
                            ['route' => 'admin.document-requests.index', 'label' => __('Document Requests'), 'icon' => 'inbox', 'active' => 'admin.document-requests.*'],
                            

                        ],
                    ];

                    $menuItems = $menus[$role] ?? [];
                @endphp

                {{-- Auto-render menu items based on role --}}
                @foreach($menuItems as $item)
                    @if(isset($item['group']))
                        {{-- Render collapsible group --}}
                        <div x-data="{ open: false }" class="space-y-1">
                            <button @click="open = !open" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition rounded-md text-white">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                    {{ $item['group'] }}
                                </span>
                                <svg x-show="!open" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                <svg x-show="open" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="ml-4 space-y-1">
                                @foreach($item['items'] as $subItem)
                                    <flux:navlist.item
                                        :icon="$subItem['icon']"
                                        :href="route($subItem['route'])"
                                        :current="request()->routeIs($subItem['active'])"
                                        wire:navigate
                                        class="rounded-md px-3 py-2 text-sm text-black font-bold tracking-wide hover:bg-white/10 focus:bg-white/15 transition flex items-center gap-2">
                                        {{ $subItem['label'] }}
                                    </flux:navlist.item>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Render regular menu item --}}
                        <flux:navlist.item
                            :icon="$item['icon']"
                            :href="route($item['route'])"
                            :current="request()->routeIs($item['active'])"
                            wire:navigate
                            class="rounded-md px-3 py-2 text-sm font-medium tracking-wide hover:bg-white/10 focus:bg-white/15 transition flex items-center gap-2">
                            {{ $item['label'] }}
                        </flux:navlist.item>
                    @endif
                @endforeach

                @if(empty($menuItems))
                    <div class="text-xs text-white/60 px-3 py-2">{{ __('No menu available') }}</div>
                @endif
            @endauth
        </flux:navlist.group>
    </flux:navlist>

    <flux:spacer />

    {{-- Desktop User Menu --}}
    @auth
    <flux:dropdown class="hidden lg:block" position="bottom" align="start">
        <flux:profile
            :name="auth()->user()?->name"
            icon:trailing="chevrons-up-down"
        />

        <flux:menu class="w-[220px]">
            <div class="p-0 text-sm font-normal">
                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                        @if(auth()->user()?->profile_image_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_image_path) }}" 
                                 alt="Profile Image"
                                 class="h-full w-full object-cover rounded-lg">
                        @else
                            <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                {{ auth()->user()?->initials() }}
                            </span>
                        @endif
                    </span>
                    <div class="grid flex-1 text-start text-sm leading-tight">
                        <span class="truncate font-semibold">{{ auth()->user()?->name }}</span>
                        <span class="truncate text-xs">{{ auth()->user()?->email }}</span>
                    </div>
                </div>
            </div>

            <flux:menu.separator />

            <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                {{ __('Settings') }}
            </flux:menu.item>

            <flux:menu.separator />

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                    {{ __('Log Out') }}
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
    @endauth

</flux:sidebar>

<flux:main>
    {{ $slot }}
</flux:main>

@fluxScripts
</body>
</html>
