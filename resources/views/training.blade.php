<x-layouts.app :title="__('My Trainings')">
    <div class="max-w-7xl mx-auto mt-8 space-y-8">
        
        <!-- TOP ROW: Left and Right Columns -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- LEFT COLUMN: Certificates -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4">
                    <span class="w-8 h-8 flex items-center justify-center rounded-xl bg-green-100 text-green-600 text-xl">🎓</span>
                    Certificates
                </h3>
                <p class="text-xs text-gray-500 mb-4">Unlock certificates by passing all quizzes (if required) or completing all modules.</p>

                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @foreach($trainings as $trainingCert)
                        @php
                            // Use the same progress calculation logic as training/take.blade.php
                            $totalC = $trainingCert->files->where('type','module')->count();
                            $readC = auth()->user()->reads()->whereIn('training_file_id', $trainingCert->files->pluck('id'))->count();
                            
                            // Get detailed module progress (same as take page)
                            $moduleProgressesC = \App\Models\UserModuleProgress::where('user_id', auth()->id())
                                ->whereIn('training_file_id', $trainingCert->files->pluck('id'))
                                ->get();
                            
                            // Calculate progress from detailed module data (same as take page)
                            if ($moduleProgressesC->count() > 0) {
                                $averageProgressC = round($moduleProgressesC->avg('completion_percentage'));
                                $progressC = $averageProgressC;
                            } else {
                                // Fallback to simple read count
                                $calculatedProgressC = $totalC > 0 ? round(($readC / $totalC) * 100) : 0;
                                $progressC = max($calculatedProgressC, $trainingCert->progress ?? 0);
                            }
                            
                            // Check if final assessment is passed (if exists)
                            $hasFinalAssessmentC = $trainingCert->finalAssessments->where('is_active', true)->count() > 0;
                            $finalAssessmentPassedC = false;
                            
                            if ($hasFinalAssessmentC) {
                                foreach ($trainingCert->finalAssessments->where('is_active', true) as $finalAssessmentC) {
                                    $finalAttemptC = $finalAssessmentAttempts[$finalAssessmentC->id] ?? null;
                                    if ($finalAttemptC && $finalAttemptC->completed_at && $finalAttemptC->passed) {
                                        $finalAssessmentPassedC = true;
                                        break;
                                    }
                                }
                            }
                            
                            // Certificate unlock logic:
                            // If quizzes exist: unlock if ALL quizzes are PASSED (regardless of module completion)
                            // If no quizzes: unlock if modules are 100% complete
                            $hasQuizC = $trainingCert->quizzes->where('is_active', true)->count() > 0;
                            $allQuizzesPassedC = true;
                            $anyQuizFailedC = false;
                            
                            if ($hasQuizC) {
                                foreach ($trainingCert->quizzes->where('is_active', true) as $quizC) {
                                    $quizAttemptC = $quizAttempts[$quizC->id] ?? null;
                                    if ($quizAttemptC && $quizAttemptC->completed_at) {
                                        if (!$quizAttemptC->passed) {
                                            $allQuizzesPassedC = false;
                                            $anyQuizFailedC = true;
                                        }
                                    } else {
                                        // Quiz not completed yet
                                        $allQuizzesPassedC = false;
                                    }
                                }
                            }
                            
                            if ($hasQuizC) {
                                $complete = $allQuizzesPassedC;
                            } else {
                                $complete = $progressC >= 100;
                            }
                        @endphp
                        <div class="border border-gray-100 rounded-2xl p-4 flex items-start gap-4 {{ $complete && $trainingCert->certificate_path ? 'bg-gradient-to-br from-green-50 to-white' : 'bg-gradient-to-br from-white to-gray-50' }} hover:shadow transition">
                            <div class="w-9 h-9 flex items-center justify-center rounded-lg {{ $complete && $trainingCert->certificate_path ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }} shadow-sm">
                                @if($complete && $trainingCert->certificate_path)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $trainingCert->title }}</p>
                                <p class="text-[11px] uppercase mt-0.5 {{ $complete && $trainingCert->certificate_path ? 'text-green-600' : 'text-gray-400' }}">{{ $complete && $trainingCert->certificate_path ? 'Ready' : 'Locked' }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="w-28 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500" style="width: {{ $progressC }}%"></div>
                                    </div>
                                    <span class="text-[11px] text-gray-500">{{ $readC }}/{{ $totalC }}</span>
                                </div>
                                @if($hasQuizC && $anyQuizFailedC)
                                    <p class="text-[10px] text-red-600 mt-1 font-medium">Some quizzes failed. Retake to unlock.</p>
                                @elseif($hasQuizC && !$allQuizzesPassedC)
                                    <p class="text-[10px] text-amber-600 mt-1 font-medium">Pass all quizzes to unlock</p>
                                @endif
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                @if($complete && $trainingCert->certificate_path)
                                    <a href="{{ route('training.certificate', $trainingCert) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-md hover:shadow-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download
                                    </a>
                                @else
                                    <button disabled 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-200 text-gray-500 cursor-not-allowed">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Locked
                                    </button>
                                @endif
                                <a href="{{ route('training.take', $trainingCert->id) }}" 
                                   class="text-[11px] text-green-600 hover:text-green-700 hover:underline font-medium transition-colors">Open Training</a>
                            </div>
                        </div>
                    @endforeach
                </div>  
            </div>

            <!-- RIGHT COLUMN: Overall Progress Summary -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 text-center">
                <div class="w-16 h-16 mx-auto bg-gradient-to-r from-green-100 to-emerald-100 flex items-center justify-center rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Training Overview</h3>
                <p class="text-sm text-gray-500 mb-4">Track your learning journey</p>

                @php
                    $totalTrainings = $trainings->count();
                    $completedTrainings = $trainings->filter(function($training) {
                        // Use the same progress calculation logic as training/take.blade.php
                        $total = $training->files->where('type', 'module')->count();
                        $read = auth()->user()->reads()->whereIn('training_file_id', $training->files->pluck('id'))->count();
                        
                        // Get detailed module progress (same as take page)
                        $moduleProgresses = \App\Models\UserModuleProgress::where('user_id', auth()->id())
                            ->whereIn('training_file_id', $training->files->pluck('id'))
                            ->get();
                        
                        // Calculate progress from detailed module data (same as take page)
                        if ($moduleProgresses->count() > 0) {
                            $averageProgress = round($moduleProgresses->avg('completion_percentage'));
                            return $averageProgress >= 100;
                        } else {
                            // Fallback to simple read count
                            return $total > 0 && $read === $total;
                        }
                    })->count();
                    $overallProgress = $totalTrainings > 0 ? round(($completedTrainings / $totalTrainings) * 100) : 0;
                @endphp

                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-3 rounded-full transition-all duration-700" style="width: {{ $overallProgress }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ $completedTrainings }}/{{ $totalTrainings }} trainings completed</p>
                </div>

                <div class="text-2xl font-bold text-green-600 mb-2">{{ $overallProgress }}%</div>
                <p class="text-xs text-gray-500">Overall completion rate</p>
            </div>
        </div>

        <!-- BOTTOM SECTION: Trainings List (Centered) -->
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center justify-center mb-6">
                <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    {{ __('My Trainings') }}
                </h1>
            </div>

            <div class="space-y-10">
                @forelse($trainings as $training)
                <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-green-200 transition-all duration-300 overflow-hidden">
                    <div class="p-6 space-y-6">
                        <!-- Training Header -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 group-hover:text-green-600 transition">
                                    {{ $training->title }}
                                </h2>
                                <p class="text-xs uppercase tracking-wide text-gray-400 mt-1">
                                    Created {{ $training->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-[11px] rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 font-medium shadow-sm">
                                Training
                            </span>
                        </div>

                        <p class="text-gray-600 leading-relaxed">
                            {{ $training->description }}
                        </p>

                        <!-- MODULES -->
                        <div class="mt-8">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="inline-flex w-6 h-6 items-center justify-center rounded-md bg-green-100 text-green-600 text-sm">📘</span>
                                Modules
                            </h3>
                            <div class="max-h-48 overflow-y-auto border border-dashed border-green-200 rounded-xl p-3 bg-gradient-to-br from-green-50 to-white space-y-2 scrollbar-thin scrollbar-thumb-green-300/40">
                                @foreach($training->files->where('type', 'module') as $index => $file)
                                    @php
                                        $isRead = auth()->user()->reads()->where('training_file_id', $file->id)->exists();
                                    @endphp
                                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-green-50 transition">
                                        <div class="w-6 text-[11px] text-gray-400 font-mono">{{ $index + 1 }}</div>
                                        <span class="flex-1 text-sm font-medium truncate {{ $isRead ? 'text-green-600' : 'text-gray-700' }}">
                                            {{ $file->original_name }}
                                        </span>
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
                            <div class="mt-8">
                                <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
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
                            <div class="mt-8 {{ !$allQuizzesPassed && $hasQuiz ? 'opacity-50' : '' }}">
                                <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
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
                        <div class="mt-8">
                        @php
                            // Use the same progress calculation logic as training/take.blade.php
                            $total = $training->files->where('type', 'module')->count();
                            $read = auth()->user()->reads()
                                        ->whereIn('training_file_id', $training->files->pluck('id'))
                                        ->count();
                            
                            // Get detailed module progress (same as take page)
                            $moduleProgresses = \App\Models\UserModuleProgress::where('user_id', auth()->id())
                                ->whereIn('training_file_id', $training->files->pluck('id'))
                                ->get();
                            
                            // Calculate progress from detailed module data (same as take page)
                            if ($moduleProgresses->count() > 0) {
                                $averageProgress = round($moduleProgresses->avg('completion_percentage'));
                                $progress = $averageProgress;
                            } else {
                                // Fallback to simple read count
                                $calculatedProgress = $total > 0 ? round(($read / $total) * 100) : 0;
                                $progress = max($calculatedProgress, $training->progress ?? 0);
                            }
                        @endphp
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <div class="w-24 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="progress-fill-{{ $training->id }}" class="h-full bg-gradient-to-r from-green-400 to-emerald-500 transition-all duration-500"
                                             style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div id="progress-bar-{{ $training->id }}" class="text-xs text-gray-500" data-total="{{ $total }}" data-read="{{ $read }}">
                                    {{ $read }}/{{ $total }} completed
                                </div>
                            </div>
                            <p id="progress-text-{{ $training->id }}" class="text-xs text-gray-500 mt-2 font-medium">{{ $progress }}% Completed</p>
                        </div>
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
                            $certificateUnlocked = $progress >= 100;
                        }
                    @endphp
                    <div class="px-6 pb-6">
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
                @empty
                    <p class="text-gray-600 text-center">No trainings available yet.</p>
                @endforelse
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
                    moduleLink.innerHTML = '✅ ' + index + '. ' + name;

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


