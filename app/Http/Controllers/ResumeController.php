<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    // Show resume creation form
    public function create()
    {
        $resume = Resume::where('user_id', Auth::id())->first();
        return view('resume.create', compact('resume'));
    }
    // List all resumes
    public function index()
    {
        $resumes = Resume::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.resume', compact('resumes')); 
    }

    // Show single resume
    public function show(Resume $resume)
    {
        $resume->load('user');
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

    // Generate resume from form data
    public function generate(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'objective' => 'required|string',
            'educational_attainment' => 'required|string',
            'training_seminars' => 'nullable|string',
            'work_experience' => 'nullable|string',
        ]);

        // Check if user already has a resume
        $resume = Resume::where('user_id', Auth::id())->first();
        
        if ($resume) {
            // Update existing resume
            $resume->update([
                'full_name' => $request->full_name,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'objective' => $request->objective,
                'educational_attainment' => $request->educational_attainment,
                'training_seminars' => $request->training_seminars,
                'work_experience' => $request->work_experience,
            ]);
            
            $message = 'Resume updated successfully!';
        } else {
            // Create new resume record
            $resume = Resume::create([
                'user_id' => Auth::id(),
                'full_name' => $request->full_name,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'objective' => $request->objective,
                'educational_attainment' => $request->educational_attainment,
                'training_seminars' => $request->training_seminars,
                'work_experience' => $request->work_experience,
            ]);
            
            $message = 'Resume generated successfully!';
        }

        // Generate PDF
        $pdf = Pdf::loadView('resume.pdf', compact('resume'));
        $pdf->setPaper('A4', 'portrait');
        
        // Save PDF to storage
        $fileName = 'resume_' . $resume->id . '_' . time() . '.pdf';
        $filePath = 'resumes/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        // Update resume with file information
        $resume->update([
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        return redirect()->route('resumes.show', $resume->id)
            ->with('success', $message);
    }

    // Download PDF
    public function downloadPdf($id)
    {
        $resume = Resume::findOrFail($id);
        
        // Check if user owns the resume or is admin
        if (Auth::check() && (Auth::id() == $resume->user_id || Auth::user()->role === 'admin')) {
            $filePath = storage_path('app/public/' . $resume->file_path);
            
            if (file_exists($filePath)) {
                return response()->download($filePath, $resume->file_name);
            }
        }
        
        abort(404, 'Resume not found.');
    }

    // Delete resume
    public function destroy(Resume $resume)
    {
        if ($resume->file_path) {
        Storage::disk('public')->delete($resume->file_path);
        }
        $resume->delete();

        return redirect()->back()->with('success', 'Resume deleted successfully.');
    }
}
