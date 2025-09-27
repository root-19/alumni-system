<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModuleProgress extends Model
{
    protected $fillable = [
        'user_id', 
        'training_file_id', 
        'scroll_progress', 
        'time_spent', 
        'completion_percentage', 
        'is_completed',
        'last_accessed_at'
    ];

    protected $casts = [
        'last_accessed_at' => 'datetime',
        'is_completed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingFile()
    {
        return $this->belongsTo(TrainingFile::class);
    }
}
