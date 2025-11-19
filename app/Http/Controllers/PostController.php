<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AlumniPost;
use App\Models\Comment;

class PostController extends Controller {
    public function show(AlumniPost $post) {
        $post->load([
            'comments.user', 
            'comments.replies.user', 
            'comments.likes',
            'registrations.user'
        ]);
        return view('events.show', compact('post'));
    }
}