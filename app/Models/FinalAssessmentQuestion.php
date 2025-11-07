<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinalAssessmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'final_assessment_id',
        'question_text',
        'correct_answer',
        'points',
        'order',
    ];

    public function finalAssessment()
    {
        return $this->belongsTo(FinalAssessment::class);
    }

    public function choices()
    {
        return $this->hasMany(FinalAssessmentChoice::class, 'question_id')->orderBy('choice_letter');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserFinalAssessmentAnswer::class);
    }
}
