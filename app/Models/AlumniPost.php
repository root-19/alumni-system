<?php

namespace App\Models;
use App\Models\Like;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Review;


class AlumniPost extends Model
{
    protected $table = 'alumni_posts';
    protected $fillable = ['content', 'title', 'description', 'event_date', 'location', 'image_path', 'user_id', 'is_archived', 'is_completed', 'max_registrations'];

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

public function eventRegistrations()
{
    return $this->hasMany(EventRegistration::class, 'alumni_post_id');
}

public function attendances()
{
    return $this->hasMany(Attendance::class, 'alumni_post_id')->with('user');
}

public function reviews()
{
    return $this->hasMany(Review::class, 'alumni_post_id')->with('user');
}

public function approvedReviews()
{
    return $this->hasMany(Review::class, 'alumni_post_id')->where('is_approved', true)->with('user');
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function isRegisteredBy(?User $user): bool
{
    if (!$user) return false;
    return $this->registrations->contains('user_id', $user->id);
}

public function isAttending(?User $user): bool
{
    if (!$user) return false;
    return $this->attendances()->where('user_id', $user->id)->where('status', 'attending')->exists();
}

public function isFull(): bool
{
    if ($this->max_registrations === null) {
        return false; // No limit set
    }
    return $this->registrations->count() >= $this->max_registrations;
}

public function getAvailableSlots(): ?int
{
    if ($this->max_registrations === null) {
        return null; // No limit set
    }
    $registered = $this->registrations->count();
    return max(0, $this->max_registrations - $registered);
}


}
