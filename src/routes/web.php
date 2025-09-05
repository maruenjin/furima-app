<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCommentController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;

use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;

/**
 * 認証前（guest）
 */
Route::middleware('guest')->group(function () {
    // ログイン
    Route::get('/login',  [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.attempt')->middleware('throttle:login');

    // 会員登録
    Route::get('/register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // パスワード再設定
    Route::get('/forgot-password',  [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password',       [NewPasswordController::class, 'store'])->name('password.update');
});

/**
 * ログアウト
 */
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware(['web','auth'])
    ->name('logout');

/**
 * メール認証（Fortify）
 */
Route::view('/email/verify', 'auth.verify-email')->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth','signed','throttle:6,1'])->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth','throttle:6,1'])->name('verification.send');

/**
 * ログイン/登録直後の合流地点
 */
Route::get('/after-login', function () {
    $u = Auth::user();
    if (Features::enabled(Features::emailVerification()) && !$u->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }
    return $u->profile_completed
        ? redirect()->route('products.index')
        : redirect()->route('profile.edit');
})->middleware('auth')->name('after-login');

/**
 * プロフィール設定（初回ユーザーが入れるように EnsureProfileCompleted をかけない）
 */
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/mypage/profile',  [ProfileController::class,'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class,'update'])->name('profile.update');
});

/**
 * 通常画面（プロフィール完了が必要）
 */
Route::middleware(['auth','verified', \App\Http\Middleware\EnsureProfileCompleted::class])->group(function () {
    Route::get('/mypage', [MyPageController::class,'index'])->name('mypage.index');

    // 出品
    Route::get('/sell',  [ProductController::class, 'create'])->name('products.create');
    Route::post('/sell', [ProductController::class, 'store'])->name('products.store');

    // いいね
    Route::post('/item/{product}/like', [ProductController::class,'toggleLike'])->name('products.like');

    // コメント投稿
    Route::post('/item/{product}/comments', [ProductCommentController::class,'store'])
        ->name('products.comments.store');
});

/**
 * 便利エンドポイント
 */
Route::get('/whoami', function () {
    if (auth()->check()) return response()->json(['id'=>auth()->id(),'email'=>auth()->user()->email]);
    return response()->json(['authenticated'=>false]);
});

/**
 * 商品一覧 + マイリスト
 */
Route::get('/', [ProductController::class, 'index'])->name('products.index');
// 互換
Route::redirect('/items', '/');
Route::redirect('/products', '/');

/**
 * 商品詳細
 */
Route::get('/item/{product}', [ProductController::class, 'show'])->name('products.show');
// 互換
Route::get('/products/{product}', function (\App\Models\Product $product) {
    return redirect()->route('products.show', $product);
});






