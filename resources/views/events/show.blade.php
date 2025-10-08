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

            <div class="flex items-center text-xs uppercase tracking-wide text-gray-500 space-x-2">
                <span class="font-semibold text-gray-600">Posted:</span>
                <span>{{ optional($post->created_at)->format('F j, Y \a\t g:i A') }}</span>
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">
                Comments ({{ $post->comments->count() }})
            </h2>

            {{-- Add Comment Button --}}
            @auth
            <div class="mb-6">
                <button type="button" onclick="document.getElementById('comment-form').classList.toggle('hidden')" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition flex items-center space-x-2">
                    <span>Add Comment</span>
                    <svg class="w-4 h-4 transition-transform" id="comment-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                {{-- Comment Form (Hidden by default) --}}
                <form action="{{ route('comments.store', $post->id) }}" method="POST" id="comment-form" class="hidden mt-4">
                    @csrf
                    <textarea name="content" rows="3" 
                              class="border border-gray-300 rounded-lg w-full p-3 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition text-black" 
                              placeholder="Write your comment..." required></textarea>
                    <div class="flex gap-2 mt-3">
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-medium shadow-sm transition">
                            Post Comment
                        </button>
                        <button type="button" onclick="document.getElementById('comment-form').classList.add('hidden')" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg font-medium shadow-sm transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
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
                            <button type="submit" class="flex items-center space-x-1 hover:text-green-600 transition-colors">
                                <span class="text-gray-500">Like:</span>
                                <span class="text-gray-600">{{ $comment->likes_count ?? 0 }}</span>
                            </button>
                        </form>

                        {{-- Reply Toggle --}}
                        <button type="button" onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')" class="flex items-center space-x-1 hover:text-green-600 transition-colors">
                            <span class="text-gray-500">Reply:</span>
                            <span class="text-gray-600">{{ $comment->replies->count() ?? 0 }}</span>
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
                        <form action="{{ route('comments.reply', $comment) }}" method="POST" id="reply-form-{{ $comment->id }}" class="mt-2 hidden">
                            @csrf
                            <textarea name="content" rows="2" 
                                      placeholder="Write a reply..." 
                                      class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition text-black"></textarea>
                            <div class="flex gap-2 mt-2">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                                    Reply
                                </button>
                                <button type="button" onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.add('hidden')" 
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                                    Cancel
                                </button>
                            </div>
                        </form>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // Toggle comment form with arrow rotation
        function toggleCommentForm() {
            const form = document.getElementById('comment-form');
            const arrow = document.getElementById('comment-arrow');
            
            form.classList.toggle('hidden');
            
            if (form.classList.contains('hidden')) {
                arrow.style.transform = 'rotate(0deg)';
            } else {
                arrow.style.transform = 'rotate(180deg)';
            }
        }

        // Update the button onclick to use the new function
        document.addEventListener('DOMContentLoaded', function() {
            const commentButton = document.querySelector('button[onclick*="comment-form"]');
            if (commentButton) {
                commentButton.setAttribute('onclick', 'toggleCommentForm()');
            }
        });
    </script>
</x-layouts.app>