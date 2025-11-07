<x-layouts.app title="Create Final Assessment">
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Create New Final Assessment</h1>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('admin.final-assessments.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Assessment Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="passing_score" class="block text-sm font-medium text-gray-700 mb-2">Passing Score (%)</label>
                        <input type="number" name="passing_score" id="passing_score" value="{{ old('passing_score', 70) }}" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-2">Time Limit (minutes, optional)</label>
                        <input type="number" name="time_limit" id="time_limit" value="{{ old('time_limit') }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.final-assessments.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Assessment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>

