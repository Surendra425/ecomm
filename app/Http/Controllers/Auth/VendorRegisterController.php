<?php

namespace App\Http\Controllers\Auth;

use App\Customer;
use App\Mail\WelcomeMailVendor;
use App\Store;
use App\User;
use App\Vendor;
use Illuminate\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\PlanOptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery\CountValidator\Exception;

class VendorRegisterController extends RegisterController
{
    /*
      |--------------------------------------------------------------------------
      | Vendor Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

    /**
     * Set current Guard.
     *
     * @var string
     */
    protected $guard = 'vendor';
    private $validationRules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'store' => 'required',
        'email' => 'required|email',
        'mobile_no' => 'required|unique:users|min:7|max:17',
        'password' => 'required|confirmed',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /* public function __construct()
      {
      parent::__construct();
      } */

    /**
     * Display login form.
     *
     * @return void
     */
    public function showRegisterForm()
    {
        if (Auth::guard('vendor')->user())
        {
            return redirect(route('vendorDashboard'));
        }
        return view('auth.vendor.register');
    }

    /**
     * Save the Vendor.
     *
     * @param Request $request
     * @return json
     */
    public function register(Request $request)
    {

        // Validate fields
        $this->validate($request, $this->validationRules);

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

        $vendor->fill($request->all());
        $vendor->status = 0;
        $vendor->type = 'vendor';
        $vendor->pending_process = 'Yes';
        $vendor->password = bcrypt($request->password);
        /*if (isset($request->plan_option) && ! empty($request->plan_option))
        {
            $vendor->selected_plan_option_id = $request->plan_option;
        }*/

        if ($vendor->save())
        {
            $nameSlug = str_slug($request->first_name . "-" . $request->last_name . "-" . $vendor->id, "-");
            $store = new Store();
            $store->store_name = $request->store;
            $store->vendor_id = $vendor->id;
            $store->status ='Inactive';
            $store->save();
            $storeSlug = str_slug($request->store. "-".$store->id);
            //echo $storeSlug; die;
            DB::table('stores')
                ->where('id', $store->id)
                ->update(['store_slug' => $storeSlug]);
            DB::table('users')
                    ->where('id', $vendor->id)
                    ->update(['has_store' => 'Yes']);

            DB::table('users')
                    ->where('id', $vendor->id)
                    ->update(['name_slug' => $nameSlug]);
            $vendor_id = $vendor->where('email', $vendor->email)->max('id');
            $post = array ('password' => $request->password, 'email' => $request->email);
            
            try
            {
                Mail::to($vendor->email)->send(new WelcomeMailVendor($vendor));
            }
            catch (Exception $exc)
            {
            //echo $exc->getTraceAsString();
            }
            //return redirect(route('vendorBusinessDetail'));
            return redirect(route('sell-with-us.index'))->with('success', trans('messages.vendor.register'));;
        }
        return redirectback(route('vendorRegister'))->with('error', trans('messages.error'));
    }

    public function showRegisterFormWithPlan(PlanOptions $plan_option)
    {
        if (Auth::guard('vendor')->user())
        {
            return redirect(route('vendorDashboard'));
        }
        $data['plan_option'] = $plan_option;
        return view('auth.vendor.register', $data);
    }

}
