<x-layouts.app :title="__('Events')">
    <div class="max-w-7xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-green-700 mb-8">Events</h1>

        {{-- Active Events --}}
        @if($alumniPosts->count())
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Active Events</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($alumniPosts as $post)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group">
                        {{-- Event Image --}}
                        @if($post->image_path)
                            @php
                                $defaultDisk = config('filesystems.default');
                                $imageUrl = null;
                                $imageExists = false;
                                
                                // Try default disk first (S3 or public)
                                if (\Illuminate\Support\Facades\Storage::disk($defaultDisk)->exists($post->image_path)) {
                                    $imageExists = true;
                                    $imageUrl = \Illuminate\Support\Facades\Storage::disk($defaultDisk)->url($post->image_path);
                                } 
                                // Fallback to local storage
                                elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($post->image_path)) {
                                    $imageExists = true;
                                    $imageUrl = asset('storage/' . $post->image_path);
                                }
                            @endphp
                            @if($imageExists)
                                <a href="{{ route('events.show', $post) }}" class="block overflow-hidden relative">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10"></div>
                                    <img src="{{ $imageUrl }}" 
                                         alt="Event Image" 
                                         class="w-full h-56 object-cover transform group-hover:scale-110 transition-transform duration-500"
                                         onerror="this.onerror=null; this.src='{{ asset('storage/' . $post->image_path) }}';">
                                </a>
                            @else
                                <div class="w-full h-56 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        @else
                            <div class="w-full h-56 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <div class="p-5 flex flex-col">
                            {{-- Event Title --}}
                            @if($post->title)
                                <a href="{{ route('events.show', $post) }}" class="block mb-3">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors duration-200">{{ $post->title }}</h3>
                                </a>
                            @endif
                            
                            {{-- Event Date & Location --}}
                            @if($post->event_date || $post->location)
                                <div class="mb-4 space-y-2">
                                    @if($post->event_date)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 mr-3 flex-shrink-0">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($post->event_date)->format('M j, Y') }}</span>
                                            <span class="text-gray-500 ml-2">{{ \Carbon\Carbon::parse($post->event_date)->format('g:i A') }}</span>
                                        </div>
                                    @endif
                                    @if($post->location)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 mr-3 flex-shrink-0">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-700 font-medium line-clamp-1">{{ $post->location }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Divider --}}
                            <div class="border-t border-gray-100 my-4"></div>

                            {{-- Meta: likes & comments --}}
                           

                            {{-- Footer with date and link --}}
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <span class="text-xs text-gray-500">
                                    {{ $post->created_at->format('M j, Y') }}
                                </span>
                                <a href="{{ route('events.show', $post) }}" 
                                   class="inline-flex items-center text-sm font-semibold text-green-600 hover:text-green-700 transition-colors duration-200 group/link">
                                    View Details
                                    <svg class="w-4 h-4 ml-1 transform group-hover/link:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($completedEvents as $post)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group opacity-90">
                        {{-- Event Image --}}
        

                        @php
                            $defaultDisk = config('filesystems.default');
                            $imageUrl = null;
                            $imageExists = false;
                            
                            if ($post->image_path) {
                                // Try default disk first (S3 or public)
                                if (\Illuminate\Support\Facades\Storage::disk($defaultDisk)->exists($post->image_path)) {
                                    $imageExists = true;
                                    $imageUrl = \Illuminate\Support\Facades\Storage::disk($defaultDisk)->url($post->image_path);
                                } 
                                // Fallback to local storage
                                elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($post->image_path)) {
                                    $imageExists = true;
                                    $imageUrl = asset('storage/' . $post->image_path);
                                }
                            }
                        @endphp
                        @if($imageExists)
                            <a href="{{ route('events.show', $post) }}" class="block overflow-hidden relative group">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10"></div>
                                <img src="{{ $imageUrl }}"
                                     onerror="this.onerror=null; this.src='{{ asset('storage/' . $post->image_path) }}';" 
             alt="Event Image" 
             class="w-full h-56 object-cover transform group-hover:scale-110 transition-transform duration-500 grayscale group-hover:grayscale-0">
    </a>
@else
    <div class="w-full h-56 bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    </div>
@endif


                        <div class="p-5 flex flex-col">
                            {{-- Completed Badge --}}
                            <div class="mb-3">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-600 text-white shadow-sm">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Completed
                                </span>
                            </div>

                            {{-- Event Title --}}
                            @if($post->title)
                                <a href="{{ route('events.show', $post) }}" class="block mb-3">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-green-600 transition-colors duration-200">{{ $post->title }}</h3>
                                </a>
                            @endif
                            
                        
                            
                            {{-- Event Date & Location --}}
                            @if($post->event_date || $post->location)
                                <div class="mb-4 space-y-2">
                                    @if($post->event_date)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 mr-3 flex-shrink-0">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($post->event_date)->format('M j, Y') }}</span>
                                            <span class="text-gray-500 ml-2">{{ \Carbon\Carbon::parse($post->event_date)->format('g:i A') }}</span>
                                        </div>
                                    @endif
                                    @if($post->location)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 mr-3 flex-shrink-0">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-700 font-medium line-clamp-1">{{ $post->location }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Divider --}}
                            <div class="border-t border-gray-100 my-4"></div>

                            {{-- Meta: likes & comments --}}
                        

                            {{-- Footer with date and link --}}
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <span class="text-xs text-gray-500">
                                    {{ $post->created_at->format('M j, Y') }}
                                </span>
                                <a href="{{ route('events.show', $post) }}" 
                                   class="inline-flex items-center text-sm font-semibold text-green-600 hover:text-green-700 transition-colors duration-200 group/link">
                                    View Details
                                    <svg class="w-4 h-4 ml-1 transform group-hover/link:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
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

