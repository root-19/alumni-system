<x-layouts.app :title="__('Profile')">
    <x-slot name="title">Profile</x-slot>

    <div class="max-w-4xl mx-auto p-8 rounded-2xl shadow-lg border border-gray-100">
        <h1 class="text-3xl font-bold text-black mb-6">My Profile</h1>
        
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Left Column --}}
                <div class="space-y-6">
                    {{-- First Name --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">First Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                            class="w-full border border-gray-300 text-black placeholder-gray-400 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm"
                            placeholder="Enter your first name">
                        @error('name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" 
                            class="w-full border border-gray-300 text-black placeholder-gray-400 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm"
                            placeholder="Enter your last name">
                        @error('last_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Suffix --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Suffix</label>
                        <input type="text" name="suffix" value="{{ old('suffix', $user->suffix) }}" 
                            class="w-full border border-gray-300 text-black placeholder-gray-400 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm"
                            placeholder="Enter suffix (e.g., Jr, Sr)">
                    </div>

                    {{-- Program --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Program</label>
                        <input type="text" name="program" value="{{ old('program', $user->program) }}" 
                            class="w-full border border-gray-300 text-black placeholder-gray-400 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm"
                            placeholder="Enter program">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Status</label>
                        <input type="text" name="status" value="{{ old('status', $user->status) }}" 
                            class="w-full border border-gray-300 text-gray-900 placeholder-gray-400 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm"
                            placeholder="Enter status">
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" 
                            class="w-full border border-gray-300 text-gray-900 placeholder-gray-400 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm"
                            placeholder="Enter address">
                    </div>

                    {{-- Profile Image --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Profile Image</label>
                        <input type="file" name="profile_image" accept="image/*"
                            class="w-full border border-gray-300 text-black placeholder-gray-400 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm">
                        @error('profile_image')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        @if($user->profile_image_path)
                            <img src="{{ asset('storage/' . $user->profile_image_path) }}" 
                                 class="mt-4 w-28 h-28 rounded-full shadow-md object-cover border-2 border-gray-200">
                        @endif
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-6">
                    {{-- Middle Name --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" 
                            class="w-full border border-gray-300 text-black placeholder-gray-400 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm"
                            placeholder="Enter middle name">
                    </div>

                    {{-- Year Graduated --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Year Graduated</label>
                        <input type="text" name="year_graduated" value="{{ old('year_graduated', $user->year_graduated) }}" 
                            class="w-full border border-gray-300 text-black placeholder-gray-400 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm"
                            placeholder="Enter graduation year">
                        @error('year_graduated')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Gender</label>
                        <select name="gender" 
                                class="w-full border border-gray-300 text-black placeholder-gray-400 
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm">
                            <option value="">Select</option>
                            <option value="male" {{ $user->gender === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    {{-- Contact Number --}}
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Contact Number</label>
                        <input type="text" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" 
                            class="w-full border border-gray-300 text-gray-900 placeholder-gray-400 
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 rounded-lg shadow-sm"
                            placeholder="Enter contact number">
                        @error('contact_number')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end mt-6">
                <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold 
                           rounded-lg shadow-md hover:from-blue-700 hover:to-blue-600 
                           focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
