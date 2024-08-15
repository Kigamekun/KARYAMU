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
                        'nip' => null
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
                            data-phone_number="' . $row['phone_number'] . '" data-address="' . $row['address'] . '"
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
            'sekolah' => School::all(),
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
                'phone_number' => 'required',
                'address' => 'required',
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
                'phone_number_teacher' => 'required',
                'address_teacher' => 'required',
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

            $artworkStudent = ArtworkStudent::where('student_id', $id)->first();
            $artworkId = $artworkStudent->artwork_id;
            $artworkStudent->delete();

            $remainingReferences = ArtworkStudent::where('artwork_id', $artworkId)->count();

            if ($remainingReferences === 0) {
                Artwork::find($artworkId)->delete();
            }

            Student::where('user_id', $id)->delete();

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

        $url = route('register.guru', ['data' => $encryptedData]);

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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed'],
            'nip' => 'required',
            'school_id' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'school_id' => $request->school_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_id' => $user->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }


}
