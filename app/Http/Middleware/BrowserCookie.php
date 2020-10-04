<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Cookie;
class BrowserCookie
{   

    /**
     * The names set of the cookies 
     * 
     * @param $request ,$next
     * @return $browserId
     */
    public function handle($request, Closure $next )
	{
	    $response = $next($request);
        
        if($request->hasCookie('browserId')) {
            return $response;    
        }

        $browserId = 'browser_'.str_random(45);

        return $response->withCookie(cookie()->forever('browserId', $browserId));
    }
}
