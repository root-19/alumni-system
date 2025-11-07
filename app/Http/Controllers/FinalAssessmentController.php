<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinalAssessment;
use App\Models\FinalAssessmentQuestion;
use App\Models\UserFinalAssessmentAnswer;
use App\Models\UserFinalAssessmentAttempt;
use Illuminate\Support\Facades\Auth;

class FinalAssessmentController extends Controller
{
    public function index()
    {
        $assessments = FinalAssessment::where('is_active', true)
            ->with('questions')
            ->latest()
            ->get();
        return view('final-assessment.index', compact('assessments'));
    }

    public function show($id)
    {
        $assessment = FinalAssessment::with('questions.choices')->findOrFail($id);
        
        if (!$assessment->is_active) {
            return redirect()->route('final-assessments.index')
                ->with('error', 'This assessment is not available.');
        }

        // Check if user has already taken this assessment
        $attempt = UserFinalAssessmentAttempt::where('user_id', Auth::id())
            ->where('final_assessment_id', $assessment->id)
            ->latest()
            ->first();

        return view('final-assessment.show', compact('assessment', 'attempt'));
    }

    public function take($id)
    {
        $assessment = FinalAssessment::with(['questions.choices'])->findOrFail($id);
        
        if (!$assessment->is_active) {
            return redirect()->route('final-assessments.index')
                ->with('error', 'This assessment is not available.');
        }

        if ($assessment->questions->isEmpty()) {
            return redirect()->route('final-assessments.show', $assessment->id)
                ->with('error', 'This assessment has no questions yet.');
        }

        // Create or get attempt
        $attempt = UserFinalAssessmentAttempt::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'final_assessment_id' => $assessment->id,
                'completed_at' => null,
            ],
            [
                'started_at' => now(),
                'score' => 0,
                'total_points' => $assessment->questions->sum('points'),
                'percentage' => 0,
                'passed' => false,
            ]
        );

        return view('final-assessment.take', compact('assessment', 'attempt'));
    }

    public function submit(Request $request, $id)
    {
        $assessment = FinalAssessment::with('questions')->findOrFail($id);
        $user = Auth::user();

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D',
        ]);

        // Get or create attempt
        $attempt = UserFinalAssessmentAttempt::where('user_id', $user->id)
            ->where('final_assessment_id', $assessment->id)
            ->whereNull('completed_at')
            ->first();

        if (!$attempt) {
            $attempt = UserFinalAssessmentAttempt::create([
                'user_id' => $user->id,
                'final_assessment_id' => $assessment->id,
                'started_at' => now(),
                'score' => 0,
                'total_points' => $assessment->questions->sum('points'),
                'percentage' => 0,
                'passed' => false,
            ]);
        }

        $score = 0;
        $totalPoints = $assessment->questions->sum('points');

        // Process each answer
        foreach ($assessment->questions as $question) {
            $selectedAnswer = $validated['answers'][$question->id] ?? null;
            
            if ($selectedAnswer) {
                $isCorrect = $selectedAnswer === $question->correct_answer;
                $pointsEarned = $isCorrect ? $question->points : 0;
                
                if ($isCorrect) {
                    $score += $question->points;
                }

                // Save or update answer
                UserFinalAssessmentAnswer::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'final_assessment_id' => $assessment->id,
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
        $passed = $percentage >= $assessment->passing_score;

        // Update attempt
        $attempt->update([
            'score' => $score,
            'total_points' => $totalPoints,
            'percentage' => $percentage,
            'passed' => $passed,
            'completed_at' => now(),
        ]);

        return redirect()->route('final-assessments.result', $attempt->id)
            ->with('success', 'Assessment submitted successfully!');
    }

    public function result($attemptId)
    {
        $attempt = UserFinalAssessmentAttempt::with(['finalAssessment.questions'])
            ->findOrFail($attemptId);

        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $answers = UserFinalAssessmentAnswer::where('user_id', Auth::id())
            ->where('final_assessment_id', $attempt->final_assessment_id)
            ->with('question')
            ->get()
            ->keyBy('question_id');

        return view('final-assessment.result', compact('attempt', 'answers'));
    }

    public function retake($id)
    {
        $assessment = FinalAssessment::findOrFail($id);
        $user = Auth::user();

        if (!$assessment->is_active) {
            return redirect()->route('final-assessments.index')
                ->with('error', 'This assessment is not available.');
        }

        // Delete all previous attempts and answers for this assessment
        $attempts = UserFinalAssessmentAttempt::where('user_id', $user->id)
            ->where('final_assessment_id', $assessment->id)
            ->get();

        foreach ($attempts as $attempt) {
            // Delete all answers for this attempt
            UserFinalAssessmentAnswer::where('user_id', $user->id)
                ->where('final_assessment_id', $assessment->id)
                ->delete();
            
            // Delete the attempt
            $attempt->delete();
        }

        return redirect()->route('final-assessments.take', $assessment->id)
            ->with('success', 'Assessment reset. You can now retake the assessment.');
    }
}
