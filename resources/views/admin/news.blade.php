<x-layouts.app :title="__('News and updates')">
    <div class="min-h-screen space-y-10">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl shadow-lg overflow-hidden">
            <div class="px-8 py-12">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-white mb-2">News & Events Management</h1>
                        <p class="text-green-100 text-lg">Create and manage news articles and events</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Forms Section -->
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Create News Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Create News Article</h2>
                            <p class="text-green-100 text-sm">Share important updates with the community</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">News Title</label>
                            <input type="text" name="title" placeholder="Enter news title..." value="{{ old('title') }}" 
                                   class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required />
                            @error('title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Content</label>
                            <textarea name="content" rows="4" placeholder="Write your news content here..." 
                                      class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Featured Image</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-green-400 transition-colors">
                                <input type="file" name="image" accept="image/*" class="hidden" id="news-image" />
                                <label for="news-image" class="cursor-pointer">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Click to upload image</p>
                                </label>
                            </div>
                            @error('image')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Publish News Article
                        </button>
                    </form>
                </div>
            </div>

            <!-- Create Event Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Create Event</h2>
                            <p class="text-green-100 text-sm">Organize and promote community events</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('alumni_posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event Title</label>
                            <input type="text" name="title" placeholder="Enter event title..." value="{{ old('title') }}" 
                                   class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required />
                            @error('title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event Description</label>
                            <textarea name="description" placeholder="Brief event description..." rows="3" 
                                      class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date & Time</label>
                                <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" 
                                       class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" />
                                @error('event_date')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                                <input type="text" name="location" placeholder="Event location..." value="{{ old('location') }}" 
                                       class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" />
                                @error('location')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum Participants (Optional)</label>
                            <input type="number" name="max_registrations" placeholder="Leave empty for unlimited..." value="{{ old('max_registrations') }}" 
                                   class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" min="1" />
                            <p class="text-xs text-gray-500 mt-1">Set maximum number of registrations. Leave empty for unlimited registrations.</p>
                            @error('max_registrations')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event Details</label>
                            <textarea name="content" placeholder="Detailed event information..." rows="4" 
                                      class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event Image</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-green-400 transition-colors">
                                <input type="file" name="image" accept="image/*" class="hidden" id="event-image" />
                                <label for="event-image" class="cursor-pointer">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Click to upload image</p>
                                </label>
                            </div>
                            @error('image')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Event
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hero with overlay: uses latest news/alumni image; text shows News and Updates and latest news title if present -->
        <section class="rounded-2xl shadow overflow-hidden">
            @php($heroImage = $featuredNews?->image_path ?? $featuredAlumni?->image_path)
            <div class="relative">
                @if($heroImage)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($heroImage) }}" alt="Hero" class="w-full h-72 object-cover">
                @endif
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="absolute inset-0 flex items-end p-6">
                    <div class="text-white max-w-3xl">
                        <p class="uppercase tracking-wider text-sm">Latest News</p>
                        @if($featuredNews)
                            <h1 class="text-3xl md:text-4xl font-semibold leading-tight">{{ $featuredNews->title }}</h1>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- News List (dynamic only) -->
        @if($news->count())
        <section class="space-y-4">
            <div class="grid gap-6">
                @foreach($news as $item)
                    <article class="bg-white rounded-2xl shadow overflow-hidden">
                        <div class="grid md:grid-cols-3">
                            @if($item->image_path)
                                <div>
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-44 md:h-full object-cover">
                                </div>
                            @endif
                            <div class="md:col-span-2 p-6 space-y-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->title }}</h3>
                                <p class="text-gray-700">{{ \Illuminate\Support\Str::limit($item->content, 220) }}</p>
                                <span class="text-xs text-gray-500">{{ $item->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Alumni Posts Grid (dynamic only) -->
        @if($alumniPosts->count())
        <section>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($alumniPosts as $post)
                    <article class="bg-white rounded-2xl shadow p-4">
                        @if($post->image_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($post->image_path) }}" class="w-full h-48 object-cover rounded-xl" alt="">
                        @endif
                        <p class="text-xs md:text-sm font-semibold text-gray-800 text-center uppercase tracking-wide mt-3">
                            {{ strtoupper(\Illuminate\Support\Str::limit(strip_tags($post->content), 60)) }}
                        </p>
                    </article>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</x-layouts.app>