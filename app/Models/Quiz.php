<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
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

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function userAttempts()
    {
        return $this->hasMany(UserQuizAttempt::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserQuizAnswer::class);
    }
}
