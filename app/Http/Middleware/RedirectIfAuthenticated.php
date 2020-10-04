<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
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
       // echo "hi";die;
        //echo $url = request()->segment(1);die;
        /*$url= url()->previous();
        $url =explode('public',$url);*/
       /* if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }*/
       /* if(strpos($url[1], 'admin/') !== false){
            return redirect('admin');
        }elseif (strpos($url[1],'vendor/register') !== false)
        {
            return $next($request);
        }
        elseif(strpos($url[1], 'vendor/') !== false){
            return redirect('vendor');
        }else{
            return $next($request);
        }*/
        return $next($request);
    }
}
