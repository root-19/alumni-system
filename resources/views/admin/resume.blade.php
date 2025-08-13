<x-layouts.app :title="__('Resume')">
    <div class="max-w-4xl mx-auto mt-8">
        
        {{-- Success Message --}}
        @if(session('success'))
            <div class="p-4 mb-4 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Upload Form --}}
        <form action="{{ route('resume.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 flex gap-3 items-center">
            @csrf
            <input type="file" name="resume_file" accept=".pdf,image/*" 
                   required 
                   class="border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none">
            <button type="submit" 
                    class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md transition">
                Upload Resume
            </button>
        </form>

        {{-- Resume Table --}}
        <div class="overflow-x-auto shadow rounded-lg border border-gray-200">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">File Name</th>
                        <th class="px-4 py-3 text-center">View</th>
                        <th class="px-4 py-3 text-center">Download</th>
                        <th class="px-4 py-3 text-center">Delete</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($resumes as $resume)
                        <tr class="hover:bg-green-50 transition">
                            <td class="px-4 py-3 text-black font-bold">{{ $resume->file_name }}</td>
                            
                            {{-- View Button --}}
                            <td class="px-4 py-3 text-center">
                                <a href="{{ asset('storage/'.$resume->file_path) }}" target="_blank" 
                                   class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md shadow-sm transition">
                                    View
                                </a>
                            </td>

                            {{-- Download Button --}}
                            <td class="px-4 py-3 text-center">
                                <a href="{{ asset('storage/'.$resume->file_path) }}" 
                                   download="{{ $resume->file_name }}"
                                   class="px-4 py-2 bg-green-400 hover:bg-green-500 text-white rounded-md shadow-sm transition">
                                    Download
                                </a>
                            </td>

                            {{-- Delete Button --}}
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('resume.destroy', $resume) }}" method="POST" onsubmit="return confirm('Delete this resume?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow-sm transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">No resumes uploaded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
