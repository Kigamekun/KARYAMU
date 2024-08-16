<?php

namespace App\Http\Controllers;

use App\Mail\NewKarya;
use App\Models\Artwork;
use App\Models\ArtworkStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use App\Models\Province;
use App\Models\School;
use App\Models\Subscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription as WebPushSubscription;
use Illuminate\Support\Facades\Crypt;


class ArtworkController extends Controller
{
    protected function sendPushNotification($sekolah_id, $karya)
    {
        $subscriptions = Subscription::join('users', 'users.id', '=', 'subscriptions.user_id')
            ->join('teachers', 'teachers.user_id', '=', 'users.id')
            ->where('teachers.school_id', $sekolah_id)
            ->get();

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:example@yourdomain.org',
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        $webPush = new WebPush($auth);

        foreach ($subscriptions as $sub) {
            $webPushSub = WebPushSubscription::create([
                'endpoint' => $sub->endpoint,
                'publicKey' => $sub->public_key,
                'authToken' => $sub->auth_token,
            ]);

            $payload = json_encode([
                'title' => 'Karya Baru Dikirim',
                'body' => 'Siswa telah mengirimkan karya baru yang perlu disetujui.',
                'data' => [
                    'karya_id' => $karya->id,
                    'judul' => $karya->judul
                ],
            ]);

            $webPush->sendOneNotification($webPushSub, $payload);
        }

        $webPush->flush();
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);

