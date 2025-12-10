<x-layouts.app :title="$training->title">
    <div class="max-w-5xl mx-auto mt-8 space-y-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('trainings.index') }}" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">Back to Trainings</span>
            </a>
        </div>

        <!-- Training Header -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $training->title }}</h1>
                    <p class="text-xs uppercase tracking-wide text-gray-400 mt-1">
                        Created {{ $training->created_at->format('M d, Y') }}
                    </p>
                </div>
                <span class="px-3 py-1 text-[11px] rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 font-medium shadow-sm">
                    Training
                </span>
            </div>
            @if($training->description)
                <p class="text-gray-600 leading-relaxed mt-4">
                    {{ $training->description }}
                </p>
            @endif
        </div>

        <!-- MODULES -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="inline-flex w-6 h-6 items-center justify-center rounded-md bg-green-100 text-green-600 text-sm">📘</span>
                Modules
            </h3>
            <div class="max-h-96 overflow-y-auto border border-dashed border-green-200 rounded-xl p-3 bg-gradient-to-br from-green-50 to-white space-y-2 scrollbar-thin scrollbar-thumb-green-300/40">
                @foreach($training->files->where('type', 'module') as $index => $file)
                    @php
                        $isRead = auth()->user()->reads()->where('training_file_id', $file->id)->exists();
                        $moduleProgress = $moduleProgresses->where('training_file_id', $file->id)->first();
                        $completionPercentage = $moduleProgress ? $moduleProgress->completion_percentage : ($isRead ? 100 : 0);
                    @endphp
                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-green-50 transition">
                        <div class="w-6 text-[11px] text-gray-400 font-mono">{{ $index + 1 }}</div>
                        <span class="flex-1 text-sm font-medium truncate {{ $isRead ? 'text-green-600' : 'text-gray-700' }}">
                            {{ $file->original_name }}
                        </span>
                        <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500 transition-all" style="width: {{ $completionPercentage }}%"></div>
                        </div>
                        <span class="text-xs {{ $isRead ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $isRead ? '✓' : '…' }}
                        </span>
                        <a href="{{ Storage::url($file->path) }}" 
                           target="_blank"
                           id="module-{{ $file->id }}"
                           class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-sm hover:shadow-md"
                           onclick="markAsRead({{ $training->id }}, {{ $file->id }}, {{ $index + 1 }}, '{{ $file->original_name }}')">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- QUIZ SECTION -->
        @if($training->quizzes->where('is_active', true)->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="inline-flex w-6 h-6 items-center justify-center rounded-md bg-blue-100 text-blue-600 text-sm">📝</span>
                    Quiz
                </h3>
                <div class="space-y-3">
                    @foreach($training->quizzes->where('is_active', true) as $quiz)
                        @php
                            $quizAttempt = $quizAttempts[$quiz->id] ?? null;
                            $isQuizCompleted = $quizAttempt && $quizAttempt->completed_at;
                            $isQuizPassed = $quizAttempt && $quizAttempt->passed;
                        @endphp
                        <div class="border border-blue-200 rounded-xl p-4 bg-gradient-to-br from-blue-50 to-white">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-800">{{ $quiz->title }}</h4>
                                    @if($quiz->description)
                                        <p class="text-xs text-gray-600 mt-1">{{ $quiz->description }}</p>
                                    @endif
                                    @if($isQuizCompleted)
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-xs font-medium {{ $isQuizPassed ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $isQuizPassed ? '✓ Passed' : '✗ Failed' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                Score: {{ $quizAttempt->percentage }}%
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($isQuizCompleted)
                                        <a href="{{ route('quizzes.result', $quizAttempt->id) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                            <span>View Results</span>
                                        </a>
                                        @if(!$isQuizPassed)
                                            <form action="{{ route('quizzes.retake', $quiz->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition"
                                                        onclick="return confirm('Are you sure you want to retake this quiz? Your previous attempt will be reset.')">
                                                    <span>🔄</span>
                                                    Retake
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('quizzes.take', $quiz->id) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition">
                                            <span>Take Quiz</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- FINAL ASSESSMENT SECTION -->
        @if($training->finalAssessments->where('is_active', true)->count() > 0)
            @php
                // Check if quiz is passed (if there's a quiz)
                $hasQuiz = $training->quizzes->where('is_active', true)->count() > 0;
                $allQuizzesPassed = true;
                if ($hasQuiz) {
                    foreach ($training->quizzes->where('is_active', true) as $quiz) {
                        $quizAttempt = $quizAttempts[$quiz->id] ?? null;
                        if (!$quizAttempt || !$quizAttempt->completed_at || !$quizAttempt->passed) {
                            $allQuizzesPassed = false;
                            break;
                        }
                    }
                }
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 {{ !$allQuizzesPassed && $hasQuiz ? 'opacity-50' : '' }}">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="inline-flex w-6 h-6 items-center justify-center rounded-md bg-purple-100 text-purple-600 text-sm">🎯</span>
                    Final Assessment
                    @if(!$allQuizzesPassed && $hasQuiz)
                        <span class="text-xs text-gray-500 font-normal">(Pass quiz first)</span>
                    @endif
                </h3>
                <div class="space-y-3">
                    @foreach($training->finalAssessments->where('is_active', true) as $finalAssessment)
                        @php
                            $finalAttempt = $finalAssessmentAttempts[$finalAssessment->id] ?? null;
                            $isFinalCompleted = $finalAttempt && $finalAttempt->completed_at;
                            $isFinalPassed = $finalAttempt && $finalAttempt->passed;
                        @endphp
                        <div class="border border-purple-200 rounded-xl p-4 bg-gradient-to-br from-purple-50 to-white">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-800">{{ $finalAssessment->title }}</h4>
                                    @if($finalAssessment->description)
                                        <p class="text-xs text-gray-600 mt-1">{{ $finalAssessment->description }}</p>
                                    @endif
                                    @if($isFinalCompleted)
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-xs font-medium {{ $isFinalPassed ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $isFinalPassed ? '✓ Passed' : '✗ Failed' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                Score: {{ $finalAttempt->percentage }}%
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($isFinalCompleted)
                                        <a href="{{ route('final-assessments.result', $finalAttempt->id) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
                                            <span>View Results</span>
                                        </a>
                                        @if($isFinalPassed)
                                            <a href="{{ route('final-assessments.certificate', $finalAttempt->id) }}"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-700 to-green-800 text-white text-sm font-semibold rounded-lg hover:from-emerald-800 hover:to-green-900 transition shadow-md">
                                                <span>📜</span>
                                                Download Certificate
                                            </a>
                                        @endif
                                        @if(!$isFinalPassed)
                                            <form action="{{ route('final-assessments.retake', $finalAssessment->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition"
                                                        onclick="return confirm('Are you sure you want to retake this assessment? Your previous attempt will be reset.')">
                                                    <span>🔄</span>
                                                    Retake Final Assessment
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($allQuizzesPassed || !$hasQuiz)
                                        <a href="{{ route('final-assessments.take', $finalAssessment->id) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-purple-800 transition shadow-md">
                                            <span>🎯</span>
                                            Take Final Assessment
                                        </a>
                                    @else
                                        <button disabled
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-400 text-sm font-medium rounded-lg cursor-not-allowed">
                                            <span>🔒</span>
                                            Locked
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- PROGRESS -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Progress</h3>
            <div class="pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="progress-fill" class="h-full bg-gradient-to-r from-green-400 to-emerald-500 transition-all duration-500"
                                 style="width: {{ $averageProgress }}%"></div>
                        </div>
                        <span class="font-semibold">{{ $averageProgress }}%</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $read }}/{{ $total }} completed
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2 font-medium">{{ $averageProgress }}% Completed</p>
            </div>
        </div>

        <!-- CERTIFICATE SECTION -->
        @php
            // Check if all quizzes are passed
            $hasQuiz = $training->quizzes->where('is_active', true)->count() > 0;
            $allQuizzesPassed = true;
            $anyQuizFailed = false;
            
            if ($hasQuiz) {
                foreach ($training->quizzes->where('is_active', true) as $quiz) {
                    $quizAttempt = $quizAttempts[$quiz->id] ?? null;
                    if ($quizAttempt && $quizAttempt->completed_at) {
                        if (!$quizAttempt->passed) {
                            $allQuizzesPassed = false;
                            $anyQuizFailed = true;
                        }
                    } else {
                        // Quiz not completed yet
                        $allQuizzesPassed = false;
                    }
                }
            }
            
            // Certificate unlock logic:
            // If quizzes exist: unlock if ALL quizzes are PASSED (regardless of module completion)
            // If no quizzes: unlock if modules are 100% complete
            if ($hasQuiz) {
                $certificateUnlocked = $allQuizzesPassed;
            } else {
                $certificateUnlocked = $averageProgress >= 100;
            }
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="p-4 gap-4 {{ $certificateUnlocked && $training->certificate_path ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-200' : 'bg-gray-50 border-gray-200' }} rounded-xl border">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl {{ $certificateUnlocked && $training->certificate_path ? 'bg-gradient-to-r from-green-100 to-emerald-100' : 'bg-gray-100' }} flex items-center justify-center shadow-sm">
                            @if($certificateUnlocked && $training->certificate_path)
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800">Training Certificate</h4>
                            @if($certificateUnlocked && $training->certificate_path)
                                <p class="text-xs text-green-600 font-medium">Ready to download!</p>
                            @elseif($certificateUnlocked && !$training->certificate_path)
                                <p class="text-xs text-amber-600 font-medium">Certificate not available</p>
                            @elseif($hasQuiz && $anyQuizFailed)
                                <p class="text-xs text-red-600 font-medium">Some quizzes failed. Retake to unlock.</p>
                            @elseif($hasQuiz && !$allQuizzesPassed)
                                <p class="text-xs text-gray-500">Pass all quizzes to unlock</p>
                            @else
                                <p class="text-xs text-gray-500">Complete all modules to unlock</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($certificateUnlocked && $training->certificate_path)
                        <a href="{{ route('training.certificate', $training) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl shadow-md hover:shadow-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download
                        </a>
                    @else
                        <button disabled
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-400 text-sm font-medium rounded-xl cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Locked
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- AJAX to track read progress -->
    <script>
        function markAsRead(trainingId, fileId, index, name) {
            const moduleLink = document.getElementById('module-' + fileId);
            if (moduleLink.classList.contains('read')) return; // Already read

            fetch(`/trainings/${trainingId}/read/${fileId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    // Mark as read visually
                    moduleLink.classList.add('read');
                    
                    // Also trigger module progress update to sync with training/take.blade.php system
                    fetch(`/trainings/${trainingId}/module-progress/${fileId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            scroll_progress: 100, // Assume full scroll when manually marked as read
                            time_spent: 60, // Assume minimum time requirement met
                            completion_percentage: 100 // Mark as fully completed
                        })
                    }).then(() => {
                        // Reload page to show updated progress and certificate status
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }).catch(error => {
                        console.error('Error updating module progress:', error);
                        // Still reload to show basic progress update
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    });
                }
            }).catch(error => {
                console.error('Error marking as read:', error);
            });
        }
    </script>
</x-layouts.app>

