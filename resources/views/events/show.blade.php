<x-layouts.app :title="$post->content ?? 'Event Details'">
    <div class="max-w-4xl mx-auto mt-8 space-y-8">
        
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
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

            {{-- Event Title --}}
            @if($post->title)
                <h1 class="text-3xl font-bold text-gray-900 leading-snug mb-2">
                    {{ $post->title }}
                </h1>
            @endif
            
            {{-- Event Description --}}
            @if($post->description)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                    <p class="text-blue-800">{{ $post->description }}</p>
                </div>
            @endif
            
            {{-- Event Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                @if($post->event_date)
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($post->event_date)->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                @endif
                
                @if($post->location)
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">{{ $post->location }}</span>
                    </div>
                @endif
            </div>
            
            {{-- Event Content --}}
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Event Details</h3>
                <p class="text-gray-700">{{ $post->content ?? 'No content available' }}</p>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center text-xs uppercase tracking-wide text-gray-500 space-x-2">
                    <span class="font-semibold text-gray-600">Posted:</span>
                    <span>{{ optional($post->created_at)->format('F j, Y \a\t g:i A') }}</span>
                </div>
                
                {{-- Attendance Statistics --}}
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>{{ $post->attendances()->attending()->count() }} attending</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Attendance Section -- Only show if event is not completed and user is not admin/assistant --}}
        @php
            $isEventComplete = $post->is_completed || ($post->event_date && \Carbon\Carbon::parse($post->event_date)->isPast());
            $isAdminOrAssistant = auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isAssistant());
        @endphp
        
        @if(!$isEventComplete && !$isAdminOrAssistant)
            @auth
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Event Attendance</h2>
                
                @php
                    $userAttendance = $post->attendances()->where('user_id', auth()->id())->first();
                @endphp
                
                @if($userAttendance)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-green-800">Your Attendance Status</h3>
                                <p class="text-green-700">
                                    Status: <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $userAttendance->status)) }}</span>
                                    @if($userAttendance->title)
                                        <br>Event: {{ $userAttendance->title }}
                                    @endif
                                </p>
                            </div>
                            @if($userAttendance->checked_in_at)
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Checked In
                                    </span>
                                    <p class="text-xs text-green-600 mt-1">{{ $userAttendance->checked_in_at->format('M j, Y g:i A') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Update Attendance Form --}}
                    <form action="{{ route('attendance.update', $userAttendance) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Update Your Attendance Status</label>
                            <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400">
                                <option value="attending" {{ $userAttendance->status === 'attending' ? 'selected' : '' }}>Yes, I will attend</option>
                                <option value="maybe" {{ $userAttendance->status === 'maybe' ? 'selected' : '' }}>Maybe, I'm not sure yet</option>
                                <option value="not_attending" {{ $userAttendance->status === 'not_attending' ? 'selected' : '' }}>No, I cannot attend</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium shadow-sm transition">
                            Update Attendance
                        </button>
                    </form>
                @else
                    {{-- Register Attendance Form --}}
                    <form action="{{ route('events.attendance.store', $post) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Will you attend this event?</label>
                            <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400">
                                <option value="attending">Yes, I will attend</option>
                                <option value="maybe">Maybe, I'm not sure yet</option>
                                <option value="not_attending">No, I cannot attend</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium shadow-sm transition">
                            Register Attendance
                        </button>
                    </form>
                @endif
            </div>
            @else
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Attendance</h2>
                <p class="text-gray-500">Please <a href="{{ route('login') }}" class="text-green-600 hover:underline">log in</a> to register your attendance for this event.</p>
            </div>
            @endauth
        @endif

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