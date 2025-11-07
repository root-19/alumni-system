<x-layouts.app title="Create Quiz">
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Create New Quiz</h1>
                <p class="mt-2 text-gray-600">Create a new quiz for a training</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('admin.quizzes.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="training_id" class="block text-sm font-medium text-gray-700 mb-2">Training</label>
                        <select name="training_id" id="training_id" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="">Select Training</option>
                            @foreach($trainings as $training)
                                <option value="{{ $training->id }}" {{ old('training_id', $selectedTrainingId ?? '') == $training->id ? 'selected' : '' }}>
                                    {{ $training->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('training_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Quiz Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="passing_score" class="block text-sm font-medium text-gray-700 mb-2">Passing Score (%)</label>
                        <input type="number" name="passing_score" id="passing_score" value="{{ old('passing_score', 70) }}" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        @error('passing_score')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-2">Time Limit (minutes, optional)</label>
                        <input type="number" name="time_limit" id="time_limit" value="{{ old('time_limit') }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        @error('time_limit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.quizzes.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Quiz</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>

