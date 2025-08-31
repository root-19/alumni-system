<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    // List all resumes
    public function index()
    {
        $resumes = Resume::all();
        return view('admin.resume', compact('resumes')); 
    }

    // Show single resume
    public function show(Resume $resume)
    {
        return view('resume-view', compact('resume'));
    } 

    // Show resume in viewer
    public function viewResumeInViewer($id)
    {
        $resume = Resume::findOrFail($id);
        return view('resume-view', compact('resume'));
    }

    // Show resume viewer with all resumes
    public function showResumeViewer()
    {
        $resumes = Resume::all();
        $resume = $resumes->first(); // Default to first resume
        return view('resume-view', compact('resume', 'resumes'));
    }

    // View PDF directly
    public function viewResume($id)
    {
        $resume = Resume::findOrFail($id);
        $filePath = storage_path('app/public/' . $resume->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'Resume not found.');
        }

        return response()->file($filePath);
    }

    // Upload resume
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

    // Delete resume
    public function destroy(Resume $resume)
    {
        Storage::disk('public')->delete($resume->file_path);
        $resume->delete();

        return redirect()->back()->with('success', 'Resume deleted successfully.');
    }
}
