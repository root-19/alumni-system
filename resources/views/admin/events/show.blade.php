<x-layouts.app :title="$post->content ?? 'Event Details'">
    <div class="max-w-4xl mx-auto mt-8 space-y-8">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

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

            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    {{ $post->registrations->count() }} attendee{{ $post->registrations->count() === 1 ? '' : 's' }} registered
                </div>
                @auth
                    @php $isRegistered = $post->isRegisteredBy(auth()->user()); @endphp
                    @if($isRegistered)
                        <form method="POST" action="{{ route('events.unregister', $post) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition">
                                Cancel Registration
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('events.register', $post) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="category" class="border border-gray-300 rounded-md text-sm px-2 py-1 text-black">
                                <option value="Alumni">Alumni</option>
                                <option value="Student">Student</option>
                               
                            </select>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition">
                                Register
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-green-700 hover:text-green-800 text-sm font-medium">Log in to register →</a>
                @endauth
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">
                Comments ({{ $post->comments->count() }})
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
                                <span class="text-gray-500">Like:</span>
                                <span>{{ $comment->likes_count ?? 0 }}</span>
                            </button>
                        </form>

                        {{-- Reply Toggle --}}
                        <button type="button" onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')" class="flex items-center space-x-1 hover:text-green-600">
                            <span class="text-gray-500">reply:</span>
                            <span>{{ $comment->replies->count() ?? 0 }}</span>
                        </button>
                    </div>

                    {{-- Replies --}}
                    <div class="ml-6 mt-3 space-y-3">
                        @foreach($comment->replies as $reply)
                        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <strong class="text-gray-900">{{ $reply->user?->name ?? 'Unknown User' }}</strong>
                                    <span class="text-xs text-gray-400">{{ $reply->created_at?->diffForHumans() ?? '' }}</span>
                                </div>
                            </div>
                            <p class="text-gray-800 mt-1">{{ $reply->content }}</p>
                            
                            {{-- Reply Action Buttons --}}
                            <div class="flex items-center mt-2 space-x-4 text-sm text-gray-500">
                                {{-- Like Button for Reply --}}
                                <form action="{{ route('comments.like', $reply) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="flex items-center space-x-1 hover:text-green-600 transition-colors">
                                        <span class="text-gray-500">Like:</span>
                                        <span class="text-gray-600">{{ $reply->likes_count ?? 0 }}</span>
                                    </button>
                                </form>

                                {{-- Reply to Reply Toggle --}}
                                <button type="button" onclick="document.getElementById('reply-to-reply-form-{{ $reply->id }}').classList.toggle('hidden')" class="flex items-center space-x-1 hover:text-green-600 transition-colors">
                                    <span class="text-gray-500">Reply:</span>
                                    <span class="text-gray-600">{{ $reply->replies->count() ?? 0 }}</span>
                                </button>
                            </div>

                            {{-- Nested Replies (Replies to Replies) --}}
                            <div class="ml-6 mt-3 space-y-2">
                                @foreach($reply->replies as $nestedReply)
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 shadow-sm">
                                    <div class="flex items-center space-x-2">
                                        <strong class="text-gray-900">{{ $nestedReply->user?->name ?? 'Unknown User' }}</strong>
                                        <span class="text-xs text-gray-400">{{ $nestedReply->created_at?->diffForHumans() ?? '' }}</span>
                                    </div>
                                    <p class="text-gray-800 mt-1">{{ $nestedReply->content }}</p>
                                    
                                    {{-- Like Button for Nested Reply --}}
                                    <div class="mt-2">
                                        <form action="{{ route('comments.like', $nestedReply) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="flex items-center space-x-1 hover:text-green-600 transition-colors text-sm text-gray-500">
                                                <span class="text-gray-500">Like:</span>
                                                <span class="text-gray-600">{{ $nestedReply->likes_count ?? 0 }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach

                                {{-- Reply to Reply Form --}}
                                @auth
                                <form action="{{ route('comments.reply', $reply) }}" method="POST" id="reply-to-reply-form-{{ $reply->id }}" class="mt-2 hidden">
                                    @csrf
                                    <textarea name="content" rows="2" 
                                              placeholder="Write a reply to {{ $reply->user?->name }}..." 
                                              class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition text-black"></textarea>
                                    <div class="flex gap-2 mt-2">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                                            Reply
                                        </button>
                                        <button type="button" onclick="document.getElementById('reply-to-reply-form-{{ $reply->id }}').classList.add('hidden')" 
                                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                                @endauth
                            </div>
                        </div>
                        @endforeach

                        {{-- Reply Form for Main Comment --}}
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

        {{-- Public Attendees List --}}
        {{-- <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Attendees</h2>
            @if($post->registrations->isEmpty())
                <p class="text-sm text-gray-500">No attendees yet. Be the first to register.</p>
            @else
                <ul class="space-y-2">
                    @foreach($post->registrations as $registration)
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-gray-900">{{ $registration->user?->name ?? 'Unknown' }}</span>
                            <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 border border-gray-200">{{ $registration->category ?? 'Alumni' }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div> --}}

        @if(auth()->check() && auth()->user()->isAdmin())
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Registrants</h2>
            @if($post->registrations->isEmpty())
                <p class="text-sm text-gray-500">No registrants yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($post->registrations as $registration)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $registration->user?->name ?? 'Unknown' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $registration->user?->email ?? '—' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $registration->category ?? 'Alumni' }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $registration->created_at?->format('M d, Y g:i A') }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <form method="POST" action="{{ route('admin.events.registrants.destroy', [$post, $registration]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-md bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium border border-red-200 transition">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @endif
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