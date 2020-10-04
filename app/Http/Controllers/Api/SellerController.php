<?php

namespace App\Http\Controllers\Api;

use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Store;
use App\Mail\WelcomeMailVendor;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class SellerController extends Controller
{
    /*
     * $validationRules: validate
     */
    private $validationRules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'store_name' => 'required',
        'email' => 'required|unique:users|email',
        'mobile_no' => 'required|min:6',
        'password' => 'required',
    ];

    /**
     * Request for vendor.
     *
     * @param Request $request
     * @return json
     */
    public function requestForVendor(Request $request)
    {
        $this->validate($request, $this->validationRules);

        $vendor = new Vendor();
        $vendor->fill($request->all());
        $vendor->status = 0;
        $vendor->type = 'vendor';
        $vendor->pending_process = 'Yes';
        $vendor->password = bcrypt($request->password);

        DB::beginTransaction();
        
        // Check vendor is saved or not
        if ($vendor->save())
        {
            $store = new Store();
            $store->store_name = $request->store_name;
            $store->vendor_id = $vendor->id;
            $store->status ='Inactive';

            // Update store
            if($store->save())
            {
                $store->store_slug = str_slug($request->store. "-".$store->id);
                $store->save();
            }

            $vendor->name_slug = str_slug($request->first_name . "-" . $request->last_name . "-" . $vendor->id);
            $vendor->has_store = 'Yes';
            
            // Update vendor
            if($vendor->save())
            {
                DB::commit();
                
                // Send mail to vendor
                //Mail::to($vendor->email)->send(new WelcomeMailVendor($vendor));
                
                return $this->toJson(null, trans('api.request_vendor.success'));
            }
        }

        return $this->toJson(null, trans('api.request_vendor.error'), 0);
    }
}