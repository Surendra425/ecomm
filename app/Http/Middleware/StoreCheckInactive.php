<?php

namespace App\Http\Middleware;

use App\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Closure;

class StoreCheckInactive
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
        $store = Store::where('vendor_id',$vendor->id)->first();
       if (isset($store) && $store->status == "Inactive")
        {
            return redirect(route('vendorInactivePage'));
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