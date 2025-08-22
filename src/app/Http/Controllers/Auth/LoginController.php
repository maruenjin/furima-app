<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    // ログインフォーム表示
    public function create(): View
    {
        return view('auth.login');
    }

    // ログイン処理
    public function store(LoginRequest $request): RedirectResponse
    {
        // FormRequestでバリデーション済み
        return app(\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class)->store($request);
    }

    // ログアウト
    public function destroy(): RedirectResponse
    {
        return app(\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class)->destroy(request());
    }
}


