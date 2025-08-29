<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Contracts\Support\Responsable;   
use Symfony\Component\HttpFoundation\Response;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

class LoginController extends Controller
{
    // ログインフォーム表示
    public function create(): View
    {
        return view('auth.login');
    }

    // ログイン処理
    public function store(\App\Http\Requests\LoginRequest $request)
{
    $cred = $request->validated();
    
    if (\Auth::guard('web')->attempt($cred, false)) {
        $request->session()->regenerate();

        
        if (method_exists($request->user(), 'hasVerifiedEmail') && ! $request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        
        return redirect()->intended(\App\Providers\RouteServiceProvider::HOME);
    }

    
    return back()
        ->withErrors(['email' => 'ログイン情報が登録されていません'])
        ->onlyInput('email');
}
    

     
   

    // ログアウト
    public function destroy(Request $request): Response
    {
        $resp = app(AuthenticatedSessionController::class)->destroy($request);
        return $resp instanceof Responsable ? $resp->toResponse($request) : $resp;
    }
}


