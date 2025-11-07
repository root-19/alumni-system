<x-layouts.app :title="__('Create Resume')">
    <div class="max-w-4xl mx-auto mt-12 mb-12">
        <div class="bg-white shadow-lg rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
                {{ __('Create Your Resume') }}
            </h2>

            <form action="{{ route('resumes.generate') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name ?? '') }}" required
                           class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('full_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Number -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Contact Number <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" required
                           class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           placeholder="e.g., +63 912 345 6789">
                    @error('contact_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                           class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Objective -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Objective <span class="text-red-500">*</span></label>
                    <textarea name="objective" rows="4" required
                              class="w-full px-4 text-black py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                              placeholder="Write a brief statement about your career objectives and goals...">{{ old('objective') }}</textarea>
                    @error('objective')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Educational Attainment -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Educational Attainment <span class="text-red-500">*</span></label>
                    <textarea name="educational_attainment" rows="6" required
                              class="w-full px-4 text-black py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                              placeholder="List your educational background (e.g., Bachelor's Degree in Computer Science, University Name, Year Graduated)">{{ old('educational_attainment') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Include degree, institution name, and year graduated. Separate multiple entries with line breaks.</p>
                    @error('educational_attainment')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Training Seminars -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Training Seminars</label>
                    <textarea name="training_seminars" rows="6"
                              class="w-full px-4 text-black py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                              placeholder="List any training programs, seminars, or workshops you've attended (e.g., Web Development Bootcamp, 2023)">{{ old('training_seminars') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Include training name and year. Separate multiple entries with line breaks.</p>
                    @error('training_seminars')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Work Experience -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Work Experience</label>
                    <textarea name="work_experience" rows="8"
                              class="w-full px-4 text-black py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                              placeholder="List your work experience (e.g., Software Developer at ABC Company, 2020-2023 - Responsibilities and achievements)">{{ old('work_experience') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Include job title, company name, dates, and key responsibilities. Separate multiple entries with line breaks.</p>
                    @error('work_experience')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-4 border-t">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg shadow-md transition duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Generate Resume
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

