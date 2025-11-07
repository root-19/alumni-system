<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'correct_answer',
        'points',
        'order',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choices()
    {
        return $this->hasMany(QuestionChoice::class)->orderBy('choice_letter');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserQuizAnswer::class);
    }
}
