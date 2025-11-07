<x-layouts.app :title="__('Resume Management')">
    <div class="max-w-7xl mx-auto mt-8 px-4">
        
        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Resume Management</h1>
            <p class="text-gray-600">View and manage all user-generated resumes</p>
        </div>

        {{-- Resume Table --}}
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Full Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">File Name</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($resumes as $resume)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold">
                                                {{ $resume->user ? strtoupper(substr($resume->user->name, 0, 2)) : 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $resume->user ? $resume->user->name : 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $resume->user ? $resume->user->email : 'No user' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $resume->full_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $resume->email ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $resume->file_name ?? 'No file' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-500">
                                        {{ $resume->created_at ? $resume->created_at->format('M d, Y') : 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $resume->created_at ? $resume->created_at->format('h:i A') : '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- View Button --}}
                                        @if($resume->full_name)
                                            <a href="{{ route('resumes.show', $resume->id) }}" 
                                               class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md shadow-sm transition text-sm flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View
                                            </a>
                                        @elseif($resume->file_path)
                                            <a href="{{ asset('storage/'.$resume->file_path) }}" target="_blank" 
                                               class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md shadow-sm transition text-sm">
                                                View PDF
                                            </a>
                                        @endif

                                        {{-- Download Button --}}
                                        @if($resume->file_path)
                                            <a href="{{ route('resumes.download', $resume->id) }}" 
                                               class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-md shadow-sm transition text-sm flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download
                                            </a>
                                        @endif

                                        {{-- Delete Button --}}
                                        <form action="{{ route('resume.destroy', $resume) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this resume?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow-sm transition text-sm flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Summary Stats --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Resumes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $resumes->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Unique Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $resumes->pluck('user_id')->filter()->unique()->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Generated Resumes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $resumes->whereNotNull('full_name')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Empty State Placeholder --}}
        @if($resumes->isEmpty())
        <div class="mt-6 bg-white shadow-lg rounded-lg p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">No Resumes Found</h3>
            <p class="text-gray-600">No users have created resumes yet.</p>
        </div>
        @endif
    </div>
</x-layouts.app>
