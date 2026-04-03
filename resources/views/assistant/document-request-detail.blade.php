<x-layouts.app :title="__('Document Request Details')">
    <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-green-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold text-gray-900">Document Request #{{ $documentRequest->id }}</h1>
                            <p class="text-sm text-gray-600">View and manage document request details</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('assistant.document-requests.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Request Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Request Information</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- User Information -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Requested By</h4>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center">
                                            <span class="text-lg font-medium text-white">{{ substr($documentRequest->user->name ?? 'U', 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $documentRequest->user->name ?? 'User #'.$documentRequest->user_id }}</div>
                                        <div class="text-sm text-gray-500">{{ $documentRequest->user->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Type -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Document Type</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $documentRequest->type }}
                                </span>
                            </div>

                            <!-- Purpose -->
                            @if($documentRequest->purpose)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Purpose</h4>
                                <p class="text-gray-900">{{ $documentRequest->purpose }}</p>
                            </div>
                            @endif

                            <!-- Message -->
                            @if($documentRequest->message)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Additional Message</h4>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $documentRequest->message }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Status -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Current Status</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($documentRequest->status === 'Pending') bg-yellow-100 text-yellow-800
                                    @elseif($documentRequest->status === 'Processing') bg-blue-100 text-blue-800
                                    @elseif($documentRequest->status === 'Approved') bg-green-100 text-green-800
                                    @elseif($documentRequest->status === 'Completed') bg-emerald-100 text-emerald-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $documentRequest->status }}
                                </span>
                            </div>

                            <!-- Admin Note -->
                            @if($documentRequest->admin_note)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-3">Admin Note</h4>
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $documentRequest->admin_note }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Timestamps -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Submitted</h4>
                                    <p class="text-gray-900">{{ $documentRequest->created_at->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-500">{{ $documentRequest->created_at->format('h:i A') }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Last Updated</h4>
                                    <p class="text-gray-900">{{ $documentRequest->updated_at->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-500">{{ $documentRequest->updated_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Update Panel -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Update Status</h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('assistant.document-requests.update', $documentRequest) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <select name="status" id="status" class="w-full text-black bg-white rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                            @foreach(['Pending','Processing','Approved','Rejected','Completed'] as $status)
                                                <option value="{{ $status }}" @selected($documentRequest->status === $status)>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="admin_note" class="block text-sm font-medium text-gray-700 mb-2">Admin Note</label>
                                        <textarea name="admin_note" id="admin_note" rows="4" class="w-full text-black bg-white rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Add a note about this status update...">{{ old('admin_note', $documentRequest->admin_note) }}</textarea>
                                    </div>

                                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors font-medium">
                                        Update Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
