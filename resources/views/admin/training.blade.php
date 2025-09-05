<x-layouts.app :title="__('Training Ground')">
    <div class="max-w-4xl mx-auto mt-8">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.trainings.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-6 bg-white p-6 rounded shadow">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="mt-1 block w-full border rounded p-2">
                @error('title') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" class="mt-1 block w-full border rounded p-2">{{ old('description') }}</textarea>
                @error('description') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Modules (images, pdf, doc)</label>
                <input type="file" name="modules[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                       class="mt-1 block w-full" />
                <p class="text-xs text-gray-500 mt-1">You can upload multiple files.</p>
                @error('modules.*') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Certificate (final)</label>
                <input type="file" name="certificate" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="mt-1 block w-full" />
                @error('certificate') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                    Create Training
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
