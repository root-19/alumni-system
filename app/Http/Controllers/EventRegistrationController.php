<?php

namespace App\Http\Controllers;

use App\Models\AlumniPost;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = EventRegistration::with(['user', 'post'])->latest();

        if ($request->filled('post_id')) {
            $query->where('alumni_post_id', $request->integer('post_id'));
        }

        $registrations = $query->paginate(20);

        // For filter dropdown
        $events = AlumniPost::orderByDesc('created_at')->get(['id', 'content']);

        return view('admin.registration_list', compact('registrations', 'events'));
    }
    public function register(Request $request, AlumniPost $post)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }

        // Prevent admin and assistant from registering
        $user = Auth::user();
        if ($user->isAdmin() || $user->isAssistant()) {
            return back()->with('error', 'Administrators and assistants cannot register for events.');
        }

        // Check if event is full
        if ($post->isFull()) {
            return back()->with('error', 'This event is already full. No more registrations are accepted.');
        }

        $validated = $request->validate([
            'category' => 'nullable|string|max:100',
        ]);

        EventRegistration::updateOrCreate(
            [
                'alumni_post_id' => $post->id,
                'user_id' => $userId,
            ],
            [
                'status' => 'registered',
                'category' => $validated['category'] ?? 'Alumni',
            ]
        );

        return back()->with('success', 'You are registered for this event.');
    }

    public function unregister(Request $request, AlumniPost $post)
    {
        $userId = Auth::id();
        if ($userId) {
            EventRegistration::where('alumni_post_id', $post->id)
                ->where('user_id', $userId)
                ->delete();
        }

        return back()->with('success', 'Your registration has been cancelled.');
    }

    public function destroy(Request $request, AlumniPost $post, EventRegistration $registration)
    {
        // Ensure the registration belongs to the post
        if ($registration->alumni_post_id !== $post->id) {
            abort(404);
        }

        $registration->delete();

        return back()->with('success', 'Registrant removed.');
    }
}
