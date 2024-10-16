<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Artwork extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'video_link',
        'video_id',
        'created_by_student_id',
        'created_by_teacher_id',
        'approved_by_teacher_id',
        'type',
        'is_approved',
        'school_id',
        'user_id',
        'category_id'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'artwork_students', 'artwork_id', 'student_id')
            ->using(ArtworkStudent::class);
    }

    public function creator()
    {
        return $this->belongsTo(Student::class, 'created_by_student_id');
    }

    public function approver()
    {
        return $this->belongsTo(Teacher::class, 'approved_by_teacher_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    use HasFactory;
}
