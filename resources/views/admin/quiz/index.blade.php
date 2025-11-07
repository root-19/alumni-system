<x-layouts.app title="Quizzes Management">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Quizzes Management</h1>
                        <p class="mt-2 text-gray-600">Manage quizzes for trainings</p>
                    </div>
                    <a href="{{ route('admin.quizzes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        + Create Quiz
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Training</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Questions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Passing Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($quizzes as $quiz)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $quiz->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $quiz->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $quiz->training->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $quiz->questions->count() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $quiz->passing_score }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($quiz->is_active)
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.quizzes.show', $quiz->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No quizzes found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

