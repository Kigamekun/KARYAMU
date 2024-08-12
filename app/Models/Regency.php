<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $table = 'master_regency';


    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    use HasFactory;
}
