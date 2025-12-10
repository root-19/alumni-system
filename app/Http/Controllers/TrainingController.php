<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Training;
use App\Models\TrainingFile;
use App\Models\UserTrainingProgress;
use App\Models\UserModuleProgress;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with(['files', 'quizzes', 'finalAssessments'])->get();
        $user = Auth::user();
        
        // Get all quiz attempts for all trainings (get latest attempt for each quiz)
        $allQuizIds = $trainings->flatMap->quizzes->pluck('id');
        $quizAttempts = \App\Models\UserQuizAttempt::where('user_id', $user->id)
            ->whereIn('quiz_id', $allQuizIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('quiz_id')
            ->keyBy('quiz_id');
        
        // Get all final assessment attempts for all trainings
        $allFinalAssessmentIds = $trainings->flatMap->finalAssessments->pluck('id');
        $finalAssessmentAttempts = \App\Models\UserFinalAssessmentAttempt::where('user_id', $user->id)
            ->whereIn('final_assessment_id', $allFinalAssessmentIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('final_assessment_id')
            ->keyBy('final_assessment_id');
        
        return view('training', compact('trainings', 'quizAttempts', 'finalAssessmentAttempts'));
    }

    public function take($id)
{
    $training = \App\Models\Training::with(['files', 'quizzes', 'finalAssessments'])->findOrFail($id);
    $user = Auth::user();

    // Get user's existing progress
    $total = $training->files->where('type', 'module')->count();
    $read = $user->reads()
                ->whereIn('training_file_id', $training->files->pluck('id'))
                ->count();
    
    // Get stored progress from user_training_progress table
    $userProgress = UserTrainingProgress::where('user_id', $user->id)
        ->where('training_id', $training->id)
        ->first();
    
    // Get detailed module progress
    $moduleProgresses = UserModuleProgress::where('user_id', $user->id)
        ->whereIn('training_file_id', $training->files->pluck('id'))
        ->get();
    
    // Calculate current progress from detailed module data
    if ($moduleProgresses->count() > 0) {
        $averageProgress = round($moduleProgresses->avg('completion_percentage'));
    } else {
        $averageProgress = $total > 0 ? round(($read / $total) * 100) : 0;
    }
    
    // Update user-specific progress with calculated value
    UserTrainingProgress::updateOrCreate(
        ['user_id' => $user->id, 'training_id' => $training->id],
        ['progress' => $averageProgress]
    );

    // Get quiz and final assessment attempts for this user (get latest attempt for each)
    $quizAttempts = \App\Models\UserQuizAttempt::where('user_id', $user->id)
        ->whereIn('quiz_id', $training->quizzes->pluck('id'))
        ->orderBy('created_at', 'desc')
        ->get()
        ->unique('quiz_id')
        ->keyBy('quiz_id');
    
    $finalAssessmentAttempts = \App\Models\UserFinalAssessmentAttempt::where('user_id', $user->id)
        ->whereIn('final_assessment_id', $training->finalAssessments->pluck('id'))
        ->orderBy('created_at', 'desc')
        ->get()
        ->unique('final_assessment_id')
        ->keyBy('final_assessment_id');

    return view('training.take', compact('training', 'total', 'read', 'moduleProgresses', 'averageProgress', 'quizAttempts', 'finalAssessmentAttempts'));
}

 public function markAsRead(Training $training, TrainingFile $file)
{
    $user = Auth::user();
    
    // Create the read record if it doesn't exist
    $user->reads()->firstOrCreate([
        'training_file_id' => $file->id
    ]);

    // Calculate new progress
    $totalModules = $training->files()->where('type', 'module')->count();
    $readModules = $user->reads()
        ->whereIn('training_file_id', $training->files()->pluck('id'))
        ->count();
    
    // Calculate new progress
    $newProgress = $totalModules > 0 ? round(($readModules / $totalModules) * 100) : 0;
    
    // Update user-specific progress
    UserTrainingProgress::updateOrCreate(
        ['user_id' => $user->id, 'training_id' => $training->id],
        ['progress' => $newProgress]
    );

    // Return updated progress info
    return response()->json([
        'success' => true,
        'progress' => $newProgress,
        'hasAllCertificates' => $newProgress === 100,
        'readCount' => $readModules,
        'totalCount' => $totalModules
    ]);
}

    /**
     * Update stored progress percentage for a training.
     * Accepts numeric progress 0-100; when reaches 100 certificate unlocks automatically (front-end already checks).
     */
    public function updateProgress(Request $request, Training $training)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100'
        ]);

        // For security ensure user has at least one read record or has accessed take page; minimal gate can be expanded.
        $training->progress = $request->progress;
        $training->save();

        return response()->json([
            'status' => 'ok',
            'progress' => $training->progress
        ]);
    }

    public function downloadCertificate(Training $training)
    {
        $user = Auth::user();
        
        // Check if user has completed the training
        $totalModules = $training->files->where('type', 'module')->count();
        $readModules = $user->reads()
            ->whereIn('training_file_id', $training->files->pluck('id'))
            ->count();
        $calculatedProgress = $totalModules > 0 ? round(($readModules / $totalModules) * 100) : 0;
        
        // Get user's stored progress
        $userProgress = UserTrainingProgress::where('user_id', $user->id)
            ->where('training_id', $training->id)
            ->first();
        $storedProgress = $userProgress ? $userProgress->progress : 0;
        
        // Check if all quizzes are passed (if exists)
        $hasQuiz = $training->quizzes()->where('is_active', true)->count() > 0;
        $allQuizzesPassed = true;
        
        if ($hasQuiz) {
            $quizIds = $training->quizzes()->where('is_active', true)->pluck('id');
            $quizAttempts = \App\Models\UserQuizAttempt::where('user_id', $user->id)
                ->whereIn('quiz_id', $quizIds)
                ->whereNotNull('completed_at')
                ->get()
                ->unique('quiz_id')
                ->keyBy('quiz_id');
            
            // Check if all quizzes are passed
            foreach ($quizIds as $quizId) {
                $quizAttempt = $quizAttempts[$quizId] ?? null;
                if (!$quizAttempt || !$quizAttempt->passed) {
                    $allQuizzesPassed = false;
                    break;
                }
            }
        }
        
        // Certificate download logic:
        // If quizzes exist: allow download if ALL quizzes are PASSED (regardless of module completion)
        // If no quizzes: allow download if modules are 100% complete
        if ($hasQuiz) {
            $canDownload = $allQuizzesPassed && $training->certificate_path;
        } else {
            $canDownload = ($calculatedProgress >= 100 || $storedProgress >= 100) && $training->certificate_path;
        }
        
        if ($canDownload) {
            $path = storage_path('app/public/' . $training->certificate_path);

            // If stored certificate exists, return it
            if (is_file($path)) {
            $fileName = Str::slug($training->title) . '-certificate.' . pathinfo($training->certificate_path, PATHINFO_EXTENSION);
            return response()->download($path, $fileName);
            }

            // Otherwise, generate a fresh PDF on-the-fly
            $user = Auth::user();
            $data = [
                'schoolName' => config('app.name', 'Alumni Training Portal'),
                'trainingTitle' => $training->title,
                'studentName' => trim($user->name . ' ' . ($user->last_name ?? '')),
                'completionDate' => now()->format('F d, Y'),
                'percentage' => max($calculatedProgress, $storedProgress),
                'attempt' => (object)['id' => $user->id],
                'logoPath' => public_path('image/logo.png'),
                'hasLogo' => file_exists(public_path('image/logo.png')),
            ];

            $pdf = Pdf::loadView('certificates.training-completion', $data)
                ->setPaper('a4', 'portrait');

            $fileName = Str::slug($training->title) . '-certificate.pdf';
            return $pdf->download($fileName);
        }
        
        return back()->with('error', 'Certificate not available. Complete all modules first.');
    }

    /**
     * Update detailed module progress (scroll position, time spent, etc.)
     */
    public function updateModuleProgress(Request $request, Training $training, TrainingFile $file)
    {
        $request->validate([
            'scroll_progress' => 'required|integer|min:0|max:100',
            'time_spent' => 'required|integer|min:0',
            'completion_percentage' => 'required|integer|min:0|max:100'
        ]);

        $user = Auth::user();
        
        // Update or create module progress
        $moduleProgress = UserModuleProgress::updateOrCreate(
            ['user_id' => $user->id, 'training_file_id' => $file->id],
            [
                'scroll_progress' => $request->scroll_progress,
                'time_spent' => $request->time_spent,
                'completion_percentage' => $request->completion_percentage,
                'is_completed' => $request->completion_percentage >= 100,
                'last_accessed_at' => now()
            ]
        );

        // Calculate overall training progress
        $this->updateTrainingProgress($user, $training);

        return response()->json([
            'success' => true,
            'module_progress' => $moduleProgress,
            'message' => 'Module progress updated successfully'
        ]);
    }

    /**
     * Update overall training progress based on module progress
     */
    private function updateTrainingProgress($user, $training)
    {
        $totalModules = $training->files()->where('type', 'module')->count();
        
        if ($totalModules === 0) {
            return 0;
        }

        // Get all module progress for this training
        $moduleProgresses = UserModuleProgress::where('user_id', $user->id)
            ->whereIn('training_file_id', $training->files()->pluck('id'))
            ->get();

        // Calculate average completion percentage
        $totalCompletion = $moduleProgresses->sum('completion_percentage');
        $averageProgress = $moduleProgresses->count() > 0 ? $totalCompletion / $moduleProgresses->count() : 0;

        // Update user training progress
        UserTrainingProgress::updateOrCreate(
            ['user_id' => $user->id, 'training_id' => $training->id],
            ['progress' => round($averageProgress)]
        );

        return round($averageProgress);
    }
}
