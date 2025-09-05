<x-layouts.app :title="__('Create Training')">
    <div class="max-w-3xl mx-auto mt-12">
        <div class="bg-white shadow-lg rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
                {{ __('Create New Training') }}
            </h2>

            <form action="{{ route('admin.trainings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 text-black py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Modules -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Modules</label>
                    <input type="file" name="modules[]" multiple
                           class="w-full text-black px-3 py-2 border rounded-lg bg-gray-50 cursor-pointer focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">Upload multiple files (JPG, PNG, PDF, DOC).</p>
                    @error('modules.*')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Certificate -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Certificate</label>
                    <input type="file" name="certificate"
                           class="w-full px-3 py-2 border  text-black rounded-lg bg-gray-50 cursor-pointer focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('certificate')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                        Save Training
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

