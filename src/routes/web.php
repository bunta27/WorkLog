<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AmendmentApplicationController;

use App\Http\Middleware\AdminStatusMiddleware;

use Laravel\Fortify\Http\Controllers\VerifyEmailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/register');
});


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/attendance', [UserController::class, 'index']);
    Route::post('/attendance', [UserController::class, 'attendance']);

    Route::get('/attendance/list', [UserController::class, 'list']);

    Route::get('/attendance/detail/{id}', [UserController::class, 'detail']);

    Route::get('/application/{id}', [UserController::class, 'applicationDetail']);

    Route::get('/stamp_correction_request/list', function (Request $request) {
        $user = auth()->user();

        if ($user && $user->admin_status) {

            return app(AdminController::class)->applicationList($request);
        }

        return app(UserController::class)->applicationList($request);
    });

    Route::post('/attendance/{id}', AmendmentApplicationController::class);
});


Route::middleware(['auth', AdminStatusMiddleware::class])->group(function () {

    Route::get('/admin/attendance/list', [AdminController::class, 'list']);

    Route::get('/admin/attendance/{id}', [AdminController::class, 'detail']);

    Route::get('/admin/staff/list', [AdminController::class, 'staffList']);

    Route::get('/admin/attendance/staff/{id}', [AdminController::class, 'staffDetailList']);

    Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminController::class, 'approvalShow']);
    Route::post('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminController::class, 'approval']);

    Route::post('/admin/attendance/export', [AdminController::class, 'export'])->name('admin.attendance.export');

    Route::post('/admin/logout', [AuthController::class, 'adminLogout']);
});


Route::get('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/admin/login', [AuthController::class, 'adminDoLogin']);


Route::post('/login', [AuthController::class, 'doLogin']);
Route::post('/logout', [AuthController::class, 'doLogout']);
Route::post('/register', [AuthController::class, 'store']);


Route::middleware('auth')->group(function () {

    Route::get('/email/verify', function () {
        return view('verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/attendance');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});
