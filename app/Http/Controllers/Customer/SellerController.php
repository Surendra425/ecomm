<?php

namespace App\Http\Controllers\Customer;

use App\Customer;
use App\Mail\VendorAccountActivation;
use App\Mail\WelcomeMailVendor;
use App\Store;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SellerController extends Controller
{
    /*
     * $validationRules: validate
     */

    private $validationRules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'store_name' => 'required',
        'email' => 'required|email',
        'mobile_no' => 'required|min:7|max:17',
        'password' => 'required',
    ];

    /**
     * Vendor Register view
     * @return View
     */
    public function index(){
        return view('front.vendor.register');
    }

    /**
     * Register Vendor
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){
        
        $this->validate($request,$this->validationRules);

        $vendor = new Customer();

        $customer = User::where('email',$request->email)->first();

        if(!empty($customer) && $customer->type != 'guest')
        {
            return redirect(route('sellWithUs'))->with('error', trans('messages.users.already_exist'));
        }
        elseif (!empty($customer) && $customer->type == 'guest')
        {
            $vendor = $customer;
        }

        $existStore = Store::where('store_name', $request->store_name)->first();
        
        if(!empty($existStore))
        {
            return redirect(route('sellWithUs'))->with('error', trans('messages.store.already_exist'));
        }

        $vendor->fill($request->all());
        $vendor->status = 0;
        $vendor->type = 'vendor';
        $vendor->pending_process = 'Yes';
        $vendor->password = bcrypt($request->password);

        \DB::beginTransaction();

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
                $store->store_slug = str_slug($request->store_name. "-".$store->id);
                $store->save();
            }

            $vendor->name_slug = str_slug($request->first_name . "-" . $request->last_name . "-" . $vendor->id);
            $vendor->has_store = 'Yes';

            // Update vendor
            if($vendor->save())
            {
                \DB::commit();

                // Send mail to vendor
                Mail::to($vendor->email)->send(new WelcomeMailVendor($vendor));

                return redirect(route('sellWithUs'))->with('success', trans('messages.vendor.register'));
            }
        }
        return redirect()->back(route('sellWithUs'))->with('error', trans('messages.vendor.register_error'));

    }
}
