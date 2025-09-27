<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCommentController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureProfileCompleted;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;

use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;


Route::middleware('guest')->group(function () {
    
    Route::get('/login',  [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.attempt')->middleware('throttle:login');

    
    Route::get('/register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

   
    Route::get('/forgot-password',  [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password',       [NewPasswordController::class, 'store'])->name('password.update');
});


Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware(['web','auth'])
    ->name('logout');

Route::view('/email/verify', 'auth.verify-email')->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth','signed','throttle:6,1'])->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth','throttle:6,1'])->name('verification.send');


Route::get('/after-login', function () {
    $u = Auth::user();
    if (Features::enabled(Features::emailVerification()) && !$u->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }
    return $u->profile_completed
        ? redirect()->route('products.index')
        : redirect()->route('profile.edit');
})->middleware('auth')->name('after-login');


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/mypage/profile',  [ProfileController::class,'edit'])->name('profile.edit');
     Route::match(['put','post'], '/mypage/profile', [ProfileController::class,'update'])->name('profile.update');
});


Route::middleware(['auth','verified', \App\Http\Middleware\EnsureProfileCompleted::class])->group(function () {

     
    
    Route::get('/purchase/{product}',  [OrderController::class,'create'])->name('orders.create');
    Route::post('/purchase/{product}', [OrderController::class,'store'])->name('orders.store');

    
    Route::get('/purchase/address/{product}',  [ProfileController::class,'editAddress'])->name('purchase.address.edit');
    Route::patch('/purchase/address/{product}',[ProfileController::class,'updateAddress'])->name('purchase.address.update');

    Route::match(['post','patch'], '/mypage/profile/address/{product}', [ProfileController::class, 'updateAddress'])
    ->name('profile.address.update');



    Route::get('/mypage', [MyPageController::class,'index'])->name('mypage.index');

    
    Route::get('/mypage/purchases', [MyPageController::class,'purchases'])->name('mypage.purchases');


    
    Route::get('/sell',  [ProductController::class, 'create'])->name('products.create');
    Route::post('/sell', [ProductController::class, 'store'])->name('products.store');

    
    Route::post('/item/{product}/like', [ProductController::class,'toggleLike'])->name('products.like');

   
    Route::post('/item/{product}/comments', [ProductCommentController::class,'store'])
        ->name('products.comments.store');
    
    

});


Route::get('/whoami', function () {
    if (auth()->check()) return response()->json(['id'=>auth()->id(),'email'=>auth()->user()->email]);
    return response()->json(['authenticated'=>false]);
});


Route::get('/', [ProductController::class, 'index'])->name('products.index');

Route::redirect('/items', '/');
Route::redirect('/products', '/');


Route::get('/item/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/products/{product}', function (\App\Models\Product $product) {
    return redirect()->route('products.show', $product);
});

Route::get('/dev/log-test', function () {
    \Illuminate\Support\Facades\Log::debug('dev.log-test ping');
    return 'ok';
});






