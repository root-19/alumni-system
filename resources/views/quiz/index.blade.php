<x-layouts.app title="Quizzes">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Available Quizzes</h1>
                <p class="mt-2 text-gray-600">Take quizzes to test your knowledge</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($quizzes as $quiz)
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $quiz->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $quiz->description ?? 'No description' }}</p>
                        <div class="text-sm text-gray-500 mb-4">
                            <p>Training: {{ $quiz->training->title }}</p>
                            <p>Questions: {{ $quiz->questions->count() }}</p>
                            <p>Passing Score: {{ $quiz->passing_score }}%</p>
                        </div>
                        <a href="{{ route('quizzes.show', $quiz->id) }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            View Quiz
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No quizzes available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>

