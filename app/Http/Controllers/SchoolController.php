<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;


class SchoolController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = School::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = Crypt::encrypt($row->id);

                    $btn = '
                        <div class="d-flex" style="gap:5px;">
                            <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#updateData"
                            data-url="' . route('sekolah.update', ['id' => $id]) . '"
                            data-id="' . $id . '" data-name="' . $row->name . '" data-address="' . $row->address . '" data-phone="' . $row->phone . '" data-email="' . $row->email . '" data-name="' . $row->name . '">
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
                ->rawColumns(['action', 'img'])
                ->make(true);
        }
        return view('admin.School', [
            'data' => School::all()
        ]);
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
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'dp_kelurahan' => 'required',

        ]);


        School::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'subdistrict_code' => $request->dp_kelurahan,
        ]);

        return redirect()->back()->with(['message' => 'School berhasil ditambahkan', 'status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        Validator::validate($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'dp_kelurahan' => 'required',
        ]);

        School::where('id', $id)->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'subdistrict_code' => $request->dp_kelurahan,
        ]);

        return redirect()->back()->with(['message' => 'School berhasil di update', 'status' => 'success']);
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);

        School::where('id', $id)->delete();
        return redirect()->route('sekolah.index')->with(['message' => 'School berhasil di delete', 'status' => 'success']);
    }
}
