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
        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Processing,Approved,Rejected,Completed',
            'admin_note' => 'nullable|string|max:2000',
        ]);
        $documentRequest->update($validated);
        return back()->with('success', 'Request status updated.');
    }
}
