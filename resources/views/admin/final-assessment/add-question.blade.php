<x-layouts.app title="Add Question">
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Add Question to Final Assessment</h1>
                <p class="mt-2 text-gray-600">Assessment: {{ $assessment->title }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('admin.final-assessments.store-question', $assessment->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">Question Text</label>
                        <textarea name="question_text" id="question_text" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>{{ old('question_text') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="correct_answer" class="block text-sm font-medium text-gray-700 mb-2">Correct Answer</label>
                        <select name="correct_answer" id="correct_answer" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="">Select Correct Answer</option>
                            <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>D</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="points" class="block text-sm font-medium text-gray-700 mb-2">Points</label>
                        <input type="number" name="points" id="points" value="{{ old('points', 1) }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Answer Choices</h3>
                        
                        <div class="mb-3">
                            <label for="choice_a" class="block text-sm font-medium text-gray-700 mb-2">Choice A</label>
                            <input type="text" name="choice_a" id="choice_a" value="{{ old('choice_a') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        </div>

                        <div class="mb-3">
                            <label for="choice_b" class="block text-sm font-medium text-gray-700 mb-2">Choice B</label>
                            <input type="text" name="choice_b" id="choice_b" value="{{ old('choice_b') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        </div>

                        <div class="mb-3">
                            <label for="choice_c" class="block text-sm font-medium text-gray-700 mb-2">Choice C</label>
                            <input type="text" name="choice_c" id="choice_c" value="{{ old('choice_c') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        </div>

                        <div class="mb-3">
                            <label for="choice_d" class="block text-sm font-medium text-gray-700 mb-2">Choice D</label>
                            <input type="text" name="choice_d" id="choice_d" value="{{ old('choice_d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.final-assessments.show', $assessment->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Add Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>

