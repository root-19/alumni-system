<x-layouts.app :title="__('Events')">
    <div class="max-w-7xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-4xl font-extrabold text-green-700">Events Management</h1>
            <a href="{{ route('admin.news') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg hover:shadow-xl">
                + Create New Event
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        {{-- Active Events --}}
        @if($alumniPosts->count())
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Active Events</h2>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($alumniPosts as $post)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($post->image_path)
                                            @php
                                                $imageUrl = null;
                                                $imageExists = false;
                                                
                                                // Try local storage first (storage/app/public/)
                                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($post->image_path)) {
                                                    $imageExists = true;
                                                    $imageUrl = asset('storage/' . $post->image_path);
                                                } 
                                                // Fallback to default disk (S3 or public)
                                                else {
                                                    $defaultDisk = config('filesystems.default');
                                                    if (\Illuminate\Support\Facades\Storage::disk($defaultDisk)->exists($post->image_path)) {
                                                        $imageExists = true;
                                                        $imageUrl = \Illuminate\Support\Facades\Storage::disk($defaultDisk)->url($post->image_path);
                                                    }
                                                }
                                            @endphp
                                            @if($imageExists)
                                                <img class="h-16 w-24 object-cover rounded-lg mr-4 border border-gray-200" 
                                                    src="{{ $imageUrl }}" 
                                                    alt="Event"
                                                    onerror="this.onerror=null; this.src='{{ asset('storage/' . $post->image_path) }}';">
                                            @else
                                                <div class="h-16 w-24 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        @else
                                            <div class="h-16 w-24 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="text-xs text-gray-500">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        @if($post->title)
                                            <div class="text-sm font-semibold text-gray-900">{{ $post->title }}</div>
                                        @endif
                                        <div class="text-sm text-gray-600 mt-1">{{ Str::limit($post->description ?? $post->content, 60) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($post->event_date)
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($post->event_date)->format('M j, Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($post->event_date)->format('g:i A') }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($post->location)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="max-w-xs truncate">{{ $post->location }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.events.show', $post) }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded transition text-xs font-semibold flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('admin.events.edit', $post) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded transition text-xs font-semibold flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.events.archive', $post) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Archive this event?')" 
                                                    class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1.5 rounded transition text-xs font-semibold flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                </svg>
                                                Archive
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination for Active Events --}}
            @if($alumniPosts->hasPages())
                <div class="mt-6">
                    {{ $alumniPosts->links() }}
                </div>
            @endif
        @endif

        {{-- Completed Events --}}
        @if(isset($completedEvents) && $completedEvents->count())
            <h2 class="text-2xl font-bold text-gray-600 mb-6 mt-12">Completed Events</h2>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden opacity-75">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($completedEvents as $post)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($post->image_path)
                                            @php
                                                $imageUrl = null;
                                                $imageExists = false;
                                                
                                                // Try local storage first (storage/app/public/)
                                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($post->image_path)) {
                                                    $imageExists = true;
                                                    $imageUrl = asset('storage/' . $post->image_path);
                                                } 
                                                // Fallback to default disk (S3 or public)
                                                else {
                                                    $defaultDisk = config('filesystems.default');
                                                    if (\Illuminate\Support\Facades\Storage::disk($defaultDisk)->exists($post->image_path)) {
                                                        $imageExists = true;
                                                        $imageUrl = \Illuminate\Support\Facades\Storage::disk($defaultDisk)->url($post->image_path);
                                                    }
                                                }
                                            @endphp
                                            @if($imageExists)
                                                <img class="h-16 w-24 object-cover rounded-lg mr-4 border border-gray-200 grayscale" 
                                                     src="{{ $imageUrl }}" 
                                                     alt="Event"
                                                     onerror="this.onerror=null; this.src='{{ asset('storage/' . $post->image_path) }}';">
                                            @else
                                                <div class="h-16 w-24 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        @else
                                            <div class="h-16 w-24 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="text-xs text-gray-500">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        @if($post->title)
                                            <div class="text-sm font-semibold text-gray-900">{{ $post->title }}</div>
                                        @endif
                                        <div class="text-sm text-gray-600 mt-1">{{ Str::limit($post->description ?? $post->content, 60) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($post->event_date)
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($post->event_date)->format('M j, Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($post->event_date)->format('g:i A') }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($post->location)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="max-w-xs truncate">{{ $post->location }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-500 text-white">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Completed
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.events.show', $post) }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded transition text-xs font-semibold flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('admin.events.edit', $post) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded transition text-xs font-semibold flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination for Completed Events --}}
            @if($completedEvents->hasPages())
                <div class="mt-6">
                    {{ $completedEvents->links() }}
                </div>
            @endif
        @endif

        @if($alumniPosts->count() == 0 && $completedEvents->count() == 0)
            <div class="text-center py-16 bg-white rounded-2xl shadow">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No events</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new event.</p>
            </div>
        @endif
    </div>
</x-layouts.app>
