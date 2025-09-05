<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\TrainingFile;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with('files')->latest()->get();
        return view('admin.training.index', compact('trainings'));
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

        return redirect()->route('admin.trainings.index')
                         ->with('success', 'Training created successfully.');
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
}
