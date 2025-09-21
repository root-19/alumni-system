<?php

namespace App\Models;
use App\Models\Like;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


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

public function registrations()
{
    return $this->hasMany(EventRegistration::class, 'alumni_post_id')->with('user');
}

public function isRegisteredBy(?User $user): bool
{
    if (!$user) return false;
    return $this->registrations->contains('user_id', $user->id);
}


}
