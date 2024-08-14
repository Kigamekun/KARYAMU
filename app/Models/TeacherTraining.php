<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherTraining extends Model
{
    protected $fillable = [
        'teacher_id',
        'training_id',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    use HasFactory;
}
