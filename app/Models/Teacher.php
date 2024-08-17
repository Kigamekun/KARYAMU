<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    protected $fillable = [
        'name',
        'nip',
        'phone',
        'address',
        'school_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_trainings')
            ->withPivot('role', 'influenced_by')
            ->withTimestamps();
    }

    use HasFactory;
}
