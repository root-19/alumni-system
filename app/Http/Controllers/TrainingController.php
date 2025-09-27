<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingFile;
use App\Models\UserTrainingProgress;
use App\Models\UserModuleProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with('files')->get();
        return view('training.index', compact('trainings'));
    }

    public function take($id)
{
    $training = \App\Models\Training::with('files')->findOrFail($id);
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

    return view('training.take', compact('training', 'total', 'read', 'moduleProgresses', 'averageProgress'));
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
        
        // User must have 100% progress to download certificate
        if (($calculatedProgress >= 100 || $storedProgress >= 100) && $training->certificate_path) {
            $path = storage_path('app/public/' . $training->certificate_path);
            $fileName = Str::slug($training->title) . '-certificate.' . pathinfo($training->certificate_path, PATHINFO_EXTENSION);
            return response()->download($path, $fileName);
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
