<?php

namespace App\Http\Controllers;

use App\Http\Requests\CorrectionRequest;
use Illuminate\Support\Facades\Auth;

class AmendmentApplicationController extends Controller
{
    public function __invoke(CorrectionRequest $request, $id)
    {
        $user = Auth::user();

        if ($user && $user->admin_status) {
            return app(AdminController::class)->amendmentApplication($request, $id);
        }

        return app(UserController::class)->amendmentApplication($request, $id);
    }
}
