<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'subdistrict_code',
        'province_code',

        'district_code',
        'regency_code',
        'subdistrict_code',
        'npsn',
        'status'
    ];

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id');
    }
    use HasFactory;
}
