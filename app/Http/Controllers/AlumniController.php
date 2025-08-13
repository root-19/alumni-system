<?php

namespace App\Http\Controllers;

use App\Models\AlumniPost;
use App\Models\Comment;
use Illuminate\Http\Request;

class AlumniController extends Controller
{

    public function index()
{
    $alumniPosts = AlumniPost::latest()->get();
    return view('admin.eventsAdmin', compact('alumniPosts'));
}

    // Show a single post with comments and likes
    public function show(AlumniPost $post)
    {
        // Eager load comments, nested replies, and likes
        $post->load([
            'comments.user', 
            'comments.replies.user', 
            'comments.likes'
        ]);

        return view('admin.events.show', compact('post'));
    }

    // Store a new comment
    public function comment(Request $request, AlumniPost $post)
    {
        $request->validate(['content' => 'required|string']);

        Comment::create([
            'user_id' => auth()->id(),
            'alumni_post_id' => $post->id, // Make sure your comments table has alumni_post_id
            'parent_id' => null,
            'content' => $request->content
        ]);

        return redirect()->back();
    }

    // Store a reply
    public function reply(Request $request, Comment $comment)
    {
        $request->validate(['content' => 'required|string']);

        Comment::create([
            'user_id' => auth()->id(),
            'alumni_post_id' => $comment->alumni_post_id,
            'parent_id' => $comment->id,
            'content' => $request->content
        ]);

        return redirect()->back();
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

}
