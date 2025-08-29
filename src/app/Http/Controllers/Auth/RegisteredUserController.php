<?php

namespace App\Http\Controllers\Auth;

use Laravel\Fortify\Contracts\CreatesNewUsers; 
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use App\Providers\RouteServiceProvider;



class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }
    
    
    public function store(\App\Http\Requests\RegisterRequest $request)
    
    {
    $user = app(\Laravel\Fortify\Contracts\CreatesNewUsers::class)
        ->create($request->validated());

     

    event(new \Illuminate\Auth\Events\Registered($user)); // 認証メール送信
     ;

    \Auth:: guard('web')->login($user);                 // ログイン
    $request->session()->regenerate();    // ★セッションID再生成（超重要）

     \Log::info('REGISTER REDIRECT to verification.notice; uid='.$user->id);


    return redirect()->route('verification.notice'); // /email/verify へ
    }

        
        
   

    }








