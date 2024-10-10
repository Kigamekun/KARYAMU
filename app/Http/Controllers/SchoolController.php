<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class SchoolController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = School::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = Crypt::encrypt($row->id);

                    $btn = '
                        <div class="d-flex" style="gap:5px;">
                            <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#updateData"
                            data-url="' . route('sekolah.update', ['id' => $id]) . '"
                            data-id="' . $id . '" data-npsn="' . $row->npsn . '" data-name="' . $row->name . '" data-address="' . $row->address . '" data-phone="' . $row->phone . '" data-email="' . $row->email . '" data-name="' . $row->name . '">
                                Edit
                            </button>
                            <form id="deleteForm" action="' . route('sekolah.delete', ['id' => $id]) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                                <button type="button" title="DELETE" class="btn btn-sm btn-primary btn-delete" onclick="confirmDelete(event)">
                                    Delete
                                </button>
                            </form>
                        </div>';
                    return $btn;
                })
                ->addColumn('img', function ($row) {
                    $img = '<img src="' . asset('storage/School/' . $row->image) . '" alt="' . $row->name . '" class="img-thumbnail" style="width: 40px; height: 40px;">';
                    return $img;
                })
                ->addIndexColumn()
                ->rawColumns(['img', 'action'])
                ->toJson();
        }
        return view('admin.school', [
            'data' => School::limit(5)->get(),
        ]);
    }

    public function edit($id)
    {
        $ids = Crypt::decrypt($id);
        $data = School::where('id', $ids)->first();

        if ((is_null($data->district_code) || is_null($data->province_code) || is_null($data->regency_code) || is_null($data->subdistrict_code))) {
            return view('admin.school-edit', [
                'data' => $data,
                'id' => $id,
            ]);
        } else {
            return redirect()->route('dashboard');
        }
    }


    public function detail($id)
    {
        $id = Crypt::decrypt($id);
        $data = School::where('id', $id)->first()->toArray();
        $kelurahan = DB::table('master_subdistrict')->where('code', $data['subdistrict_code'])->first();
        $data['kelurahan'] = $kelurahan;
        $data['kecamatan'] = DB::table('master_district')->where('code', $kelurahan->district_code)->first();
        $data['kabupaten'] = DB::table('master_regency')->where('code', $data['kecamatan']->regency_code)->first();
        $data['provinsi'] = DB::table('master_province')->where('code', $data['kabupaten']->province_code)->first();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        Validator::validate($request->all(), [
            'npsn' => 'required',
            'name' => 'required',
            'status' => 'required',
            'dp_kelurahan' => 'required',
            'dp_provinsi' => 'required',

        ]);


        School::create([
            'npsn' => $request->npsn,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'subdistrict_code' => $request->dp_kelurahan,
            'province_code' => $request->dp_provinsi,
            'district_code' => $request->dp_kecamatan,
            'regency_code' => $request->dp_kota,
            'status' => $request->status,


        ]);

        return redirect()->back()->with(['message' => 'School berhasil ditambahkan', 'status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        Validator::validate($request->all(), [
            'npsn' => 'required',
            'name' => 'required',
            'dp_kelurahan' => 'required',
            'dp_provinsi' => 'required',
        ]);

        School::where('id', $id)->update([
            'npsn' => $request->npsn,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status,
            'subdistrict_code' => $request->dp_kelurahan,
            'province_code' => $request->dp_provinsi,
            'district_code' => $request->dp_kecamatan,
            'regency_code' => $request->dp_kota,

        ]);

        return redirect()->back()->with(['message' => 'School berhasil di update', 'status' => 'success']);
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);

        School::where('id', $id)->delete();
        return redirect()->route('sekolah.index')->with(['message' => 'School berhasil di delete', 'status' => 'success']);
    }

    public function registerSekolah(Request $request)
    {
        if ($request->method() == 'POST') {


            $kecamatan = DB::table('master_district')->where('code', $request->dp_kecamatan)->first()->name;
            $provinsi = DB::table('master_province')->where('code', $request->dp_provinsi)->first()->name;
            $kota = DB::table('master_regency')->where('code', $request->dp_kota)->first()->name;
            $kelurahan = DB::table('master_subdistrict')->where('code', $request->dp_kelurahan)->first()->name;


            $data = [
                'npsn' => $request->npsn,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'dp_kelurahan' => $kelurahan,
                'dp_provinsi' => $provinsi,
                'dp_kecamatan' => $kecamatan,
                'dp_kota' => $kota,
                'status' => $request->status,
            ];

            $adminEmail = 'helpdeskgencerling@gmail.com'; // Ganti dengan email admin
            Mail::send('mail.sekolah-registrasi', $data, function ($message) use ($adminEmail) {
                $message->to($adminEmail)
                    ->from('gencerling@gmail.com', 'Information')  // Menambahkan nama pengirim
                    ->subject('Registrasi Sekolah Baru - Perhatian Diperlukan');
            });
            return redirect()->back()->with(['message' => 'Registrasi Sekolah berhasil dikirim', 'status' => 'success']);
        }
        return view('admin.registrasi-sekolah');
    }

}
