<?php

use App\Http\Controllers\{ProfileController,SubscriptionController, ArtworkController, MasterController, UserController, SchoolController, TeacherController, StudentController, TrainingController};
use Illuminate\Support\Facades\Route;
use App\Models\{Artwork, Training, Teacher, School, Province};

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
        $data = Artwork::where('is_approved', 1)->whereIn('school_id', $school)->get();

    } else {
        $data = Artwork::where('is_approved', 1)->get();
    }

    return view('welcome', [
        'data' => $data
    ]);
})->name('home');



Route::get('/generate-register-link', [UserController::class, 'createLinkRegisterGuru'])->name('generate.register.link');

// Rute untuk menampilkan halaman register guru dengan link yang terenkripsi
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
        $data = Artwork::orderBy('created_at','DESC')->limit(6)->whereIn('school_id', $school)->get();

    } else {
        $data = Artwork::orderBy('created_at','DESC')->limit(6)->get();
    }

    $trainings = Training::orderBy('created_at','DESC')->limit(6)->get();

    // Dapatkan jumlah teachers
    $totalTeachers = Teacher::count();

    $pelatihan = $trainings->map(function ($training) use ($totalTeachers) {
        $participants = $training->teacherTrainings()->count();
        return [
            'title' => $training->title,
            'participant' => $participants,
            'total' => $totalTeachers,
        ];
    });

    return view('dashboard', [
        'data' => $data,
        'pelatihan' => $pelatihan
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::post('/like-item/{id}', [ArtworkController::class, 'like'])->name('like-item');


Route::middleware('auth')->group(function () {

    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/unsubscribe', [SubscriptionController::class, 'unsubscribe']);

    Route::prefix('master')->group(function () {
        Route::get('/provinsi', [MasterController::class, 'provinsi'])->name('master.provinsi');
        Route::get('/kota/{id}', [MasterController::class, 'kota'])->name('master.kota');
        Route::get('/kecamatan/{id}', [MasterController::class, 'kecamatan'])->name('master.kecamatan');
        Route::get('/kelurahan/{id}', [MasterController::class, 'kelurahan'])->name('master.kelurahan');
    });


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::prefix('karya')->group(function () {
        Route::get('/', [ArtworkController::class, 'index'])->name('karya.index');
        Route::post('/store', [ArtworkController::class, 'store'])->name('karya.store');
        Route::get('/{id}/edit', [ArtworkController::class, 'edit'])->name('karya.edit');
        Route::get('/{id}', [ArtworkController::class, 'detail'])->name('karya.detail');
        Route::post('/filter', [ArtworkController::class, 'filter'])->name('karya.filter')->withoutMiddleware('auth');

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

    Route::prefix('sekolah')->group(function () {
        Route::get('/', [SchoolController::class, 'index'])->name('sekolah.index');
        Route::get('/{id}', [SchoolController::class, 'detail'])->name('sekolah.detail');
        Route::post('/store', [SchoolController::class, 'store'])->name('sekolah.store');
        Route::put('/update/{id}', [SchoolController::class, 'update'])->name('sekolah.update');
        Route::delete('/delete/{id}', [SchoolController::class, 'destroy'])->name('sekolah.delete');
    });

    Route::prefix('guru')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('guru.index');
        Route::post('/store', [TeacherController::class, 'store'])->name('guru.store');
        Route::put('/update/{id}', [TeacherController::class, 'update'])->name('guru.update');
        Route::delete('/delete/{id}', [TeacherController::class, 'destroy'])->name('guru.delete');
    });

    Route::prefix('siswa')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('siswa.index');
        Route::post('/store', [StudentController::class, 'store'])->name('siswa.store');
        Route::put('/update/{id}', [StudentController::class, 'update'])->name('siswa.update');
        Route::delete('/delete/{id}', [StudentController::class, 'destroy'])->name('siswa.delete');
    });

    Route::prefix('pelatihan')->group(function () {
        Route::get('/', [TrainingController::class, 'index'])->name('pelatihan.index');
        Route::get('/{id}', [TrainingController::class, 'detail'])->name('pelatihan.detail');

        Route::post('/store', [TrainingController::class, 'store'])->name('pelatihan.store');
        Route::get('/{id}/edit', [TrainingController::class, 'edit'])->name('pelatihan.edit');

        Route::put('/update/{id}', [TrainingController::class, 'update'])->name('pelatihan.update');
        Route::delete('/delete/{id}', [TrainingController::class, 'destroy'])->name('pelatihan.delete');
    });

});

require __DIR__ . '/auth.php';

