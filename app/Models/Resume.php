<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'contact_number',
        'email',
        'objective',
        'educational_attainment',
        'training_seminars',
        'work_experience',
        'file_name',
        'file_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
