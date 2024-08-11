<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ArtworkStudent extends Pivot
{
    protected $fillable = [
        'artwork_id',
        'student_id',
    ];
    use HasFactory;
}
