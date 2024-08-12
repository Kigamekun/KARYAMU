<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    protected $table = 'master_subdistrict';


    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    use HasFactory;
}
