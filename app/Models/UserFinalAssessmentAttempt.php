<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserFinalAssessmentAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'final_assessment_id',
        'score',
        'total_points',
        'percentage',
        'passed',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'passed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function finalAssessment()
    {
        return $this->belongsTo(FinalAssessment::class);
    }
}
