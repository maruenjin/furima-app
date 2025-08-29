<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str; 
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        

         // ★ ログイン直後の遷移先を要件通りに分岐
    $this->app->singleton(LoginResponse::class, function () {
        return new class implements LoginResponse {
            public function toResponse($request)
            {
                $user = $request->user();

                // 未認証なら認証案内へ
                if (method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail()) {
                    return redirect()->route('verification.notice');
                }

                // プロフィール未完了なら編集へ
                if (empty($user->postal_code) || empty($user->address) /* || empty($user->avatar_path) 等 */) {
                    return redirect()->route('profile.edit');
                }

                // 既定
                return redirect()->intended(\App\Providers\RouteServiceProvider::HOME);
            }
        };
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(\App\Actions\Fortify\CreateNewUser::class);

        


        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    
    //画面の割り当て
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn ($req) => view('auth.reset-password', ['request' => $req]));

 
    }
}
