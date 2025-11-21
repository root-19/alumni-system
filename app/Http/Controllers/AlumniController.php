<?php

namespace App\Http\Controllers;

use App\Models\AlumniPost;
use App\Models\Comment;
use Illuminate\Http\Request;

class AlumniController extends Controller
{

    public function index()
{
    // Reset events that are incorrectly marked as completed but their event_date hasn't passed yet
    AlumniPost::where('is_archived', false)
        ->where('is_completed', true)
        ->whereNotNull('event_date')
        ->where('event_date', '>=', now()->startOfDay())
        ->update(['is_completed' => false]);
    
    // Automatically mark events as completed only if their event_date has completely passed (after end of event day)
    AlumniPost::where('is_archived', false)
        ->where('is_completed', false)
        ->whereNotNull('event_date')
        ->where('event_date', '<', now()->startOfDay())
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
    // Reset events that are incorrectly marked as completed but their event_date hasn't passed yet
    AlumniPost::where('is_archived', false)
        ->where('is_completed', true)
        ->whereNotNull('event_date')
        ->where('event_date', '>=', now()->startOfDay())
        ->update(['is_completed' => false]);
    
    // Automatically mark events as completed only if their event_date has completely passed (after end of event day)
    AlumniPost::where('is_archived', false)
        ->where('is_completed', false)
        ->whereNotNull('event_date')
        ->where('event_date', '<', now()->startOfDay())
        ->update(['is_completed' => true]);
    
    // Get active (not completed) events with pagination
    $alumniPosts = AlumniPost::where('is_archived', false)
        ->where('is_completed', false)
        ->latest()
        ->paginate(10, ['*'], 'active_page');
    
    // Get completed events with pagination
    $completedEvents = AlumniPost::where('is_archived', false)
        ->where('is_completed', true)
        ->latest()
        ->paginate(10, ['*'], 'completed_page');
    
    return view('admin.eventsAdmin', compact('alumniPosts', 'completedEvents'));
}

    // Store a new alumni post
    public function store(Request $request)
    {
        try {
            // DEBUG: Check request data
            \Log::info('Alumni Post Store Request:', [
                'has_image' => $request->hasFile('image'),
                'filesystem_default' => config('filesystems.default'),
                'title' => $request->title,
                'content_length' => strlen($request->content ?? ''),
            ]);

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
                'is_archived' => false, // Explicitly set to false when creating
                'is_completed' => false, // Explicitly set to false when creating
            ];

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                try {
                    // Check if S3 is configured
                    $s3Bucket = env('AWS_BUCKET');
                    $s3Key = env('AWS_ACCESS_KEY_ID');
                    
                    if ($s3Bucket && $s3Key) {
                        // Store in S3
                        \Log::info('Storing event image to S3');
                        $imagePath = $request->file('image')->store('alumni-posts', 's3');
                        $data['image_path'] = $imagePath;
                        \Log::info('Event image stored successfully to S3:', [
                            'path' => $imagePath,
                            'disk' => 's3',
                        ]);
                    } else {
                        // Fallback to local storage
                        \Log::info('S3 not configured, storing event image to local storage');
                        $imagePath = $request->file('image')->store('alumni-posts', 'public');
                        $data['image_path'] = $imagePath;
                        \Log::info('Event image stored successfully to local storage:', [
                            'path' => $imagePath,
                            'disk' => 'public',
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error storing event image: ' . $e->getMessage());
                    \Log::error('Stack trace: ' . $e->getTraceAsString());
                    return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage())->withInput();
                }
            }

            $alumniPost = AlumniPost::create($data);

            \Log::info('Alumni Post created successfully:', [
                'id' => $alumniPost->id,
                'image_path' => $alumniPost->image_path,
                'is_archived' => $alumniPost->is_archived,
            ]);

            // Redirect to admin events page if accessed from admin, otherwise redirect back
            if (request()->is('admin/*') || str_contains(request()->header('referer', ''), '/admin/')) {
                return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
            }
            
            return redirect()->back()->with('success', 'Event created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing alumni post: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'An error occurred while creating the event. Please try again.')->withInput();
        }
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
            'is_completed' => false, // Explicitly set to false when updating
        ];

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Check if S3 is configured
            $s3Bucket = env('AWS_BUCKET');
            $s3Key = env('AWS_ACCESS_KEY_ID');
            
            if ($s3Bucket && $s3Key) {
                // Delete old image from S3 if exists
                if ($post->image_path) {
                    try {
                        if (\Storage::disk('s3')->exists($post->image_path)) {
                            \Storage::disk('s3')->delete($post->image_path);
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Could not delete old image from S3: ' . $e->getMessage());
                    }
                }
                
                // Store in S3
                $imagePath = $request->file('image')->store('alumni-posts', 's3');
                $data['image_path'] = $imagePath;
            } else {
                // Delete old image from local storage if exists
                if ($post->image_path && \Storage::disk('public')->exists($post->image_path)) {
                    \Storage::disk('public')->delete($post->image_path);
                }
                
                // Store in local storage
                $imagePath = $request->file('image')->store('alumni-posts', 'public');
                $data['image_path'] = $imagePath;
            }
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
