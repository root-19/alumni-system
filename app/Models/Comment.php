<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Comment extends Model
{
    protected $fillable = ['user_id', 'post_id', 'parent_id', 'content'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function post() {
        return $this->belongsTo(AlumniPost::class);
    }

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id')->with('user','replies','likes');
    }

    public function likes() {
        return $this->hasMany(CommentLike::class);
    }

    public function likedBy(User $user) {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
