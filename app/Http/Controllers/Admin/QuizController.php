<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\Training;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['training', 'questions'])->latest()->get();
        return view('admin.quiz.index', compact('quizzes'));
    }

    public function create(Request $request)
    {
        $trainings = Training::all();
        $selectedTrainingId = $request->get('training_id');
        return view('admin.quiz.create', compact('trainings', 'selectedTrainingId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $quiz = Quiz::create($validated);

        return redirect()->route('admin.quizzes.show', $quiz->id)
            ->with('success', 'Quiz created successfully. Now add questions.');
    }

    public function show($id)
    {
        $quiz = Quiz::with(['training', 'questions.choices'])->findOrFail($id);
        return view('admin.quiz.show', compact('quiz'));
    }

    public function getParticipants($id)
    {
        $quiz = Quiz::with('training')->findOrFail($id);
        
        // Get all attempts for this quiz
        $allAttempts = \App\Models\UserQuizAttempt::where('quiz_id', $id)
            ->with('user')
            ->orderBy('user_id')
            ->orderBy('created_at')
            ->get();
        
        // Group by user and calculate statistics
        $participants = [];
        
        foreach ($allAttempts->groupBy('user_id') as $userId => $userAttempts) {
            $user = $userAttempts->first()->user;
            $attemptsCount = $userAttempts->count();
            $passedAttempt = $userAttempts->where('passed', true)->first();
            $latestAttempt = $userAttempts->sortByDesc('created_at')->first();
            
            $participants[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'total_attempts' => $attemptsCount,
                'passed' => $passedAttempt !== null,
                'attempts_before_pass' => $passedAttempt ? 
                    $userAttempts->where('created_at', '<=', $passedAttempt->created_at)->count() : 
                    null,
                'latest_percentage' => $latestAttempt ? $latestAttempt->percentage : null,
                'latest_attempt_date' => $latestAttempt && $latestAttempt->completed_at ? 
                    $latestAttempt->completed_at->format('M d, Y h:i A') : null,
            ];
        }
        
        return response()->json([
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'passing_score' => $quiz->passing_score,
            ],
            'participants' => $participants,
            'total_participants' => count($participants),
            'passed_count' => collect($participants)->where('passed', true)->count(),
            'failed_count' => collect($participants)->where('passed', false)->count(),
        ]);
    }

    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        $trainings = Training::all();
        return view('admin.quiz.edit', compact('quiz', 'trainings'));
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.show', $quiz->id)
            ->with('success', 'Quiz updated successfully.');
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }

    public function addQuestion($id)
    {
        $quiz = Quiz::findOrFail($id);
        return view('admin.quiz.add-question', compact('quiz'));
    }

    public function storeQuestion(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
            'points' => 'required|integer|min:1',
            'choice_a' => 'required|string',
            'choice_b' => 'required|string',
            'choice_c' => 'required|string',
            'choice_d' => 'required|string',
        ]);

        // Get the next order number
        $order = $quiz->questions()->max('order') + 1;

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $validated['question_text'],
            'correct_answer' => $validated['correct_answer'],
            'points' => $validated['points'],
            'order' => $order,
        ]);

        // Create choices
        QuestionChoice::create([
            'question_id' => $question->id,
            'choice_letter' => 'A',
            'choice_text' => $validated['choice_a'],
        ]);

        QuestionChoice::create([
            'question_id' => $question->id,
            'choice_letter' => 'B',
            'choice_text' => $validated['choice_b'],
        ]);

        QuestionChoice::create([
            'question_id' => $question->id,
            'choice_letter' => 'C',
            'choice_text' => $validated['choice_c'],
        ]);

        QuestionChoice::create([
            'question_id' => $question->id,
            'choice_letter' => 'D',
            'choice_text' => $validated['choice_d'],
        ]);

        return redirect()->route('admin.quizzes.show', $quiz->id)
            ->with('success', 'Question added successfully.');
    }

    public function editQuestion($quizId, $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = Question::with('choices')->findOrFail($questionId);
        return view('admin.quiz.edit-question', compact('quiz', 'question'));
    }

    public function updateQuestion(Request $request, $quizId, $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = Question::findOrFail($questionId);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
            'points' => 'required|integer|min:1',
            'choice_a' => 'required|string',
            'choice_b' => 'required|string',
            'choice_c' => 'required|string',
            'choice_d' => 'required|string',
        ]);

        $question->update([
            'question_text' => $validated['question_text'],
            'correct_answer' => $validated['correct_answer'],
            'points' => $validated['points'],
        ]);

        // Update choices
        $choices = ['A' => 'choice_a', 'B' => 'choice_b', 'C' => 'choice_c', 'D' => 'choice_d'];
        foreach ($choices as $letter => $field) {
            QuestionChoice::where('question_id', $question->id)
                ->where('choice_letter', $letter)
                ->update(['choice_text' => $validated[$field]]);
        }

        return redirect()->route('admin.quizzes.show', $quiz->id)
            ->with('success', 'Question updated successfully.');
    }

    public function deleteQuestion($quizId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->delete();

        return redirect()->route('admin.quizzes.show', $quizId)
            ->with('success', 'Question deleted successfully.');
    }
}
