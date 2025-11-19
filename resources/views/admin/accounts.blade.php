<x-layouts.app :title="__('accounts')">
    <div class="max-w-7xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-green-700 mb-6">User Accounts</h1>

        @if($users->isEmpty())
            <div class="p-6 bg-yellow-100 border border-yellow-300 text-yellow-800 rounded-lg shadow-sm">
                No user accounts found.
            </div>
        @else
            @php
                $grouped = $users->groupBy(function($u){
                    return $u->program ? trim($u->program) : 'Unspecified Program';
                })->sortKeys();
            @endphp

            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-200">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.users.bulk-update') }}" method="POST" class="space-y-6">
                @csrf
                <div class="flex flex-wrap gap-3 items-end p-4 border rounded-lg bg-gray-50">
                    <div>
                        <label class="block text-sm text-black">Program (course)</label>
                        <input type="text" name="program" class="mt-1 border text-black rounded px-3 py-2" placeholder="e.g., BSIT" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Year Graduated</label>
                        <input type="text" name="year_graduated" class="mt-1 text-black border rounded px-3 py-2" placeholder="YYYY" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Status</label>
                        <input type="text" name="status" class="mt-1 border text-black rounded px-3 py-2" placeholder="e.g., Graduated" />
                    </div>
                    <button type="submit" class="ml-auto inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Apply to selected</button>
                </div>

            @foreach($grouped as $programName => $group)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-xl font-semibold text-green-700">
                            {{ $programName }}
                        </h2>
                        <div class="flex items-center gap-3">
                            <label class="inline-flex items-center text-sm text-gray-700">
                                <input type="checkbox" class="select-all group-{{ $loop->index }} mr-2">
                                Select all in group
                            </label>
                            <span class="text-sm text-gray-600">{{ $group->count() }} {{ $group->count() === 1 ? 'user' : 'users' }}</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden">
                            <thead class="bg-green-600 text-white">
                                <tr>
                                    <th class="px-4 py-3"><!-- checkbox header --></th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Program</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Year Graduated</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Address</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Events Attended</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Role</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($group->sortBy('name') as $user)
                                    <tr class="hover:bg-green-50 transition">
                                        <td class="px-4 py-4">
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="row-check group-{{ $loop->parent->index }}">
                                        </td>
                                        {{-- Profile Image --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->profile_image_path)
                                                <img src="{{ asset('storage/' . $user->profile_image_path) }}" alt="Profile Image" class="w-12 h-12 rounded-full object-cover border border-gray-300 shadow-sm">
                                            @else
                                                <span class="text-gray-500 italic">No Image</span>
                                            @endif
                                        </td>

                                        {{-- Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-black font-medium">
                                            {{ $user->name }} {{ $user->last_name }}
                                        </td>

                                        {{-- Program --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-black">
                                            {{ $user->program }}
                                        </td>

                                        {{-- Year Graduated --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-black">
                                            {{ $user->year_graduated }}
                                        </td>

                                        {{-- Contact --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-black">
                                            {{ $user->contact_number }}
                                        </td>

                                        {{-- Address --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-black">
                                            {{ $user->address }}
                                        </td>

                                        {{-- Email --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-black">
                                            {{ $user->email }}
                                        </td>

                                        {{-- Events Attended --}}
                                        <td class="px-6 py-4 text-black">
                                            @if($user->eventRegistrations && $user->eventRegistrations->count() > 0)
                                                <div class="max-w-xs">
                                                    <div class="text-sm font-semibold text-green-600 mb-1">
                                                        {{ $user->eventRegistrations->count() }} event{{ $user->eventRegistrations->count() > 1 ? 's' : '' }}
                                                    </div>
                                                    <div class="space-y-1">
                                                        @foreach($user->eventRegistrations->take(3) as $registration)
                                                            <div class="text-xs bg-green-50 text-green-800 px-2 py-1 rounded border border-green-200">
                                                                {{ Str::limit($registration->alumniPost->content ?? 'Event', 30) }}
                                                            </div>
                                                        @endforeach
                                                        @if($user->eventRegistrations->count() > 3)
                                                            <div class="text-xs text-gray-500 italic">
                                                                +{{ $user->eventRegistrations->count() - 3 }} more...
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic text-sm">No events attended</span>
                                            @endif
                                        </td>

                                        {{-- Role --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-black font-semibold">
                                            {{ ucfirst($user->role) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.select-all').forEach(function(cb){
                        cb.addEventListener('change', function(){
                            const cls = this.className.split(' ').find(c => c.startsWith('group-'));
                            if(!cls) return;
                            document.querySelectorAll('input.row-check.'+cls).forEach(function(row){
                                row.checked = cb.checked;
                            });
                        });
                    });
                });
            </script>
        @endif
    </div>
</x-layouts.app>
