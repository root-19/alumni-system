<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinalAssessmentChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'choice_letter',
        'choice_text',
    ];

    public function question()
    {
        return $this->belongsTo(FinalAssessmentQuestion::class, 'question_id');
    }
}
