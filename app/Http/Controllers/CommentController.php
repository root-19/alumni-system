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

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        return back();
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate(['content' => 'required|string']);

        $comment->replies()->create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        return back();
    }

    public function like(Comment $comment)
    {
        CommentLike::firstOrCreate([
            'comment_id' => $comment->id,
            'user_id'    => auth()->id()
        ]);

        return back();
    }
}
