<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinalAssessment;
use App\Models\FinalAssessmentQuestion;
use App\Models\FinalAssessmentChoice;

class FinalAssessmentController extends Controller
{
    public function index()
    {
        $assessments = FinalAssessment::with('questions')->latest()->get();
        return view('admin.final-assessment.index', compact('assessments'));
    }

    public function create()
    {
        return view('admin.final-assessment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $assessment = FinalAssessment::create($validated);

        return redirect()->route('admin.final-assessments.show', $assessment->id)
            ->with('success', 'Final Assessment created successfully. Now add questions.');
    }

    public function show($id)
    {
        $assessment = FinalAssessment::with('questions.choices')->findOrFail($id);
        return view('admin.final-assessment.show', compact('assessment'));
    }

    public function edit($id)
    {
        $assessment = FinalAssessment::findOrFail($id);
        return view('admin.final-assessment.edit', compact('assessment'));
    }

    public function update(Request $request, $id)
    {
        $assessment = FinalAssessment::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $assessment->update($validated);

        return redirect()->route('admin.final-assessments.show', $assessment->id)
            ->with('success', 'Final Assessment updated successfully.');
    }

    public function destroy($id)
    {
        $assessment = FinalAssessment::findOrFail($id);
        $assessment->delete();

        return redirect()->route('admin.final-assessments.index')
            ->with('success', 'Final Assessment deleted successfully.');
    }

    public function addQuestion($id)
    {
        $assessment = FinalAssessment::findOrFail($id);
        return view('admin.final-assessment.add-question', compact('assessment'));
    }

    public function storeQuestion(Request $request, $id)
    {
        $assessment = FinalAssessment::findOrFail($id);

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
        $order = $assessment->questions()->max('order') + 1;

        $question = FinalAssessmentQuestion::create([
            'final_assessment_id' => $assessment->id,
            'question_text' => $validated['question_text'],
            'correct_answer' => $validated['correct_answer'],
            'points' => $validated['points'],
            'order' => $order,
        ]);

        // Create choices
        FinalAssessmentChoice::create([
            'question_id' => $question->id,
            'choice_letter' => 'A',
            'choice_text' => $validated['choice_a'],
        ]);

        FinalAssessmentChoice::create([
            'question_id' => $question->id,
            'choice_letter' => 'B',
            'choice_text' => $validated['choice_b'],
        ]);

        FinalAssessmentChoice::create([
            'question_id' => $question->id,
            'choice_letter' => 'C',
            'choice_text' => $validated['choice_c'],
        ]);

        FinalAssessmentChoice::create([
            'question_id' => $question->id,
            'choice_letter' => 'D',
            'choice_text' => $validated['choice_d'],
        ]);

        return redirect()->route('admin.final-assessments.show', $assessment->id)
            ->with('success', 'Question added successfully.');
    }

    public function editQuestion($assessmentId, $questionId)
    {
        $assessment = FinalAssessment::findOrFail($assessmentId);
        $question = FinalAssessmentQuestion::with('choices')->findOrFail($questionId);
        return view('admin.final-assessment.edit-question', compact('assessment', 'question'));
    }

    public function updateQuestion(Request $request, $assessmentId, $questionId)
    {
        $assessment = FinalAssessment::findOrFail($assessmentId);
        $question = FinalAssessmentQuestion::findOrFail($questionId);

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
            FinalAssessmentChoice::where('question_id', $question->id)
                ->where('choice_letter', $letter)
                ->update(['choice_text' => $validated[$field]]);
        }

        return redirect()->route('admin.final-assessments.show', $assessment->id)
            ->with('success', 'Question updated successfully.');
    }

    public function deleteQuestion($assessmentId, $questionId)
    {
        $question = FinalAssessmentQuestion::findOrFail($questionId);
        $question->delete();

        return redirect()->route('admin.final-assessments.show', $assessmentId)
            ->with('success', 'Question deleted successfully.');
    }
}
