<x-layouts.app title="Final Assessments">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Final Assessments</h1>
                <p class="mt-2 text-gray-600">Take final assessments to complete your training</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($assessments as $assessment)
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $assessment->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $assessment->description ?? 'No description' }}</p>
                        <div class="text-sm text-gray-500 mb-4">
                            <p>Questions: {{ $assessment->questions->count() }}</p>
                            <p>Passing Score: {{ $assessment->passing_score }}%</p>
                        </div>
                        <a href="{{ route('final-assessments.show', $assessment->id) }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            View Assessment
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No assessments available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>

