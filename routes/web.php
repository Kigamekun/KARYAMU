<?php

use App\Http\Controllers\{ProfileController, NotificationController, SubscriptionController, ArtworkController, MasterController, UserController, SchoolController, TeacherController, StudentController, TrainingController};
use Illuminate\Support\Facades\Route;
use App\Models\{Artwork, Training, Teacher, School, Province, TeacherTraining, Student};

Route::get('/', function () {
    if (isset($_GET['provinsi'])) {
        $provinceId = Province::where('name', $_GET['provinsi'])->first()->id;
        $school = School::join('master_subdistrict', 'schools.subdistrict_code', '=', 'master_subdistrict.code')
            ->join('master_district', 'master_subdistrict.district_code', '=', 'master_district.code')
            ->join('master_regency', 'master_district.regency_code', '=', 'master_regency.code')
            ->join('master_province', 'master_regency.province_code', '=', 'master_province.code')
            ->where('master_province.id', $provinceId)
            ->pluck('schools.id')
            ->toArray();

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            if ($sort == 'desc') {
                $data = Artwork::where('is_approved', 1)->whereIn('school_id', $school)->orderBy('likes', 'DESC')->orderBy('id', 'DESC')->paginate(12)->appends(request()->query());
            } else {
                $data = Artwork::where('is_approved', 1)->whereIn('school_id', $school)->orderBy('likes', 'ASC')->orderBy('id', 'DESC')->paginate(12)->appends(request()->query());
            }
        } else {
            $data = Artwork::where('is_approved', 1)->whereIn('school_id', $school)->orderBy('likes', 'DESC')->orderBy('id', 'DESC')->paginate(12)->appends(request()->query());
        }
    } else {
        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            if ($sort == 'desc') {
                $data = Artwork::where('is_approved', 1)->orderBy('likes', 'DESC')->orderBy('id', 'DESC')->paginate(12)->appends(request()->query());
            } else {
                $data = Artwork::where('is_approved', 1)->orderBy('likes', 'ASC')->orderBy('id', 'DESC')->paginate(12)->appends(request()->query());
            }
        } else {
            $data = Artwork::where('is_approved', 1)->orderBy('likes', 'DESC')->orderBy('id', 'DESC')->paginate(12)->appends(request()->query());
        }
    }

    return view('welcome', [
        'data' => $data
    ]);
})->name('home');

Route::get('/get-schools', function (Request $request) {
    $search = $_GET['q'];
    return DB::table('schools')
        ->select('id', 'name')
        ->where('name', 'like', "%{$search}%")
        ->limit(50) // Hanya ambil 50 hasil per permintaan
        ->get();
});

Route::get('/get-students', function (Request $request) {
    $search = $_GET['q'];
    if (Auth::user()->role == 'teacher') {
        $school_id = Auth::user()->teacher->school_id;

        $students = Student::where('school_id', $school_id)
            ->select('id', 'name')
            ->where('name', 'like', "%{$search}%")
            ->limit(50) // Hanya ambil 50 hasil per permintaan
            ->get();
    } else if (Auth::user()->role == 'student') {
        $school_id = Auth::user()->student->school_id;
        $students = Student::where('school_id', $school_id)
            ->select('id', 'name')
            ->where('name', 'like', "%{$search}%")
            ->limit(50) // Hanya ambil 50 hasil per permintaan
            ->get();
    } else {
        $students = Student::select('id', 'name')
            ->where('name', 'like', "%{$search}%")
            ->limit(50) // Hanya ambil 50 hasil per permintaan
            ->get();
    }

    return $students;
});

Route::get('/get-teachers', function (Request $request) {
    $search = $_GET['q'];
    return DB::table('teachers')
        ->select('id', 'name')
        ->where('name', 'like', "%{$search}%")
        ->limit(50) // Hanya ambil 50 hasil per permintaan
        ->get();
});

Route::get('/trainings/impact/{teacherId}', [TrainingController::class, 'showImpact']);

Route::get('/generate-register-link', [UserController::class, 'createLinkRegisterGuru'])->name('generate.register.link');

Route::match(['GET', 'POST'], '/register-sekolah', [SchoolController::class, 'registerSekolah'])->name('register.sekolah');
Route::get('/register-guru', [UserController::class, 'showRegisterGuru'])->name('register.guru');
Route::post('/create-guru', [UserController::class, 'createGuru'])->name('register.create-guru');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('mail.artwork-publish');
})->name('contact');

Route::prefix('karya-home')->group(function () {
    Route::get('/', [ArtworkController::class, 'karyaHome'])->name('karya-home.index');
    Route::get('/detail/{id}', [ArtworkController::class, 'detailHome'])->name('karya-home.detail');
    Route::get('/filter', [ArtworkController::class, 'filterHome'])->name('karya-home.filter');
});

Route::get('/edit/{id}', [SchoolController::class, 'edit'])->name('sekolah.edit');
Route::prefix('master')->group(function () {
    Route::get('/provinsi', [MasterController::class, 'provinsi'])->name('master.provinsi');
    Route::get('/kota/{id}', [MasterController::class, 'kota'])->name('master.kota');
    Route::get('/kecamatan/{id}', [MasterController::class, 'kecamatan'])->name('master.kecamatan');
    Route::get('/kelurahan/{id}', [MasterController::class, 'kelurahan'])->name('master.kelurahan');
});

