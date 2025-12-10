<x-layouts.app :title="__('Create Training')">
    <div class="max-w-3xl mx-auto mt-12">
        <div class="bg-white shadow-lg rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
                {{ __('Create New Training') }}
            </h2>

            <form action="{{ route('admin.trainings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 text-black py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Modules -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Modules</label>
                    <input type="file" name="modules[]" multiple
                           class="w-full text-black px-3 py-2 border rounded-lg bg-gray-50 cursor-pointer focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">Upload multiple files (JPG, PNG, PDF, DOC).</p>
                    @error('modules.*')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Certificate -->
                <!-- <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Certificate</label>
                    <input type="file" name="certificate"
                           class="w-full px-3 py-2 border  text-black rounded-lg bg-gray-50 cursor-pointer focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('certificate')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div> -->

                <!-- Quiz Section -->
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quiz</h3>
                    <p class="text-sm text-gray-600 mb-4">You can create a quiz for this training now, or add it later.</p>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="create_quiz" id="create_quiz" value="1" 
                                   class="rounded border-gray-300" onchange="toggleQuizFields()">
                            <span class="ml-2 text-sm font-medium text-gray-700">Create Quiz for this Training</span>
                        </label>
                    </div>

                    <div id="quiz_fields" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Quiz Title</label>
                            <input type="text" name="quiz_title" id="quiz_title" value="{{ old('quiz_title') }}"
                                   class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error('quiz_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Quiz Description</label>
                            <textarea name="quiz_description" id="quiz_description" rows="2"
                                      class="w-full px-4 text-black py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('quiz_description') }}</textarea>
                            @error('quiz_description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Passing Score (%)</label>
                                <input type="number" name="quiz_passing_score" id="quiz_passing_score" 
                                       value="{{ old('quiz_passing_score', 70) }}" min="0" max="100"
                                       class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('quiz_passing_score')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                {{-- <label class="block text-sm font-semibold text-gray-700 mb-1">Time Limit (minutes, optional)</label>
                                <input type="number" name="quiz_time_limit" id="quiz_time_limit" 
                                       value="{{ old('quiz_time_limit') }}" min="1"
                                       class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('quiz_time_limit')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror --}}
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="quiz_is_active" value="1" {{ old('quiz_is_active', true) ? 'checked' : '' }}
                                       class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Quiz Active</span>
                            </label>
                        </div>

                        <!-- Questions Section -->
                        <div class="border-t pt-4 mt-4">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-semibold text-gray-800">Questions</h4>
                                <button type="button" onclick="addQuestion()" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                    + Add Question
                                </button>
                            </div>
                            <div id="questions_container" class="space-y-6">
                                <!-- Questions will be added here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Final Assessment Section -->
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Final Assessment</h3>
                    <p class="text-sm text-gray-600 mb-4">You can create a final assessment for this training now, or add it later.</p>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="create_final_assessment" id="create_final_assessment" value="1" 
                                   class="rounded border-gray-300" onchange="toggleFinalAssessmentFields()">
                            <span class="ml-2 text-sm font-medium text-gray-700">Create Final Assessment for this Training</span>
                        </label>
                    </div>

                    <div id="final_assessment_fields" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Final Assessment Title</label>
                            <input type="text" name="final_assessment_title" id="final_assessment_title" value="{{ old('final_assessment_title') }}"
                                   class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @error('final_assessment_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Final Assessment Description</label>
                            <textarea name="final_assessment_description" id="final_assessment_description" rows="2"
                                      class="w-full px-4 text-black py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('final_assessment_description') }}</textarea>
                            @error('final_assessment_description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Passing Score (%)</label>
                                <input type="number" name="final_assessment_passing_score" id="final_assessment_passing_score" 
                                       value="{{ old('final_assessment_passing_score', 70) }}" min="0" max="100"
                                       class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('final_assessment_passing_score')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Time Limit (minutes, optional)</label>
                                <input type="number" name="final_assessment_time_limit" id="final_assessment_time_limit" 
                                       value="{{ old('final_assessment_time_limit') }}" min="1"
                                       class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @error('final_assessment_time_limit')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="final_assessment_is_active" value="1" {{ old('final_assessment_is_active', true) ? 'checked' : '' }}
                                       class="rounded border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Final Assessment Active</span>
                            </label>
                        </div>

                        <!-- Final Assessment Questions Section -->
                        <div class="border-t pt-4 mt-4">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-semibold text-gray-800">Questions</h4>
                                <button type="button" onclick="addFinalAssessmentQuestion()" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                    + Add Question
                                </button>
                            </div>
                            <div id="final_assessment_questions_container" class="space-y-6">
                                <!-- Questions will be added here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                        Save Training
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let questionCount = 0;
        let finalAssessmentQuestionCount = 0;

        function toggleQuizFields() {
            const checkbox = document.getElementById('create_quiz');
            const quizFields = document.getElementById('quiz_fields');
            
            if (checkbox.checked) {
                quizFields.classList.remove('hidden');
                // Make quiz title required when checkbox is checked
                document.getElementById('quiz_title').setAttribute('required', 'required');
                // Add first question if none exist
                if (questionCount === 0) {
                    addQuestion();
                }
            } else {
                quizFields.classList.add('hidden');
                document.getElementById('quiz_title').removeAttribute('required');
            }
        }

        function toggleFinalAssessmentFields() {
            const checkbox = document.getElementById('create_final_assessment');
            const finalAssessmentFields = document.getElementById('final_assessment_fields');
            
            if (checkbox.checked) {
                finalAssessmentFields.classList.remove('hidden');
                // Make final assessment title required when checkbox is checked
                document.getElementById('final_assessment_title').setAttribute('required', 'required');
                // Add first question if none exist
                if (finalAssessmentQuestionCount === 0) {
                    addFinalAssessmentQuestion();
                }
            } else {
                finalAssessmentFields.classList.add('hidden');
                document.getElementById('final_assessment_title').removeAttribute('required');
            }
        }

        function addQuestion() {
            questionCount++;
            const container = document.getElementById('questions_container');
            
            const questionDiv = document.createElement('div');
            questionDiv.className = 'border rounded-lg p-4 bg-gray-50';
            questionDiv.id = `question_${questionCount}`;
            
            questionDiv.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h5 class="font-semibold text-gray-700">Question ${questionCount}</h5>
                    <button type="button" onclick="removeQuestion(${questionCount})" class="text-red-600 hover:text-red-800 text-sm">
                        Remove
                    </button>
                </div>
                
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Question Text</label>
                    <textarea name="questions[${questionCount}][question_text]" rows="2" required
                              class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correct Answer</label>
                    <select name="questions[${questionCount}][correct_answer]" required
                            class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Select Correct Answer</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                    <input type="number" name="questions[${questionCount}][points]" value="1" min="1" required
                           class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="space-y-2">
                    <h6 class="text-sm font-medium text-gray-700 mb-2">Answer Choices:</h6>
                    
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700 w-6">A.</span>
                        <input type="text" name="questions[${questionCount}][choice_a]" placeholder="Choice A" required
                               class="flex-1 text-black px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700 w-6">B.</span>
                        <input type="text" name="questions[${questionCount}][choice_b]" placeholder="Choice B" required
                               class="flex-1 text-black px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700 w-6">C.</span>
                        <input type="text" name="questions[${questionCount}][choice_c]" placeholder="Choice C" required
                               class="flex-1 text-black px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700 w-6">D.</span>
                        <input type="text" name="questions[${questionCount}][choice_d]" placeholder="Choice D" required
                               class="flex-1 text-black px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
            `;
            
            container.appendChild(questionDiv);
        }

        function removeQuestion(id) {
            const questionDiv = document.getElementById(`question_${id}`);
            if (questionDiv) {
                questionDiv.remove();
                // Renumber remaining questions
                renumberQuestions();
            }
        }

        function renumberQuestions() {
            const container = document.getElementById('questions_container');
            const questions = container.querySelectorAll('[id^="question_"]');
            questionCount = 0;
            
            questions.forEach((questionDiv, index) => {
                questionCount++;
                const questionNum = questionCount;
                questionDiv.id = `question_${questionNum}`;
                
                // Update question number in header
                const header = questionDiv.querySelector('h5');
                if (header) {
                    header.textContent = `Question ${questionNum}`;
                }
                
                // Update remove button
                const removeBtn = questionDiv.querySelector('button[onclick*="removeQuestion"]');
                if (removeBtn) {
                    removeBtn.setAttribute('onclick', `removeQuestion(${questionNum})`);
                }
                
                // Update all input names
                const inputs = questionDiv.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const match = name.match(/questions\[(\d+)\]/);
                        if (match) {
                            input.setAttribute('name', name.replace(/questions\[\d+\]/, `questions[${questionNum}]`));
                        }
                    }
                });
            });
        }

        function addFinalAssessmentQuestion() {
            finalAssessmentQuestionCount++;
            const container = document.getElementById('final_assessment_questions_container');
            
            const questionDiv = document.createElement('div');
            questionDiv.className = 'border rounded-lg p-4 bg-gray-50';
            questionDiv.id = `final_assessment_question_${finalAssessmentQuestionCount}`;
            
            questionDiv.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h5 class="font-semibold text-gray-700">Question ${finalAssessmentQuestionCount}</h5>
                    <button type="button" onclick="removeFinalAssessmentQuestion(${finalAssessmentQuestionCount})" class="text-red-600 hover:text-red-800 text-sm">
                        Remove
                    </button>
                </div>
                
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Question Text</label>
                    <textarea name="final_assessment_questions[${finalAssessmentQuestionCount}][question_text]" rows="2" required
                              class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correct Answer</label>
                    <select name="final_assessment_questions[${finalAssessmentQuestionCount}][correct_answer]" required
                            class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Select Correct Answer</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                    <input type="number" name="final_assessment_questions[${finalAssessmentQuestionCount}][points]" value="1" min="1" required
                           class="w-full text-black px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="space-y-2">
                    <h6 class="text-sm font-medium text-gray-700 mb-2">Answer Choices:</h6>
                    
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700 w-6">A.</span>
                        <input type="text" name="final_assessment_questions[${finalAssessmentQuestionCount}][choice_a]" placeholder="Choice A" required
                               class="flex-1 text-black px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700 w-6">B.</span>
                        <input type="text" name="final_assessment_questions[${finalAssessmentQuestionCount}][choice_b]" placeholder="Choice B" required
                               class="flex-1 text-black px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700 w-6">C.</span>
                        <input type="text" name="final_assessment_questions[${finalAssessmentQuestionCount}][choice_c]" placeholder="Choice C" required
                               class="flex-1 text-black px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-700 w-6">D.</span>
                        <input type="text" name="final_assessment_questions[${finalAssessmentQuestionCount}][choice_d]" placeholder="Choice D" required
                               class="flex-1 text-black px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
            `;
            
            container.appendChild(questionDiv);
        }

        function removeFinalAssessmentQuestion(id) {
            const questionDiv = document.getElementById(`final_assessment_question_${id}`);
            if (questionDiv) {
                questionDiv.remove();
                // Renumber remaining questions
                renumberFinalAssessmentQuestions();
            }
        }

        function renumberFinalAssessmentQuestions() {
            const container = document.getElementById('final_assessment_questions_container');
            const questions = container.querySelectorAll('[id^="final_assessment_question_"]');
            finalAssessmentQuestionCount = 0;
            
            questions.forEach((questionDiv, index) => {
                finalAssessmentQuestionCount++;
                const questionNum = finalAssessmentQuestionCount;
                questionDiv.id = `final_assessment_question_${questionNum}`;
                
                // Update question number in header
                const header = questionDiv.querySelector('h5');
                if (header) {
                    header.textContent = `Question ${questionNum}`;
                }
                
                // Update remove button
                const removeBtn = questionDiv.querySelector('button[onclick*="removeFinalAssessmentQuestion"]');
                if (removeBtn) {
                    removeBtn.setAttribute('onclick', `removeFinalAssessmentQuestion(${questionNum})`);
                }
                
                // Update all input names
                const inputs = questionDiv.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const match = name.match(/final_assessment_questions\[(\d+)\]/);
                        if (match) {
                            input.setAttribute('name', name.replace(/final_assessment_questions\[\d+\]/, `final_assessment_questions[${questionNum}]`));
                        }
                    }
                });
            });
        }
    </script>
</x-layouts.app>

