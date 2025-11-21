<x-layouts.app :title="__('Edit Event')">
    <div class="max-w-4xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-green-700">Edit Event</h1>
            <p class="text-gray-600 mt-2">Update event details and information</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Edit Event Details</h2>
                        <p class="text-green-100 text-sm">Update event information below</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <form action="{{ route('admin.events.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Title</label>
                        <input type="text" name="title" placeholder="Enter event title..." value="{{ old('title', $post->title) }}" 
                               class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required />
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Description</label>
                        <textarea name="description" placeholder="Brief event description..." rows="3" 
                                  class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">{{ old('description', $post->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date & Time</label>
                            <input type="datetime-local" name="event_date" value="{{ old('event_date', $post->event_date ? date('Y-m-d\TH:i', strtotime($post->event_date)) : '') }}" 
                                   class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" />
                            @error('event_date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                            <input type="text" name="location" placeholder="Event location..." value="{{ old('location', $post->location) }}" 
                                   class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" />
                            @error('location')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum Participants (Optional)</label>
                        <input type="number" name="max_registrations" placeholder="Leave empty for unlimited..." value="{{ old('max_registrations', $post->max_registrations) }}" 
                               class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" min="1" />
                        <p class="text-xs text-gray-500 mt-1">Set maximum number of registrations. Leave empty for unlimited registrations.</p>
                        @error('max_registrations')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Details/Content</label>
                        <textarea name="content" placeholder="Detailed event information..." rows="4" 
                                  class="w-full rounded-lg border border-gray-300 text-gray-900 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Event Image</label>
                        @if($post->image_path)
                            @php
                                $imageUrl = null;
                                $imageExists = false;
                                
                                if ($post->image_path) {
                                    $s3Configured = !empty(env('AWS_BUCKET')) && !empty(env('AWS_ACCESS_KEY_ID'));
                                    
                                    // Try S3 first if configured
                                    if ($s3Configured) {
                                        try {
                                            if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($post->image_path)) {
                                                $imageExists = true;
                                                $imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($post->image_path);
                                            }
                                        } catch (\Exception $e) {
                                            // S3 error, fall through to local storage
                                        }
                                    }
                                    
                                    // Fallback to local storage (storage/app/public/)
                                    if (!$imageExists && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->image_path)) {
                                        $imageExists = true;
                                        $imageUrl = asset('storage/' . $post->image_path);
                                    }
                                }
                            @endphp
                            @if($imageExists)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                    <img src="{{ $imageUrl }}" 
                                         alt="Current Event Image" 
                                         class="w-full h-48 object-cover rounded-lg border border-gray-300"
                                         onerror="this.onerror=null; this.src='{{ asset('storage/' . $post->image_path) }}';">
                                </div>
                            @endif
                        @endif
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-green-400 transition-colors">
                            <input type="file" name="image" accept="image/*" class="hidden" id="event-image" />
                            <label for="event-image" class="cursor-pointer">
                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-600">{{ $post->image_path ? 'Click to upload new image' : 'Click to upload image' }}</p>
                            </label>
                        </div>
                        @error('image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Event
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="flex-1 bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 transition-all duration-200 text-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>

