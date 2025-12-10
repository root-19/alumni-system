<x-layouts.app :title="__('Register')">
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-emerald-50 via-white to-emerald-50">
        <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md border border-emerald-100 text-gray-800">
            
            <!-- Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-emerald-600 to-green-700 rounded-full mb-4 shadow-lg shadow-emerald-200/60">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                <p class="text-gray-600">Join our community and get started 🚀</p>
            </div>

            <!-- Success message -->
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 p-4 rounded-r-lg mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error messages -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-r-lg mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <ul class="text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form id="register-form" action="{{ route('admin.register.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder:text-gray-400"
                            placeholder="Enter your full name"
                            required
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder:text-gray-400"
                            placeholder="your@email.com"
                            required
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Account Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <input 
                                type="radio" 
                                id="user" 
                                name="role" 
                                value="user" 
                                {{ old('role') == 'user' ? 'checked' : '' }}
                                class="sr-only peer"
                                required
                            >
                            <label for="user" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-emerald-300 transition-all duration-200 bg-white text-gray-700">
                                <svg class="w-6 h-6 text-emerald-500 peer-checked:text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm font-medium">User</span>
                            </label>
                        </div>
                        <div class="relative">
                            <input 
                                type="radio" 
                                id="assistant" 
                                name="role" 
                                value="assistant" 
                                {{ old('role') == 'assistant' ? 'checked' : '' }}
                                class="sr-only peer"
                                required
                            >
                            <label for="assistant" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-emerald-300 transition-all duration-200 bg-white text-gray-700">
                                <svg class="w-6 h-6 text-emerald-500 peer-checked:text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium">Assistant</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Alumni Status Selection -->
                <div id="alumni-status-section">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alumni Status</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <input 
                                type="radio" 
                                id="alumni_yes" 
                                name="is_alumni" 
                                value="1" 
                                {{ old('is_alumni') == '1' ? 'checked' : '' }}
                                class="sr-only peer"
                            >
                            <label for="alumni_yes" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-emerald-300 transition-all duration-200 bg-white text-gray-700">
                                <svg class="w-6 h-6 text-emerald-500 peer-checked:text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Alumni</span>
                            </label>
                        </div>
                        <div class="relative">
                            <input 
                                type="radio" 
                                id="alumni_no" 
                                name="is_alumni" 
                                value="0" 
                                {{ old('is_alumni') == '0' ? 'checked' : '' }}
                                class="sr-only peer"
                            >
                            <label for="alumni_no" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-emerald-300 transition-all duration-200 bg-white text-gray-700">
                                <svg class="w-6 h-6 text-emerald-500 peer-checked:text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="text-sm font-medium">Not Alumni</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden input for assistant role (default to 0) -->
                <input type="hidden" name="is_alumni" id="assistant_alumni" value="0">

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder:text-gray-400"
                            placeholder="Create a strong password"
                            required
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-emerald-700 to-green-800 text-white py-3 rounded-xl font-semibold hover:from-emerald-800 hover:to-green-900 transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl shadow-emerald-900/50"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Create Account
                    </span>
                </button>
            </form>

            <!-- Confirm Modal -->
            <div id="confirm-modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 backdrop-blur-sm">
                <div class="bg-white border border-emerald-100 rounded-2xl shadow-2xl max-w-sm w-full mx-4 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center border border-emerald-100">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Create this account?</h3>
                            <p class="text-sm text-gray-600">Review details and confirm to continue.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button id="cancel-confirm" type="button" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition">Cancel</button>
                        <button id="confirm-submit" type="button" class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-green-700 text-white font-semibold hover:from-emerald-700 hover:to-green-800 transition">Yes, continue</button>
                    </div>
                </div>
            </div>

            <!-- Login link -->
            
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const alumniSection = document.getElementById('alumni-status-section');
            const assistantAlumniInput = document.getElementById('assistant_alumni');
            const alumniYesInput = document.getElementById('alumni_yes');
            const alumniNoInput = document.getElementById('alumni_no');
            const confirmModal = document.getElementById('confirm-modal');
            const confirmSubmitBtn = document.getElementById('confirm-submit');
            const cancelConfirmBtn = document.getElementById('cancel-confirm');
            const registerForm = document.getElementById('register-form');
            let allowSubmit = false;
            
            function toggleAlumniSection() {
                const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
                
                if (selectedRole === 'assistant') {
                    // Hide alumni section and set default value to 0
                    alumniSection.style.display = 'none';
                    assistantAlumniInput.name = 'is_alumni';
                    assistantAlumniInput.value = '0';
                    // Disable and uncheck alumni radio inputs
                    alumniYesInput.required = false;
                    alumniNoInput.required = false;
                    alumniYesInput.checked = false;
                    alumniNoInput.checked = false;
                    alumniYesInput.disabled = true;
                    alumniNoInput.disabled = true;
                } else {
                    // Show alumni section
                    alumniSection.style.display = 'block';
                    assistantAlumniInput.name = '';
                    // Enable and make required alumni radio inputs
                    alumniYesInput.required = true;
                    alumniNoInput.required = true;
                    alumniYesInput.disabled = false;
                    alumniNoInput.disabled = false;
                }
            }
            
            // Add event listeners to role inputs
            roleInputs.forEach(input => {
                input.addEventListener('change', toggleAlumniSection);
            });
            
            // Check on page load
            toggleAlumniSection();

            // Intercept submit to show custom confirmation
            registerForm.addEventListener('submit', function(event) {
                if (allowSubmit) {
                    allowSubmit = false; // reset for future submissions
                    return;
                }
                event.preventDefault();
                confirmModal.classList.remove('hidden');
                confirmModal.classList.add('flex');
            });

            // Proceed with submission after confirm
            confirmSubmitBtn.addEventListener('click', function() {
                allowSubmit = true;
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
                registerForm.submit();
            });

            // Cancel confirmation
            cancelConfirmBtn.addEventListener('click', function() {
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
            });
        });
    </script>
</x-layouts.app>
