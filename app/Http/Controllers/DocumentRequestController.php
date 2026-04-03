<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentRequestController extends Controller
{
    public function index()
    {
        $requests = DocumentRequest::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('documents', [
            'requests' => $requests,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|max:100',
            'purpose' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:2000',
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 'Pending';

        DocumentRequest::create($data);

        return redirect()->route('documents.index')->with('success', 'Your request has been submitted.');
    }

    public function adminIndex()
    {
        $requests = DocumentRequest::with('user')->latest()->paginate(20);
        return view('admin.document-requests', compact('requests'));
    }

    public function updateStatus(Request $request, DocumentRequest $documentRequest)
    {
        try {
            // Debug logging
            \Log::info('DocumentRequest updateStatus called');
            \Log::info('User: ' . (auth()->check() ? auth()->user()->email : 'not authenticated'));
            \Log::info('User role: ' . (auth()->check() ? auth()->user()->role : 'N/A'));
            \Log::info('Request URL: ' . $request->url());
            \Log::info('Request method: ' . $request->method());
            \Log::info('DocumentRequest ID: ' . $documentRequest->id);
            
            $validated = $request->validate([
                'status' => 'required|string|in:Pending,Processing,Approved,Rejected,Completed',
                'admin_note' => 'nullable|string|max:2000',
            ]);
            
            $documentRequest->update($validated);
            
            \Log::info('DocumentRequest updated successfully');
            
            return back()->with('success', 'Request status updated.');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('DocumentRequest update error: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all()));
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return with error message
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update request: ' . $e->getMessage()]);
        }
    }

    public function show(DocumentRequest $documentRequest)
    {
        $documentRequest->load('user');
        return view('assistant.document-request-detail', compact('documentRequest'));
    }
}
