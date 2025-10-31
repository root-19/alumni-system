<?php

namespace App\Http\Controllers;

use App\Models\AlumniPost;
use App\Models\Comment;
use Illuminate\Http\Request;

class AlumniController extends Controller
{

    public function index()
{
    // Automatically mark events as completed if their event_date has passed
    AlumniPost::where('is_archived', false)
        ->where('is_completed', false)
        ->whereNotNull('event_date')
        ->where('event_date', '<', now())
        ->update(['is_completed' => true]);
    
    // Get active (not completed) events
    $alumniPosts = AlumniPost::where('is_archived', false)
        ->where('is_completed', false)
        ->latest()
        ->get();
    
    // Get completed events
    $completedEvents = AlumniPost::where('is_archived', false)
        ->where('is_completed', true)
        ->latest()
        ->get();
    
    return view('events', compact('alumniPosts', 'completedEvents'));
}

    public function adminIndex()
{
    // Automatically mark events as completed if their event_date has passed
    AlumniPost::where('is_archived', false)
        ->where('is_completed', false)
        ->whereNotNull('event_date')
        ->where('event_date', '<', now())
        ->update(['is_completed' => true]);
    
    // Get active (not completed) events
    $alumniPosts = AlumniPost::where('is_archived', false)
        ->where('is_completed', false)
        ->latest()
        ->get();
    
    // Get completed events
    $completedEvents = AlumniPost::where('is_archived', false)
        ->where('is_completed', true)
        ->latest()
        ->get();
    
    return view('admin.eventsAdmin', compact('alumniPosts', 'completedEvents'));
}

    // Store a new alumni post
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'max_registrations' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'content' => $request->content,
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'location' => $request->location,
            'max_registrations' => $request->max_registrations,
            'user_id' => auth()->id(),
        ];

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('alumni-posts', 'public');
            $data['image_path'] = $imagePath;
        }

        AlumniPost::create($data);

        return redirect()->back()->with('success', 'Event created successfully!');
    }

    // Show a single post with comments and likes
    public function show(AlumniPost $post)
    {
        // Eager load comments, nested replies, and likes
        $post->load([
            'comments.user', 
            'comments.replies.user', 
            'comments.likes',
            'registrations.user'
        ]);

        return view('admin.events.show', compact('post'));
    }

    // Store a new comment
    public function comment(Request $request, AlumniPost $post)
    {
        $request->validate(['content' => 'required|string']);

        // Check for duplicate comment within last 30 seconds
        $recentComment = Comment::where('user_id', auth()->id())
            ->where('alumni_post_id', $post->id)
            ->where('parent_id', null)
            ->where('content', $request->content)
            ->where('created_at', '>=', now()->subSeconds(30))
            ->first();

        if ($recentComment) {
            return redirect()->back()->with('error', 'Please wait before posting the same comment again.');
        }

        Comment::create([
            'user_id' => auth()->id(),
            'alumni_post_id' => $post->id, // Make sure your comments table has alumni_post_id
            'parent_id' => null,
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Comment posted successfully!');
    }

    // Store a reply
    public function reply(Request $request, Comment $comment)
    {
        $request->validate(['content' => 'required|string']);

        // Check for duplicate reply within last 30 seconds
        $recentReply = Comment::where('user_id', auth()->id())
            ->where('parent_id', $comment->id)
            ->where('content', $request->content)
            ->where('created_at', '>=', now()->subSeconds(30))
            ->first();

        if ($recentReply) {
            return redirect()->back()->with('error', 'Please wait before posting the same reply again.');
        }

        Comment::create([
            'user_id' => auth()->id(),
            'alumni_post_id' => $comment->alumni_post_id,
            'parent_id' => $comment->id,
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Reply posted successfully!');
    }

    public function like(\App\Models\Comment $comment) {
    $userId = auth()->id();
    $like = $comment->likes()->where('user_id', $userId)->first();

    if ($like) {
        $like->delete(); // Unlike
    } else {
        $comment->likes()->create(['user_id' => $userId]);
    }

    return redirect()->back();
}

    public function likePost(\App\Models\AlumniPost $post) {
        $userId = auth()->id();
        $like = $post->likes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete(); // Unlike
        } else {
            $post->likes()->create(['user_id' => $userId]);
        }

        return redirect()->back();
    }

    public function adminShow(AlumniPost $post)
    {
        // Eager load comments, nested replies, and likes for admin view
        $post->load([
            'comments.user', 
            'comments.replies.user', 
            'comments.likes',
            'registrations.user'
        ]);

        return view('admin.events.show', compact('post'));
    }

    /**
     * Show form to edit an event
     */
    public function edit(AlumniPost $post)
    {
        return view('admin.events.edit', compact('post'));
    }

    /**
     * Update an event
     */
    public function update(Request $request, AlumniPost $post)
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'max_registrations' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'content' => $request->content,
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'location' => $request->location,
            'max_registrations' => $request->max_registrations,
        ];

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image_path && \Storage::disk('public')->exists($post->image_path)) {
                \Storage::disk('public')->delete($post->image_path);
            }
            
            $imagePath = $request->file('image')->store('alumni-posts', 'public');
            $data['image_path'] = $imagePath;
        }

        $post->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully!');
    }

    /**
     * Delete an event (soft delete/archive)
     */
    public function destroy(AlumniPost $post)
    {
        $post->update(['is_archived' => true]);
        
        return redirect()->route('admin.events.index')->with('success', 'Event archived successfully!');
    }

}
