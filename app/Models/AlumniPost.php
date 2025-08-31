<?php

namespace App\Models;
use App\Models\Like;
use Illuminate\Database\Eloquent\Model;


class AlumniPost extends Model
{
    protected $table = 'alumni_posts';
    protected $fillable = ['content', 'image_path', 'user_id'];

    public function comments() {
        return $this->hasMany(Comment::class)->with('user', 'replies', 'likes');
    }
    public function likedBy($user) {
    return $this->likes->contains('user_id', $user->id) ?? false;
}

public function likes() {
    return $this->hasMany(Like::class); // or belongsToMany if you have pivot table
}


}
