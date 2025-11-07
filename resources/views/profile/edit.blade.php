<x-layouts.app :title="__('Profile')">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">My Profile</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
       

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- First Name --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">First Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900 placeholder-gray-400
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="Enter first name">
                </div>

                {{-- Last Name --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900 placeholder-gray-400
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="Enter last name">
                    @error('last_name')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Middle Name --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900 placeholder-gray-400
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="Enter middle name">
                </div>

                {{-- Suffix --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Suffix</label>
                    <input type="text" name="suffix" value="{{ old('suffix', $user->suffix) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900 placeholder-gray-400
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="e.g. Jr, Sr">
                </div>

                {{-- Year Graduated --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Year Graduated</label>
                    <input type="text" name="year_graduated" value="{{ old('year_graduated', $user->year_graduated) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900 placeholder-gray-400
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="Enter year">
                </div>

                {{-- Program --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Program</label>
                    <input type="text" name="program" value="{{ old('program', $user->program) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900 placeholder-gray-400
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="Enter program">
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Gender</label>
                    <select name="gender"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        <option value="">Select</option>
                        <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Status</label>
                    <input type="text" name="status" value="{{ old('status', $user->status) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900 placeholder-gray-400
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="Enter status">
                </div>

                {{-- Contact Number --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900 placeholder-gray-400
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="Enter contact number">
                </div>

                {{-- Address (Full Width) --}}
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-1">Address</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900 placeholder-gray-400
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                        placeholder="Enter address">
                </div>

                {{-- Profile Image (Full Width) --}}
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-1">Profile Image</label>
                    <input type="file" name="profile_image"
                        class="w-full border border-gray-300 rounded-lg p-3 text-gray-900
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    
                    @if($user->profile_image_path)
                        <img src="{{ asset('storage/' . $user->profile_image_path) }}"
                             class="w-24 h-24 mt-4 rounded-full object-cover border-2 border-gray-200 shadow-sm">
                    @endif
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end">
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
