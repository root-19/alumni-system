<x-layouts.app title="Final Assessment Details">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $assessment->title }}</h1>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.final-assessments.edit', $assessment->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Edit</a>
                        <a href="{{ route('admin.final-assessments.add-question', $assessment->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">+ Add Question</a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Questions</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $assessment->questions->count() }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Passing Score</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $assessment->passing_score }}%</p>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                    <p class="mt-2">
                        @if($assessment->is_active)
                            <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($assessment->description)
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-600">{{ $assessment->description }}</p>
                </div>
            @endif

            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Questions ({{ $assessment->questions->count() }})</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($assessment->questions as $index => $question)
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">
                                        Question {{ $index + 1 }}: {{ $question->question_text }}
                                    </h4>
                                    <p class="mt-2 text-sm text-gray-600">
                                        Correct Answer: <span class="font-medium text-green-600">{{ $question->correct_answer }}</span> | 
                                        Points: {{ $question->points }}
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.final-assessments.edit-question', [$assessment->id, $question->id]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</a>
                                    <form action="{{ route('admin.final-assessments.delete-question', [$assessment->id, $question->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                            <div class="ml-4 space-y-2">
                                @foreach($question->choices as $choice)
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-700 w-8">{{ $choice->choice_letter }}.</span>
                                        <span class="text-gray-600 {{ $choice->choice_letter === $question->correct_answer ? 'font-semibold text-green-600' : '' }}">
                                            {{ $choice->choice_text }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            No questions yet. <a href="{{ route('admin.final-assessments.add-question', $assessment->id) }}" class="text-blue-600 hover:text-blue-900">Add your first question</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

