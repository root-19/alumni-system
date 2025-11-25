<div class="flex flex-col h-full">
    <!-- Admin Selection Header -->
    @if(isset($availableAdmins) && $availableAdmins && $availableAdmins->isNotEmpty())
        <div class="p-4 bg-white border-b border-gray-200">
            <!-- Admin Profile Header -->
            @if($admin)
            <div class="flex items-center space-x-3">
                <!-- Admin Profile Image -->
                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-300 flex-shrink-0">
                    @if(isset($admin->profile_image_path) && $admin->profile_image_path)
                        <img src="{{ asset('storage/' . $admin->profile_image_path) }}" 
                             alt="{{ $admin->name ?? 'Admin' }}" 
                             class="w-full h-full object-cover">
                    @else
                        <!-- Default Admin Avatar -->
                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <!-- Admin Info -->
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $admin->name ?? 'Admin' }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        <span class="inline-flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Online
                        </span>
                    </p>
                </div>
                
            </div>
            @endif
        </div>
    @endif

    <!-- Messages Area -->
    <div class="flex-1 p-6 overflow-y-auto space-y-3 bg-gray-50" wire:poll.5000ms="loadMessages">
        @if($selectedAdminId && $admin)
            @forelse($messages as $msg)
                <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                    @if($msg->sender_id != auth()->id())
                        <!-- Admin message with avatar -->
                        <div class="flex items-end space-x-2 max-w-xs">
                            <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-300 flex-shrink-0">
                                @if(isset($admin->profile_image_path) && $admin->profile_image_path)
                                    <img src="{{ asset('storage/' . $admin->profile_image_path) }}" 
                                         alt="{{ $admin->name ?? 'Admin' }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-xs">
                                        {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="px-4 py-2 rounded-2xl rounded-bl-none shadow text-sm bg-gray-200 text-gray-800">
                                {{ $msg->message }}
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $msg->created_at->format('g:i A') }}
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- User message -->
                        <div class="max-w-xs px-4 py-2 rounded-2xl rounded-br-none shadow text-sm bg-green-600 text-white">
                            {{ $msg->message }}
                            <div class="text-xs text-green-200 mt-1">
                                {{ $msg->created_at->format('g:i A') }}
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Start a Conversation</h3>
                    <p class="text-gray-500">Send a message to {{ $admin->name }} to begin chatting.</p>
                </div>
            @endforelse
        @else
            <div class="flex flex-col items-center justify-center h-full text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No Admin Available</h3>
                <p class="text-gray-500">Please contact support for assistance.</p>
            </div>
        @endif
    </div>

    <!-- Message Input -->
    @if($selectedAdminId && $admin)
        <form class="p-4 bg-white border-t border-gray-200 flex gap-3" wire:submit.prevent="sendMessage">
            <input type="text" 
                   class="flex-1 border border-gray-300 text-black rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                   placeholder="Type a message..." 
                   wire:model.defer="message"
                   wire:keydown.enter.prevent="sendMessage">
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full shadow transition disabled:opacity-50 flex items-center space-x-2"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </span>
                <span wire:loading>
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </form>
    @endif
</div>