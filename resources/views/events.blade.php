<x-layouts.app :title="__('Events')">
    <div class="max-w-7xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-green-700 mb-8">Events</h1>

        {{-- Active Events --}}
        @if($alumniPosts->count())
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Active Events</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($alumniPosts as $post)
                    <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover:shadow-2xl transition duration-300">
                        {{-- Event Image --}}
                        @if($post->image_path)
                            <a href="{{ route('events.show', $post) }}" class="block overflow-hidden">
                                <img src="{{ asset('storage/' . $post->image_path) }}" 
                                     alt="Event Image" 
                                     class="w-full h-48 object-cover transform hover:scale-105 transition duration-500">
                            </a>
                        @endif

                        <div class="p-6 flex flex-col justify-between h-full">
                            {{-- Event Title --}}
                            @if($post->title)
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $post->title }}</h3>
                            @endif
                            
                            {{-- Event Description --}}
                            @if($post->description)
                                <p class="text-blue-600 text-sm mb-3 font-medium">{{ $post->description }}</p>
                            @endif
                            
                            {{-- Event Content --}}
                            <p class="text-gray-800 text-base mb-4 line-clamp-3">
                                {{ $post->content }}
                            </p>
                            
                            {{-- Event Date & Location --}}
                            @if($post->event_date || $post->location)
                                <div class="mb-4 space-y-1">
                                    @if($post->event_date)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($post->event_date)->format('M j, Y g:i A') }}
                                        </div>
                                    @endif
                                    @if($post->location)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $post->location }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Meta: likes & comments --}}
                            <div class="flex items-center gap-8 mb-4 text-xs font-medium tracking-wide text-gray-600 uppercase">
                                @php $likeCount = method_exists($post, 'likes') ? $post->likes->count() : ($post->like_count ?? 0); @endphp
                                @php $commentCount = method_exists($post, 'comments') ? $post->comments->count() : ($post->comment_count ?? 0); @endphp
                                <span class="flex items-center gap-1">
                                    <span class="text-gray-500">Like:</span>
                                    <span class="text-gray-800">{{ $likeCount }}</span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="text-gray-500">Comment:</span>
                                    <span class="text-gray-800">{{ $commentCount }}</span>
                                </span>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-400">
                                {{-- Date & Time --}}
                                <span class="mb-2 sm:mb-0">
                                    {{ $post->created_at->format('F j, Y \a\t g:i A') }}
                                </span>

                                {{-- Minimal view link --}}
                                <a href="{{ route('events.show', $post) }}" 
                                   class="text-green-600 hover:text-green-800 font-semibold transition">
                                    Details →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Completed Events --}}
        @if(isset($completedEvents) && $completedEvents->count())
            <h2 class="text-2xl font-bold text-gray-600 mb-6 mt-12">Completed Events</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($completedEvents as $post)
                    <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover:shadow-2xl transition duration-300 opacity-75">
                        {{-- Event Image --}}
                        @if($post->image_path)
                            <a href="{{ route('events.show', $post) }}" class="block overflow-hidden">
                                <img src="{{ asset('storage/' . $post->image_path) }}" 
                                     alt="Event Image" 
                                     class="w-full h-48 object-cover transform hover:scale-105 transition duration-500 grayscale">
                            </a>
                        @endif

                        <div class="p-6 flex flex-col justify-between h-full">
                            {{-- Event Title --}}
                            @if($post->title)
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $post->title }}</h3>
                            @endif
                            
                            {{-- Event Description --}}
                            @if($post->description)
                                <p class="text-blue-600 text-sm mb-3 font-medium">{{ $post->description }}</p>
                            @endif
                            
                            {{-- Event Content --}}
                            <p class="text-gray-800 text-base mb-4 line-clamp-3">
                                {{ $post->content }}
                            </p>
                            
                            {{-- Event Date & Location --}}
                            @if($post->event_date || $post->location)
                                <div class="mb-4 space-y-1">
                                    @if($post->event_date)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($post->event_date)->format('M j, Y g:i A') }}
                                        </div>
                                    @endif
                                    @if($post->location)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $post->location }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Completed Badge --}}
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-500 text-white">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Completed
                                </span>
                            </div>

                            {{-- Meta: likes & comments --}}
                            <div class="flex items-center gap-8 mb-4 text-xs font-medium tracking-wide text-gray-600 uppercase">
                                @php $likeCount = method_exists($post, 'likes') ? $post->likes->count() : ($post->like_count ?? 0); @endphp
                                @php $commentCount = method_exists($post, 'comments') ? $post->comments->count() : ($post->comment_count ?? 0); @endphp
                                <span class="flex items-center gap-1">
                                    <span class="text-gray-500">Like:</span>
                                    <span class="text-gray-800">{{ $likeCount }}</span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="text-gray-500">Comment:</span>
                                    <span class="text-gray-800">{{ $commentCount }}</span>
                                </span>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-400">
                                {{-- Date & Time --}}
                                <span class="mb-2 sm:mb-0">
                                    {{ $post->created_at->format('F j, Y \a\t g:i A') }}
                                </span>

                                {{-- Minimal view link --}}
                                <a href="{{ route('events.show', $post) }}" 
                                   class="text-green-600 hover:text-green-800 font-semibold transition">
                                    Details →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!$alumniPosts->count() && (!isset($completedEvents) || !$completedEvents->count()))
            <div class="text-center text-gray-400 py-16 text-lg">
                No news or events posted yet.
            </div>
        @endif
    </div>
</x-layouts.app>

