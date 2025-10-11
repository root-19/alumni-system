<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AlumniPost;
use App\Models\User;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display attendance list for admin
     */
    public function index(Request $request)
    {
        // Get event registrations (actual registrations) instead of attendance records
        $query = EventRegistration::with(['user', 'post'])->latest();

        // Filter by event if specified
        if ($request->filled('event_id')) {
            $query->where('alumni_post_id', $request->integer('event_id'));
        }

        // Filter by status if specified
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $registrations = $query->paginate(20);

        // For filter dropdowns
        $events = AlumniPost::orderByDesc('created_at')->get(['id', 'content', 'title']);
        $statuses = ['registered', 'cancelled', 'attended'];

        return view('admin.attendance', compact('registrations', 'events', 'statuses'));
    }

    /**
     * Store attendance record for an event
     */
    public function store(Request $request, AlumniPost $post)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'status' => 'required|in:attending,not_attending,maybe',
        ]);

        Attendance::updateOrCreate(
            [
                'alumni_post_id' => $post->id,
                'user_id' => $userId,
            ],
            [
                'title' => $post->title, // Use event title from AlumniPost
                'description' => $post->description, // Use event description from AlumniPost
                'status' => $validated['status'],
            ]
        );

        return back()->with('success', 'Your attendance has been recorded.');
    }

    /**
     * Update attendance status
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:attending,not_attending,maybe',
        ]);

        // Update status and sync with event data
        $attendance->update([
            'status' => $validated['status'],
            'title' => $attendance->alumniPost->title, // Keep in sync with event
            'description' => $attendance->alumniPost->description, // Keep in sync with event
        ]);

        return back()->with('success', 'Attendance updated successfully.');
    }

    /**
     * Mark a user as attended for an event
     */
    public function markAttended(EventRegistration $registration)
    {
        $registration->update([
            'status' => 'attended'
        ]);

        return back()->with('success', 'User marked as attended successfully.');
    }

    /**
     * Delete registration record
     */
    public function destroy(EventRegistration $registration)
    {
        $registration->delete();

        return back()->with('success', 'Registration record deleted successfully.');
    }

    /**
     * Get registration statistics for an event
     */
    public function getEventStats(AlumniPost $post)
    {
        $stats = [
            'total_registered' => $post->registrations()->count(),
            'registered' => $post->registrations()->where('status', 'registered')->count(),
            'cancelled' => $post->registrations()->where('status', 'cancelled')->count(),
            'attended' => $post->registrations()->where('status', 'attended')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Export registration list for an event
     */
    public function export(AlumniPost $post)
    {
        $registrations = $post->registrations()->with('user')->get();

        $csvData = "Name,Email,Category,Status,Registered At\n";
        
        foreach ($registrations as $registration) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s\n",
                $registration->user->name,
                $registration->user->email,
                $registration->category,
                ucfirst($registration->status),
                $registration->created_at->format('Y-m-d H:i:s')
            );
        }

        $filename = 'registrations_' . $post->id . '_' . now()->format('Y-m-d') . '.csv';

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}