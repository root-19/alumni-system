<x-layouts.app :title="__('My Trainings')">
    <div class="max-w-7xl mx-auto mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- LEFT COLUMN: Trainings List -->
        <div class="lg:col-span-2 space-y-6">
            <h1 class="text-2xl font-bold mb-4">{{ __('My Trainings') }}</h1>

            @forelse($trainings as $training)
                <div class="bg-white shadow rounded-xl p-6 hover:shadow-md transition">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $training->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $training->created_at->format('d M Y') }} | <span class="text-indigo-600">Express, Handlebars</span>
                    </p>
                    <p class="text-gray-600 mt-3">{{ $training->description }}</p>

                    <!-- MODULES -->
                    <div class="mt-4">
                        <h3 class="font-medium text-gray-800 mb-2">📘 Modules</h3>
                        <div class="max-h-40 overflow-y-auto border rounded-lg p-3 bg-gray-50 space-y-2">
                            @foreach($training->files->where('type', 'module') as $index => $file)
                                <a href="{{ Storage::url($file->path) }}" target="_blank"
                                   class="block text-blue-600 hover:underline text-sm"
                                   onclick="markAsRead({{ $training->id }}, {{ $file->id }})">
                                    {{ $index + 1 }}. {{ $file->original_name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- PROGRESS -->
                    <div class="mt-5">
                        @php
                            $total = $training->files->where('type', 'module')->count();
                            $read = auth()->user()->reads()
                                        ->whereIn('training_file_id', $training->files->pluck('id'))
                                        ->count();
                            $progress = $total > 0 ? round(($read / $total) * 100) : 0;
                        @endphp

                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-green-500 h-3 rounded-full text-xs text-center text-white"
                                 style="width: {{ $progress }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $progress }}% Completed</p>
                    </div>

                    <!-- CERTIFICATE -->
                    <div class="mt-4">
                        @if($progress == 100 && $training->certificate_path)
                            <a href="{{ Storage::url($training->certificate_path) }}"
                               class="inline-block px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                                🎓 Download Certificate
                            </a>
                        @else
                            <button disabled
                                    class="inline-block px-5 py-2 bg-gray-400 text-white rounded-lg shadow cursor-not-allowed">
                                🔒 Complete all modules to unlock certificate
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-600">No trainings available yet.</p>
            @endforelse
        </div>

        <!-- RIGHT COLUMN: User Progress -->
        <div>
            <div class="bg-white shadow rounded-xl p-6 text-center">
                <div class="w-16 h-16 mx-auto bg-green-100 flex items-center justify-center rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mt-4">Your Training Progress</h3>
                <p class="text-sm text-gray-500">Accomplish this now</p>

                <div class="mt-6">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: 75%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">75% of total trainings</p>
                </div>

                <div class="mt-6">
                    <img src="https://via.placeholder.com/400x200" alt="Certificate Preview"
                         class="rounded-lg shadow">
                    <a href="#" class="mt-4 inline-block px-5 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700">
                        📥 Download E-Certificate
                    </a>
                </div>
            </div>
        </div>
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
>

