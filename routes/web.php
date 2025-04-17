<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonalInfoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('personal-info.create');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// روت‌های عمومی
Route::middleware(['web'])->group(function () {
    // نمایش فرم ثبت اطلاعات
    Route::get('/personal-info/create', [PersonalInfoController::class, 'create'])
        ->name('personal-info.create');

    // پردازش فرم ثبت اطلاعات (AJAX)
    Route::post('/personal-info', [PersonalInfoController::class, 'store'])
        ->name('personal-info.store');

    // نمایش اطلاعات ثبت شده
//    Route::get('/personal-info/{id}', [PersonalInfoController::class, 'show'])
//        ->name('personal-info.show');

    Route::get('/personal-info', [PersonalInfoController::class, 'index'])->name('personal-info.index');
    Route::get('/personal-info/data', [PersonalInfoController::class, 'getData'])->name('personal-info.data');
    Route::get('/personal-info/{id}', [PersonalInfoController::class, 'show'])->name('personal-info.show');
    Route::delete('/personal-info/{id}', [PersonalInfoController::class, 'destroy'])->name('personal-info.destroy');
});

// روت‌های مدیریتی (نیاز به احراز هویت)
//Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
//    // لیست تمام اطلاعات ثبت شده
//    Route::get('/personal-infos', [PersonalInfoController::class, 'index'])
//        ->name('admin.personal-infos.index');
//
//    // تایید اطلاعات
//    Route::patch('/personal-infos/{id}/approve', [PersonalInfoController::class, 'approve'])
//        ->name('admin.personal-infos.approve');
//
//    // رد اطلاعات
//    Route::patch('/personal-infos/{id}/reject', [PersonalInfoController::class, 'reject'])
//        ->name('admin.personal-infos.reject');
//
//    // مشاهده جزئیات
//    Route::get('/personal-infos/{id}', [PersonalInfoController::class, 'adminShow'])
//        ->name('admin.personal-infos.show');
//});
//
//Route::group(['middleware' => ['web']], function () {
//    Route::get('/personal-info', [PersonalInfoController::class, 'create'])->name('personal-info.create');
//    Route::post('/personal-info', [PersonalInfoController::class, 'store'])->name('submit.personal.info');
//    Route::get('/personal-info/{id}', [PersonalInfoController::class, 'show'])->name('personal-info.show');
//});

require __DIR__.'/auth.php';
