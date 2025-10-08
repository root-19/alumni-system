<x-layouts.app :title="__('Events')">
    <div class="max-w-7xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-green-700 mb-8">Events</h1>

        @if($alumniPosts->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
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
                            {{-- Event Content --}}
                            <p class="text-gray-800 text-base mb-4 line-clamp-3">
                                {{ $post->content }}
                            </p>

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
        @else
            <div class="text-center text-gray-400 py-16 text-lg">
                No news or events posted yet.
            </div>
        @endif
    </div>
</x-layouts.app>

