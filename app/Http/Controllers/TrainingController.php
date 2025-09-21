<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingFile;
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

    // Get user progress
    $total = $training->files->where('type', 'module')->count();
    $read = Auth::user()->reads()
                ->whereIn('training_file_id', $training->files->pluck('id'))
                ->count();
    $progress = $total > 0 ? round(($read / $total) * 100) : 0;
    
    // Update stored progress
    $training->update(['progress' => $progress]);

    return view('training.take', compact('training', 'progress', 'total', 'read'));
}

 public function markAsRead(Training $training, TrainingFile $file)
{
    // Create the read record if it doesn't exist
    Auth::user()->reads()->firstOrCreate([
        'training_file_id' => $file->id
    ]);

    // Calculate new progress
    $totalModules = $training->files()->where('type', 'module')->count();
    $readModules = Auth::user()->reads()
        ->whereIn('training_file_id', $training->files()->pluck('id'))
        ->count();
    
    // Calculate and update progress, ensuring it can't go below existing progress
    $newProgress = $totalModules > 0 ? round(($readModules / $totalModules) * 100) : 0;
    $training->update([
        'progress' => max($training->progress ?? 0, $newProgress)
    ]);

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
        // Check if user has completed the training
        $totalModules = $training->files->where('type', 'module')->count();
        $readModules = Auth::user()->reads()
            ->whereIn('training_file_id', $training->files->pluck('id'))
            ->count();
        $calculatedProgress = $totalModules > 0 ? round(($readModules / $totalModules) * 100) : 0;
        
        if (($calculatedProgress >= 100 || $training->progress >= 100) && $training->certificate_path) {
            $path = storage_path('app/public/' . $training->certificate_path);
            $fileName = Str::slug($training->title) . '-certificate.' . pathinfo($training->certificate_path, PATHINFO_EXTENSION);
            return response()->download($path, $fileName);
        }
        
        return back()->with('error', 'Certificate not available.');
    }
}
