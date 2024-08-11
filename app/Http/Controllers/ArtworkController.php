<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\ArtworkStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;


class ArtworkController extends Controller
{

    public function edit($id)
    {
        $artwork = Artwork::find($id);
        $artwork->students = ArtworkStudent::where('artwork_id', $id)->get()->pluck('student_id');
        return response()->json($artwork, 200);
    }

    public function detail($id)
    {
        $artwork = Artwork::find($id);
        $artwork->students = ArtworkStudent::where('artwork_id', $id)
            ->join('students', 'students.id', '=', 'artwork_students.student_id')
            ->pluck('students.name') // Hanya ambil nama siswa
            ->implode(', ');

        return response()->json($artwork, 200);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Artwork::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex" style="gap:5px;">';
                    if ($row->is_approved == 0 and Auth::user()->role == 'teacher') {
                        $btn .= '<form id="approveForm" action="' . route('karya.approve', ['id' => $row->id]) . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('PUT') . '
                            <button type="button" title="APPROVE" class="btn btn-sm btn-success btn-approve" onclick="confirmApprove(event)">
                                Approve
                            </button>
                        </form>';
                        $btn .= '
                        <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-info" data-toggle="modal" data-target="#detailData"
                            data-id="' . $row->id . '" >
                                Detail
                            </button>
                            <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#updateData"
                            data-url="' . route('karya.update', ['id' => $row->id]) . '"
                            data-id="' . $row->id . '" data-title="' . $row->title . '" data-description="' . $row->description . '" data-type="' . $row->type . '" data-video_link="' . $row->video_link . '" data-file_path="' . asset('storage/artwork/' . $row->file_path) . '">
                                Edit
                            </button>
                        ';
                    }
                    $btn .= '
                    <form id="deleteForm" action="' . route('karya.delete', ['id' => $row->id]) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                                <button type="button" title="DELETE" class="btn btn-sm btn-primary btn-delete" onclick="confirmDelete(event)">
                                    Delete
                                </button>
                            </form>
                    </div>';
                    return $btn;
                })
                ->addColumn('type', function ($row) {
                    switch ($row->type) {
                        case 'image':
                            $type = '<badge class="badge badge-primary">Image</badge>';
                            break;
                        case 'video':
                            $type = '<badge class="badge badge-warning">Video</badge>';
                            break;
                        default:
                            # code...
                            break;
                    }
                    return $type;
                })
                ->addColumn('status', function ($row) {
                    switch ($row->is_approved) {
                        case 1:
                            $status = '<badge class="badge badge-success">Publish</badge>';
                            break;
                        case 0:
                            $status = '<badge class="badge badge-danger">Draft</badge>';
                            break;
                        default:
                            # code...
                            break;
                    }
                    return $status;
                })
                ->rawColumns(['action', 'type', 'status'])
                ->make(true);
        }

        if (Auth::user()->role == 'admin') {
            $students = Student::all();
        } else if (Auth::user()->role == 'teacher') {
            $school_id = Teacher::where('user_id', Auth::user()->id)->first()->school_id;
            $students = Student::where('school_id', $school_id)->get();
        } else if (Auth::user()->role == 'student') {
            $school_id = Student::where('user_id', Auth::user()->id)->first()->school_id;
            $students = Student::where('school_id', $school_id)->get();
        }

        return view('admin.artwork', [
            'data' => Artwork::all(),
            'students' => $students
        ]);
    }

    function extractVideoId($url)
    {
        $regex = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/';
        if (preg_match($regex, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function store(Request $request)
    {
        Validator::validate($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'type' => 'required',
            'students.*' => 'required',
        ]);
        $student_id = Student::where('user_id', Auth::user()->id)->first() != null ? Student::where('user_id', Auth::user()->id)->first()->id : null;
        $school_id = Student::where('user_id', Auth::user()->id)->first() != null ? Student::where('user_id', Auth::user()->id)->first()->school_id : null;

        if ($school_id == null) {
            $school_id = Teacher::where('user_id', Auth::user()->id)->first()->school_id;
        }

        if ($request->type == 'image') {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1048',
            ]);
            $file = $request->file('image');
            $thumbname = time() . '-' . $file->getClientOriginalName();
            Storage::disk('public')->put('artwork/' . $thumbname, file_get_contents($file));
            $artwork = Artwork::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'file_path' => $thumbname,
                'is_approved' => 0,
                'created_by_student_id' => $student_id,
                'school_id' => $school_id,
            ]);
            foreach ($request->students as $student) {
                ArtworkStudent::create([
                    'artwork_id' => $artwork->id,
                    'student_id' => $student
                ]);
            }
        } else {
            $request->validate([
                'video' => 'required|url',
            ]);
            $videoId = $this->extractVideoId($request->video);
            if (!$videoId) {
                return redirect()->back()->with(['message' => 'Link video tidak valid', 'status' => 'danger']);
            }
            $artwork = Artwork::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'video_link' => $request->video,
                'video_id' => $videoId,
                'is_approved' => 0,
                'created_by_student_id' => $student_id,
                'school_id' => $school_id,
            ]);
            foreach ($request->students as $student) {
                ArtworkStudent::create([
                    'artwork_id' => $artwork->id,
                    'student_id' => $student
                ]);
            }
        }
        return redirect()->back()->with(['message' => 'Artwork berhasil ditambahkan', 'status' => 'success']);
    }

    public function approve($id)
    {
        Artwork::where('id', $id)->update([
            'is_approved' => 1,
            'approved_by_teacher_id' => Teacher::where('user_id', Auth::user()->id)->first()->id
        ]);
        return redirect()->back()->with(['message' => 'Artwork berhasil di approve', 'status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        Validator::validate($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'type' => 'required',
            'students.*' => 'required',
        ]);
        if ($request->type == 'image') {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $thumbname = time() . '-' . $file->getClientOriginalName();
                Storage::disk('public')->put('artwork/' . $thumbname, file_get_contents($file));
                Artwork::where('id', $id)->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'type' => $request->type,
                    'file_path' => $thumbname,
                ]);
            } else {
                Artwork::where('id', $id)->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'type' => $request->type,
                ]);
            }
        } else {
            $videoId = $this->extractVideoId($request->video);
            if (!$videoId) {
                return redirect()->back()->with(['message' => 'Link video tidak valid', 'status' => 'danger']);
            }
            Artwork::where('id', $id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'video_link' => $request->video,
                'video_id' => $videoId,
            ]);
        }

        ArtworkStudent::where('artwork_id', $id)->delete();
        foreach ($request->students as $student) {
            ArtworkStudent::create([
                'artwork_id' => $id,
                'student_id' => $student
            ]);
        }

        return redirect()->back()->with(['message' => 'Artwork berhasil di update', 'status' => 'success']);
    }

    public function destroy($id)
    {
        Artwork::where('id', $id)->delete();
        return redirect()->route('karya.index')->with(['message' => 'Artwork berhasil di delete', 'status' => 'success']);
    }

    public function karyaHome()
    {
        $data = Artwork::where('is_approved', 1)->paginate(12);
        return view('karya-home', [
            'data' => $data
        ]);
    }

    public function detailHome($id)
    {
        $artwork = Artwork::find($id);
        $terkait = Artwork::where('is_approved', 1)->limit(4)->get();


        return view('karya-detail', [
            'data' => $artwork,
            'terkait' => $terkait
        ]);
    }
}
