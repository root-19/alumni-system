<x-layouts.app :title="$post->content ?? 'Event Details'">
    <div class="max-w-4xl mx-auto mt-8 space-y-8">

        {{-- Event Card --}}
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
            @if($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" 
                     alt="Event Image" 
                     class="w-full h-72 object-cover rounded-xl mb-5 shadow-sm">
            @endif

            <h1 class="text-2xl font-bold text-gray-900 leading-snug mb-3">
                {{ $post->content ?? 'No content available' }}
            </h1>

            <div class="flex items-center text-sm text-gray-500 space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z" />
                </svg>
                <span>Posted on {{ optional($post->created_at)->format('F j, Y \a\t g:i A') }}</span>
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center space-x-2">
                <span>💬</span>
                <span>Comments ({{ $post->comments->count() }})</span>
            </h2>

            {{-- Add Comment Form --}}
            @auth
            <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-6">
                @csrf
                <textarea name="content" rows="3" 
                          class="border border-gray-300 rounded-lg w-full p-3 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition text-black" 
                          placeholder="Write your comment..." required></textarea>
                <button type="submit" 
                        class="mt-3 bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-medium shadow-sm transition">
                    Post Comment
                </button>
            </form>
            @else
            <p class="text-gray-500 mb-6">Please <a href="{{ route('login') }}" class="text-green-600 hover:underline">log in</a> to post a comment.</p>
            @endauth

            {{-- Display Comments --}}
            @foreach($post->comments as $comment)
                <div class="bg-gray-50 p-4 rounded-xl mb-4 border border-gray-200 hover:bg-gray-100 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            {{-- Circle user icon --}}
                            <div class="w-10 h-10 bg-green-200 text-green-800 rounded-full flex items-center justify-center font-bold">
                                {{ strtoupper(substr($comment->user?->name ?? 'U', 0, 1)) }}
                            </div>
                            <strong class="text-gray-900">
                                {{ $comment->user?->name ?? 'Unknown User' }}
                            </strong>
                        </div>
                        <span class="text-xs text-gray-400">{{ $comment->created_at?->diffForHumans() ?? '' }}</span>
                    </div>

                    <p class="mt-2 text-gray-900">{{ $comment->content }}</p>

                    {{-- Action Buttons --}}
                    <div class="flex items-center mt-2 space-x-4 text-sm text-gray-500">
                        {{-- Like Button --}}
                        <form action="{{ route('comments.like', $comment) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-1 hover:text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="{{ $comment->likedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                                <span>{{ $comment->likes_count ?? 0 }}</span>
                            </button>
                        </form>

                        {{-- Reply Toggle --}}
                        <button type="button" onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')" class="flex items-center space-x-1 hover:text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3 3 4-4 4 4 3-3h4" />
                            </svg>
                            <span>{{ $comment->replies->count() ?? 0 }}</span>
                        </button>
                    </div>

                    {{-- Replies --}}
                    <div class="ml-6 mt-3 space-y-2">
                        @foreach($comment->replies as $reply)
                        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                            <strong class="text-gray-900">{{ $reply->user?->name ?? 'Unknown User' }}</strong>
                            <p class="text-gray-800">{{ $reply->content }}</p>
                        </div>
                        @endforeach

                        {{-- Reply Form --}}
                        @auth
                        <form action="{{ route('comments.reply', $comment) }}" method="POST" id="reply-form-{{ $comment->id }}" class="mt-2 flex hidden">
                            @csrf
                            <input type="text" name="content" 
                                   placeholder="Write a reply..." 
                                   class="border border-gray-300 rounded-lg p-2 flex-1 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition text-black">
                            <button type="submit" class="ml-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                                Reply
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>