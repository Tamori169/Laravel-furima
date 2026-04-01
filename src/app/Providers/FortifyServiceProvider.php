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
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 会員登録後のリダイレクト先を強制的に変更
        $this->app->instance(
            \Laravel\Fortify\Http\Responses\RegisterResponse::class,
            new class implements \Laravel\Fortify\Contracts\RegisterResponse {
                public function toResponse($request) {
                    return redirect('/email/verify'); 
                }
            }
        );

        // メール認証成功直後のリダイレクト先を指定
        $this->app->instance(
            VerifyEmailResponse::class, new class implements VerifyEmailResponse {
                public function toResponse($request) {
                    return redirect('/setup-profile');
                }
            }
        );

        // ログアウト後のリダイレクト先をカスタマイズ
        $this->app->instance(
            LogoutResponse::class, new class implements LogoutResponse {
                public function toResponse($request) {
                    return redirect('/');
                }
            }
        );

        // ログインリクエストを実装 //
        $this->app->bind(
        \Laravel\Fortify\Http\Requests\LoginRequest::class,
        \App\Http\Requests\LoginRequest::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
