<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with(['student', 'teacher']);
            if (Auth::user()->role == 'teacher') {
                $data = $data->where('role', 'student');
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
                        'phone_number' => $user->student->phone_number ?? null,
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
                        'phone_number' => $user->teacher->phone_number ?? null,
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
                    $btn = '
                        <div class="d-flex" style="gap:5px;">
                            <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#updateData"
                            data-url="' . route('user.update', ['id' => $row['id']]) . '"
                            data-id="' . $row['id'] . '" data-name="' . $row['name'] . '"
                            data-username="' . $row['username'] . '"
                            data-email="' . $row['email'] . '" data-role="' . $row['role'] . '"
                            data-nis="' . $row['nis'] . '" data-nip="' . $row['nip'] . '"
                            data-phone_number="' . $row['phone_number'] . '" data-address="' . $row['address'] . '"
                            data-school_id="' . $row['school_id'] . '">
                                Edit
                            </button>
                            <form id="deleteForm" action="' . route('user.delete', ['id' => $row['id']]) . '" method="POST">
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
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $thumbname = time() . '-' . $file->getClientOriginalName();
            Storage::disk('public')->put('users/' . $thumbname, file_get_contents($file));
            User::insert([
                'photo' => $thumbname,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'unit_id' => $request->unit,
            ]);
        } else {
            User::insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'unit_id' => $request->unit,
            ]);
        }

        return redirect()->back()->with(['message' => 'Users berhasil ditambahkan', 'status' => 'success']);

    }


    public function update(Request $request, $id)
    {
        Validator::validate($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $thumbname = time() . '-' . $file->getClientOriginalName();
            Storage::disk('public')->put('users/' . $thumbname, file_get_contents($file));
            User::where('id', $id)->update([
                'photo' => $thumbname,
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'unit_id' => $request->unit,
            ]);
        } else {
            User::where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'unit_id' => $request->unit,
            ]);
        }

        if ($request->password) {
            User::where('id', $id)->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return redirect()->back()->with(['message' => 'Users berhasil di update', 'status' => 'success']);
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('unit.index')->with(['message' => 'Users berhasil di delete', 'status' => 'success']);
    }
}
