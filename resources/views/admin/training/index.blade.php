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
                    <th class="border px-4 py-2">Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainings as $training)
                    <tr class="text-black">
                        <td class="border px-4 py-2">{{ $training->id }}</td>
                        <td class="border px-4 py-2">{{ $training->title }}</td>
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
                        <td class="border px-4 py-2">{{ $training->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No trainings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
