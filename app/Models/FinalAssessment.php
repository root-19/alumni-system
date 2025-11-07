<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinalAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'title',
        'description',
        'passing_score',
        'time_limit',
        'is_active',
    ];

    public function questions()
    {
        return $this->hasMany(FinalAssessmentQuestion::class)->orderBy('order');
    }

    public function userAttempts()
    {
        return $this->hasMany(UserFinalAssessmentAttempt::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserFinalAssessmentAnswer::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}
