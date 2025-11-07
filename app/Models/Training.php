<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'certificate_path',
    'progress',
    ];

    public function files()
    {
        return $this->hasMany(TrainingFile::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserTrainingProgress::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function finalAssessments()
    {
        return $this->hasMany(FinalAssessment::class);
    }
}
