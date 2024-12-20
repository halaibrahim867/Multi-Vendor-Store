<?php

namespace App\Providers;

use App\Actions\Fortify\AuthenticateUser;
use App\Actions\Fortify\AuthenticateUserForRemember;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Config;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $request= \request();
        if ($request->is('admin/*')){

            Config::set('fortify.guard','admin');
            Config::set('fortify.passwords','admins');
            Config::set('fortify.prefix','admin');
            //Config::set('fortify.home','admin/dashboard');
        }else {
            Config::set('fortify.guard', 'web');
            Config::set('fortify.passwords', 'users');
            Config::set('fortify.prefix', '');
        }

        $this->app->instance(LoginResponse::class,new class implements LoginResponse{
            public function toResponse($request)
            {
                 if ($request->user('admin')){
                     return redirect()->intended('admin/dashboard');
                 }
                 return redirect()->intended('/');
            }
        });
        /*$this->app->instance(LogoutResponse::class,new class implements LogoutResponse{
            public function toResponse($request)
            {
                // Log out the user
                $user=Auth::guard('web')->logout();

                // Invalidate the session
                $request->session()->invalidate();

                // Regenerate the session token
                //$request->session()->regenerateToken();

                // Clear the remember me cookie (dynamically get the cookie name based on guard)
                $cookieName = 'remember_' . Auth::guard('web')->getRecallerName();
                //dd(Cookie::get());
                Cookie::forget($cookieName);

                // Debugging: Uncomment this to check the cookies
                if ($user) {
                    $user->forceFill(['remember_token' => null])->save();
                }

                // Redirect to the homepage or login page
                return redirect('/')->with('success', 'Signed out successfully');
            }
        });*/
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);



        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        if (Config::get('fortify.guard') == 'admin'){
            Fortify::authenticateUsing([new AuthenticateUser,'authenticate']);

            Fortify::viewPrefix('auth.');
        }else{
            Fortify::authenticateUsing([new AuthenticateUserForRemember,'authenticate']);
            Fortify::viewPrefix('front.auth.');
        }


        //Fortify::loginView('auth.login');
       // Fortify::registerView(function (){
        //    return view('auth.register');
        //});
    }
}
