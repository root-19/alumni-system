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
            <span class="font-medium text-gray-800">{{ auth()->user()?->name }}</span>

            {{-- Optional dropdown (can hide for now) --}}
        </div>

        {{-- Notification Bell (icon only) --}}
        <button class="p-2 rounded-full hover:bg-gray-200">
            <icon name="bell" class="w-6 h-6 text-gray-600" />
        </button>

    </div>
    @endauth
</header>
