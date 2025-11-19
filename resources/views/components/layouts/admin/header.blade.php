<flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    <a href="{{ route('admin.dashboard') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0" wire:navigate>
        <x-app-logo />
    </a>

    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item icon="layout-grid" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
            {{ __('Admin Dashboard') }}
        </flux:navbar.item>
    </flux:navbar>

    <flux:spacer />

    <!-- Admin Name -->
    <div class="flex items-center px-4">
        <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
            {{ auth()->user()->name }} {{ auth()->user()->last_name }}
        </span>
    </div>
</flux:header>
