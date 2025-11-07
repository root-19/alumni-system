<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\UserQuizAnswer;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::where('is_active', true)
            ->with(['training', 'questions'])
            ->latest()
            ->get();
        return view('quiz.index', compact('quizzes'));
    }

    public function show($id)
    {
        $quiz = Quiz::with(['training', 'questions.choices'])->findOrFail($id);
        
        if (!$quiz->is_active) {
            return redirect()->route('quizzes.index')
                ->with('error', 'This quiz is not available.');
        }

        // Check if user has already taken this quiz
        $attempt = UserQuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->first();

        return view('quiz.show', compact('quiz', 'attempt'));
    }

    public function take($id)
    {
        $quiz = Quiz::with(['questions.choices'])->findOrFail($id);
        
        if (!$quiz->is_active) {
            return redirect()->route('quizzes.index')
                ->with('error', 'This quiz is not available.');
        }

        if ($quiz->questions->isEmpty()) {
            return redirect()->route('quizzes.show', $quiz->id)
                ->with('error', 'This quiz has no questions yet.');
        }

        // Create or get attempt
        $attempt = UserQuizAttempt::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'quiz_id' => $quiz->id,
                'completed_at' => null,
            ],
            [
                'started_at' => now(),
                'score' => 0,
                'total_points' => $quiz->questions->sum('points'),
                'percentage' => 0,
                'passed' => false,
            ]
        );

        return view('quiz.take', compact('quiz', 'attempt'));
    }

    public function submit(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $user = Auth::user();

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D',
        ]);

        // Get or create attempt
        $attempt = UserQuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->whereNull('completed_at')
            ->first();

        if (!$attempt) {
            $attempt = UserQuizAttempt::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'started_at' => now(),
                'score' => 0,
                'total_points' => $quiz->questions->sum('points'),
                'percentage' => 0,
                'passed' => false,
            ]);
        }

        $score = 0;
        $totalPoints = $quiz->questions->sum('points');

        // Process each answer
        foreach ($quiz->questions as $question) {
            $selectedAnswer = $validated['answers'][$question->id] ?? null;
            
            if ($selectedAnswer) {
                $isCorrect = $selectedAnswer === $question->correct_answer;
                $pointsEarned = $isCorrect ? $question->points : 0;
                
                if ($isCorrect) {
                    $score += $question->points;
                }

                // Save or update answer
                UserQuizAnswer::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'quiz_id' => $quiz->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'selected_answer' => $selectedAnswer,
                        'is_correct' => $isCorrect,
                        'points_earned' => $pointsEarned,
                    ]
                );
            }
        }

        // Calculate percentage
        $percentage = $totalPoints > 0 ? round(($score / $totalPoints) * 100) : 0;
        $passed = $percentage >= $quiz->passing_score;

        // Update attempt
        $attempt->update([
            'score' => $score,
            'total_points' => $totalPoints,
            'percentage' => $percentage,
            'passed' => $passed,
            'completed_at' => now(),
        ]);

        return redirect()->route('quizzes.result', $attempt->id)
            ->with('success', 'Quiz submitted successfully!');
    }

    public function result($attemptId)
    {
        $attempt = UserQuizAttempt::with(['quiz.questions', 'quiz.training'])
            ->findOrFail($attemptId);

        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $answers = UserQuizAnswer::where('user_id', Auth::id())
            ->where('quiz_id', $attempt->quiz_id)
            ->with('question')
            ->get()
            ->keyBy('question_id');

        return view('quiz.result', compact('attempt', 'answers'));
    }

    public function retake($id)
    {
        $quiz = Quiz::findOrFail($id);
        $user = Auth::user();

        if (!$quiz->is_active) {
            return redirect()->route('quizzes.index')
                ->with('error', 'This quiz is not available.');
        }

        // Delete all previous attempts and answers for this quiz
        $attempts = UserQuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->get();

        foreach ($attempts as $attempt) {
            // Delete all answers for this attempt
            UserQuizAnswer::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->delete();
            
            // Delete the attempt
            $attempt->delete();
        }

        return redirect()->route('quizzes.take', $quiz->id)
            ->with('success', 'Quiz reset. You can now retake the quiz.');
    }
}
