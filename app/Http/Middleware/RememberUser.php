<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RememberUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard(config('fortify.guard')); // Use dynamic guard

        if (!Auth::check() && $request->hasCookie(Auth::guard('web')->getRecallerName())) {
            // Check if the user can be authenticated via the "remember me" cookie
            $user = Auth::guard('web')->viaRemember();

            // If no user was authenticated, do not proceed with the next request
            if (!$user) {
                return redirect()->route('login'); // Redirect to the login page or wherever you want
            }
        }
        return $next($request);
    }
}
