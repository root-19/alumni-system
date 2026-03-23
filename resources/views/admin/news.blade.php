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

        <!-- Management Section -->
        <div class="space-y-8">
            <!-- News Management -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Manage News Articles</h2>
                                <p class="text-green-100 text-sm">Edit or delete existing news articles</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($news->count() > 0)
                        <div class="space-y-4">
                            @foreach($news as $item)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $item->title }}</h3>
                                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit(strip_tags($item->content), 150) }}</p>
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <span>Created: {{ $item->created_at->format('M d, Y') }}</span>
                                                @if($item->image_path)
                                                    <span class="text-green-600">✓ Has Image</span>
                                                @else
                                                    <span class="text-gray-400">No Image</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 ml-4">
                                            <button onclick="editNews({{ $item->id }})" 
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>
                                            <form action="{{ route('news.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this news article?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            <p>No news articles found. Create your first news article above!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Events Management -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Manage Events</h2>
                                <p class="text-green-100 text-sm">Edit or archive existing events</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if($alumniPosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($alumniPosts as $event)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit(strip_tags($event->content), 150) }}</p>
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                @if($event->event_date)
                                                    <span>Event: {{ $event->event_date->format('M d, Y') }}</span>
                                                @endif
                                                @if($event->location)
                                                    <span>📍 {{ $event->location }}</span>
                                                @endif
                                                @if($event->image_path)
                                                    <span class="text-green-600">✓ Has Image</span>
                                                @else
                                                    <span class="text-gray-400">No Image</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 ml-4">
                                            <button onclick="editEvent({{ $event->id }})" 
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>
                                            <form action="{{ route('alumni_posts.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure you want to archive this event?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14l-1 12H6L5 8zM7 8V6a2 2 0 012-2h6a2 2 0 012 2v2M9 8h6"></path>
                                                    </svg>
                                                    Archive
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p>No events found. Create your first event above!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    <div id="editNewsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Edit News Article</h3>
                <form id="editNewsForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editNewsId" name="news_id">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">News Title</label>
                            <input type="text" id="editNewsTitle" name="title" required
                                   class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Content</label>
                            <textarea id="editNewsContent" name="content" rows="4" required
                                      class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Update Image (Optional)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-green-400 transition-colors">
                                <input type="file" name="image" accept="image/*" class="hidden" id="editNewsImage" />
                                <label for="editNewsImage" class="cursor-pointer">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Click to upload new image (optional)</p>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                Update News Article
                            </button>
                            <button type="button" onclick="closeEditNewsModal()" class="flex-1 bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div id="editEventModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Edit Event</h3>
                <form id="editEventForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editEventId" name="event_id">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event Title</label>
                            <input type="text" id="editEventTitle" name="title" required
                                   class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event Description</label>
                            <textarea id="editEventDescription" name="description" rows="3"
                                      class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date & Time</label>
                                <input type="datetime-local" id="editEventDate" name="event_date"
                                       class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                                <input type="text" id="editEventLocation" name="location"
                                       class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum Participants (Optional)</label>
                            <input type="number" id="editEventMaxRegistrations" name="max_registrations" min="1"
                                   class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event Details</label>
                            <textarea id="editEventContent" name="content" rows="4" required
                                      class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Update Event Image (Optional)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-green-400 transition-colors">
                                <input type="file" name="image" accept="image/*" class="hidden" id="editEventImage" />
                                <label for="editEventImage" class="cursor-pointer">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Click to upload new image (optional)</p>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                Update Event
                            </button>
                            <button type="button" onclick="closeEditEventModal()" class="flex-1 bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // News data for editing
        const newsData = @json($news);
        const eventsData = @json($alumniPosts);

        function editNews(id) {
            const news = newsData.find(n => n.id === id);
            if (news) {
                document.getElementById('editNewsId').value = news.id;
                document.getElementById('editNewsTitle').value = news.title;
                document.getElementById('editNewsContent').value = news.content;
                document.getElementById('editNewsForm').action = '{{ route("news.update", ":id") }}'.replace(':id', id);
                document.getElementById('editNewsModal').classList.remove('hidden');
            }
        }

        function closeEditNewsModal() {
            document.getElementById('editNewsModal').classList.add('hidden');
        }

        function editEvent(id) {
            const event = eventsData.find(e => e.id === id);
            if (event) {
                document.getElementById('editEventId').value = event.id;
                document.getElementById('editEventTitle').value = event.title;
                document.getElementById('editEventDescription').value = event.description || '';
                document.getElementById('editEventDate').value = event.event_date ? new Date(event.event_date).toISOString().slice(0, 16) : '';
                document.getElementById('editEventLocation').value = event.location || '';
                document.getElementById('editEventMaxRegistrations').value = event.max_registrations || '';
                document.getElementById('editEventContent').value = event.content;
                document.getElementById('editEventForm').action = '{{ route("alumni_posts.update", ":id") }}'.replace(':id', id);
                document.getElementById('editEventModal').classList.remove('hidden');
            }
        }

        function closeEditEventModal() {
            document.getElementById('editEventModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.getElementById('editNewsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditNewsModal();
            }
        });

        document.getElementById('editEventModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditEventModal();
            }
        });
    </script>
</x-layouts.app>