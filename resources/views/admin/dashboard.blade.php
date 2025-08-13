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

            <!-- Reports -->
            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-yellow-100 rounded-xl">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13 7h-6m6 6H7m6 6H7m12-6a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Reports</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-1">10</p>
                    </div>
                </div>
            </div>

            <!-- Giving Back -->
            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-blue-100 rounded-xl">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13 7h-6m6 6H7m6 6H7m12-6a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Giving Back</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-1">20,010</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback and Activity -->
        <div class="bg-white rounded-2xl shadow p-6">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Feedbacks and Activity</h2>
                <a href="#" class="text-sm text-blue-600 hover:underline">View all</a>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex space-x-6 text-sm font-medium text-gray-600">
                    <a href="#" class="pb-2 border-b-2 border-blue-600 text-blue-600">All</a>
                    <a href="#" class="pb-2 hover:text-blue-600">Events</a>
                    <a href="#" class="pb-2 hover:text-blue-600">Giving Back</a>
                    <a href="#" class="pb-2 hover:text-blue-600">Reports</a>
                    <a href="#" class="pb-2 hover:text-blue-600">Passwords</a>
                    <a href="#" class="pb-2 hover:text-blue-600">News and Updates</a>
                </nav>
            </div>

            <!-- Activity Feed -->
            <div class="space-y-8">
                <!-- Today -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 mb-3">Today</h4>
                    <ul class="space-y-4">
                        @foreach (['Jeff Britto', 'Sajibul', 'Jessica', 'Deyson', 'Mohamed'] as $user)
                            <li class="flex items-start justify-between">
                                <div class="flex items-start gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user) }}" alt="{{ $user }}" class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="text-sm text-gray-800">
                                            <span class="font-semibold">{{ $user }}</span>
                                            submitted a feedback on “Alumni toolkit for staying connected.”
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500">39m ago</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Yesterday -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 mb-3">Yesterday</h4>
                    <ul class="space-y-4">
                        @foreach (['Jeff Britto', 'Ariel Endra', 'Sandro Rino'] as $user)
                            <li class="flex items-start justify-between">
                                <div class="flex items-start gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user) }}" alt="{{ $user }}" class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="text-sm text-gray-800">
                                            <span class="font-semibold">{{ $user }}</span>
                                            submitted a feedback on “Alumni toolkit for staying connected.”
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500">21h ago</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Divider -->
            <hr class="my-8">

            <!-- Upcoming Events -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800">Upcoming Event</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach (['Batch 2 Alumni Homecoming', 'Batch 3 Alumni Homecoming', 'Intramurals Meet'] as $event)
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">{{ $event }}</label>
                            <form action="{{ route('alumni_posts.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <textarea name="content" placeholder="Type event context here..." class="w-full rounded-md border border-gray-300 text-black px-3 py-2 mb-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                <input type="file" name="image" class="text-sm text-blue-500 font-bold mb-2" />
                                <button type="submit" class="bg-gradient-to-b from-green-700 to-green-500 cursor-pointer text-white px-4 py-2 rounded-md hover:bg-blue-700">Post</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- News Section -->
            <div class="mt-10 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">News</h3>
                <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="title" placeholder="News Title..." class="w-full rounded-md border text-black border-gray-300 px-3 py-2 mb-4 focus:ring-blue-500 focus:border-blue-500" />
                    <textarea name="content" rows="4" placeholder="Type news content here..." class="w-full rounded-md border text-black border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    <div class="flex justify-between items-center mt-4">
                        <input type="file" name="image" class="text-sm text-blue-500 font-bold" />
                        <button type="submit" class="bg-gradient-to-b from-green-700 to-green-500 cursor-pointer text-white px-4 py-2 rounded-md hover:bg-blue-700">Post</button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</x-layouts.app>
