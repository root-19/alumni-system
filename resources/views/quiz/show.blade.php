<x-layouts.app title="{{ $quiz->title }}">
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">{{ $quiz->title }}</h1>
                <p class="mt-2 text-gray-600">{{ $quiz->description ?? '' }}</p>
            </div>

            @if($attempt && $attempt->completed_at)
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Previous Attempt</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Score</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attempt->score }}/{{ $attempt->total_points }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Percentage</p>
                            <p class="text-2xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">{{ $attempt->percentage }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-2xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                {{ $attempt->passed ? 'Passed' : 'Failed' }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('quizzes.result', $attempt->id) }}" class="mt-4 inline-block text-blue-600 hover:text-blue-900">View Results</a>
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quiz Information</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><strong>Training:</strong> {{ $quiz->training->title }}</p>
                    <p><strong>Total Questions:</strong> {{ $quiz->questions->count() }}</p>
                    <p><strong>Passing Score:</strong> {{ $quiz->passing_score }}%</p>
                    @if($quiz->time_limit)
                        <p><strong>Time Limit:</strong> {{ $quiz->time_limit }} minutes</p>
                    @endif
                </div>
                <div class="mt-6">
                    <a href="{{ route('quizzes.take', $quiz->id) }}" class="block w-full text-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        {{ $attempt && $attempt->completed_at ? 'Retake Quiz' : 'Start Quiz' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

