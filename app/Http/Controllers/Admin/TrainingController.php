<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\TrainingFile;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\FinalAssessment;
use App\Models\FinalAssessmentQuestion;
use App\Models\FinalAssessmentChoice;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with(['files', 'quizzes'])->latest()->get();
        return view('admin.training.index', compact('trainings'));
    }

    public function show($id)
    {
        $training = Training::with(['quizzes', 'files'])->findOrFail($id);
        
        // Get all quiz IDs for this training
        $quizIds = $training->quizzes->where('is_active', true)->pluck('id');
        
        // Get all users who attempted quizzes for this training
        $allAttempts = \App\Models\UserQuizAttempt::whereIn('quiz_id', $quizIds)
            ->with(['user', 'quiz'])
            ->orderBy('user_id')
            ->orderBy('quiz_id')
            ->orderBy('created_at')
            ->get();
        
        // Group by user and calculate statistics
        $participants = [];
        
        foreach ($allAttempts->groupBy('user_id') as $userId => $userAttempts) {
            $user = $userAttempts->first()->user;
            $userStats = [
                'user' => $user,
                'quizzes' => [],
                'total_attempts' => 0,
                'passed_quizzes' => 0,
                'failed_quizzes' => 0,
                'overall_status' => 'In Progress'
            ];
            
            // Group attempts by quiz
            foreach ($userAttempts->groupBy('quiz_id') as $quizId => $quizAttempts) {
                $quiz = $quizAttempts->first()->quiz;
                $attemptsCount = $quizAttempts->count();
                $passedAttempt = $quizAttempts->where('passed', true)->first();
                $latestAttempt = $quizAttempts->sortByDesc('created_at')->first();
                
                $userStats['quizzes'][] = [
                    'quiz' => $quiz,
                    'attempts_count' => $attemptsCount,
                    'passed' => $passedAttempt !== null,
                    'attempts_before_pass' => $passedAttempt ? 
                        $quizAttempts->where('created_at', '<=', $passedAttempt->created_at)->count() : 
                        null,
                    'latest_percentage' => $latestAttempt ? $latestAttempt->percentage : null,
                    'latest_attempt' => $latestAttempt
                ];
                
                $userStats['total_attempts'] += $attemptsCount;
                
                if ($passedAttempt) {
                    $userStats['passed_quizzes']++;
                } else {
                    $userStats['failed_quizzes']++;
                }
            }
            
            // Determine overall status
            $totalQuizzes = $training->quizzes->where('is_active', true)->count();
            if ($userStats['passed_quizzes'] === $totalQuizzes && $totalQuizzes > 0) {
                $userStats['overall_status'] = 'Passed';
            } elseif ($userStats['failed_quizzes'] > 0 || $userStats['passed_quizzes'] > 0) {
                $userStats['overall_status'] = 'In Progress';
            } else {
                $userStats['overall_status'] = 'Not Started';
            }
            
            $participants[] = $userStats;
        }
        
        // Sort participants by overall status (Passed first, then In Progress, then Not Started)
        usort($participants, function($a, $b) {
            $order = ['Passed' => 1, 'In Progress' => 2, 'Not Started' => 3];
            return ($order[$a['overall_status']] ?? 4) <=> ($order[$b['overall_status']] ?? 4);
        });
        
        return view('admin.training.show', compact('training', 'participants'));
    }

    public function create()
    {
        return view('admin.training.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'modules.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'create_quiz' => 'nullable|boolean',
            'quiz_title' => 'required_if:create_quiz,1|string|max:255',
            'quiz_description' => 'nullable|string',
            'quiz_passing_score' => 'nullable|integer|min:0|max:100',
            'quiz_time_limit' => 'nullable|integer|min:1',
            'quiz_is_active' => 'nullable|boolean',
            'questions' => 'nullable|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.correct_answer' => 'required|in:A,B,C,D',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.choice_a' => 'required|string',
            'questions.*.choice_b' => 'required|string',
            'questions.*.choice_c' => 'required|string',
            'questions.*.choice_d' => 'required|string',
            'create_final_assessment' => 'nullable|boolean',
            'final_assessment_title' => 'required_if:create_final_assessment,1|string|max:255',
            'final_assessment_description' => 'nullable|string',
            'final_assessment_passing_score' => 'nullable|integer|min:0|max:100',
            'final_assessment_time_limit' => 'nullable|integer|min:1',
            'final_assessment_is_active' => 'nullable|boolean',
            'final_assessment_questions' => 'nullable|array',
            'final_assessment_questions.*.question_text' => 'required|string',
            'final_assessment_questions.*.correct_answer' => 'required|in:A,B,C,D',
            'final_assessment_questions.*.points' => 'required|integer|min:1',
            'final_assessment_questions.*.choice_a' => 'required|string',
            'final_assessment_questions.*.choice_b' => 'required|string',
            'final_assessment_questions.*.choice_c' => 'required|string',
            'final_assessment_questions.*.choice_d' => 'required|string',
        ]);

        $training = Training::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        // store modules
        if ($request->hasFile('modules')) {
            foreach ($request->file('modules') as $file) {
                $path = $file->store("trainings/{$training->id}/modules", 'public');
                TrainingFile::create([
                    'training_id' => $training->id,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'type' => 'module',
                ]);
            }
        }

        // store certificate
        if ($request->hasFile('certificate')) {
            $file = $request->file('certificate');
            $path = $file->store("trainings/{$training->id}/certificate", 'public');
            $training->update(['certificate_path' => $path]);
        }

        // Create quiz if requested
        if ($request->has('create_quiz') && $request->create_quiz == '1') {
            $quiz = Quiz::create([
                'training_id' => $training->id,
                'title' => $validated['quiz_title'],
                'description' => $validated['quiz_description'] ?? null,
                'passing_score' => $validated['quiz_passing_score'] ?? 70,
                'time_limit' => $validated['quiz_time_limit'] ?? null,
                'is_active' => $request->has('quiz_is_active') ? true : false,
            ]);

            // Add questions if provided
            if ($request->has('questions') && is_array($request->questions)) {
                $order = 1;
                foreach ($request->questions as $questionData) {
                    if (!empty($questionData['question_text'])) {
                        $question = Question::create([
                            'quiz_id' => $quiz->id,
                            'question_text' => $questionData['question_text'],
                            'correct_answer' => $questionData['correct_answer'],
                            'points' => $questionData['points'] ?? 1,
                            'order' => $order++,
                        ]);

                        // Create choices
                        QuestionChoice::create([
                            'question_id' => $question->id,
                            'choice_letter' => 'A',
                            'choice_text' => $questionData['choice_a'],
                        ]);

                        QuestionChoice::create([
                            'question_id' => $question->id,
                            'choice_letter' => 'B',
                            'choice_text' => $questionData['choice_b'],
                        ]);

                        QuestionChoice::create([
                            'question_id' => $question->id,
                            'choice_letter' => 'C',
                            'choice_text' => $questionData['choice_c'],
                        ]);

                        QuestionChoice::create([
                            'question_id' => $question->id,
                            'choice_letter' => 'D',
                            'choice_text' => $questionData['choice_d'],
                        ]);
                    }
                }
            }
        }

        // Create final assessment if requested
        if ($request->has('create_final_assessment') && $request->create_final_assessment == '1') {
            $finalAssessment = FinalAssessment::create([
                'training_id' => $training->id,
                'title' => $validated['final_assessment_title'],
                'description' => $validated['final_assessment_description'] ?? null,
                'passing_score' => $validated['final_assessment_passing_score'] ?? 70,
                'time_limit' => $validated['final_assessment_time_limit'] ?? null,
                'is_active' => $request->has('final_assessment_is_active') ? true : false,
            ]);

            // Add questions if provided
            if ($request->has('final_assessment_questions') && is_array($request->final_assessment_questions)) {
                $order = 1;
                foreach ($request->final_assessment_questions as $questionData) {
                    if (!empty($questionData['question_text'])) {
                        $question = FinalAssessmentQuestion::create([
                            'final_assessment_id' => $finalAssessment->id,
                            'question_text' => $questionData['question_text'],
                            'correct_answer' => $questionData['correct_answer'],
                            'points' => $questionData['points'] ?? 1,
                            'order' => $order++,
                        ]);

                        // Create choices
                        FinalAssessmentChoice::create([
                            'question_id' => $question->id,
                            'choice_letter' => 'A',
                            'choice_text' => $questionData['choice_a'],
                        ]);

                        FinalAssessmentChoice::create([
                            'question_id' => $question->id,
                            'choice_letter' => 'B',
                            'choice_text' => $questionData['choice_b'],
                        ]);

                        FinalAssessmentChoice::create([
                            'question_id' => $question->id,
                            'choice_letter' => 'C',
                            'choice_text' => $questionData['choice_c'],
                        ]);

                        FinalAssessmentChoice::create([
                            'question_id' => $question->id,
                            'choice_letter' => 'D',
                            'choice_text' => $questionData['choice_d'],
                        ]);
                    }
                }
            }
        }

        $message = 'Training created successfully.';
        if ($request->has('create_quiz') && $request->create_quiz == '1') {
            $questionCount = $request->has('questions') ? count(array_filter($request->questions, function($q) {
                return !empty($q['question_text']);
            })) : 0;
            
            if ($questionCount > 0) {
                $message .= " Quiz created with {$questionCount} question(s).";
            } else {
                $message .= ' Quiz created. You can now add questions to it.';
            }
        }

        if ($request->has('create_final_assessment') && $request->create_final_assessment == '1') {
            $finalAssessmentQuestionCount = $request->has('final_assessment_questions') ? count(array_filter($request->final_assessment_questions, function($q) {
                return !empty($q['question_text']);
            })) : 0;
            
            if ($finalAssessmentQuestionCount > 0) {
                $message .= " Final Assessment created with {$finalAssessmentQuestionCount} question(s).";
            } else {
                $message .= ' Final Assessment created. You can now add questions to it.';
            }
        }

        return redirect()->route('admin.trainings.index')
                         ->with('success', $message);
    }

    // for users function
    public function userIndex()
{
    $trainings = Training::with('files')->get();
    return view('training.index', compact('trainings'));
}

public function markAsRead($trainingId, $fileId)
{
    TrainingRead::firstOrCreate([
        'user_id' => auth()->id(),
        'training_file_id' => $fileId,
    ]);

    return response()->json(['status' => 'ok']);
}

 public function take($id)
    {
        $training = Training::with('files')->findOrFail($id);

        // progress
        $total = $training->files->where('type', 'module')->count();
        $read = auth()->user()->reads()
                    ->whereIn('training_file_id', $training->files->pluck('id'))
                    ->count();
        $progress = $total > 0 ? round(($read / $total) * 100) : 0;

        return view('training.take', compact('training', 'progress', 'total', 'read'));
    }

    public function destroy($id)
    {
        $training = Training::findOrFail($id);
        
        // Delete associated files from storage
        foreach ($training->files as $file) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($file->path);
        }
        
        // Delete certificate if exists
        if ($training->certificate_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($training->certificate_path);
        }
        
        // Delete the training (cascades to related records)
        $training->delete();
        
        return redirect()->back()->with('success', 'Training deleted successfully!');
    }
}
