<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Helpers\KirimWAHelper;
use Cookie;

class TwoFactorVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $redirectTo="?redirect=".urlencode($request->url());
        if(!env('APP_2FA')){
            return $next($request);
        }
       
        $now =  \Carbon\Carbon::now();
        $user = Auth::user();
        $lastrequest = $user->token_last_request??"1970-01-01 00:00:00";
        $token2fa = Cookie::get(env('APP_2FA_NAME')??"token_2fa");
        if(!empty($token2fa)){
            if($user->id==$token2fa){
                return $next($request);
            }
        }
        
        if(date("Y-m-d H:i:s",strtotime($lastrequest."+2 minutes")) < $now){
            $user->token_2fa = mt_rand(10000,99999);
            $user->save();
            // send wa
            if($user->nowa<>""){
                $user->token_last_request = $now;
                $user->save();
                $wa = new KirimWAHelper;
                if($wa->kirim($user->nowa, env('APP_NAME').' OTP Code', 'Your login code to '.env('APP_NAME').' is : '.$user->token_2fa, "Don't share this code to anyone.")){
                    return redirect('/2fa'.$redirectTo); 
                }else{
                    return redirect('/2fa'.$redirectTo)->withErrors('Something went wrong, try again later.'); 
                }
            }else{
                return redirect('/2fa'.$redirectTo); 
            }
        }else{
            return redirect('/2fa'.$redirectTo)->withErrors('Enter OTP Code that we\'ve sent you at '.$lastrequest); 
        }
        
    }
}
