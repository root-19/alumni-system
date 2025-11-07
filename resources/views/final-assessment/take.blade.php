<x-layouts.app title="Take Final Assessment">
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">{{ $assessment->title }}</h1>
            </div>

            <form action="{{ route('final-assessments.submit', $assessment->id) }}" method="POST" id="assessment-form">
                @csrf
                
                @foreach($assessment->questions as $index => $question)
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Question {{ $index + 1 }}: {{ $question->question_text }}
                        </h3>
                        <div class="space-y-3">
                            @foreach($question->choices as $choice)
                                <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $choice->choice_letter }}" class="mr-3" required>
                                    <span class="font-medium text-gray-700 mr-2">{{ $choice->choice_letter }}.</span>
                                    <span class="text-gray-600">{{ $choice->choice_text }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="bg-white shadow rounded-lg p-6">
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-3 rounded-md hover:bg-green-700 text-lg font-medium">
                        Submit Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

