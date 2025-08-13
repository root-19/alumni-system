<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function index()
    {
        $resumes = Resume::all();
        return view('admin.resume', compact('resumes')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'resume_file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('resume_file');
        $path = $file->store('resumes', 'public');

        Resume::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);

        return redirect()->back()->with('success', 'Resume uploaded successfully.');
    }

    public function destroy(Resume $resume)
    {
        Storage::disk('public')->delete($resume->file_path);
        $resume->delete();

        return redirect()->back()->with('success', 'Resume deleted successfully.');
    }
}
