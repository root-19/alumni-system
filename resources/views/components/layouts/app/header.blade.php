<header class="bg-white shadow flex items-center justify-between px-6 py-3 sticky top-0 z-50">
    {{-- Left side: Page title --}}
    <div class="text-xl font-semibold text-gray-800">
        {{ $title ?? 'Dashboard' }}
    </div>

    {{-- Right side: Profile & Notification --}}
    @auth
    <div class="flex items-center gap-4">

        {{-- Profile --}}
        <div class="flex items-center gap-2 cursor-pointer relative">
            @if(auth()->user()?->profile_image_path)
                <img src="{{ asset('storage/' . auth()->user()->profile_image_path) }}" 
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

        {{-- Notifications Dropdown --}}
        <div x-data="{
            open: false,
            items: [],
            loading: true,
            unreadCount: 0,
            init() {
                this.fetchFeed();
                this.fetchUnreadCount();
                setInterval(() => this.fetchFeed(), 30000);
                setInterval(() => this.fetchUnreadCount(), 10000);
            },
            fetchFeed() {
                fetch('{{ route('notifications.feed') }}')
                    .then(r => r.json())
                    .then(data => {
                        this.items = data;
                        this.loading = false;
                    });
            },
            fetchUnreadCount() {
                fetch('{{ route('notifications.unread-count') }}')
                    .then(r => r.json())
                    .then(data => {
                        this.unreadCount = data.unread_count;
                    });
            },
            markAsRead(notificationId) {
                fetch('{{ route('notifications.mark-read') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        notification_id: notificationId
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        this.unreadCount = data.unread_count || 0;
                        // Update the specific item
                        const item = this.items.find(i => i.id === notificationId);
                        if (item) {
                            item.is_read = true;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                    // Still update the UI even if the request fails
                    const item = this.items.find(i => i.id === notificationId);
                    if (item) {
                        item.is_read = true;
                    }
                });
            },
            markAllRead() {
                fetch('{{ route('notifications.mark-all-read') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        this.items = this.items.map(i => ({...i, is_read: true}));
                        this.unreadCount = 0;
                    }
                })
                .catch(error => {
                    console.error('Error marking all notifications as read:', error);
                    // Still update the UI even if the request fails
                    this.items = this.items.map(i => ({...i, is_read: true}));
                    this.unreadCount = 0;
                });
            },
            colorFor(type) {
                return {
                    'donation': 'bg-green-500',
                    'event': 'bg-purple-500',
                    'comment': 'bg-yellow-500',
                    'like': 'bg-pink-500',
                    'message': 'bg-blue-500',
                    'news': 'bg-indigo-500'
                }[type] || 'bg-gray-400';
            }
        }" 
        x-init="init()"
        class="relative">
            <button 
                @click="open = !open" 
                class="p-2 rounded-full hover:bg-gray-200 relative focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span 
                    x-show="unreadCount > 0" 
                    x-text="unreadCount" 
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                </span>
            </button>

            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                @click.away="open = false"
                class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 shadow-xl rounded-lg overflow-hidden z-50">
                
                <div class="flex items-center justify-between px-4 py-2 border-b bg-gray-50">
                    <h4 class="font-semibold text-gray-700 text-sm">Notifications</h4>
                    <button @click="markAllRead" class="text-xs text-blue-600 hover:underline">Mark all read</button>
                </div>

                <ul class="max-h-96 overflow-y-auto divide-y divide-gray-100">
                    <template x-if="loading">
                        <li class="p-4 text-center text-xs text-gray-500">Loading...</li>
                    </template>
                    <template x-if="!loading && items.length === 0">
                        <li class="p-4 text-center text-xs text-gray-500">No notifications</li>
                    </template>
                    <template x-for="(item, idx) in items" :key="idx">
                        <li class="px-4 py-3 text-sm flex gap-2 hover:bg-gray-50 cursor-pointer" 
                            :class="{'bg-blue-50': !item.is_read}"
                            @click="markAsRead(item.id)">
                            <div class="w-2 h-2 mt-2 rounded-full" :class="colorFor(item.type)"></div>
                            <div class="flex-1">
                                <p class="text-gray-700" x-text="item.message"></p>
                                <span class="text-xs text-gray-400" x-text="item.time_human"></span>
                            </div>
                            <div x-show="!item.is_read" class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        </li>
                    </template>
                </ul>

                <div class="px-4 py-2 bg-gray-50 text-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-blue-600 hover:underline">View dashboard activity</a>
                </div>
            </div>
        </div>

    </div>
    @endauth
</header>

{{-- Move Alpine.js script to the app layout head --}}
