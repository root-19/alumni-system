<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'path',
        'original_name',
        'mime_type',
        'type',
        'certificate_path',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}
