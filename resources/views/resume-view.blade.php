<x-layouts.app :title="__('Resume')">
    <div class="max-w-5xl mx-auto mt-12 mb-12">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($resume))
            <div class="bg-white shadow-lg rounded-2xl p-8">
                <!-- Header Actions -->
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Resume Preview</h2>
                    <div class="flex gap-3">
                        @if(auth()->check() && (auth()->id() == $resume->user_id || auth()->user()->role === 'admin'))
                            @if($resume->file_path)
                                <a href="{{ route('resumes.download', $resume->id) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition duration-200 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download PDF
                                </a>
                            @endif
                        @endif
                        @auth
                            <a href="{{ route('resumes.create') }}" 
                               class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition duration-200">
                                Create New Resume
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Resume Content -->
                <div class="resume-preview bg-gray-50 p-8 rounded-lg">
                    <!-- Header -->
                    <div class="text-center border-b-4 border-black pb-6 mb-8">
                        <h1 class="text-4xl font-bold text-black mb-4 uppercase tracking-wide">
                            {{ $resume->full_name }}
                        </h1>
                        <div class="flex justify-center gap-6 text-gray-600">
                            @if($resume->contact_number)
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $resume->contact_number }}
                                </span>
                            @endif
                            @if($resume->email)
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $resume->email }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Objective -->
                    @if($resume->objective)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-black border-b-2 border-black pb-2 mb-4 uppercase">Objective</h2>
                            <p class="text-gray-700 italic leading-relaxed whitespace-pre-line">{{ $resume->objective }}</p>
                        </div>
                    @endif

                    <!-- Educational Attainment -->
                    @if($resume->educational_attainment)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-black border-b-2 border-black pb-2 mb-4 uppercase">Educational Attainment</h2>
                            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                                @php
                                    $educationItems = explode("\n", $resume->educational_attainment);
                                @endphp
                                @foreach($educationItems as $item)
                                    @if(trim($item))
                                        <div class="mb-3 pl-4 border-l-4 border-gray-300">{{ trim($item) }}</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Training Seminars -->
                    @if($resume->training_seminars)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-black border-b-2 border-black pb-2 mb-4 uppercase">Training & Seminars</h2>
                            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                                @php
                                    $trainingItems = explode("\n", $resume->training_seminars);
                                @endphp
                                @foreach($trainingItems as $item)
                                    @if(trim($item))
                                        <div class="mb-3 pl-4 border-l-4 border-gray-300">{{ trim($item) }}</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Work Experience -->
                    @if($resume->work_experience)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-black border-b-2 border-black pb-2 mb-4 uppercase">Work Experience</h2>
                            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                                @php
                                    $experienceItems = explode("\n", $resume->work_experience);
                                @endphp
                                @foreach($experienceItems as $item)
                                    @if(trim($item))
                                        <div class="mb-3 pl-4 border-l-4 border-gray-300">{{ trim($item) }}</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-white shadow-lg rounded-2xl p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">No Resume Found</h2>
                <p class="text-gray-600 mb-6">Create your first resume to get started.</p>
                @auth
                    <a href="{{ route('resumes.create') }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg shadow-md transition duration-200">
                        Create Resume
                    </a>
                @endauth
            </div>
        @endif
    </div>
</x-layouts.app>
