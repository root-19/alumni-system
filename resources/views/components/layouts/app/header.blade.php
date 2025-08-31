<header class="bg-white shadow flex items-center justify-between px-6 py-3 sticky top-0 z-50">
    {{-- Left side: Page title --}}
    <div class="text-xl font-semibold text-gray-800">
        {{ $title ?? 'Dashboard' }}
    </div>

    {{-- Right side: Profile & Notification --}}
    @auth
    <div class="flex items-center gap-4">

        {{-- Profile first --}}
        <div class="flex items-center gap-2 cursor-pointer relative">
            @if(auth()->user()?->profile_image)
                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" 
                     alt="Profile Image"
                     class="w-10 h-10 rounded-full object-cover border-2 border-gray-300">
            @else
                <span class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-black font-bold">
                    {{ auth()->user()?->initials() }}
                </span>
            @endif
            <div class="flex flex-col leading-tight">
                <span class="font-medium text-gray-800">
                    {{ auth()->user()?->name }}
                </span>
                @if(auth()->user()?->year_graduated)
                    <span class="text-sm text-gray-500">
                        {{ auth()->user()->year_graduated }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Notification Bell (fixed icon) --}}
        <button class="p-2 rounded-full hover:bg-gray-200 relative">
            {{-- Heroicon bell --}}
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-6 h-6 text-gray-600" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor" 
                 stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 
                      0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 
                      2 0 10-4 0v.341C7.67 6.165 6 8.388 6 
                      11v3.159c0 .538-.214 1.055-.595 
                      1.436L4 17h5m6 0v1a3 3 0 11-6 
                      0v-1m6 0H9" />
            </svg>
        </button>

    </div>
    @endauth
</header>
