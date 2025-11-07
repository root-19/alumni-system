<x-layouts.app title="Assessment Results">
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Assessment Results</h1>
                <p class="mt-2 text-gray-600">{{ $attempt->finalAssessment->title }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-500">Score</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $attempt->score }}/{{ $attempt->total_points }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Percentage</p>
                        <p class="text-3xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">{{ $attempt->percentage }}%</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-2xl font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                            {{ $attempt->passed ? '✓ Passed' : '✗ Failed' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Question Review</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($attempt->finalAssessment->questions as $index => $question)
                        @php
                            $userAnswer = $answers[$question->id] ?? null;
                        @endphp
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">
                                Question {{ $index + 1 }}: {{ $question->question_text }}
                            </h4>
                            <div class="space-y-2 ml-4">
                                @foreach($question->choices as $choice)
                                    <div class="flex items-center p-2 rounded {{ 
                                        $choice->choice_letter === $question->correct_answer ? 'bg-green-50 border border-green-200' : 
                                        ($userAnswer && $choice->choice_letter === $userAnswer->selected_answer && !$userAnswer->is_correct ? 'bg-red-50 border border-red-200' : '')
                                    }}">
                                        <span class="font-medium text-gray-700 w-8">{{ $choice->choice_letter }}.</span>
                                        <span class="text-gray-600">{{ $choice->choice_text }}</span>
                                        @if($choice->choice_letter === $question->correct_answer)
                                            <span class="ml-auto text-green-600 font-medium">✓ Correct Answer</span>
                                        @endif
                                        @if($userAnswer && $choice->choice_letter === $userAnswer->selected_answer && !$userAnswer->is_correct)
                                            <span class="ml-auto text-red-600 font-medium">✗ Your Answer</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