        $artwork = Artwork::find($id);
        $artwork->students = ArtworkStudent::where('artwork_id', $id)->get()->pluck('student_id');
        return response()->json($artwork, 200);
    }

    public function detail($id)
    {
        $id = Crypt::decrypt($id);
        $artwork = Artwork::find($id);
        $artwork->students = ArtworkStudent::where('artwork_id', $id)
            ->join('students', 'students.id', '=', 'artwork_students.student_id')
            ->pluck('students.name')
            ->implode(', ');

        return response()->json($artwork, 200);
    }

    public function filterHome(Request $request)
    {
        if (isset($_GET['provinsi'])) {
            $provinceId = Province::where('name', $_GET['provinsi'])->first()->id;
            $school = School::join('master_subdistrict', 'schools.subdistrict_code', '=', 'master_subdistrict.code')
                ->join('master_district', 'master_subdistrict.district_code', '=', 'master_district.code')
                ->join('master_regency', 'master_district.regency_code', '=', 'master_regency.code')
                ->join('master_province', 'master_regency.province_code', '=', 'master_province.code')
                ->where('master_province.id', $provinceId)
                ->pluck('schools.id')
                ->toArray();
            $data = Artwork::where('is_approved', 1)->whereIn('school_id', $school)->paginate(12);

        } else if (isset($_GET['search'])) {

            $query = Artwork::query();
            if (isset($_GET['search'])) {
                $query->where('title', 'like', '%' . $_GET['search'] . '%');
            }

            if (isset($_GET['schools'])) {
                $query->whereIn('school_id', $_GET['schools']);
            }

            if (isset($_GET['type'])) {
                $query->whereIn('type', $_GET['type']);
            }

            $data = $query->paginate(12);

        } else {
            $data = Artwork::where('is_approved', 1)->paginate(12);
        }
        return view('karya-home', [
            'data' => $data
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            if (Auth::user()->role == 'admin') {
                $data = Artwork::all();
            } else if (Auth::user()->role == 'teacher') {
                $school_id = Teacher::where('user_id', Auth::user()->id)->first()->school_id;
                $data = Artwork::where('school_id', $school_id)->get();
            } else if (Auth::user()->role == 'student') {
                $school_id = Student::where('user_id', Auth::user()->id)->first()->school_id;
                $data = Artwork::where('school_id', $school_id)->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $id = Crypt::encrypt($row->id);
                    $btn = '<div class="d-flex" style="gap:5px;">';
                    if ($row->is_approved == 0 and Auth::user()->role == 'teacher') {
                        $btn .= '<form id="approveForm" action="' . route('karya.approve', ['id' => $id]) . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('PUT') . '
                            <button type="button" title="APPROVE" class="btn btn-sm btn-success btn-approve" onclick="confirmApprove(event)">
                                Approve
                            </button>
                        </form>';
                        $btn .= '

                            <button type="button" title="EDIT" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#updateData"
                            data-url="' . route('karya.update', ['id' => $id]) . '"
                            data-id="' . $id . '" data-title="' . $row->title . '" data-description="' . $row->description . '" data-type="' . $row->type . '" data-video_link="' . $row->video_link . '" data-file_path="' . asset('storage/artwork/' . $row->file_path) . '">
                                Edit
                            </button>
                        ';
                    } else {
                        $btn .= ' <button type="button" title="Detail" class="btn btn-sm btn-warning btn-info" data-toggle="modal" data-target="#detailData"
                            data-id="' . $id . '" >
                                Detail
                            </button>';
                    }
                    $btn .= '
                    <form id="deleteForm" action="' . route('karya.delete', ['id' => $id]) . '" method="POST">
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
                            $status = '<badge class="badge badge-error">Draft</badge>';
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
            'students' => 'required',
        ]);

        if (Student::whereIn('id', $request->students)->count() != count($request->students)) {
            return redirect()->back()->with(['message' => 'Data student tidak ada', 'status' => 'error']);
        }

        $student_id = Student::where('user_id', Auth::user()->id)->first() != null ? Student::where('user_id', Auth::user()->id)->first()->id : null;
        $teacher_id = Teacher::where('user_id', Auth::user()->id)->first() != null ? Teacher::where('user_id', Auth::user()->id)->first()->id : null;

        if (Auth::user()->role == 'admin') {
            $school_id = null;
        } else if (Auth::user()->role == 'teacher') {
            $school_id = Teacher::where('user_id', Auth::user()->id)->first()->school_id;
        } else if (Auth::user()->role == 'student') {
            $school_id = Student::where('user_id', Auth::user()->id)->first()->school_id;
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
                'created_by_teacher_id' => $teacher_id,
                'school_id' => $school_id,
            ]);
            foreach ($request->students as $student) {
                ArtworkStudent::create([
                    'artwork_id' => $artwork->id,
                    'student_id' => $student
                ]);
            }

            try {
                $this->sendPushNotification($school_id, $artwork);

            } catch (\Throwable $th) {
                //throw $th;
            }

        } else {
            $request->validate([
                'video' => 'required|url',
            ]);
            $videoId = $this->extractVideoId($request->video);
            if (!$videoId) {
                return redirect()->back()->with(['message' => 'Link video tidak valid', 'status' => 'error']);
            }
            $artwork = Artwork::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'video_link' => $request->video,
                'video_id' => $videoId,
                'is_approved' => 0,
                'created_by_student_id' => $student_id,
                'created_by_teacher_id' => $teacher_id,
                'school_id' => $school_id,
            ]);
            foreach ($request->students as $student) {
                ArtworkStudent::create([
                    'artwork_id' => $artwork->id,
                    'student_id' => $student
                ]);
            }

            try {
                $this->sendPushNotification($school_id, $artwork);

            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        // try {
        //     $teach = Teacher::where('school_id', $school_id)->join('users', 'users.id', '=', 'teachers.user_id')->select('users.email')->get();
        //     foreach ($teach as $teacher) {
        //         Mail::to($teacher->email)->send(new NewKarya($artwork));
        //     }
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }

        return redirect()->back()->with(['message' => 'Artwork berhasil ditambahkan', 'status' => 'success']);
    }

    public function approve($id)
    {
        $id = Crypt::decrypt($id);
        Artwork::where('id', $id)->update([
            'is_approved' => 1,
            'approved_by_teacher_id' => Teacher::where('user_id', Auth::user()->id)->first()->id
        ]);
        return redirect()->back()->with(['message' => 'Artwork berhasil di approve', 'status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        Validator::validate($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'type' => 'required',
            'students.*' => 'required',
            'students' => 'required',
        ]);

        if (Student::whereIn('id', $request->students)->count() != count($request->students)) {
            return redirect()->back()->with(['message' => 'Data student tidak ada', 'status' => 'error']);
        }

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
                return redirect()->back()->with(['message' => 'Link video tidak valid', 'status' => 'error']);
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
        $id = Crypt::decrypt($id);
        Artwork::where('id', $id)->delete();
        return redirect()->route('karya.index')->with(['message' => 'Artwork berhasil di delete', 'status' => 'success']);
    }

    public function karyaHome()
    {
        if (isset($_GET['provinsi'])) {
            $provinceId = Province::where('name', $_GET['provinsi'])->first()->id;
            $school = School::join('master_subdistrict', 'schools.subdistrict_code', '=', 'master_subdistrict.code')
                ->join('master_district', 'master_subdistrict.district_code', '=', 'master_district.code')
                ->join('master_regency', 'master_district.regency_code', '=', 'master_regency.code')
                ->join('master_province', 'master_regency.province_code', '=', 'master_province.code')
                ->where('master_province.id', $provinceId)
                ->pluck('schools.id')
                ->toArray();
            $data = Artwork::where('is_approved', 1)->whereIn('school_id', $school)->paginate(12)->appends(request()->query());

        } else {
            $data = Artwork::where('is_approved', 1)->paginate(12)->appends(request()->query());
        }
        return view('karya-home', [
            'data' => $data
        ]);
    }

    public function detailHome($id)
    {
        $artwork = Artwork::find($id);

        if (!$artwork) {
            return redirect()->route('karya-home.index')->with(['message' => 'Karya tidak ditemukan', 'status' => 'error']);
        }

        $terkait = Artwork::where('is_approved', 1)->limit(3)->get();

        return view('karya-detail', [
            'data' => $artwork,
            'terkait' => $terkait
        ]);
    }

    public function filter(Request $request)
    {
        $search = $request->input('search');
        $selectedSchools = $request->input('schools');
        $types = $request->input('type');

        $query = Artwork::query();
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($selectedSchools) {
            $query->whereIn('school_id', $selectedSchools);
        }

        if ($types) {
            $query->whereIn('type', $types);
        }

        $karyas = $query->paginate(12)->appends(request()->query());

        return view('karya-home', [
            'data' => $karyas
        ]);
    }

    public function like(Request $request, $id)
    {
        $artwork = Artwork::find($id);

        if (!$artwork) {
            return response()->json(['error' => 'Artwork not found'], 404);
        }

        $isLiked = $request->input('liked');

        if ($isLiked) {
            $artwork->likes = max(0, $artwork->likes - 1);
        } else {
            $artwork->likes += 1;
        }

        $artwork->save();

        return response()->json(['likes' => $artwork->likes]);
    }
}
