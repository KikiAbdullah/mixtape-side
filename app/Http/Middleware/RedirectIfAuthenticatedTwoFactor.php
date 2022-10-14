<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Cookie;
use Auth;

class RedirectIfAuthenticatedTwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(!env('APP_2FA')){
            return redirect(RouteServiceProvider::HOME);
        }
        $token2fa = Cookie::get(env('APP_2FA_NAME')??"token_2fa");
        $user = Auth::user();
        if(!empty($token2fa)){
            if($user->id==$token2fa){
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
