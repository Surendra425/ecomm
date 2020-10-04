<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class previousUrlCookie
{
    /**
     * Handle an incoming request & set previous url in cookie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $previousUrl = url()->previous();
        $setPrevUrl = (Cookie::get('previousUrl'))?Cookie::get('previousUrl'):'';

        preg_match("/[^\/]+$/", $previousUrl, $matches);
        $last_word = isset($matches[0])?$matches[0]:'';

        if($last_word != 'login' && $last_word != 'customer-register'){
            Cookie::forget('previousUrl');
            $setPrevUrl = cookie()->forever('previousUrl', url()->previous());

        }

        $response = $next($request);

        return $response->withCookie($setPrevUrl);

    }
}
