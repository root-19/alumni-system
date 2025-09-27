<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTrainingProgress extends Model
{
    protected $fillable = ['user_id', 'training_id', 'progress'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}
