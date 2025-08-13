<x-layouts.app :title="__('accounts')">
    <div class="max-w-7xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-green-700 mb-6">User Accounts</h1>

        @if($users->isEmpty())
            <div class="p-6 bg-yellow-100 border border-yellow-300 text-yellow-800 rounded-lg shadow-sm">
                No user accounts found.
            </div>
        @else
            <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Program</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Year Graduated</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Address</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-green-50 transition">
                                
                                {{-- Profile Image --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile_image) }}" 
                                             alt="Profile Image" 
                                             class="w-12 h-12 rounded-full object-cover border border-gray-300 shadow-sm">
                                    @else
                                        <span class="text-gray-500 italic">No Image</span>
                                    @endif
                                </td>

                                {{-- Name --}}
                                <td class="px-6 py-4 whitespace-nowrap text-black font-medium">
                                    {{ $user->name }}
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

                                {{-- Role --}}
                                <td class="px-6 py-4 whitespace-nowrap text-black font-semibold">
                                    {{ ucfirst($user->role) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layouts.app>
