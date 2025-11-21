{{-- {{ dd(get_defined_vars()) }} --}}

<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col gap-6">

        {{-- ✅ Welcome Banner --}}
        <div class="relative rounded-xl bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800  text-white p-6 flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90">{{ now()->format('F d, Y') }}</p>
                <h2 class="text-2xl font-bold mt-2">Welcome back, {{ auth()->user()->name }}!</h2>
                <p class="text-sm">Always stay updated in your alumni portal</p>
            </div>
            {{-- <img src="{{ asset('images/graduation-cap.png') }}" alt="Banner Illustration" class="w-28 h-28"> --}}
        </div>

        {{-- ✅ Main Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            {{-- Left Column: Upcoming Events --}}
            <div class="md:col-span-1 space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Upcoming Events</h3>
                <div class="bg-white p-4 rounded-lg shadow">
                    @if($upcomingEvents->count() > 0)
                        @foreach($upcomingEvents as $event)
                            <div class="flex gap-3 mb-3 last:mb-0">
                                @php
                                    $defaultDisk = config('filesystems.default');
                                    $imageExists = $event->image_path && \Illuminate\Support\Facades\Storage::disk($defaultDisk)->exists($event->image_path);
                                    $imageUrl = $imageExists ? \Illuminate\Support\Facades\Storage::disk($defaultDisk)->url($event->image_path) : null;
                                @endphp

                                @if($imageExists)
                                    <img src="{{ $imageUrl }}" 
                                         alt="Event Image" 
                                         class="w-16 h-16 rounded-md object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-md bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm text-gray-800 line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit($event->content, 60) }}
                                    </p>
                                    <span class="text-sm text-gray-500">
                                        {{ $event->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        {{-- View All Events Link --}}
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <a href="{{ route('events') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium inline-flex items-center gap-1">
                                View All Events
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                    @else
                        {{-- No upcoming events message --}}
                        <div class="text-center py-6">
                            <div class="w-12 h-12 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 mb-2">No upcoming events</p>
                            <p class="text-xs text-gray-400">Check back later for new events</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Middle Column: Big News --}}
           <div class="md:col-span-2">
    <div class="relative overflow-hidden rounded-xl shadow-lg">
        {{-- Bigger image (h-96 instead of h-64) --}}
        <img src="{{ asset('image/alumni.jpg') }}" class="w-full h-96 object-cover">
        
        {{-- Dark overlay with left-aligned text & button --}}
        <div class="absolute inset-0 bg-black/50 flex flex-col justify-end p-8 text-left text-white">
            <h3 class="text-sm font-bold">Nuevo Secretario Ejecutivo del Facultad</h3>
            <p class="text-sm mt-3 max-w-xl">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                <br> Vitae, corrupti.
            </p>
            <button class="mt-4 px-6 py-2 bg-green-700 hover:bg-green-800 rounded-lg text-sm font-semibold w-max">
                Visit Page
            </button>
        </div>
    </div>
</div>


            {{-- Right Column: Widgets --}}
            <div class="md:col-span-1 space-y-4">

                {{-- Events Section --}}
                <div class="bg-white p-4 rounded-lg shadow">
                    <!-- <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-gray-800">Events</h4>
                        <button onclick="openAddEventModal()" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg font-medium transition-colors">
                            Add Event
                        </button>
                    </div> -->
                    
                    @if($events->count() > 0)
                        <div class="space-y-3">
                            @foreach($events as $event)
                                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                    @php
                                        $defaultDisk = config('filesystems.default');
                                        $imageExists = $event->image_path && \Illuminate\Support\Facades\Storage::disk($defaultDisk)->exists($event->image_path);
                                        $imageUrl = $imageExists ? \Illuminate\Support\Facades\Storage::disk($defaultDisk)->url($event->image_path) : null;
                                    @endphp

                                    @if($imageExists)
                                        <img src="{{ $imageUrl }}" 
                                             alt="Event Image" 
                                             class="w-12 h-12 rounded-md object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-md bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-sm text-gray-800 truncate">{{ $event->title ?? \Illuminate\Support\Str::limit($event->content, 30) }}</p>
                                        <p class="text-xs text-gray-500">{{ $event->created_at->format('M d, Y') }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            @if($event->created_at->gte(now()->subDays(5)))
                                                <button onclick="openReviewModal({{ $event->id }})" class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                                                    Add Review
                                                </button>
                                                <span class="text-xs text-gray-400">•</span>
                                            @endif
                                            <a href="{{ route('events.show', $event) }}" class="text-xs text-green-600 hover:text-green-700 font-medium">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <a href="{{ route('events') }}" class="text-sm text-green-600 hover:text-green-700 font-medium inline-flex items-center gap-1">
                                View All Events
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="w-12 h-12 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 mb-2">No events available yet</p>
                        </div>
                    @endif
                </div>

                {{-- Daily Notice --}}
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="font-semibold mb-2">Daily Notice</h4>
                    <p class="text-sm text-gray-600">Come celebrate with us and promote the event with your section.</p>
                    <a href="#" class="text-sm text-green-500 mt-2 inline-block">See more</a>
                </div>

                {{-- Profile Card --}}
                <div class="bg-white p-4 rounded-lg shadow flex gap-3 items-center">
                    {{-- Always use initials since we're not fetching profile image data --}}
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-semibold">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->program ?? 'Alumni Member' }}</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- ✅ Beautiful News Section with Latest Posts --}}
        <section class="space-y-6">
            {{-- Section Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Latest News & Updates</h2>
                    <p class="text-gray-600 mt-1">Stay informed with the latest happenings in our alumni community</p>
                </div>
                <a href="#" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                    View All News
                </a>
            </div>

            {{-- Featured News (Hero) --}}
            @if(isset($featuredNews) && $featuredNews)
            <div class="relative overflow-hidden rounded-2xl shadow-xl">
                @php
                    $defaultDisk = config('filesystems.default');
                    $imageExists = $featuredNews->image_path && \Illuminate\Support\Facades\Storage::disk($defaultDisk)->exists($featuredNews->image_path);
                    $imageUrl = $imageExists ? \Illuminate\Support\Facades\Storage::disk($defaultDisk)->url($featuredNews->image_path) : null;
                @endphp

                @if($imageExists)
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $featuredNews->title }}" 
                         class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                        <div class="text-white text-center">
                            <svg class="w-24 h-24 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <p class="text-xl font-semibold">Featured News</p>
                        </div>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded-full">Featured</span>
                        <span class="text-sm opacity-90">{{ $featuredNews->created_at->format('M d, Y') }}</span>
                    </div>
                    <h3 class="text-3xl font-bold mb-3 leading-tight">{{ $featuredNews->title }}</h3>
                    <p class="text-lg opacity-90 max-w-3xl mb-4">
                        {{ \Illuminate\Support\Str::limit($featuredNews->content, 200) }}
                    </p>
                    <a href="#" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-semibold transition-colors">
                        Read Full Article
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                    </a>
                </div>
            </div>
            @endif

            {{-- Latest News Grid (5 Posts from Database) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($latestAlumniPosts as $index => $post)
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="relative">
                            @php
                                $defaultDisk = config('filesystems.default');
                                $imageExists = $post->image_path && \Illuminate\Support\Facades\Storage::disk($defaultDisk)->exists($post->image_path);
                                $imageUrl = $imageExists ? \Illuminate\Support\Facades\Storage::disk($defaultDisk)->url($post->image_path) : null;
                            @endphp

                            @if($imageExists)
                                <img src="{{ $imageUrl }}" 
                                     alt="Alumni Post {{ $index + 1 }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                             
                                {{-- <span class="px-3 py-1 {{ $color }} text-white text-xs font-semibold rounded-full">{{ $category }}</span> --}}
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-3 line-clamp-2">
                                {{ \Illuminate\Support\Str::limit($post->content, 80) }}
                            </h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ \Illuminate\Support\Str::limit($post->content, 150) }}
                            </p>
                            <a href="#" class="text-green-600 hover:text-green-700 font-medium text-sm inline-flex items-center gap-1">
                                Read More
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                @empty
                    {{-- Show placeholder cards if no posts exist --}}
                    @for($i = 1; $i <= 5; $i++)
                        <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="absolute top-4 left-4">
                                    @php
                                        $categories = ['Alumni', 'Achievement', 'Event', 'Education', 'Community'];
                                        $colors = ['bg-blue-600', 'bg-purple-600', 'bg-orange-600', 'bg-green-600', 'bg-red-600'];
                                        $category = $categories[$i - 1];
                                        $color = $colors[$i - 1];
                                    @endphp
                                    <span class="px-3 py-1 {{ $color }} text-white text-xs font-semibold rounded-full">{{ $category }}</span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Coming Soon</span>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 line-clamp-2">
                                    No posts available yet
                                </h4>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    Check back later for the latest updates and news from our alumni community.
                                </p>
                                <span class="text-gray-400 text-sm">No content available</span>
                            </div>
                        </article>
                    @endfor
                @endforelse
            </div>

            {{-- Load More Button --}}
            <div class="text-center pt-6">
                <button class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                    Load More News
                </button>
            </div>
        </section>

    </div>

    {{-- Add Event Modal --}}
    <div id="addEventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Add New Event</h3>
                        <button onclick="closeAddEventModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('alumni_posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Event Title</label>
                            <input type="text" name="title" id="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                        
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Event Description</label>
                            <textarea name="content" id="content" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required></textarea>
                        </div>
                        
                        <div>
                            <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                            <input type="datetime-local" name="event_date" id="event_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" name="location" id="location" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Event Image</label>
                            <input type="file" name="image" id="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeAddEventModal()" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                Create Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Review Modal --}}
    <div id="addReviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Add Review</h3>
                        <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form id="reviewForm" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <div class="flex space-x-1" id="starRating">
                                <button type="button" class="star text-2xl text-gray-300 hover:text-yellow-400" data-rating="1">★</button>
                                <button type="button" class="star text-2xl text-gray-300 hover:text-yellow-400" data-rating="2">★</button>
                                <button type="button" class="star text-2xl text-gray-300 hover:text-yellow-400" data-rating="3">★</button>
                                <button type="button" class="star text-2xl text-gray-300 hover:text-yellow-400" data-rating="4">★</button>
                                <button type="button" class="star text-2xl text-gray-300 hover:text-yellow-400" data-rating="5">★</button>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" required>
                        </div>
                        
                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Comment (Optional)</label>
                            <textarea name="comment" id="comment" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Share your thoughts about this event..."></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeReviewModal()" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentEventId = null;
        let selectedRating = 0;

        function openAddEventModal() {
            document.getElementById('addEventModal').classList.remove('hidden');
        }

        function closeAddEventModal() {
            document.getElementById('addEventModal').classList.add('hidden');
        }

        function openReviewModal(eventId) {
            currentEventId = eventId;
            document.getElementById('reviewForm').action = `/events/${eventId}/reviews`;
            document.getElementById('addReviewModal').classList.remove('hidden');
            resetStarRating();
        }

        function closeReviewModal() {
            document.getElementById('addReviewModal').classList.add('hidden');
            resetStarRating();
        }

        function resetStarRating() {
            selectedRating = 0;
            document.querySelectorAll('.star').forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });
            document.getElementById('ratingInput').value = '';
        }

        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.star').forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    selectedRating = rating;
                    document.getElementById('ratingInput').value = rating;
                    
                    // Update star display
                    document.querySelectorAll('.star').forEach((s, index) => {
                        if (index < rating) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-400');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                        }
                    });
                });

                star.addEventListener('mouseenter', function() {
                    const rating = parseInt(this.dataset.rating);
                    document.querySelectorAll('.star').forEach((s, index) => {
                        if (index < rating) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-400');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                        }
                    });
                });
            });

            // Reset stars on mouse leave
            document.getElementById('starRating').addEventListener('mouseleave', function() {
                document.querySelectorAll('.star').forEach((s, index) => {
                    if (index < selectedRating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.id === 'addEventModal') {
                closeAddEventModal();
            }
            if (e.target.id === 'addReviewModal') {
                closeReviewModal();
            }
        });
    </script>
</x-layouts.app>
