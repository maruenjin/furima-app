<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [\App\Http\Controllers\ItemController::class, 'index']);


   Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'create'])
        ->name('login');

    
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'store'])
        ->name('login.attempt')
        ->middleware('throttle:login');

              

    Route::get('/register', [RegisteredUserController::class,'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class,'store']);       // ← FormRequestで検証→CreateNewUser

    // パスワード再設定（画面は自作、処理はFortifyに委譲）
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// 認証済
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'destroy'])->middleware('auth')->name('logout');



// メール認証
Route::view('/email/verify', 'auth.verify-email')->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth','signed','throttle:6,1'])->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth','throttle:6,1'])->name('verification.send');

    // 認証が必要な画面
Route::middleware(['auth','verified', \App\Http\Middleware\EnsureProfileCompleted::class])->group(function () {
    Route::get('/mypage', [\App\Http\Controllers\MyPageController::class,'index']);
    Route::get('/mypage/profile', [\App\Http\Controllers\ProfileController::class,'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [\App\Http\Controllers\ProfileController::class,'update'])->name('profile.update');
});
