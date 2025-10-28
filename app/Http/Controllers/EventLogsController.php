<?php

namespace App\Http\Controllers;

use App\Models\AlumniPost;
use Illuminate\Http\Request;

class EventLogsController extends Controller
{
    /**
     * Display archived events
     */
    public function index()
    {
        $archivedEvents = AlumniPost::where('is_archived', true)
            ->with('user')
            ->latest()
            ->get();
        
        return view('admin.event-logs', compact('archivedEvents'));
    }

    /**
     * Archive an event
     */
    public function archive(AlumniPost $post)
    {
        $post->update(['is_archived' => true]);
        
        return redirect()->back()->with('success', 'Event archived successfully!');
    }

    /**
     * Unarchive an event
     */
    public function unarchive(AlumniPost $post)
    {
        $post->update(['is_archived' => false]);
        
        return redirect()->back()->with('success', 'Event unarchived successfully!');
    }

    /**
     * Delete an archived event permanently
     */
    public function destroy(AlumniPost $post)
    {
        if (!$post->is_archived) {
            return redirect()->back()->with('error', 'Only archived events can be permanently deleted.');
        }

        $post->delete();
        
        return redirect()->route('admin.event-logs.index')->with('success', 'Event permanently deleted!');
    }
}
