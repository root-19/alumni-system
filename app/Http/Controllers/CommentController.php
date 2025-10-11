<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\CommentLike;
use App\Models\AlumniPost;

class CommentController extends Controller
{
    public function store(Request $request, AlumniPost $post)
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
            return back()->with('error', 'Please wait before posting the same comment again.');
        }

        Comment::create([
            'user_id' => auth()->id(),
            'alumni_post_id' => $post->id,
            'parent_id' => null,
            'content' => $request->content
        ]);

        return back()->with('success', 'Comment posted successfully!');
    }

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
            return back()->with('error', 'Please wait before posting the same reply again.');
        }

        Comment::create([
            'user_id' => auth()->id(),
            'alumni_post_id' => $comment->alumni_post_id,
            'parent_id' => $comment->id,
            'content' => $request->content
        ]);

        return back()->with('success', 'Reply posted successfully!');
    }

    public function like(Comment $comment)
    {
        $userId = auth()->id();
        $like = $comment->likes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete(); // Unlike
        } else {
            $comment->likes()->create(['user_id' => $userId]);
        }

        return back();
    }
}
