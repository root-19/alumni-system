<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

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

    // kunin progress ng user
    $total = $training->files->where('type', 'module')->count();
    $read = auth()->user()->reads()
                ->whereIn('training_file_id', $training->files->pluck('id'))
                ->count();
    $progress = $total > 0 ? round(($read / $total) * 100) : 0;

    return view('training.take', compact('training', 'progress', 'total', 'read'));
}

}
