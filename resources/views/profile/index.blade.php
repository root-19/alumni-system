<x-layouts.app  :title="__('Profile')">
<div class="container mx-auto max-w-2xl">
    <h2 class="text-xl font-bold mb-4">My Profile</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="block">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="border p-2 w-full">
        </div>

        <div class="mb-3">
            <label class="block">Middle Name</label>
            <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" class="border p-2 w-full">
        </div>

        <div class="mb-3">
            <label class="block">Suffix</label>
            <input type="text" name="suffix" value="{{ old('suffix', $user->suffix) }}" class="border p-2 w-full">
        </div>

        <div class="mb-3">
            <label class="block">Year Graduated</label>
            <input type="text" name="year_graduated" value="{{ old('year_graduated', $user->year_graduated) }}" class="border p-2 w-full">
        </div>

        <div class="mb-3">
            <label class="block">Program</label>
            <input type="text" name="program" value="{{ old('program', $user->program) }}" class="border p-2 w-full">
        </div>

        <div class="mb-3">
            <label class="block">Gender</label>
            <select name="gender" class="border p-2 w-full">
                <option value="">Select</option>
                <option value="Male" {{ $user->gender=='Male' ? 'selected':'' }}>Male</option>
                <option value="Female" {{ $user->gender=='Female' ? 'selected':'' }}>Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="block">Status</label>
            <input type="text" name="status" value="{{ old('status', $user->status) }}" class="border p-2 w-full">
        </div>

        <div class="mb-3">
            <label class="block">Contact Number</label>
            <input type="text" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" class="border p-2 w-full">
        </div>

        <div class="mb-3">
            <label class="block">Address</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="border p-2 w-full">
        </div>

        <div class="mb-3">
            <label class="block">Profile Image</label>
            <input type="file" name="profile_image_path" class="border p-2 w-full">
            @if($user->profile_image_path)
                <img src="{{ asset($user->profile_image_path) }}" class="w-20 mt-2">
            @endif
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
</x-layouts.app >
