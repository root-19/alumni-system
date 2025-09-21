<x-layouts.app :title="$training->title">
    <div class="max-w-5xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $training->title }}</h1>
        <p class="text-gray-600 mt-2">{{ $training->description }}</p>

        <!-- Overall Training Progress -->
        @php
            // Fallback compute (controller already passes these normally)
            $total = $total ?? $training->files->where('type','module')->count();
            $read = $read ?? auth()->user()->reads()->whereIn('training_file_id', $training->files->pluck('id'))->count();
            $calculatedProgress = $total > 0 ? round(($read / $total) * 100) : 0;
            // Always use the higher progress value (calculated or stored)
            $progress = max($calculatedProgress, $training->progress ?? 0);
        @endphp
        <div id="training-progress" data-total="{{ $total }}" data-read="{{ $read }}" class="mt-6">
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div id="training-progress-fill" class="bg-green-500 h-3 rounded-full" style="width: {{ $progress }}%"></div>
            </div>
            <p id="training-progress-text" class="text-sm text-gray-500 mt-2">{{ $progress }}% completed ({{ $read }}/{{ $total }} modules)</p>
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
                    <div class="mb-4">
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-green-500 h-3 rounded-full" style="width: {{ $isRead ? '100' : '0' }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $isRead ? '100' : '0' }}% Completed</p>
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
               

                    <!-- Certificate for this module -->
                    <div class="mt-4">
                        @if(($isRead || $training->progress >= 100) && $training->certificate_path)
                            <a href="{{ route('training.certificate', $training) }}"
                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                                🎓 Download Certificate
                            </a>
                        @elseif($isRead || $training->progress >= 100)
                            <p class="text-sm text-gray-500" style="display: block;">Certificate not available for this module.</p>
                        @else
                            <button disabled
                                    class="inline-block px-4 py-2 bg-gray-400 text-white rounded-lg shadow cursor-not-allowed">
                                🔒 Complete module to unlock certificate
                            </button>
                            @if($file->certificate_path)
                                <a href="{{ Storage::url($file->certificate_path) }}"
                                   class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700"
                                   style="display: none;">
                                    🎓 Download Certificate
                                </a>
                            @else
                                <p class="text-sm text-gray-500" style="display: none;">Certificate not available for this module.</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- AJAX + Auto-detect reading to track progress -->
    <script>
        const AUTO_VISIBLE_MS = 1000; // ms required in view
        const AUTO_VISIBLE_RATIO = 0.2; // 20% visibility

        const observedTimers = new Map();
        const sentSet = new Set();

        function updateOverallProgress() {
            const container = document.getElementById('training-progress');
            if (!container) return;
            const total = parseInt(container.dataset.total);
            let read = parseInt(container.dataset.read);
            const fill = document.getElementById('training-progress-fill');
            const text = document.getElementById('training-progress-text');
            const percent = total > 0 ? Math.round((read / total) * 100) : 0;
            fill.style.width = percent + '%';
            text.textContent = `${percent}% completed (${read}/${total} modules)`;
        }

        function markAsRead(trainingId, fileId, index, name) {
            // Prevent duplicate
            if (sentSet.has(fileId)) return;
            sentSet.add(fileId);

            const moduleCard = document.querySelector(`iframe[data-file-id="${fileId}"]`)?.closest('.bg-white');
            const progressBar = moduleCard?.querySelector('.bg-gray-200 .bg-green-500');
            const progressText = moduleCard?.querySelector('.text-xs.text-gray-500');
            const title = moduleCard?.querySelector('h3');
            const button = moduleCard?.querySelector('button');
            fetch(`/trainings/${trainingId}/read/${fileId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            }).then(async (resp) => {
                let data = {};
                try { data = await resp.json(); } catch(_) {}
                if (!resp.ok) throw new Error(data?.message || 'Failed');
                // Update module UI
                if (progressBar) progressBar.style.width = '100%';
                if (progressText) progressText.textContent = '100% Completed';
                if (title) title.innerHTML = `${index}. ${name} ✅`;
                if (button) button.style.display = 'none';

                // Show certificate elements
               // Show certificate elements correctly
const certDiv = moduleCard?.querySelector('.mt-4:last-child');
if (certDiv) {
    const disabledBtn = certDiv.querySelector('button[disabled]');
    if (disabledBtn) disabledBtn.style.display = 'none';

    const hiddenLink = certDiv.querySelector('a[style*="display: none"]');
    if (hiddenLink) hiddenLink.style.display = 'inline-block';

    const hiddenMsg = certDiv.querySelector('p[style*="display: none"]');
    if (hiddenMsg) hiddenMsg.style.display = 'block';
}


                // Update global progress from server response
                if (typeof data.progress !== 'undefined') {
                    const container = document.getElementById('training-progress');
                    if (container) {
                        container.dataset.read = data.readCount;
                        updateOverallProgress();
                    }

                    // When training is complete (100%), show all certificates
                    if (data.progress === 100) {
                        document.querySelectorAll('.mt-4:last-child').forEach(certDiv => {
                            const disabledBtn = certDiv.querySelector('button[disabled]');
                            const hiddenLink = certDiv.querySelector('a[style*="display: none"]');
                            const hiddenMsg = certDiv.querySelector('p[style*="display: none"]');
                            
                            if (disabledBtn) disabledBtn.style.display = 'none';
                            if (hiddenLink) hiddenLink.style.display = 'inline-block';
                            if (hiddenMsg) hiddenMsg.style.display = 'block';
                        });
                    }
                }
            }).catch(() => {
                // allow retry
                sentSet.delete(fileId);
            });
        }

        // Auto-detect reading via IntersectionObserver
        document.addEventListener('DOMContentLoaded', () => {
            const iframes = document.querySelectorAll('iframe[data-training-id][data-file-id]');
            if (!('IntersectionObserver' in window) || iframes.length === 0) return;

            const observer = new IntersectionObserver(entries => {
                for (const entry of entries) {
                    const el = entry.target;
                    const fileId = el.dataset.fileId;
                    const trainingId = el.dataset.trainingId;
                    const index = el.dataset.index;
                    const name = el.dataset.name;

                    if (sentSet.has(fileId)) continue;

                    if (entry.intersectionRatio >= AUTO_VISIBLE_RATIO) {
                        if (observedTimers.has(fileId)) continue;
                        const t = setTimeout(() => {
                            markAsRead(trainingId, fileId, index, name);
                            observedTimers.delete(fileId);
                        }, AUTO_VISIBLE_MS);
                        observedTimers.set(fileId, t);
                    } else {
                        if (observedTimers.has(fileId)) {
                            clearTimeout(observedTimers.get(fileId));
                            observedTimers.delete(fileId);
                        }
                    }
                }
            }, { threshold: [0, AUTO_VISIBLE_RATIO, 1] });

            iframes.forEach(f => observer.observe(f));
        });
    </script>
    <script>
        // Auto-complete modules after 3 minutes of active viewing
        (function(){
            const AUTO_COMPLETE_MS = 180000; // 3 minutes
            let timeoutId;

            function resetTimer() {
                if (timeoutId) clearTimeout(timeoutId);
                timeoutId = setTimeout(autoComplete, AUTO_COMPLETE_MS);
            }

            function autoComplete() {
                document.querySelectorAll('iframe[data-training-id][data-file-id]').forEach(iframe => {
                    const { trainingId, fileId, index, name } = iframe.dataset;
                    markAsRead(trainingId, fileId, index, name);
                });
            }

            ['mousemove','keypress','scroll','click'].forEach(evt =>
                document.addEventListener(evt, resetTimer)
            );

            resetTimer();
        })();
    </script>
</x-layouts.app>
