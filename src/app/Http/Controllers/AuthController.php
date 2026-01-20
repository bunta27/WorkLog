<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Actions\Fortify\CreateNewUser;

class AuthController extends Controller
{
    protected $creator;

    public function __construct(CreateNewUser $creator)
    {
        $this->creator = $creator;
    }

    public function adminLogin()
    {
        return view('admin/admin-login');
    }

    public function adminDoLogin(AdminLoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->admin_status) {
                return redirect('admin/attendance/list');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'ログイン情報が登録されていません',
                ]);
            }
        }

        return redirect()->back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput();
    }

    public function adminLogout(Request $request)
    {
        Auth::logout();
        return redirect('/admin/login');
    }

    public function store(RegisterRequest $request)
    {
        $user = $this->creator->create($request->all());

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        return redirect()
            ->route('verification.notice');
    }

    public function doLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (! $user->hasVerifiedEmail()) {
                return redirect()
                    ->route('verification.notice');
            }

            return redirect()->intended('/attendance');
        }

        return redirect()->back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    public function doLogout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
