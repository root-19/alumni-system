<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingRead extends Model
{
    protected $fillable = ['user_id', 'training_file_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(TrainingFile::class, 'training_file_id');
    }
}
