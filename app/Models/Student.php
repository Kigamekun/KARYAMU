<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class, 'artwork_students', 'student_id', 'artwork_id')
            ->using(ArtworkStudent::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    use HasFactory;
}