Route::prefix('sekolah')->group(function () {
    Route::get('/', [SchoolController::class, 'index'])->name('sekolah.index');
    Route::get('/{id}', [SchoolController::class, 'detail'])->name('sekolah.detail');
    Route::post('/store', [SchoolController::class, 'store'])->name('sekolah.store');
    Route::put('/update/{id}', [SchoolController::class, 'update'])->name('sekolah.update');
    Route::delete('/delete/{id}', [SchoolController::class, 'destroy'])->name('sekolah.delete');
});

Route::get('/dashboard', function () {

    if (isset($_GET['provinsi'])) {
        $provinceId = Province::where('name', $_GET['provinsi'])->first()->id;
        $school = School::join('master_subdistrict', 'schools.subdistrict_code', '=', 'master_subdistrict.code')
            ->join('master_district', 'master_subdistrict.district_code', '=', 'master_district.code')
            ->join('master_regency', 'master_district.regency_code', '=', 'master_regency.code')
            ->join('master_province', 'master_regency.province_code', '=', 'master_province.code')
            ->where('master_province.id', $provinceId)
            ->pluck('schools.id')
            ->toArray();
        $data = Artwork::orderBy('created_at', 'DESC')->limit(6)->whereIn('school_id', $school)->get();
    } else {
        $data = Artwork::orderBy('created_at', 'DESC')->limit(6)->get();
    }

    if (Auth::user()->role == 'teacher') {
        $teacher_id = Auth::user()->teacher->id;

        $totalTeachers = Teacher::count();
        $pelatihan = Training::where('trainer_teacher_id', $teacher_id)
        ->whereHas('teacherTrainings', function ($query) {
            $query->where('role', 'instructor');
        })
        ->first();


        if (!is_null($pelatihan)) {
            $maxLevel = 1; // Initialize the max level

            $impactedTeachers = app('App\Http\Controllers\TrainingController')->getImpactedTeachers($teacher_id, 1, $maxLevel, null);

        } else {
            $pelatihan = null;
            $impactedTeachers = [];
            $maxLevel = 0;
        }

    } else {
        $pelatihan = null;
        $impactedTeachers = [];
        $maxLevel = 0;
    }

    return view('dashboard', [
        'data' => $data,
        'pelatihan' => $pelatihan,
        'impactedTeachers' => $impactedTeachers,
        'maxLevel' => $maxLevel
    ]);
})->middleware(['auth', 'verified', 'check.school.data'])->name('dashboard');

Route::post('/like-item/{id}', [ArtworkController::class, 'like'])->name('like-item');

Route::middleware(['auth', 'verified', 'check.school.data'])->group(function () {

    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/unsubscribe', [SubscriptionController::class, 'unsubscribe']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('karya')->group(function () {
        Route::get('/', [ArtworkController::class, 'index'])->name('karya.index');
        Route::post('/store', [ArtworkController::class, 'store'])->name('karya.store');
        Route::get('/{id}/edit', [ArtworkController::class, 'edit'])->name('karya.edit');
        Route::get('/{id}', [ArtworkController::class, 'detail'])->name('karya.detail');
        Route::post('/filter', [ArtworkController::class, 'filter'])->name('karya.filter')->withoutMiddleware('auth');

        Route::put('/unpublish/{id}', [ArtworkController::class, 'unpublish'])->name('karya.unpublish');
        Route::put('/approve/{id}', [ArtworkController::class, 'approve'])->name('karya.approve');
        Route::put('/update/{id}', [ArtworkController::class, 'update'])->name('karya.update');
        Route::delete('/delete/{id}', [ArtworkController::class, 'destroy'])->name('karya.delete');
    });

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
    });

    Route::prefix('pelatihan')->group(function () {
        Route::get('/', [TrainingController::class, 'index'])->name('pelatihan.index');
        Route::get('/{id}', [TrainingController::class, 'detail'])->name('pelatihan.detail');
        Route::get('/detail-imbas/{id}', [TrainingController::class, 'detailImbas'])->name('pelatihan.detail-imbas');
        Route::get('/getTotals/{search}', [TrainingController::class, 'getTotals'])->name('pelatihan.getTotals');

        Route::post('/store', [TrainingController::class, 'store'])->name('pelatihan.store');
        Route::get('/{id}/edit', [TrainingController::class, 'edit'])->name('pelatihan.edit');

        Route::put('/update/{id}', [TrainingController::class, 'update'])->name('pelatihan.update');
        Route::delete('/delete/{id}', [TrainingController::class, 'destroy'])->name('pelatihan.delete');
    });

    Route::prefix('notification')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notification.index');
        Route::get('/markAsRead', [NotificationController::class, 'markAsRead'])->name('notification.mark-as-read');
    });

});

Route::get('/foo', function () {

    \Artisan::call('optimize:clear');

});

require __DIR__ . '/auth.php';

