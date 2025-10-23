<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col gap-10 min-h-screen">

        <!-- Stat Cards -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Accounts -->
            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-green-100 rounded-xl">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13 7h-6m6 6H7m6 6H7m12-6a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Accounts</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $userCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Messages / Reports (chat count) -->
            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-yellow-100 rounded-xl">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m8 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Messages</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $messageCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Giving Back (Total Donations) -->
            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-blue-100 rounded-xl">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3 0 1.656 1.343 3 3 3s3-1.344 3-3c0-1.657-1.343-3-3-3zm0-6a9 9 0 00-9 9c0 7 9 13 9 13s9-6 9-13a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Giving Back</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-1">₱{{ number_format($totalDonationAmount ?? 0,2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback and Activity -->
        <div class="bg-white rounded-2xl shadow p-6 text-black">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Feedbacks and Activity</h2>
                <a href="#" class="text-sm text-blue-600 hover:underline">View all</a>
            </div>

            <!-- Tabs (client-side filter) -->
            <div x-data="{tab:'all'}" class="mb-6">
                <div class="border-b border-gray-200 mb-4">
                    <nav class="flex space-x-6 text-sm font-medium text-black">
                        <button @click="tab='all'" :class="tab==='all' ? 'border-blue-600 text-blue-600' : 'hover:text-blue-600'" class="pb-2 border-b-2" :class="tab==='all' ? 'border-blue-600' : 'border-transparent'">All</button>
                        <button @click="tab='events'" :class="tab==='events' ? 'border-blue-600 text-blue-600' : 'hover:text-blue-600'" class="pb-2 border-b-2">Events</button>
                        <button @click="tab='giving'" :class="tab==='giving' ? 'border-blue-600 text-blue-600' : 'hover:text-blue-600'" class="pb-2 border-b-2">Giving Back</button>
                        <button @click="tab='messages'" :class="tab==='messages' ? 'border-blue-600 text-blue-600' : 'hover:text-blue-600'" class="pb-2 border-b-2">Messages</button>
                        <button @click="tab='news'" :class="tab==='news' ? 'border-blue-600 text-blue-600' : 'hover:text-blue-600'" class="pb-2 border-b-2">News & Updates</button>
                    </nav>
                </div>

                <!-- Combined Feed -->
                <div class="space-y-6">
                    <!-- Giving Back (Donations) -->
                    <template x-if="tab==='all' || tab==='giving'">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 mb-3">Recent Donations</h4>
                            <ul class="space-y-3">
                                @forelse($donations as $d)
                                    <li class="flex justify-between text-sm">
                                        <div class="flex items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($d->user->name ?? 'User') }}" class="w-8 h-8 rounded-full" alt="avatar">
                                            <span><strong>{{ $d->user->name ?? 'Unknown' }}</strong> donated ₱{{ number_format($d->amount,2) }}</span>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $d->created_at->diffForHumans() }}</span>
                                    </li>
                                @empty
                                    <li class="text-xs text-gray-500">No donations yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </template>

                    <!-- Events -->
                    <template x-if="tab==='all' || tab==='events'">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 mb-3">Recent Events</h4>
                            <ul class="space-y-3">
                                @forelse($events as $e)
                                    <li class="flex justify-between text-sm">
                                        <span class="line-clamp-1">{{ Str::limit($e->content, 60) }}</span>
                                        <span class="text-xs text-gray-500">{{ $e->created_at->diffForHumans() }}</span>
                                    </li>
                                @empty
                                    <li class="text-xs text-gray-500">No events posted.</li>
                                @endforelse
                            </ul>
                        </div>
                    </template>

                    <!-- Messages -->
                    <template x-if="tab==='all' || tab==='messages'">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 mb-3">Recent Messages</h4>
                            <ul class="space-y-3">
                                @forelse($messages as $m)
                                    <li class="flex justify-between text-sm">
                                        <span><strong>{{ $m->sender->name ?? 'User' }}:</strong> {{ Str::limit($m->message,50) }}</span>
                                        <span class="text-xs text-gray-500">{{ $m->created_at->diffForHumans() }}</span>
                                    </li>
                                @empty
                                    <li class="text-xs text-gray-500">No messages yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </template>

                    <!-- News -->
                    <template x-if="tab==='all' || tab==='news'">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 mb-3">Latest News</h4>
                            <ul class="space-y-3">
                                @forelse($news as $n)
                                    <li class="flex justify-between text-sm">
                                        <span class="line-clamp-1 font-medium">{{ Str::limit($n->title, 70) }}</span>
                                        <span class="text-xs text-gray-500">{{ $n->created_at->diffForHumans() }}</span>
                                    </li>
                                @empty
                                    <li class="text-xs text-gray-500">No news posted.</li>
                                @endforelse
                            </ul>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="mt-8 space-y-6">
                <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('admin.news') }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 hover:from-green-100 hover:to-emerald-100 transition-all duration-200">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Manage News & Events</h4>
                            <p class="text-sm text-gray-600">Create and manage news articles and events</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('accounts') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 hover:from-blue-100 hover:to-indigo-100 transition-all duration-200">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">User Management</h4>
                            <p class="text-sm text-gray-600">Manage user accounts and permissions</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>

    </div>
</x-layouts.app>