<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'alumni_post_id',
        'user_id',
        'title',
        'description',
        'status',
        'checked_in_at'
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    /**
     * Get the alumni post (event) that this attendance record belongs to.
     */
    public function alumniPost(): BelongsTo
    {
        return $this->belongsTo(AlumniPost::class);
    }

    /**
     * Get the user that this attendance record belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only attending records.
     */
    public function scopeAttending($query)
    {
        return $query->where('status', 'attending');
    }

    /**
     * Scope to get only not attending records.
     */
    public function scopeNotAttending($query)
    {
        return $query->where('status', 'not_attending');
    }

    /**
     * Scope to get only maybe attending records.
     */
    public function scopeMaybe($query)
    {
        return $query->where('status', 'maybe');
    }

    /**
     * Scope to get checked-in records.
     */
    public function scopeCheckedIn($query)
    {
        return $query->whereNotNull('checked_in_at');
    }
}