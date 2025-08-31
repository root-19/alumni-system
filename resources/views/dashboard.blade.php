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
                                @if($event->image_path)
                                    <img src="{{ asset('storage/'.$event->image_path) }}" 
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

                {{-- Top Contributors from Donations --}}
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="font-semibold mb-3 text-gray-800">Top Contributors</h4>
                    
                    @if($topContributors->count() > 0)
                        <div class="space-y-3">
                            @foreach($topContributors as $index => $contributor)
                                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="relative">
                                        {{-- Always use initials since we're not fetching profile image data --}}
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-semibold text-sm border-2 border-gray-200">
                                            {{ substr($contributor->name, 0, 2) }}
                                        </div>
                                        {{-- Rank badge --}}
                                        @if($index < 3)
                                            @php
                                                $rankColors = ['bg-yellow-500', 'bg-gray-400', 'bg-orange-500'];
                                                $rankIcons = ['🥇', '🥈', '🥉'];
                                            @endphp
                                            <div class="absolute -top-1 -right-1 w-5 h-5 {{ $rankColors[$index] }} rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                {{ $rankIcons[$index] }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-sm text-gray-800 truncate">{{ $contributor->name }}</p>
                                        <p class="text-xs text-green-600 font-semibold">
                                            ₱{{ number_format($contributor->total_donated, 2) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <a href="{{ route('donations') }}" class="text-sm text-green-600 hover:text-green-700 font-medium inline-flex items-center gap-1">
                                View All Donations
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="w-12 h-12 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 mb-2">No confirmed donations yet</p>
                            <p class="text-xs text-gray-400 mb-2">Only confirmed donations are counted</p>
                            <a href="{{ route('donations') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                                Be the first to donate
                            </a>
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
                @if($featuredNews->image_path)
                    <img src="{{ asset('storage/'.$featuredNews->image_path) }}" 
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
                            @if($post->image_path)
                                <img src="{{ asset('storage/'.$post->image_path) }}" 
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
                                @php
                                    $categories = ['Alumni', 'Achievement', 'Event', 'Education', 'Community'];
                                    $colors = ['bg-blue-600', 'bg-purple-600', 'bg-orange-600', 'bg-green-600', 'bg-red-600'];
                                    $category = $categories[$index % count($categories)];
                                    $color = $colors[$index % count($colors)];
                                @endphp
                                <span class="px-3 py-1 {{ $color }} text-white text-xs font-semibold rounded-full">{{ $category }}</span>
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
</x-layouts.app>
