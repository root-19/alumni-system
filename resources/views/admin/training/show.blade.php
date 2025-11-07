<x-layouts.app :title="__('Training Participants')">
    <div class="max-w-7xl mx-auto mt-8">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-black">{{ $training->title }}</h1>
                <p class="text-gray-600 mt-1">{{ $training->description }}</p>
            </div>
            <a href="{{ route('admin.trainings.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                ← Back to Trainings
            </a>
        </div>

        <!-- Training Info -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Total Quizzes</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $training->quizzes->where('is_active', true)->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Participants</p>
                    <p class="text-2xl font-bold text-gray-800">{{ count($participants) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Passed</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ collect($participants)->where('overall_status', 'Passed')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Participants Table -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Training Participants</h2>
            </div>
            
            @if(count($participants) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Quizzes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Total Attempts</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Details</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($participants as $participant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $participant['user']->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $participant['user']->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($participant['overall_status'] === 'Passed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                ✓ Passed
                                            </span>
                                        @elseif($participant['overall_status'] === 'In Progress')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                In Progress
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Not Started
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>
                                            <span class="text-green-600 font-medium">{{ $participant['passed_quizzes'] }}</span> passed,
                                            <span class="text-red-600 font-medium">{{ $participant['failed_quizzes'] }}</span> failed
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            of {{ $training->quizzes->where('is_active', true)->count() }} total
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $participant['total_attempts'] }} attempt(s)
                                    </td>
                                    <td class="px-6 py-4">
                                        <button onclick="toggleDetails({{ $loop->index }})" 
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                <!-- Expandable Details Row -->
                                <tr id="details-{{ $loop->index }}" class="hidden">
                                    <td colspan="5" class="px-6 py-4 bg-gray-50">
                                        <div class="space-y-4">
                                            <h4 class="font-semibold text-gray-800 mb-3">Quiz Attempts Breakdown:</h4>
                                            @foreach($participant['quizzes'] as $quizData)
                                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <div>
                                                            <h5 class="font-medium text-gray-900">{{ $quizData['quiz']->title }}</h5>
                                                            <p class="text-xs text-gray-500">Passing Score: {{ $quizData['quiz']->passing_score }}%</p>
                                                        </div>
                                                        @if($quizData['passed'])
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                                ✓ Passed
                                                            </span>
                                                        @else
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                                ✗ Failed
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4 mt-3 text-sm">
                                                        <div>
                                                            <span class="text-gray-500">Total Attempts:</span>
                                                            <span class="font-medium text-gray-900 ml-2">{{ $quizData['attempts_count'] }}</span>
                                                        </div>
                                                        @if($quizData['passed'] && $quizData['attempts_before_pass'])
                                                            <div>
                                                                <span class="text-gray-500">Attempts Before Pass:</span>
                                                                <span class="font-medium text-green-600 ml-2">{{ $quizData['attempts_before_pass'] }}</span>
                                                            </div>
                                                        @endif
                                                        @if($quizData['latest_percentage'] !== null)
                                                            <div>
                                                                <span class="text-gray-500">Latest Score:</span>
                                                                <span class="font-medium text-gray-900 ml-2">{{ $quizData['latest_percentage'] }}%</span>
                                                            </div>
                                                        @endif
                                                        @if($quizData['latest_attempt'] && $quizData['latest_attempt']->completed_at)
                                                            <div>
                                                                <span class="text-gray-500">Last Attempt:</span>
                                                                <span class="font-medium text-gray-900 ml-2">
                                                                    {{ $quizData['latest_attempt']->completed_at->format('M d, Y h:i A') }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500">No participants have taken this training yet.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleDetails(index) {
            const detailsRow = document.getElementById('details-' + index);
            const button = event.target;
            
            if (detailsRow.classList.contains('hidden')) {
                detailsRow.classList.remove('hidden');
                button.textContent = 'Hide Details';
            } else {
                detailsRow.classList.add('hidden');
                button.textContent = 'View Details';
            }
        }
    </script>
</x-layouts.app>

