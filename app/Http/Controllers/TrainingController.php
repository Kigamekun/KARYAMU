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
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Crypt;


class TrainingController extends Controller
{
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data = Training::where('id', $id)->first();

        $members = Teacher::leftJoin('teacher_trainings', 'teachers.id', '=', 'teacher_trainings.teacher_id')
            ->select('teachers.id', 'teachers.name')
            ->where('teacher_trainings.training_id', $id)
            ->where('teacher_trainings.role', 'participant')
            ->get();

        return response()->json([
            'data' => $data,
            'members' => $members
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->role == 'admin') {
                $data = Training::join('teachers as trainer', 'trainer.id', '=', 'trainings.trainer_teacher_id')
                    ->leftJoin('schools', 'schools.id', '=', 'trainer.school_id')
                    ->leftJoin('teacher_trainings', 'teacher_trainings.training_id', '=', 'trainings.id')
                    ->leftJoin('teachers as participant', 'participant.id', '=', 'teacher_trainings.teacher_id')
                    ->leftJoin('teachers', 'teachers.id', '=', 'trainings.trainer_teacher_id') // Join ke tabel users
                    ->select(
                        'trainings.id',
                        'trainings.description',
                        'trainings.activity_photo',
                        'trainings.trainer_teacher_id as trainer_teacher_id',
                        'trainer.name as trainer_name',
                        'schools.name as trainer_school',
                        'teachers.name as trainer_teacher_name', // Mendapatkan nama dari tabel users
                        DB::raw('COUNT(teacher_trainings.teacher_id) - 1 as total_participants')
                    )
                    ->groupBy(
                        'trainings.id',
                        'trainings.description',
                        'trainings.trainer_teacher_id',
                        'trainings.activity_photo',
                        'trainer.name',
                        'schools.name',
                        'teachers.name' // Tambahkan 'users.name' ke dalam groupBy
                    )
                    ->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $id = Crypt::encrypt($row->id);

                        $btn = '
                            <div class="d-flex" style="gap:5px;">
                            <a href="' . route('pelatihan.detail-imbas', ['id' => $id]) . '" class="btn btn-sm btn-success"
                                >
                                    Lihat Imbas
                                </a>

                                <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-info" data-toggle="modal" data-target="#detailData"
                                data-id="' . $id . '" >
                                    Detail
                                </button>
                                <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#updateData"
                                data-url="' . route('pelatihan.update', ['id' => $id]) . '"
                                data-id="' . $id . '"
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
                    ->addColumn('jumlah_terimbas', function ($row) {

                        $maxLevel = 1; // Initialize the max level

                        $impactedTeachers = $this->getImpactedTeachers($row->trainer_teacher_id, 1, $maxLevel, $row->id);

                        return count($impactedTeachers);
                    })
                    ->addColumn('role', function ($row) {
                        return $row->role == 'instructor' ? '<div class="badge badge-success">Instruktur</div>' : '<div class="badge badge-primary">Peserta</div>';
                    })
                    ->rawColumns(['action', 'role'])
                    ->make(true);

            } else {
                $teacherId = auth()->user()->teacher->id;
                $data = Training::join('teachers as trainer', 'trainer.id', '=', 'trainings.trainer_teacher_id')
                    ->leftJoin('schools', 'schools.id', '=', 'trainer.school_id')
                    ->leftJoin('teacher_trainings', 'teacher_trainings.training_id', '=', 'trainings.id')
                    ->leftJoin('teachers as participant', 'participant.id', '=', 'teacher_trainings.teacher_id')
                    ->leftJoin('teachers', 'teachers.id', '=', 'trainings.trainer_teacher_id')
                    ->select(
                        'trainings.id',
                        'trainings.description',
                        'trainings.activity_photo',
                        'trainings.trainer_teacher_id as trainer_teacher_id',
                        'trainer.name as trainer_name',
                        'schools.name as trainer_school',
                        'teachers.name as trainer_teacher_name',
                        DB::raw('(SELECT COUNT(tt.teacher_id) FROM teacher_trainings tt WHERE tt.training_id = trainings.id) - 1 as total_participants'),
                        DB::raw('IF(teacher_trainings.role = "instructor", "instructor", "participant") as role')
                    )
                    ->where('teacher_trainings.teacher_id', '=', $teacherId)
                    ->groupBy(
                        'trainings.id',
                        'trainings.description',
                        'trainings.activity_photo',
                        'trainings.trainer_teacher_id',
                        'trainer.name',
                        'schools.name',
                        'teacher_trainings.role',
                        'teachers.name'
                    )
                    ->get();


                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $id = Crypt::encrypt($row->id);

                        if ($row->role == 'instructor') {
                            $btn = '
                            <div class="d-flex" style="gap:5px;">
                            <a href="' . route('pelatihan.detail-imbas', ['id' => $id]) . '" class="btn btn-sm btn-success"
                                >
                                    Lihat Imbas
                                </a>

                                <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-info" data-toggle="modal" data-target="#detailData"
                                data-id="' . $id . '" >
                                    Detail
                                </button>
                                <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#updateData"
                                data-url="' . route('pelatihan.update', ['id' => $id]) . '"
                                data-id="' . $id . '"
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
                        } else {
                            $btn = '
                            <div class="d-flex" style="gap:5px;">
                                <button type="button" title="Detail" class="btn btn-sm btn-warning btn-info" data-toggle="modal" data-target="#detailData"
                                data-id="' . $id . '" >
                                    Detail
                                </button>
                            </div>';

                        }
                        return $btn;
                    })
                    ->addColumn('role', function ($row) {
                        return $row->role == 'instructor' ? '<div class="badge badge-success">Instruktur</div>' : '<div class="badge badge-primary">Peserta</div>';
                    })
                    ->addColumn('jumlah_terimbas', function ($row) {

                        $maxLevel = 1; // Initialize the max level

                        $impactedTeachers = $this->getImpactedTeachers($row->trainer_teacher_id, 1, $maxLevel, $row->id);

                        return count($impactedTeachers);
                    })
                    ->rawColumns(['action', 'role'])
                    ->make(true);
            }

        }

        $members = Teacher::leftJoin('teacher_trainings', 'teachers.id', '=', 'teacher_trainings.teacher_id')
            ->whereNull('teacher_trainings.teacher_id')
            ->select('teachers.id', 'teachers.name')
            ->get();

        return view('admin.training', [
            'data' => Training::all(),
            'members' => $members
        ]);
    }

    public function store(Request $request)
    {
        Validator::validate($request->all(), [
            'description' => 'required',
            'activity_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1048',
            'members.*' => 'required',
            'members' => 'required',
        ]);

        if (Teacher::whereIn('id', $request->members)->count() != count($request->members)) {
            return redirect()->back()->with(['message' => 'Data guru tidak ada', 'status' => 'error']);
        }

        // check if members is are have a pelatihan
        foreach ($request->members as $member) {
            if (TeacherTraining::where('teacher_id', $member)->exists()) {
                return redirect()->back()->with(['message' => 'Data guru sudah memiliki pelatihan', 'status' => 'error']);
            }
        }

        if (Auth::user()->role == 'teacher') {
            $teacher_id = auth()->user()->teacher->id;
        } else {
            $teacher_id = $request->creator;
        }


        if (!is_null($teach = TeacherTraining::where('teacher_id', $teacher_id)->whereIn('role', ['participant', 'instructor'])->first()) || Auth::user()->role == 'admin') {

            if ($request->hasFile('activity_photo')) {
                $request->validate([
                    'activity_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1048',
                ]);
            }

            $file = $request->file('activity_photo');
            $thumbname = time() . '-' . $file->getClientOriginalName();
            Storage::disk('public')->put('activity_photo/' . $thumbname, file_get_contents($file));

            $training = Training::create([
                'description' => $request->description,
                'trainer_teacher_id' => $teacher_id,
                'activity_photo' => $thumbname
            ]);

            TeacherTraining::create([
                'training_id' => $training->id,
                'teacher_id' => $teacher_id,
                'role' => 'instructor',
                'original_training_id' => !is_null($teach)
                    ? (!is_null($teach->original_training_id) ? $teach->original_training_id : $teach->training_id)
                    : null,
                'parent_id' => !is_null($teach) ? $teach->training_id : null
            ]);

            foreach ($request->members as $member) {
                TeacherTraining::create([
                    'training_id' => $training->id,
                    'teacher_id' => $member,
                    'role' => 'participant',
                    'influenced_by' => $teacher_id,
                    'original_training_id' => !is_null($teach)
                        ? (!is_null($teach->original_training_id) ? $teach->original_training_id : $teach->training_id)
                        : null,
                    'parent_id' => !is_null($teach) ? $teach->training_id : null
                ]);
            }

            return redirect()->back()->with(['message' => 'Training berhasil ditambahkan', 'status' => 'success']);
        } else {
            return redirect()->back()->with(['message' => 'Guru tidak memiliki pelatihan', 'status' => 'error']);
        }
    }


    public function detail($id)
    {
        $id = Crypt::decrypt($id);

        $data = Training::where('id', $id)->first()->toArray();

        $members = TeacherTraining::where('training_id', $id)->where('role', 'participant')->get()->map(function ($item) {
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
            'description' => 'required',
            'activity_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1048',
            'members.*' => 'required',
            'members' => 'required',
        ]);

        if (Teacher::whereIn('id', $request->members)->count() != count($request->members)) {
            return redirect()->back()->with(['message' => 'Data guru tidak ada', 'status' => 'error']);
        }

        if (Auth::user()->role == 'teacher') {
            $teacher_id = auth()->user()->teacher->id;
        } else {
            $training = Training::where('id', $id)->first();
            $teacher_id = $training->trainer_teacher_id;
        }

        if (!is_null($teach = TeacherTraining::where('teacher_id', $teacher_id)->where('role', 'participant')->first())) {

            $training = Training::where('id', $id)->first();

            if ($request->hasFile('activity_photo')) {
                $request->validate([
                    'activity_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1048',
                ]);

                $file = $request->file('activity_photo');
                $thumbname = time() . '-' . $file->getClientOriginalName();
                Storage::disk('public')->put('activity_photo/' . $thumbname, file_get_contents($file));

                $training->update([
                    'description' => $request->description,
                    'activity_photo' => $thumbname
                ]);
            } else {
                $training->update([
                    'description' => $request->description,
                ]);
            }

            TeacherTraining::where('training_id', $id)->delete();

            TeacherTraining::create([
                'training_id' => $id,
                'teacher_id' => $teacher_id,
                'role' => 'instructor',
                'original_training_id' => $teach->original_training_id
            ]);

            foreach ($request->members as $member) {
                TeacherTraining::create([
                    'training_id' => $id,
                    'teacher_id' => $member,
                    'role' => 'participant',
                    'influenced_by' => $teacher_id,
                    'original_training_id' => $teach->original_training_id
                ]);
            }
        }

        return redirect()->back()->with(['message' => 'Training berhasil di update', 'status' => 'success']);
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);

        Training::where('id', $id)->delete();
        return redirect()->route('pelatihan.index')->with(['message' => 'Training berhasil di delete', 'status' => 'success']);
    }

    public function addTraining(Request $request)
    {
        $training = Training::create($request->only(['title', 'description', 'date']));

        foreach ($request->input('participants') as $participant) {
            TeacherTraining::create([
                'teacher_id' => $participant['teacher_id'],
                'training_id' => $training->id,
                'role' => $participant['role'],
                'influenced_by' => $participant['influenced_by'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Training added successfully']);
    }

    // Method untuk menampilkan imbas dari seorang guru
    public function showImpact($teacherId)
    {
        $impactedTeachers = $this->getImpactedTeachers($teacherId);

        return response()->json($impactedTeachers);
    }

    public function getImpactedTeachers($teacherId, $level = 1, &$maxLevel = 1, $training_id)
    {
        // Array to store unique impacts
        $result = [];

        // Array to track seen combinations
        $seen = [];

        // Helper function to recursively find impacts
        $findImpacts = function ($id, $currentLevel, $trainingId) use (&$findImpacts, &$result, &$seen, &$maxLevel) {
            // Fetch direct impacts for the current teacher and training
            $directImpact = TeacherTraining::where('influenced_by', $id)
                ->where(function ($query) use ($trainingId) {
                    $query->where('original_training_id', $trainingId)
                        ->orWhere('training_id', $trainingId)
                        ->orWhere('parent_id', $trainingId);
                })
                ->with('teacher', 'training', 'influencedBy')
                ->get();

            foreach ($directImpact as $impact) {
                $uniqueKey = $impact->teacher->id . '_' . $impact->training->id;

                // Check if the combination has already been seen
                if (!isset($seen[$uniqueKey])) {
                    // Update maximum level
                    if ($currentLevel > $maxLevel) {
                        $maxLevel = $currentLevel;
                    }

                    // Store current impact data
                    $result[] = [
                        'teacher_id' => $impact->teacher->id,
                        'teacher_name' => $impact->teacher->name,
                        'level' => $currentLevel,
                        'training_id' => $impact->training->id,
                        'training_name' => $impact->training->title,
                        'training_description' => $impact->training->description,
                        'influenced_by' => [
                            'teacher_id' => $impact->influencedBy->id ?? null,
                            'teacher_name' => $impact->influencedBy->name ?? null,
                        ],
                    ];

                    // Mark this combination as seen
                    $seen[$uniqueKey] = true;

                    // Recursive call to fetch sub-impacts based on the current training
                    $findImpacts($impact->teacher->id, $currentLevel + 1, $impact->training->id);
                }
            }
        };

        // Start the recursive search from the given teacherId and trainingId
        $findImpacts($teacherId, $level, $training_id);

        return $result;
    }



    public function detailImbas($id)
    {
        $id = Crypt::decrypt($id);

        $data = Training::where('id', $id)->first()->toArray();

        $members = TeacherTraining::where('training_id', $id)->get()->map(function ($item) {
            return [
                'name' => $item->teacher->name,
                'school' => $item->teacher->school->name
            ];
        });
        $maxLevel = 1; // Initialize the max level

        $impactedTeachers = $this->getImpactedTeachers($data['trainer_teacher_id'], 1, $maxLevel, $id);

        return view('admin.detail-imbas', [
            'data' => $data,
            'members' => $members,
            'impactedTeachers' => $impactedTeachers,
            'maxLevel' => $maxLevel
        ]);

    }

}
