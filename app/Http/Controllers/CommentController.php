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

        Comment::create([
            'user_id' => auth()->id(),
            'alumni_post_id' => $post->id,
            'parent_id' => null,
            'content' => $request->content
        ]);

        return back();
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate(['content' => 'required|string']);

        Comment::create([
            'user_id' => auth()->id(),
            'alumni_post_id' => $comment->alumni_post_id,
            'parent_id' => $comment->id,
            'content' => $request->content
        ]);

        return back();
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
