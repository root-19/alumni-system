<x-layouts.app :title="$training->title">
    <div class="max-w-5xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $training->title }}</h1>
        <p class="text-gray-600 mt-2">{{ $training->description }}</p>

        <!-- Progress Bar -->
        <div class="mt-6">
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-green-500 h-3 rounded-full"
                     style="width: {{ $progress }}%">
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-2">{{ $progress }}% completed ({{ $read }}/{{ $total }} modules)</p>
        </div>

        <!-- Modules Embed -->
        <div class="mt-8 space-y-6">
            @foreach($training->files->where('type','module') as $index => $file)
                <div class="bg-white shadow rounded-lg p-4">
                    <h3 class="font-semibold text-gray-700 mb-2">
                        {{ $index + 1 }}. {{ $file->original_name }}
                    </h3>

                    <!-- Embedded file (PDF, docs, etc.) -->
                    <iframe src="{{ Storage::url($file->path) }}"
                            class="w-full h-64 border rounded"
                            data-training-id="{{ $training->id }}"
                            data-file-id="{{ $file->id }}"></iframe>
                </div>
            @endforeach
        </div>

        <!-- Certificate -->
        <div class="mt-10">
            @if($progress == 100 && $training->certificate_path)
                <a href="{{ Storage::url($training->certificate_path) }}"
                   class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                    🎓 Download Certificate
                </a>
            @else
                <button disabled
                        class="inline-block px-6 py-2 bg-gray-400 text-white rounded-lg shadow cursor-not-allowed">
                    🔒 Complete all modules to unlock certificate
                </button>
            @endif
        </div>
    </div>

    <!-- AJAX to track read progress -->
    <script>
        // Time (ms) the iframe must remain sufficiently visible before marking as read
        const VISIBLE_MS = 3000;
        // Visibility ratio threshold (0 to 1)
        const VISIBLE_RATIO = 0.5;

        // track timers and sent state per file id
        const visibilityTimers = new Map();
        const sentRead = new Set();

        function sendMarkAsRead(trainingId, fileId) {
            if (sentRead.has(fileId)) return;
            sentRead.add(fileId);

            fetch(`/trainings/${trainingId}/read/${fileId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).catch(() => {
                // on network error, allow retry by removing from sentRead
                sentRead.delete(fileId);
            });
        }

        // Observe each iframe and start a timer when it becomes visible.
        document.addEventListener('DOMContentLoaded', () => {
            const iframes = document.querySelectorAll('iframe[data-training-id][data-file-id]');
            if (iframes.length === 0) return;

            const observer = new IntersectionObserver((entries) => {
                for (const entry of entries) {
                    const el = entry.target;
                    const trainingId = el.dataset.trainingId;
                    const fileId = el.dataset.fileId;

                    if (entry.intersectionRatio >= VISIBLE_RATIO) {
                        if (visibilityTimers.has(fileId) || sentRead.has(fileId)) continue;
                        // start timer
                        const t = setTimeout(() => {
                            sendMarkAsRead(trainingId, fileId);
                            visibilityTimers.delete(fileId);
                        }, VISIBLE_MS);
                        visibilityTimers.set(fileId, t);
                    } else {
                        // not enough visible — cancel pending timer
                        if (visibilityTimers.has(fileId)) {
                            clearTimeout(visibilityTimers.get(fileId));
                            visibilityTimers.delete(fileId);
                        }
                    }
                }
            }, { threshold: [0, VISIBLE_RATIO, 1] });

            iframes.forEach(f => observer.observe(f));
        });
    </script>
</x-layouts.app>
