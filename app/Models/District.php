<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'master_district';

    public function city()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    use HasFactory;
}
