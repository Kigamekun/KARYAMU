<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'title',
        'description',

        'activity_photo',
        'trainer_teacher_id',
    ];

    public function teacherTrainings()
    {
        return $this->hasMany(TeacherTraining::class);
    }
    use HasFactory;
}
