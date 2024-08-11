<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = School::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                        <div class="d-flex" style="gap:5px;">
                            <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#updateData"
                            data-url="' . route('sekolah.update', ['id' => $row->id]) . '"
                            data-id="' . $row->id . '" data-name="' . $row->name . '" data-image="' . asset('storage/School/' . $row->image) . '">
                                Edit
                            </button>
                            <form id="deleteForm" action="' . route('sekolah.delete', ['id' => $row->id]) . '" method="POST">
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

    public function store(Request $request)
    {
        Validator::validate($request->all(), [
            'name' => 'required',
            'image' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $thumbname = time() . '-' . $file->getClientOriginalName();
            Storage::disk('public')->put('School/' . $thumbname, file_get_contents($file));
            School::insert([
                'image' => $thumbname,
                'name' => $request->name,
            ]);
        } else {
            School::insert([
                'name' => $request->name,
            ]);
        }

        return redirect()->back()->with(['message' => 'School berhasil ditambahkan', 'status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $thumbname = time() . '-' . $file->getClientOriginalName();
            Storage::disk('public')->put('School/' . $thumbname, file_get_contents($file));
            School::where('id', $id)->update([
                'image' => $thumbname,
                'name' => $request->name,
            ]);
        } else {
            School::where('id', $id)->update([
                'name' => $request->name,
            ]);
        }
        return redirect()->back()->with(['message' => 'School berhasil di update', 'status' => 'success']);
    }

    public function destroy($id)
    {
        School::where('id', $id)->delete();
        return redirect()->route('School.index')->with(['message' => 'School berhasil di delete', 'status' => 'success']);
    }
}
