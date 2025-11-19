<x-layouts.app :title="__('Account Management')">
    <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-green-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold text-gray-900">Account Management</h1>
                            <p class="text-sm text-gray-600">Manage alumni accounts and bulk operations</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('assistant.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Alumni</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'user')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Verified</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'user')->whereNotNull('email_verified_at')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Programs</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'user')->distinct('program')->count('program') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Graduation Years</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'user')->distinct('year_graduated')->count('year_graduated') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Operations -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Bulk Operations</h3>
                    <p class="text-sm text-gray-600">Perform bulk updates on alumni accounts</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Bulk Status Update -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Bulk Status Update</h4>
                            <form action="{{ route('admin.users.bulk-update') }}" method="POST" class="space-y-3 text-black">
                                @csrf
                                <select name="filter_field" class="w-full rounded-md border-gray-300 text-sm">
                                    <option value="">Select Filter</option>
                                    <option value="program">By Program</option>
                                    <option value="year_graduated">By Graduation Year</option>
                                    <option value="status">By Current Status</option>
                                </select>
                                <input type="text" name="filter_value" placeholder="Filter value" class="w-full rounded-md border-gray-300 text-sm">
                                <select name="new_status" class="w-full rounded-md border-gray-300 text-sm">
                                    <option value="">Select New Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Graduated">Graduated</option>
                                </select>
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                    Update Status
                                </button>
                            </form>
                        </div>

                        <!-- Bulk Role Update -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Bulk Role Update</h4>
                            <form action="{{ route('admin.users.bulk-update') }}" method="POST" class="space-y-3 text-black">
                                @csrf
                                <select name="filter_field" class="w-full rounded-md border-gray-300 text-sm">
                                    <option value="">Select Filter</option>
                                    <option value="program">By Program</option>
                                    <option value="year_graduated">By Graduation Year</option>
                                </select>
                                <input type="text" name="filter_value" placeholder="Filter value" class="w-full rounded-md border-gray-300 text-sm">
                                <select name="new_role" class="w-full rounded-md border-gray-300 text-sm">
                                    <option value="">Select New Role</option>
                                    <option value="user">User</option>
                                    <option value="assistant">Assistant</option>
                                </select>
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    Update Role
                                </button>
                            </form>
                        </div>

                        <!-- Export Data -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Export Data</h4>
                            <div class="space-y-3">
                                <select class="w-full rounded-md border-gray-300 text-sm">
                                    <option value="">Select Export Type</option>
                                    <option value="all">All Alumni</option>
                                    <option value="by_program">By Program</option>
                                    <option value="by_year">By Graduation Year</option>
                                </select>
                                <button class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-sm">
                                    Export CSV
                                </button>
                                <button class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                                    Export Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alumni List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Alumni Directory</h3>
                            <p class="text-sm text-gray-600">Manage individual alumni accounts</p>
                        </div>
                        <div class="flex space-x-3">
                            <select id="programFilter" class="rounded-md border-gray-300 text-sm">
                                <option value="">All Programs</option>
                                @foreach(\App\Models\User::where('role', 'user')->distinct()->pluck('program') as $program)
                                    @if($program)
                                        <option value="{{ $program }}">{{ $program }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <select id="yearFilter" class="rounded-md border-gray-300 text-sm">
                                <option value="">All Years</option>
                                @foreach(\App\Models\User::where('role', 'user')->distinct()->pluck('year_graduated') as $year)
                                    @if($year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <input type="text" id="searchInput" placeholder="Search alumni..." class="rounded-md border-gray-300 text-sm">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(\App\Models\User::where('role', 'user')->latest()->paginate(20) as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }} {{ $user->last_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->contact_number ?? 'No contact' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if($user->email_verified_at)
                                            <span class="text-green-600">Verified</span>
                                        @else
                                            <span class="text-red-600">Unverified</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $user->program ?? 'Not specified' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->year_graduated ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $user->status ?? 'Active' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="text-green-600 hover:text-green-900 text-xs">Edit</button>
                                        <button class="text-blue-600 hover:text-blue-900 text-xs">View</button>
                                        <button class="text-red-600 hover:text-red-900 text-xs">Deactivate</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ \App\Models\User::where('role', 'user')->latest()->paginate(20)->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
