<x-layouts.app :title="__('Trainings')">
    <div class="max-w-6xl mx-auto mt-8">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold text-black">All Trainings</h1>
            <a href="{{ route('admin.trainings.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">+ New Training</a>
        </div>

        <table class="w-full border-collapse border text-left">
            <thead class="bg-gray-100 text-black">
                <tr>
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Title</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Certificate</th>
                    <th class="border px-4 py-2">Modules</th>
                    <th class="border px-4 py-2">Quiz</th>
                    <th class="border px-4 py-2">Created</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainings as $training)
                    <tr class="text-black">
                        <td class="border px-4 py-2">{{ $training->id }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('admin.trainings.show', $training->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                {{ $training->title }}
                            </a>
                        </td>
                        <td class="border px-4 py-2">{{ $training->description }}</td>
                        <td class="border px-4 py-2">
                            @if($training->certificate_path)
                                <a href="{{ Storage::url($training->certificate_path) }}" class="text-blue-600" target="_blank">View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            @foreach($training->files as $file)
                                <a href="{{ Storage::url($file->path) }}" class="text-blue-600 block" target="_blank">{{ $file->original_name }}</a>
                            @endforeach
                        </td>
                        <td class="border px-4 py-2">
                            @if($training->quizzes->count() > 0)
                                @foreach($training->quizzes as $quiz)
                                    <button onclick="showQuizParticipants({{ $quiz->id }}, '{{ $quiz->title }}')" 
                                            class="text-green-600 hover:text-green-800 mr-2">
                                        View Quiz: {{ $quiz->title }}
                                    </button>
                                @endforeach
                            @else
                                <a href="{{ route('admin.quizzes.create') }}?training_id={{ $training->id }}" class="text-blue-600 hover:text-blue-800">
                                    + Add Quiz
                                </a>
                            @endif
                        </td>
                        <td class="border px-4 py-2">{{ $training->created_at->format('M d, Y') }}</td>
                        <td class="border px-4 py-2">
                            <form method="POST" action="{{ route('admin.trainings.destroy', $training->id) }}" class="delete-form inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded text-xs font-semibold transition-colors" title="Delete Training">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No trainings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Quiz Participants Modal -->
    <div id="quizModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Quiz Participants</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="modalContent" class="mt-4">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Delete Training Form with SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formEl = this;
                    
                    Swal.fire({
                        title: 'Delete Training?',
                        text: 'This will also delete all associated quizzes, files, and modules. This action cannot be undone!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formEl.submit();
                        }
                    });
                });
            });
        });

        function showQuizParticipants(quizId, quizTitle) {
            const modal = document.getElementById('quizModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            
            modalTitle.textContent = quizTitle + ' - Participants';
            modal.classList.remove('hidden');
            
            // Show loading
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Loading...</p>
                </div>
            `;
            
            // Fetch participants data
            fetch(`/admin/quizzes/${quizId}/participants`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                let html = `
                    <div class="mb-4 grid grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Total Participants</p>
                            <p class="text-2xl font-bold text-blue-600">${data.total_participants}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Passed</p>
                            <p class="text-2xl font-bold text-green-600">${data.passed_count}</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Failed</p>
                            <p class="text-2xl font-bold text-red-600">${data.failed_count}</p>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-sm text-gray-600">Passing Score: <span class="font-semibold">${data.quiz.passing_score}%</span></p>
                    </div>
                    <div class="overflow-x-auto max-h-96 overflow-y-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-100 sticky top-0">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">User</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Status</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Total Attempts</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Attempts Before Pass</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Latest Score</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Last Attempt</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                if (data.participants.length === 0) {
                    html += `
                        <tr>
                            <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                                No participants have taken this quiz yet.
                            </td>
                        </tr>
                    `;
                } else {
                    data.participants.forEach(participant => {
                        html += `
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 px-4 py-2">
                                    <div class="font-medium text-gray-900">${participant.user_name}</div>
                                    <div class="text-xs text-gray-500">${participant.user_email}</div>
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    ${participant.passed ? 
                                        '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✓ Passed</span>' : 
                                        '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">✗ Failed</span>'
                                    }
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-sm">${participant.total_attempts}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm">
                                    ${participant.attempts_before_pass !== null ? 
                                        '<span class="font-semibold text-green-600">' + participant.attempts_before_pass + '</span>' : 
                                        '<span class="text-gray-400">-</span>'
                                    }
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-sm">
                                    ${participant.latest_percentage !== null ? 
                                        participant.latest_percentage + '%' : 
                                        '<span class="text-gray-400">-</span>'
                                    }
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-xs text-gray-600">
                                    ${participant.latest_attempt_date || '<span class="text-gray-400">-</span>'}
                                </td>
                            </tr>
                        `;
                    });
                }
                
                html += `
                            </tbody>
                        </table>
                    </div>
                `;
                
                modalContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = `
                    <div class="text-center py-8">
                        <p class="text-red-600">Error loading participants data. Please try again.</p>
                    </div>
                `;
            });
        }
        
        function closeModal() {
            document.getElementById('quizModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('quizModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</x-layouts.app>
