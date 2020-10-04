<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Closure;

class VendorBusiness
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        if ($guard == "vendor")
//        {
        
        $vendor = Auth::guard('vendor')->user();

        if (isset($vendor->pending_process) && $vendor->pending_process == "Yes")
        {
            return redirect(route('vendorBusinessDetail'));
        }
        else
        {
            return $next($request);
        }
//        }
//        else
//        {
//            return $next($request);
//        }
    }

}