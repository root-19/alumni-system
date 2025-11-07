<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserFinalAssessmentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'final_assessment_id',
        'question_id',
        'selected_answer',
        'is_correct',
        'points_earned',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function finalAssessment()
    {
        return $this->belongsTo(FinalAssessment::class);
    }

    public function question()
    {
        return $this->belongsTo(FinalAssessmentQuestion::class, 'question_id');
    }
}
