<x-layouts.app :title="__('My Trainings')">
    <div class="max-w-7xl mx-auto mt-10 grid grid-cols-1 lg:grid-cols-3 gap-10 px-4 sm:px-6 lg:px-8">

        <!-- LEFT COLUMN: Trainings List -->
        <div class="lg:col-span-2 space-y-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                    {{ __('My Trainings') }}
                </h1>
            </div>

            @forelse($trainings as $training)
                <div class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-indigo-200 transition-all duration-300 overflow-hidden">
                    <div class="p-6 space-y-5">
                        <!-- Training Header -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 group-hover:text-indigo-600 transition">
                                    {{ $training->title }}
                                </h2>
                                <p class="text-xs uppercase tracking-wide text-gray-400 mt-1">
                                    Created {{ $training->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-[11px] rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 font-medium shadow-sm">
                                Training
                            </span>
                        </div>

                        <p class="text-gray-600 leading-relaxed">
                            {{ $training->description }}
                        </p>

                        <!-- MODULES -->
                        <div class="mt-4">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <span class="inline-flex w-6 h-6 items-center justify-center rounded-md bg-indigo-100 text-indigo-600 text-sm">📘</span>
                                Modules
                            </h3>
                            <div class="max-h-48 overflow-y-auto border border-dashed rounded-xl p-3 bg-gradient-to-br from-gray-50 to-white space-y-2 scrollbar-thin scrollbar-thumb-indigo-300/40">
                                @foreach($training->files->where('type', 'module') as $index => $file)
                                    @php
                                        $isRead = auth()->user()->reads()->where('training_file_id', $file->id)->exists();
                                    @endphp
                                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-50 transition">
                                        <div class="w-6 text-[11px] text-gray-400 font-mono">{{ $index + 1 }}</div>
                                        <a href="{{ Storage::url($file->path) }}" target="_blank"
                                           class="flex-1 text-sm font-medium truncate {{ $isRead ? 'text-indigo-600' : 'text-gray-700' }} hover:text-indigo-700"
                                           onclick="markAsRead({{ $training->id }}, {{ $file->id }})">
                                            {{ $file->original_name }}
                                        </a>
                                        <span class="text-xs {{ $isRead ? 'text-green-600' : 'text-gray-400' }}">
                                            {{ $isRead ? '✓' : '…' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Training Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            @php
                                $pct = $training->progress ?? 0;
                            @endphp
                            <a href="{{ route('training.take', $training->id) }}"
                               class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-medium shadow hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition">
                                <span>🚀 Take Training</span>
                            </a>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <div class="w-24 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-500"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                                <span>{{ $pct }}%</span>
                            </div>
                        </div>

                        <!-- Progress -->
                        @php
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
                                $progress = $averageProgress;
                            } else {
                                // Fallback to simple read count
                                $calculatedProgress = $total > 0 ? round(($read / $total) * 100) : 0;
                                $progress = max($calculatedProgress, $training->progress ?? 0);
                            }
                        @endphp
                        <div class="pt-4">
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-green-400 to-emerald-600 h-3 transition-all duration-500" style="width: {{ $progress }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 font-medium">{{ $progress }}% Completed</p>
                        </div>

                        <!-- Certificate Section -->
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl {{ $progress >= 100 ? 'bg-gradient-to-r from-green-100 to-emerald-100' : 'bg-gray-100' }} flex items-center justify-center">
                                        @if($progress >= 100 && $training->certificate_path)
                                            <span class="text-green-600 text-lg">🎓</span>
                                        @else
                                            <span class="text-gray-400 text-lg">🔒</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">Certificate</p>
                                        @if($progress >= 100 && $training->certificate_path)
                                            <p class="text-xs text-green-600 font-medium">Ready to download</p>
                                        @elseif($progress >= 100 && !$training->certificate_path)
                                            <p class="text-xs text-amber-600 font-medium">Certificate not available</p>
                                        @else
                                            <p class="text-xs text-gray-500">Complete all modules to unlock ({{ $progress }}%)</p>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($progress >= 100 && $training->certificate_path)
                                    <a href="{{ route('training.certificate', $training) }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl shadow-md hover:shadow-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105">
                                        <span>📥</span>
                                        Download
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
                </div>
            @empty
                <p class="text-gray-600">No trainings available yet.</p>
            @endforelse
        </div>

        <!-- RIGHT COLUMN: Certificates Locker -->
        <aside class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4">
                    <span class="w-8 h-8 flex items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 text-xl">🎓</span>
                    Certificates
                </h3>
                <p class="text-xs text-gray-500 mb-4">Unlock certificates by completing every module in a training.</p>

                <div class="space-y-4">
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
                            
                            $complete = $progressC >= 100;
                        @endphp
                        <div class="border border-gray-100 rounded-2xl p-4 flex items-start gap-4 bg-gradient-to-br from-white to-gray-50 hover:shadow transition">
                            <div class="w-9 h-9 flex items-center justify-center rounded-lg {{ $complete ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }} text-lg">
                                {{ $complete ? '✅' : '🔒' }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $trainingCert->title }}</p>
                                <p class="text-[11px] uppercase mt-0.5 {{ $complete ? 'text-green-600' : 'text-gray-400' }}">{{ $complete ? 'Ready' : 'Locked' }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="w-28 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full {{ $complete ? 'bg-green-500' : 'bg-indigo-400/60' }}" style="width: {{ $progressC }}%"></div>
                                    </div>
                                    <span class="text-[11px] text-gray-500">{{ $readC }}/{{ $totalC }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                @if($complete && $trainingCert->certificate_path)
                                    <a href="{{ route('training.certificate', $trainingCert) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-md hover:shadow-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105">
                                        <span>📥</span>
                                        Download
                                    </a>
                                @else
                                    <button disabled 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-200 text-gray-500 cursor-not-allowed">
                                        <span>🔒</span>
                                        Locked
                                    </button>
                                @endif
                                <a href="{{ route('training.take', $trainingCert->id) }}" 
                                   class="text-[11px] text-indigo-600 hover:text-indigo-700 hover:underline font-medium transition-colors">Open Training</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>

    <!-- AJAX to track read progress -->
    <script>
        function markAsRead(trainingId, fileId) {
            fetch(`/trainings/${trainingId}/read/${fileId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
        }
    </script>
</x-layouts.app>
