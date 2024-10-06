<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherTraining extends Model
{
    protected $fillable = ['teacher_id', 'training_id', 'role', 'influenced_by','original_training_id','parent_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    public function influencedBy()
    {
        return $this->belongsTo(Teacher::class, 'influenced_by');
    }
}
