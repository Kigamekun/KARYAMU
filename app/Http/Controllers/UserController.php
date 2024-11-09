<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Crypt;
use App\Models\ArtworkStudent;
use App\Models\Artwork;
use App\Models\Training;
use App\Models\TeacherTraining;


class UserController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with(['student', 'teacher']);
            if (Auth::user()->role == 'teacher') {
                $data = User::with('student')
                    ->where('role', 'student')
                    ->whereHas('student', function ($query) {
                        $query->where('school_id', Auth::user()->teacher->school_id);
                    });
            }
            $data = $data->get()->map(function ($user) {
                if ($user->role == 'student') {

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'role' => $user->role,
                        'nis' => $user->student->nis ?? null,
                        'nip' => $user->teacher->nip ?? null,
                        'phone_number' => $user->student->phone ?? null,
                        'address' => $user->student->address ?? null,
                        'school_id' => $user->student->school_id ?? null,
                        'school_name' => $user->student->school->name ?? null,
                    ];
                } elseif ($user->role == 'teacher') {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'role' => $user->role,
                        'nip' => $user->teacher->nip ?? null,
                        'nis' => $user->teacher->nis ?? null,
                        'phone_number' => $user->teacher->phone ?? null,
                        'address' => $user->teacher->address ?? null,
                        'school_id' => $user->teacher->school_id ?? null,
                        'school_name' => $user->teacher->school->name ?? null,
                    ];
                } else {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'role' => $user->role,
                        'phone_number' => null,
                        'address' => null,
                        'school_id' => null,
                        'nis' => null,
                        'nip' => null,
                        'school_name' => null,
                    ];
                }
            });
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = Crypt::encrypt($row['id']);


                    $btn = '
                        <div class="d-flex" style="gap:5px;">
                            <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#updateData"
                            data-url="' . route('user.update', ['id' => $id]) . '"
                            data-id="' . $id . '" data-name="' . $row['name'] . '"
                            data-username="' . $row['username'] . '"
                            data-email="' . $row['email'] . '" data-role="' . $row['role'] . '"
                            data-nis="' . $row['nis'] . '" data-nip="' . $row['nip'] . '"
                            data-phone_number="' . $row['phone_number'] . '" data-address="' . $row['address'] . '" data-school_name="' . $row['school_name'] . '"
                            data-school_id="' . $row['school_id'] . '">
                                Edit
                            </button>
                            <form id="deleteForm" action="' . route('user.delete', ['id' => $id]) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                                <button type="button" title="DELETE" class="btn btn-sm btn-primary btn-delete" onclick="confirmDelete(event)">
                                    Delete
                                </button>
                            </form>
                        </div>';
                    return $btn;
                })
                ->addColumn('role', function ($row) {
                    switch ($row['role']) {
                        case 'admin':
                            $role = '<span class="badge badge-warning">' . $row['role'] . '</span>';
                            break;
                        case 'student':
                            $role = '<span class="badge badge-primary">' . $row['role'] . '</span>';
                            break;
                        case 'teacher':
                            $role = '<span class="badge badge-success">' . $row['role'] . '</span>';
                        default:
                            break;
                    }
                    return $role;
                })

                ->rawColumns(['action', 'role'])
                ->make(true);
        }
        return view('admin.user', [
            'data' => User::all(),
        ]);
    }

    public function store(Request $request)
    {
        Validator::validate($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        if ($request->role == 'student') {

            Validator::validate($request->all(), [
                'nis' => 'required|unique:students',
            ]);


            if (Auth::user()->role == 'admin') {

                $user->student()->create([
                    'name' => $request->name,
                    'nis' => $request->nis,
                    'school_id' => $request->school_id,
                    'phone' => $request->phone_number,
                    'address' => $request->address,
                ]);
            } else {
                $user->student()->create([
                    'name' => $request->name,
                    'nis' => $request->nis,
                    'school_id' => Auth::user()->teacher->school_id,
                    'phone' => $request->phone_number,
                    'address' => $request->address,
                ]);
            }

        } elseif ($request->role == 'teacher') {
            Validator::validate($request->all(), [
                'nip' => 'required|unique:teachers',
                'school_id_teacher' => 'required',
            ]);

            $user->teacher()->create([
                'name' => $request->name,
                'nip' => $request->nip,
                'school_id' => $request->school_id_teacher,
                'phone' => $request->phone_number_teacher,
                'address' => $request->address_teacher,
            ]);
        }


        return redirect()->back()->with(['message' => 'Users berhasil ditambahkan', 'status' => 'success']);

    }


    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);


        Validator::validate($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);


        if ($request->role == 'student') {
            Validator::validate($request->all(), [
                'nis' => [
                    'required',
                    Rule::unique('students')->ignore($id, 'user_id')
                ],
                'phone_number' => 'required',
                'address' => 'required',
            ]);
            Teacher::where('user_id', $id)->delete();


            if (Auth::user()->role == 'admin') {
                User::find($id)->student()->updateOrCreate(
                    ['user_id' => $id],
                    [
                        'name' => $request->name,
                        'nis' => $request->nis,
                        'school_id' => $request->school_id,
                        'phone' => $request->phone_number,
                        'address' => $request->address,
                    ]
                );
            } else {
                User::find($id)->student()->updateOrCreate(
                    ['user_id' => $id],
                    [
                        'name' => $request->name,
                        'nis' => $request->nis,
                        'school_id' => Auth::user()->teacher->school_id,
                        'phone' => $request->phone_number,
                        'address' => $request->address,
                    ]
                );
            }
        } elseif ($request->role == 'teacher') {
            Validator::validate($request->all(), [
                'nip' => [
                    'required',
                    Rule::unique('teachers')->ignore($id, 'user_id')
                ],
                'school_id_teacher' => 'required',
                'phone_number_teacher' => 'required',
                'address_teacher' => 'required',
            ]);

            try {
                $stut = Student::where('user_id', $id);
                $artworkStudent = ArtworkStudent::where('student_id', $stut->first()->id)->get();

                foreach ($artworkStudent as $art) {
                    $artworkId = $art->artwork_id;
                    $remainingReferences = ArtworkStudent::where('artwork_id', $artworkId)->count() - 1;
                    if ($remainingReferences === 0) {
                        Artwork::find($artworkId)->delete();
                    }
                }

                $stut->delete();
            } catch (\Throwable $th) {
                //throw $th;
            }

            User::find($id)->teacher()->updateOrCreate(
                ['user_id' => $id],
                [
                    'name' => $request->name,
                    'nip' => $request->nip,
                    'school_id' => $request->school_id_teacher,
                    'phone' => $request->phone_number_teacher,
                    'address' => $request->address_teacher,
                ]
            );

        } else {
            Teacher::where('user_id', $id)->delete();
            Student::where('user_id', $id)->delete();
        }

        if ($request->password) {
            User::where('id', $id)->update([
                'password' => bcrypt($request->password),
            ]);
        }

        User::where('id', $id)->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->back()->with(['message' => 'Users berhasil di update', 'status' => 'success']);
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);

        User::where('id', $id)->delete();
        return redirect()->route('user.index')->with(['message' => 'Users berhasil di delete', 'status' => 'success']);
    }

    public function createLinkRegisterGuru()
    {
        $token = bin2hex(random_bytes(16));

        $expiresAt = now()->addHour();

        $encryptedData = encrypt(json_encode([
            'token' => $token,
            'expires_at' => $expiresAt
        ]));


        if (Auth::user()->role == 'admin') {
            $url = route('register.guru', ['data' => $encryptedData]);

        } else {
            $id_encrpt = Crypt::encrypt(Auth::user()->teacher->id);

            $url = route('register.guru', ['data' => $encryptedData, 'fr' => $id_encrpt]);

        }

        return response()->json(['url' => $url]);
    }

    public function showRegisterGuru(Request $request)
    {
        $encryptedData = $request->query('data');

        try {
            // Decrypt and decode the data
            $data = json_decode(decrypt($encryptedData), true);

            // Check if the link has expired
            if (now()->greaterThan($data['expires_at'])) {
                return abort(403, 'Link ini sudah kadaluwarsa.');
            }

            // Proceed with showing the registration form
            return view('auth.register-guru');

        } catch (\Exception $e) {
            return abort(403, 'Link tidak valid.');
        }
    }

    public function createGuru(Request $request)
    {
        if (isset($request->fr)) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed'],
                'nip' => ['required', 'unique:' . Teacher::class],
                'school_id' => 'required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'teacher',
            ]);

            $teacher = Teacher::create([
                'name' => $request->name,
                'nip' => $request->nip,
                'school_id' => $request->school_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'user_id' => $user->id,
            ]);


            $trainer_id = Crypt::decrypt($request->fr);

            if (!DB::table('teacher_trainings')->where('teacher_id', $trainer_id)->where('role', 'instructor')->exists()) {
                $original_training = DB::table('teacher_trainings')->where('teacher_id', $trainer_id)->first();








                $training = Training::create([
                    'description' => "Training Guru Mandiri",
                    'trainer_teacher_id' => $trainer_id,
                ]);

                TeacherTraining::create([
                    'training_id' => $training->id,
                    'teacher_id' => $trainer_id,
                    'role' => 'instructor',
                    'original_training_id' => $original_training->training_id,
                    'parent_id' => $original_training->training_id,
                ]);

                TeacherTraining::create([
                    'training_id' => $training->id,
                    'teacher_id' => $teacher->id,
                    'role' => 'participant',
                    'influenced_by' => $trainer_id,
                    'original_training_id' => $original_training->training_id,
                    'parent_id' => $original_training->training_id,
                ]);
            } else {
                $original_training = DB::table('teacher_trainings')->where('teacher_id', $trainer_id)->first();
                $teacher_training = DB::table('teacher_trainings')->where('teacher_id', $trainer_id)->where('role', 'instructor')->first();
                TeacherTraining::create([
                    'training_id' => $teacher_training->training_id,
                    'teacher_id' => $teacher->id,
                    'role' => 'participant',
                    'original_training_id' => $original_training->training_id,
                    'parent_id' => $teacher_training->training_id,
                ]);
            }
            event(new Registered($user));
            Auth::login($user);
        } else {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed'],
                'nip' => ['required', 'unique:' . Teacher::class],
                'school_id' => 'required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'teacher',
            ]);

            $teacher = Teacher::create([
                'name' => $request->name,
                'nip' => $request->nip,
                'school_id' => $request->school_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'user_id' => $user->id,
            ]);

            $training = Training::create([
                'description' => "Training Mandiri",
                'trainer_teacher_id' => $teacher->id,
            ]);

            TeacherTraining::create([
                'training_id' => $training->id,
                'teacher_id' => $teacher->id,
                'role' => 'participant',
                'influenced_by' => $teacher->id,
            ]);
            event(new Registered($user));

            Auth::login($user);
        }

        return redirect(route('dashboard', absolute: false));
    }


}
