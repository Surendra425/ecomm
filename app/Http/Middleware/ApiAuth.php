<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'guest')
    {


        if(empty($request->language))
        {
            $request->language = 'en';
        }

        $keyMd5 = md5(config('api.key'));

        $user = null;
        
        // Check auth key is valid or not
        if($request->header('Authentication') == $keyMd5) 
        {
            if($request->user_id) {
               
                $user = User::find($request->user_id);
            }
        }
        else 
        {
            return response()->json([
                'status' => 0,
                'result' => new \stdClass(),
                'message' => trans('api.auth.invalid'),
            ]);
        }

        if((!empty($user) && $user->status == 0) && $guard != 'guest')
        {
            return response()->json([
                'status' => 0,
                'result' => new \stdClass(),
                'message' => trans('api.auth.inactive_user'),
            ]);
        }
        // Check user is valid or not
        if((!empty($user)) || $guard == "guest") {
            
            session()->put('authUser', $user);
            $request->isAr = 0;
            $locale = 'en';

            if($request->language == "ar")
            {
                $request->isAr = 1;

                app()->setLocale("ar");

                $locale = 'ar';
            }
            session()->put('appLocale', $locale);

            return $next($request);
        }
        
        return response()->json([
            'status' => 0,
            'result' => new \stdClass(),
            'message' => trans('api.auth.invalid_user'),
        ]);
    }
}
