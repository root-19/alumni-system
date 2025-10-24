<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'alumni_post_id',
        'rating',
        'comment',
        'is_approved'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    /**
     * Get the user that wrote the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event (alumni post) that this review belongs to.
     */
    public function alumniPost(): BelongsTo
    {
        return $this->belongsTo(AlumniPost::class);
    }

    /**
     * Scope to get only approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get reviews by rating.
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Get the average rating for an event.
     */
    public static function getAverageRatingForEvent($alumniPostId)
    {
        return static::where('alumni_post_id', $alumniPostId)
            ->where('is_approved', true)
            ->avg('rating');
    }

    /**
     * Get the total number of reviews for an event.
     */
    public static function getReviewCountForEvent($alumniPostId)
    {
        return static::where('alumni_post_id', $alumniPostId)
            ->where('is_approved', true)
            ->count();
    }
}
