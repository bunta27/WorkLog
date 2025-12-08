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

// ▼ 初期表示は会員登録画面へ
Route::get('/', function () {
    return redirect('/register');
});


// =========================
// 一般ユーザー（ログイン＋メール認証必須）
// =========================
Route::middleware(['auth', 'verified'])->group(function () {

    // 勤怠登録画面（一般ユーザー）
    Route::get('/attendance', [UserController::class, 'index']);
    Route::post('/attendance', [UserController::class, 'attendance']);

    // 勤怠一覧画面（一般ユーザー）
    Route::get('/attendance/list', [UserController::class, 'list']);

    // 勤怠詳細画面（一般ユーザー）
    Route::get('/attendance/detail/{id}', [UserController::class, 'detail']);

    // 申請詳細画面（一般ユーザー）
    Route::get('/application/{id}', [UserController::class, 'applicationDetail']);

    // 申請一覧画面（一般ユーザー & 管理者 共通URL）
    Route::get('/stamp_correction_request/list', function (Request $request) {
        $user = auth()->user();

        if ($user && $user->admin_status) {
            // 管理者用一覧
            return app(AdminController::class)->applicationList($request);
        }

        // 一般ユーザー用一覧
        return app(UserController::class)->applicationList($request);
    });

    // 修正申請登録（一般ユーザー & 管理者 共通）
    // 実際の処理の振り分けは AmendmentApplicationController で行う
    Route::post('/attendance/{id}', AmendmentApplicationController::class);
});


// =========================
// 管理者専用（ログイン＋AdminStatusMiddleware）
// =========================
Route::middleware(['auth', AdminStatusMiddleware::class])->group(function () {

    // 勤怠一覧画面（管理者）
    Route::get('/admin/attendance/list', [AdminController::class, 'list']);

    // 勤怠詳細画面（管理者）
    Route::get('/admin/attendance/{id}', [AdminController::class, 'detail']);

    // スタッフ一覧画面（管理者）
    Route::get('/admin/staff/list', [AdminController::class, 'staffList']);

    // スタッフ別勤怠一覧画面（管理者）
    Route::get('/admin/attendance/staff/{id}', [AdminController::class, 'staffDetailList']);

    // 修正申請承認画面（管理者）
    Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminController::class, 'approvalShow']);
    Route::post('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminController::class, 'approval']);

    // 勤怠CSV出力（管理者）
    Route::post('/export', [AdminController::class, 'export']);

    // 管理者ログアウト
    Route::post('/admin/logout', [AuthController::class, 'adminLogout']);
});


// =========================
// 管理者ログイン画面
// =========================
Route::get('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/admin/login', [AuthController::class, 'adminDoLogin']);


// =========================
// 一般ユーザー 認証系
// =========================
Route::post('/login', [AuthController::class, 'doLogin']);
Route::post('/logout', [AuthController::class, 'doLogout']);
Route::post('/register', [AuthController::class, 'store']);


// =========================
// メール認証（登録直後＆初ログイン時に利用）
// =========================
Route::middleware('auth')->group(function () {

    // 認証待ち画面
    Route::get('/email/verify', function () {
        return view('verify-email');
    })->name('verification.notice');

    // メール内リンクを踏んだとき
    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed'])
        ->name('verification.verify');

    // 認証メール送信／再送信
    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/attendance');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});
