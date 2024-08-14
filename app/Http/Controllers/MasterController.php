<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    public function kota($id)
    {
        $kota = DB::table('master_regency')->where('province_code', $id)->get();
        return response()->json($kota);
    }

    public function kecamatan($id)
    {
        $kecamatan = DB::table('master_district')->where('regency_code', $id)->get();
        return response()->json($kecamatan);
    }

    public function provinsi()
    {
        $province = DB::table('master_province')->get();
        return response()->json($province);
    }

    public function kelurahan($id)
    {
        $kelurahan = DB::table('master_subdistrict')->where('district_code', $id)->get();
        return response()->json($kelurahan);
    }

}
