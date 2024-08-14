<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $fillable = [
        'name',
        'nis',
        'phone',
        'address',
        'school_id',
        'user_id',
    ];
    public function artworks()
    {
        return $this->belongsToMany(Artwork::class, 'artwork_students', 'student_id', 'artwork_id')
            ->using(ArtworkStudent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    use HasFactory;
}
