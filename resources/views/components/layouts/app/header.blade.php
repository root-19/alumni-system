<header class="bg-white shadow sticky top-0 z-50">
    {{-- Top Bar: Title and Actions --}}
    <div class="flex items-center justify-between px-4 md:px-6 py-3">
        {{-- Left side: Page title --}}
        <div class="text-xl font-semibold text-gray-800">
            {{ $title ?? 'Dashboard' }}
        </div>

        {{-- Right side: Profile & Notification --}}
        @auth
        <div class="flex items-center gap-2 md:gap-4">

            {{-- User Name (Hidden on mobile, shown on tablet+) --}}
            <div class="hidden md:flex items-center px-2 md:px-4">
                <span class="text-sm font-semibold text-gray-800">
                    {{ auth()->user()?->name }} {{ auth()->user()?->last_name }}
                </span>
            </div>

            {{-- Notifications Dropdown --}}
            <div x-data="{
                open: false,
                items: [],
                loading: true,
                unreadCount: 0,
                isAdmin: {{ auth()->user()->role === 'admin' || auth()->user()->role === 'assistant' ? 'true' : 'false' }},
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
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'assistant')
                            <button @click="markAllRead" class="text-xs text-blue-600 hover:underline">Mark all read</button>
                        @endif
                    </div>

                    <ul class="max-h-96 overflow-y-auto divide-y divide-gray-100">
                        <template x-if="loading">
                            <li class="p-4 text-center text-xs text-gray-500">Loading...</li>
                        </template>
                        <template x-if="!loading && items.length === 0">
                            <li class="p-4 text-center text-xs text-gray-500">No notifications</li>
                        </template>
                        <template x-for="(item, idx) in items" :key="idx">
                            <li class="px-4 py-3 text-sm flex gap-2" 
                                :class="{'bg-blue-50': !item.is_read, 'hover:bg-gray-50 cursor-pointer': isAdmin, 'cursor-default': !isAdmin}"
                                @click="isAdmin ? markAsRead(item.id) : null">
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
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'assistant')
                            <a href="{{ route('admin.dashboard') }}" class="text-xs text-blue-600 hover:underline">View dashboard activity</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-xs text-blue-600 hover:underline">View dashboard</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
        @endauth
    </div>

    {{-- Mobile Navigation Bar (Topbar) --}}
    @auth
    <div class="lg:hidden border-t border-gray-200 bg-white overflow-x-auto">
        <nav class="flex items-center gap-1 px-2 py-2" style="scrollbar-width: none; -ms-overflow-style: none;">
            @php
                $role = auth()->user()?->role;
                $isAlumni = auth()->user()?->is_alumni;

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
                        ['route' => 'admin.news', 'label' => __('Events & Updates'), 'icon' => 'newspaper', 'active' => 'admin.news'],
                        ['route' => 'admin.events.index', 'label' => __('Events'), 'icon' => 'calendar-days', 'active' => 'admin.events.*'],
                        ['route' => 'admin.registrations.index', 'label' => __('Registrations'), 'icon' => 'clipboard-document-list', 'active' => 'admin.registrations.*'],
                        ['route' => 'admin.attendance.index', 'label' => __('Attendance'), 'icon' => 'check-circle', 'active' => 'admin.attendance.*'],
                        ['route' => 'admin.event-logs.index', 'label' => __('Event Logs'), 'icon' => 'archive-box', 'active' => 'admin.event-logs.*'],
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

            @foreach($menuItems as $item)
                @if(!isset($item['group']))
                    <a href="{{ route($item['route']) }}" 
                       class="flex flex-col items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors whitespace-nowrap {{ request()->routeIs($item['active']) ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($item['icon'] === 'home')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            @elseif($item['icon'] === 'calendar-days')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            @elseif($item['icon'] === 'newspaper')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            @elseif($item['icon'] === 'chat-bubble-left-right')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            @elseif($item['icon'] === 'user-circle')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @elseif($item['icon'] === 'heart')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            @elseif($item['icon'] === 'users')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            @elseif($item['icon'] === 'academic-cap')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v6m-4-4h8"></path>
                            @elseif($item['icon'] === 'chart-bar')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            @elseif($item['icon'] === 'clipboard-document-list')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            @elseif($item['icon'] === 'check-circle')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @elseif($item['icon'] === 'archive-box')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            @elseif($item['icon'] === 'document-text')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            @elseif($item['icon'] === 'chat-bubble')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            @elseif($item['icon'] === 'user-plus')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            @elseif($item['icon'] === 'rectangle-group')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 10h20M2 14h20m-10-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            @elseif($item['icon'] === 'inbox')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            @elseif($item['icon'] === 'document-duplicate')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-6M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            @endif
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>
    @endauth

</header>

{{-- Move Alpine.js script to the app layout head --}}
