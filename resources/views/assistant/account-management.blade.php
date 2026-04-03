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
                                        <button onclick="openView({{ $user->id }})" class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700 hover:bg-blue-200 transition">View</button>
                                        <button onclick="openEdit({{ $user->id }})" class="px-2 py-1 text-xs rounded bg-green-100 text-green-700 hover:bg-green-200 transition">Edit</button>
                                        <button onclick="toggleDeactivate({{ $user->id }}, this)" class="px-2 py-1 text-xs rounded {{ ($user->status ?? 'Active') === 'Inactive' ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }} transition">
                                            {{ ($user->status ?? 'Active') === 'Inactive' ? 'Activate' : 'Deactivate' }}
                                        </button>
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

    {{-- VIEW MODAL --}}
    <div id="viewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Account Details</h3>
                <button onclick="closeModal('viewModal')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <div id="viewContent" class="space-y-3 text-sm text-gray-700">
                <p class="text-center text-gray-400">Loading...</p>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Account</h3>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <form id="editForm" class="space-y-3">
                <input type="hidden" id="editUserId">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">First Name</label>
                        <input id="editName" type="text" class="w-full text-black text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Last Name</label>
                        <input id="editLastName" type="text" class="w-full text-black text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Program</label>
                        <input id="editProgram" type="text" class="w-full text-black text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Year Graduated</label>
                        <input id="editYear" type="number" min="1900" max="2100" class="w-full text-black text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Contact Number</label>
                    <input id="editContact" type="text" class="w-full text-black text-sm rounded-md border-gray-300 focus:ring-green-500 focus:border-green-500">
                </div>
                <button type="button" onclick="submitEdit()" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 text-sm font-medium transition">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const baseUrl = '{{ url("/assistant/accounts") }}';

        function openModal(id) {
            const m = document.getElementById(id);
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        function openView(userId) {
            document.getElementById('viewContent').innerHTML = '<p class="text-center text-gray-400">Loading...</p>';
            openModal('viewModal');
            fetch(`${baseUrl}/${userId}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(u => {
                document.getElementById('viewContent').innerHTML = `
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-lg">${(u.name || '?')[0]}</div>
                        <div>
                            <p class="font-semibold text-gray-900">${u.name || ''} ${u.last_name || ''}</p>
                            <p class="text-xs text-gray-500">${u.email || ''}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-gray-50 rounded p-2"><span class="text-gray-500">Program</span><p class="font-medium text-gray-900 mt-0.5">${u.program || '—'}</p></div>
                        <div class="bg-gray-50 rounded p-2"><span class="text-gray-500">Year Graduated</span><p class="font-medium text-gray-900 mt-0.5">${u.year_graduated || '—'}</p></div>
                        <div class="bg-gray-50 rounded p-2"><span class="text-gray-500">Contact</span><p class="font-medium text-gray-900 mt-0.5">${u.contact_number || '—'}</p></div>
                        <div class="bg-gray-50 rounded p-2"><span class="text-gray-500">Status</span><p class="font-medium text-gray-900 mt-0.5">${u.status || 'Active'}</p></div>
                    </div>
                `;
            })
            .catch(() => {
                document.getElementById('viewContent').innerHTML = '<p class="text-red-500 text-center">Failed to load user data.</p>';
            });
        }

        function openEdit(userId) {
            openModal('editModal');
            fetch(`${baseUrl}/${userId}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(u => {
                document.getElementById('editUserId').value = u.id;
                document.getElementById('editName').value = u.name || '';
                document.getElementById('editLastName').value = u.last_name || '';
                document.getElementById('editEmail').value = u.email || '';
                document.getElementById('editProgram').value = u.program || '';
                document.getElementById('editYear').value = u.year_graduated || '';
                document.getElementById('editContact').value = u.contact_number || '';
            });
        }

        function submitEdit() {
            const userId = document.getElementById('editUserId').value;
            fetch(`${baseUrl}/${userId}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    name: document.getElementById('editName').value,
                    last_name: document.getElementById('editLastName').value,
                    program: document.getElementById('editProgram').value,
                    year_graduated: document.getElementById('editYear').value || null,
                    contact_number: document.getElementById('editContact').value,
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closeModal('editModal');
                    showToast('Account updated successfully!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to update account.');
                    showToast(msg, 'error');
                }
            })
            .catch(err => {
                showToast('Server error. Please try again.', 'error');
                console.error(err);
            });
        }

        function toggleDeactivate(userId, btn) {
            fetch(`${baseUrl}/${userId}/deactivate`, {
                method: 'PATCH',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const isInactive = data.status === 'Inactive';
                    btn.textContent = isInactive ? 'Activate' : 'Deactivate';
                    btn.className = `px-2 py-1 text-xs rounded transition ${isInactive ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200'}`;
                    showToast(`Account ${data.status.toLowerCase()} successfully!`, 'success');
                }
            });
        }

        function showToast(message, type) {
            document.querySelectorAll('.toast-msg').forEach(el => el.remove());
            const div = document.createElement('div');
            div.className = `toast-msg fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 flex items-center gap-3 ${type === 'success' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300'}`;
            div.innerHTML = `<span>${message}</span><button onclick="this.parentElement.remove()" class="ml-2 font-bold text-lg">&times;</button>`;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 4000);
        }

        // Close modals on backdrop click
        ['viewModal', 'editModal'].forEach(id => {
            document.getElementById(id).addEventListener('click', function(e) {
                if (e.target === this) closeModal(id);
            });
        });
    </script>
</x-layouts.app>
