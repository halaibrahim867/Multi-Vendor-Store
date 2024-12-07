<?php

namespace App\Actions\Fortify;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use function Symfony\Component\String\u;

class AuthenticateUserForRemember
{
    public function authenticate($request)
    {
        $username = $request->post(config('fortify.username'));
        $password = $request->post('password');
        $remember = $request->filled('remember_token'); // Capture "Remember Me" checkbox value

        $user = User::Where('email', '=', $username)->first();


        //dd($remember);
        if ($user && Hash::check($password, $user->password)) {

           // $rememberDuration = now()->addMinutes(2);
           //$durationInMinutes = $rememberDuration->diffInMinutes(now());

            //dd($durationInMinutes);// Remember for 30 days

            Auth::guard('web')->login($user, $remember); // Pass $remember here
            //$cookie = cookie('remember_token', $rememberDuration);

            //dd($user);
            //return $user;


            // Manually setting the expiration for the remember_token cookie
            //$minutes = config('session.remember_lifetime', 1440); // Default to 1440 minutes (1 day)
            //$cookie = cookie('remember_token', Str::random(60), $minutes);

            // Return response with cookie
            return $user;
        }
        return false;
    }



}
