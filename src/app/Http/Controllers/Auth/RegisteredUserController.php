<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // 会員登録フォーム表示
    public function create(): View
    {
        return view('auth.register');
    }

    // 会員登録処理
    public function store(RegisterRequest $request): RedirectResponse
    {
        // FormRequestでバリデーション済み
        $user = app(CreateNewUser::class)->create($request->validated());

        // 認証メール送信イベント
        event(new Registered($user));

        // ログイン状態にする
        Auth::login($user);

        // メール未認証なら認証誘導画面へ
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->with('status', 'verification-link-sent');
        }

        // 認証済ならプロフィール編集へ
        return redirect()->route('profile.edit');
    }
}

