<x-layouts.app :title="$training->title">
    <div class="max-w-5xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $training->title }}</h1>
        <p class="text-gray-600 mt-2">{{ $training->description }}</p>

        <!-- Overall Training Progress -->
        @php
            // Get user-specific progress
            $total = $total ?? $training->files->where('type','module')->count();
            $read = $read ?? auth()->user()->reads()->whereIn('training_file_id', $training->files->pluck('id'))->count();
            
            // Use the average progress from detailed module data (passed from controller)
            $progress = $averageProgress ?? 0;
        @endphp
        <div id="training-progress" data-total="{{ $total }}" data-read="{{ $read }}" class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl border border-indigo-100">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 text-sm">📊</span>
                    Training Progress
                </h3>
                <span class="text-2xl font-bold text-indigo-600">{{ $progress }}%</span>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                <div id="training-progress-fill" class="bg-gradient-to-r from-indigo-500 to-purple-500 h-4 rounded-full transition-all duration-700 ease-out" style="width: {{ $progress }}%"></div>
            </div>
            
            <div class="flex items-center justify-between mt-2">
                <p id="training-progress-text" class="text-sm text-gray-600 font-medium">{{ $read }}/{{ $total }} modules completed</p>
                @if($progress >= 100)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">🎉 Completed!</span>
                @endif
            </div>
        </div>

        <!-- Modules -->
        <div class="mt-8 space-y-6">
            @foreach($training->files->where('type','module') as $index => $file)
                @php
                    $isRead = auth()->user()->reads()->where('training_file_id', $file->id)->exists();
                @endphp
                <div class="bg-white shadow rounded-lg p-4">
                    <h3 class="font-semibold text-gray-700 mb-2">
                        {{ $index + 1 }}. {{ $file->original_name }}
                        @if($isRead) ✅ @endif
                    </h3>

                    <!-- Progress for this module -->
                    @php
                        // Get progress for this specific module
                        $moduleProgress = isset($moduleProgresses) ? 
                            $moduleProgresses->where('training_file_id', $file->id)->first() : null;
                        $moduleProgressPercent = $moduleProgress ? $moduleProgress->completion_percentage : 0;
                    @endphp
                    <div class="mb-4 p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Module Progress</span>
                            <span class="text-sm font-bold {{ $moduleProgressPercent >= 100 ? 'text-green-600' : 'text-indigo-600' }}">{{ $moduleProgressPercent }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full transition-all duration-500 {{ $moduleProgressPercent >= 100 ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gradient-to-r from-indigo-400 to-purple-500' }}" style="width: {{ $moduleProgressPercent }}%"></div>
                        </div>
                        @if($moduleProgress)
                            <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                                <span>⏱️ {{ $moduleProgress->time_spent }}s reading</span>
                                <span>📜 {{ $moduleProgress->scroll_progress }}% scrolled</span>
                            </div>
                        @else
                            <p class="text-xs text-gray-400 mt-2">Start reading to track progress</p>
                        @endif
                    </div>

                    <!-- Embedded file (PDF, docs, etc.) -->
            <iframe 
    src="{{ Storage::url($file->path) }}"
    class="w-full h-[842px] border rounded shadow-md"
    style="aspect-ratio: 210 / 297;"
    data-training-id="{{ $training->id }}"
    data-file-id="{{ $file->id }}"
    data-index="{{ $index + 1 }}"
    data-name="{{ $file->original_name }}">
</iframe>


                    <!-- Mark as Read Button -->
                    <!-- Removed: No manual button, progress is tracked automatically -->

                    <!-- Certificate Section -->
                    <div class="mt-6 p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl {{ $progress >= 100 ? 'bg-gradient-to-r from-green-100 to-emerald-100' : 'bg-gray-100' }} flex items-center justify-center">
                                    @if($progress >= 100 && $training->certificate_path)
                                        <span class="text-green-600 text-xl">🎓</span>
                                    @else
                                        <span class="text-gray-400 text-xl">🔒</span>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800">Training Certificate</h4>
                                    @if($progress >= 100 && $training->certificate_path)
                                        <p class="text-xs text-green-600 font-medium">Congratulations! Certificate ready</p>
                                    @elseif($progress >= 100 && !$training->certificate_path)
                                        <p class="text-xs text-amber-600 font-medium">Certificate not available</p>
                                    @else
                                        <p class="text-xs text-gray-500">Complete all modules ({{ $progress }}%) to unlock</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($progress >= 100 && $training->certificate_path)
                                <a href="{{ route('training.certificate', $training) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl shadow-md hover:shadow-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105">
                                    <span>📥</span>
                                    Download Certificate
                                </a>
                            @else
                                <button disabled
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-400 text-sm font-medium rounded-xl cursor-not-allowed">
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

    <!-- AJAX + Auto-detect reading to track progress -->
    <script>
        // Progress tracking configuration
        const MIN_READING_TIME_MS = 60000; // 60 seconds minimum reading time
        const SCROLL_UPDATE_INTERVAL = 3000; // Update progress every 3 seconds
        const TIME_UPDATE_INTERVAL = 1000; // Update time tracking every second
        
        // Track progress for each module
        const moduleProgress = new Map();
        const moduleTimers = new Map();
        const scrollPositions = new Map();
        const timeSpent = new Map();
        const lastUpdateTime = new Map();
 

 function calculateCompletionPercentage(fileId, scrollPercent, timeSpentSeconds) {
    console.log(`Calculating progress for file ${fileId}: scroll=${scrollPercent}%, time=${timeSpentSeconds}s`);

    // ✅ If time >= 60s OR scroll >= 70%, award 100%
    if (timeSpentSeconds >= 60 || scrollPercent >= 70) {
        console.log(`Requirements met: time ${timeSpentSeconds}s >= 60 OR scroll ${scrollPercent}% >= 70 → 100%`);
        return 100;
    } 
    
    // Otherwise, calculate partial progress
    let timeProgress = Math.min((timeSpentSeconds / 60) * 60, 60); // up to 60%
    let scrollProgress = Math.min((scrollPercent / 100) * 40, 40); // up to 40%
    let totalProgress = timeProgress + scrollProgress;

    console.log(`Both factors: time=${Math.round(timeProgress)}%, scroll=${Math.round(scrollProgress)}%, total=${Math.round(totalProgress)}%`);
    return Math.min(Math.round(totalProgress), 100);
}


        // Update module progress to server
        function updateModuleProgressToServer(fileId, trainingId, scrollPercent, timeSpentSeconds, completionPercent) {
            const now = Date.now();
            const lastUpdate = lastUpdateTime.get(fileId) || 0;
            
            // Only update if enough time has passed (throttle updates)
            if (now - lastUpdate < SCROLL_UPDATE_INTERVAL) {
                return;
            }
            
            lastUpdateTime.set(fileId, now);
            
            console.log(`Updating module ${fileId}: Scroll ${scrollPercent}%, Time ${timeSpentSeconds}s, Completion ${completionPercent}%`);
            
            fetch(`/trainings/${trainingId}/module-progress/${fileId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    scroll_progress: scrollPercent,
                    time_spent: timeSpentSeconds,
                    completion_percentage: completionPercent
                })
            }).then(async (response) => {
                const data = await response.json();
                if (response.ok) {
                    console.log('Module progress updated:', data);
                        updateOverallProgress();
                } else {
                    console.error('Failed to update module progress:', data);
                }
            }).catch(error => {
                console.error('Error updating module progress:', error);
            });
        }

        function updateOverallProgress() {
            const container = document.getElementById('training-progress');
            if (!container) return;
            
            // Calculate overall progress from individual module progress
            let totalCompletion = 0;
            let moduleCount = 0;
            let completedModules = 0;
            
            moduleProgress.forEach((progress, fileId) => {
                totalCompletion += progress.completion_percentage;
                moduleCount++;
                if (progress.completion_percentage >= 100) {
                    completedModules++;
                }
            });
            
            const overallProgress = moduleCount > 0 ? Math.round(totalCompletion / moduleCount) : 0;
            
            const fill = document.getElementById('training-progress-fill');
            const text = document.getElementById('training-progress-text');
            const progressPercent = document.querySelector('.text-2xl.font-bold.text-indigo-600');
            
            console.log(`Overall progress update: ${moduleCount} modules, total=${totalCompletion}, avg=${overallProgress}%`);
            
            if (fill) {
                fill.style.width = overallProgress + '%';
                // Update progress bar color based on completion
                if (overallProgress >= 100) {
                    fill.className = 'bg-gradient-to-r from-green-400 to-emerald-500 h-4 rounded-full transition-all duration-700 ease-out';
                } else {
                    fill.className = 'bg-gradient-to-r from-indigo-500 to-purple-500 h-4 rounded-full transition-all duration-700 ease-out';
                }
            }
            
            if (text) text.textContent = `${completedModules}/${moduleCount} modules completed`;
            if (progressPercent) progressPercent.textContent = `${overallProgress}%`;
            
            // Show completion badge if 100%
            const completionBadge = document.querySelector('.text-xs.bg-green-100');
            if (overallProgress >= 100 && !completionBadge) {
                const progressContainer = document.getElementById('training-progress');
                const badge = document.createElement('span');
                badge.className = 'text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium';
                badge.textContent = '🎉 Completed!';
                const textContainer = text.parentElement;
                textContainer.appendChild(badge);
            }
        }

        // Track scroll position for PDFs
        function trackScrollPosition(fileId, trainingId) {
            const iframe = document.querySelector(`iframe[data-file-id="${fileId}"]`);
            if (!iframe) {
                console.log(`No iframe found for file ${fileId}`);
                return;
            }

            console.log(`Setting up scroll tracking for file ${fileId}`);

            try {
                const doc = iframe.contentDocument || iframe.contentWindow.document;
                if (!doc) {
                    console.log(`Cannot access iframe document for file ${fileId}`);
                    return;
                }

                doc.addEventListener('scroll', () => {
                    const el = doc.scrollingElement || doc.documentElement;
                    if (!el) return;

                    const scrollTop = el.scrollTop;
                    const scrollHeight = el.scrollHeight;
                    const clientHeight = el.clientHeight;
                    
                    // Calculate scroll percentage
                    const scrollPercent = Math.round((scrollTop / (scrollHeight - clientHeight)) * 100);
                    console.log(`Scroll detected for file ${fileId}: ${scrollPercent}%`);
                    
                    scrollPositions.set(fileId, scrollPercent);

                    // Update module progress
                    const currentTime = timeSpent.get(fileId) || 0;
                    const completionPercent = calculateCompletionPercentage(fileId, scrollPercent, currentTime);
                    
                    moduleProgress.set(fileId, {
                        scroll_progress: scrollPercent,
                        time_spent: currentTime,
                        completion_percentage: completionPercent
                    });

                    console.log(`Updated module progress for ${fileId}: ${completionPercent}%`);
                    updateModuleProgressToServer(fileId, trainingId, scrollPercent, currentTime, completionPercent);
                }, { passive: true });
            } catch (e) {
                console.log('Cannot access iframe content (likely PDF):', e.message);
                // For PDFs, we'll rely on time-based progress
                console.log(`Setting up time-based progress for PDF file ${fileId}`);
            }
        }

        // Initialize progress tracking for all modules
        document.addEventListener('DOMContentLoaded', () => {
            const iframes = document.querySelectorAll('iframe[data-training-id][data-file-id]');
            
            if (iframes.length === 0) return;

            // Load existing progress from server
            @if(isset($moduleProgresses))
                @foreach($moduleProgresses as $moduleProgress)
                    const existingProgress{{ $moduleProgress->training_file_id }} = {
                        scroll_progress: {{ $moduleProgress->scroll_progress }},
                        time_spent: {{ $moduleProgress->time_spent }},
                        completion_percentage: {{ $moduleProgress->completion_percentage }}
                    };
                    moduleProgress.set('{{ $moduleProgress->training_file_id }}', existingProgress{{ $moduleProgress->training_file_id }});
                    timeSpent.set('{{ $moduleProgress->training_file_id }}', {{ $moduleProgress->time_spent }});
                    scrollPositions.set('{{ $moduleProgress->training_file_id }}', {{ $moduleProgress->scroll_progress }});
                    console.log(`Loaded existing progress for file {{ $moduleProgress->training_file_id }}: {{ $moduleProgress->completion_percentage }}%`);
                @endforeach
            @endif

            // Initialize tracking for each iframe
            iframes.forEach(iframe => {
                const fileId = iframe.dataset.fileId;
                const trainingId = iframe.dataset.trainingId;
                
                // Initialize tracking data (use existing if available)
                if (!timeSpent.has(fileId)) {
                timeSpent.set(fileId, 0);
                }
                if (!scrollPositions.has(fileId)) {
                    scrollPositions.set(fileId, 0);
                }
                if (!moduleProgress.has(fileId)) {
                    moduleProgress.set(fileId, {
                        scroll_progress: 0,
                        time_spent: 0,
                        completion_percentage: 0
                    });
                }
                
                // Set up scroll tracking
                trackScrollPosition(fileId, trainingId);
            });

            // Time tracking: increment time spent every second for visible iframes
            setInterval(() => {
                iframes.forEach(iframe => {
                    const fileId = iframe.dataset.fileId;
                    const trainingId = iframe.dataset.trainingId;
                    
                    const rect = iframe.getBoundingClientRect();
                    const inView = rect.top < window.innerHeight && rect.bottom > 0;
                    
                    if (inView) {
                        const currentTime = timeSpent.get(fileId) || 0;
                        const newTime = currentTime + 1; // Increment by 1 second
                        timeSpent.set(fileId, newTime);
                        
                        // Update progress with new time
                        const scrollPercent = scrollPositions.get(fileId) || 0;
                        const completionPercent = calculateCompletionPercentage(fileId, scrollPercent, newTime);
                        
                        moduleProgress.set(fileId, {
                            scroll_progress: scrollPercent,
                            time_spent: newTime,
                            completion_percentage: completionPercent
                        });

                        console.log(`Time-based progress for ${fileId}: ${newTime}s, scroll=${scrollPercent}%, completion=${completionPercent}%`);

                        // Update server every 3 seconds
                        if (newTime % 3 === 0) {
                            updateModuleProgressToServer(fileId, trainingId, scrollPercent, newTime, completionPercent);
                        }
                    }
                });
            }, TIME_UPDATE_INTERVAL);
        });
    </script>
</x-layouts.app>
