<x-layouts.app :title="__('Resume Viewer')">
@section('content')
<style>
    .pdf-container {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    .pdf-container object {
        display: block;
        min-height: 600px;
    }
    
    .pdf-container p {
        padding: 20px;
        text-align: center;
        background-color: #f9fafb;
        margin: 0;
    }
    
    @media (max-width: 768px) {
        .pdf-container object {
            height: 500px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resumeSelect = document.getElementById('resume-select');
        if (resumeSelect) {
            resumeSelect.addEventListener('change', function() {
                window.location.href = this.value;
            });
        }
    });
</script>
<div class="container mx-auto px-4 py-8">
  
    @isset($resume)
        <div class="mb-6">
            @if(isset($resumes) && $resumes->count() > 1)
                <div class="mb-4">
                    <label for="resume-select" class="block text-sm font-medium text-gray-700 mb-2">Select Resume:</label>
                    <select id="resume-select" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @foreach($resumes as $res)
                            <option value="{{ route('resume-view.show', $res->id) }}" {{ $res->id === $resume->id ? 'selected' : '' }}>
                                {{ $res->file_name }} ({{ $res->created_at->format('M j, Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $resume->file_name }}</h1>
            <p class="text-gray-600">Resume uploaded on {{ $resume->created_at->format('F j, Y') }}</p>
            <div class="mt-4">
                <a href="{{ asset('storage/' . $resume->file_path) }}" 
                   download="{{ $resume->file_name }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Resume
                </a>
            </div>
        </div>
        @if($resume->file_path)
            <div class="pdf-container">
                <object 
                    data="{{ asset('storage/' . $resume->file_path) }}"
                    type="application/pdf"
                    width="100%" 
                    height="800px">
                    <p>Your browser does not support PDF embedding. 
                        <a href="{{ asset('storage/' . $resume->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                            Click here to view the PDF
                        </a>
                    </p>
                </object>
            </div>
        @else
            <p class="text-red-500">No resume file found.</p>
        @endif
    @else
        <p class="text-red-500"> No resume available.</p>
    @endisset
</div>
</x-layouts.app>