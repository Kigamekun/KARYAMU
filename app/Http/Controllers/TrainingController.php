<?php

namespace App\Http\Controllers;

use App\Models\TeacherTraining;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;

use Illuminate\Support\Facades\Crypt;


class TrainingController extends Controller
{
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data = Training::where('id', $id)->first();
        $members = TeacherTraining::where('training_id', $id)->get()->pluck('teacher_id');
        return response()->json([
            'data' => $data,
            'members' => $members
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Training::join('teachers as trainer', 'trainer.id', '=', 'trainings.trainer_teacher_id')
                ->leftJoin('schools', 'schools.id', '=', 'trainer.school_id')
                ->leftJoin('teacher_trainings', 'teacher_trainings.training_id', '=', 'trainings.id')
                ->select(
                    'trainings.id',
                    'trainings.title',
                    'trainings.description',
                    'trainings.activity_photo',
                    'trainer.name as trainer_name',
                    'schools.name as trainer_school',
                    DB::raw('COUNT(teacher_trainings.teacher_id) as total_participants')
                )
                ->groupBy('trainings.id','trainings.title','trainings.description','trainings.activity_photo', 'schools.name','trainer.name', 'schools.name')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = Crypt::encrypt($row->id);

                    $btn = '
                        <div class="d-flex" style="gap:5px;">
                            <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-info" data-toggle="modal" data-target="#detailData"
                            data-id="' . $id . '" >
                                Detail
                            </button>
                            <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#updateData"
                            data-url="' . route('pelatihan.update', ['id' => $id]) . '"
                            data-id="' . $id . '"
                            data-title="' . $row->title . '"
                            data-description="' . $row->description . '" data-activity_photo="' . asset('storage/activity_photo/' . $row->activity_photo) . '">
                                Edit
                            </button>
                            <form id="deleteForm" action="' . route('pelatihan.delete', ['id' => $id]) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                                <button type="button" title="DELETE" class="btn btn-sm btn-primary btn-delete" onclick="confirmDelete(event)">
                                    Delete
                                </button>
                            </form>
                        </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $members = Teacher::all();
        return view('admin.training', [
            'data' => Training::all(),
            'members' => $members
        ]);
    }

    public function store(Request $request)
    {
        Validator::validate($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'activity_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1048',
            'members.*' => 'required',
            'members' => 'required',
        ]);

        if(Teacher::whereIn('id', $request->members)->count() != count($request->members)){
            return redirect()->back()->with(['message' => 'Data guru tidak ada', 'status' => 'danger']);
        }

        $teacher_id = auth()->user()->teacher->id;

        $file = $request->file('activity_photo');
        $thumbname = time() . '-' . $file->getClientOriginalName();
        Storage::disk('public')->put('activity_photo/' . $thumbname, file_get_contents($file));

        $training = Training::create([
            'title' => $request->title,
            'description' => $request->description,
            'trainer_teacher_id' => $teacher_id,
            'activity_photo' => $thumbname
        ]);

        foreach ($request->members as $member) {
            TeacherTraining::create([
                'training_id' => $training->id,
                'teacher_id' => $member
            ]);
        }

        return redirect()->back()->with(['message' => 'Training berhasil ditambahkan', 'status' => 'success']);
    }

    public function detail($id)
    {
        $id = Crypt::decrypt($id);

        $data = Training::where('id', $id)->first()->toArray();

        $members = TeacherTraining::where('training_id', $id)->get()->map(function ($item) {
            return [
                'name' => $item->teacher->name,
                'school' => $item->teacher->school->name
            ];
        });
        return response()->json([
            'data' => $data,
            'members' => $members
        ]);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        Validator::validate($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'members.*' => 'required',
            'members' => 'required',
        ]);

        if(Teacher::whereIn('id', $request->members)->count() != count($request->members)){
            return redirect()->back()->with(['message' => 'Data guru tidak ada', 'status' => 'danger']);
        }

        $training = Training::where('id', $id)->first();
        $thumbname = $training->activity_photo;

        if ($request->hasFile('activity_photo')) {
            $request->validate([
                'activity_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1048',
            ]);
            $file = $request->file('activity_photo');
            $thumbname = time() . '-' . $file->getClientOriginalName();
            Storage::disk('public')->put('activity_photo/' . $thumbname, file_get_contents($file));
        }

        Training::where('id', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'activity_photo' => $thumbname
        ]);

        TeacherTraining::where('training_id', $id)->delete();
        foreach ($request->members as $member) {
            TeacherTraining::create([
                'training_id' => $id,
                'teacher_id' => $member
            ]);
        }

        return redirect()->back()->with(['message' => 'Training berhasil di update', 'status' => 'success']);
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);

        Training::where('id', $id)->delete();
        return redirect()->route('pelatihan.index')->with(['message' => 'Training berhasil di delete', 'status' => 'success']);
    }
}
