<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{    
    protected $table = 'alumni_posts';
    protected $fillable = ['content', 'image_path'];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}
