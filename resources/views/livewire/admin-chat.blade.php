<div class="flex h-screen bg-gray-100 font-sans">
    {{-- Sidebar --}}
    <aside class="w-72 bg-white shadow-lg rounded-3xl m-4 flex flex-col overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-gray-800 text-2xl font-bold flex items-center gap-2">
                <span class="text-green-600">Reports</span>
            </h2>
        </div>
        <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            @if($users->isNotEmpty())
                @foreach($users as $user)
                    <button wire:click="selectUser({{ $user->id }})"
                            class="w-full flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 {{ $user->id == $selectedUserId ? 'bg-green-50 border-l-4 border-green-500' : '' }}">
                        <img src="{{ $user->profile_image_path ? asset('storage/' . $user->profile_image_path) : 'https://via.placeholder.com/40' }}" 
                             class="w-12 h-12 rounded-full object-cover border-2 border-green-500">
                        <span class="text-gray-700 font-medium">{{ $user->name }}</span>
                    </button>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center h-full text-center p-6">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No Users Available</h3>
                    <p class="text-gray-500">No users have registered yet.</p>
                </div>
            @endif
        </div>
    </aside>

    {{-- Main Chat Area --}}
    <main class="flex-1 flex flex-col bg-white rounded-3xl m-4 shadow-lg overflow-hidden">
        {{-- Chat Header --}}
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">
                @if($selectedUserId && $users->find($selectedUserId))
                    {{ $users->find($selectedUserId)->name }}
                @else
                    Select a user to start chatting
                @endif
            </h2>
            @if($selectedUserId)
                <span class="text-sm text-gray-500">Online</span>
            @endif
        </div>

        {{-- Messages --}}
        <div class="flex-1 p-6 overflow-y-auto space-y-3 bg-gray-50 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
             wire:poll.5000ms="loadMessages">
            @if($selectedUserId)
                @forelse($messages as $msg)
                    <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs px-5 py-3 rounded-2xl shadow text-sm break-words
                            {{ $msg->sender_id == auth()->id() 
                                ? 'bg-green-600 text-white rounded-br-none' 
                                : 'bg-gray-200 text-gray-800 rounded-bl-none' }}">
                            {{ $msg->message }}
                            <div class="text-xs {{ $msg->sender_id == auth()->id() ? 'text-green-200' : 'text-gray-500' }} mt-1">
                                {{ $msg->created_at->format('g:i A') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Start a Conversation</h3>
                        <p class="text-gray-500">Send a message to begin chatting.</p>
                    </div>
                @endforelse
            @else
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Select a User</h3>
                    <p class="text-gray-500">Choose a user from the sidebar to start chatting.</p>
                </div>
            @endif
        </div>

        {{-- Reply Form --}}
        @if($selectedUserId)
            <form class="p-4 bg-gray-50 border-t border-gray-200 flex gap-3" wire:submit.prevent="sendMessage">
                <input type="text" 
                       class="flex-1 border text-black border-gray-300 rounded-full px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                       placeholder="Type your message..." wire:model.defer="message">
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full shadow-lg transition transform hover:scale-105"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>Send</span>
                    <span wire:loading>
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </form>
        @endif
    </main>
</div>
