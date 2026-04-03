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

        if (auth()->user()->role === 'assistant') {
            return view('assistant.document-requests', compact('requests'));
        }

        return view('admin.document-requests', compact('requests'));
    }

    public function updateStatus(Request $request, DocumentRequest $documentRequest)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Processing,Approved,Rejected,Completed',
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $documentRequest->update($validated);
        $documentRequest->refresh();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $documentRequest->status,
                'admin_note' => $documentRequest->admin_note,
            ]);
        }

        return back()->with('success', 'Request status updated.');
    }

    public function show(DocumentRequest $documentRequest)
    {
        $documentRequest->load('user');
        return view('assistant.document-request-detail', compact('documentRequest'));
    }
}
