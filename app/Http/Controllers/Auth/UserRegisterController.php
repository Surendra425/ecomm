<?php

namespace App\Http\Controllers\Auth;

use App\Customer;
use App\Helpers\ApiHelper;
use App\Helpers\NameHelper;
use App\Mail\WelcomeMail;
use App\ProductAttrCombination;
use App\ProductCart;
use Illuminate\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Country;
use App\State;
use App\UserAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserRegisterController extends RegisterController
{
    /*
      |--------------------------------------------------------------------------
      | Admin Login Controller
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
    protected $guard = 'customer';
    private $validationRules = [
        'first_name' => 'required|max:55',
        'last_name' => 'required|max:55',
        'gender' => 'required',
        'email' => 'required|email',
        'mobile_no' => 'min:7|max:17',
        'password' => 'required|confirmed'
    ];

    private $validationRulesForAddress = [

        'block' => 'required',
        'street' => 'required',
        'building' => 'required',
        'city_id' => 'required',
        'country_id' => 'required',
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
        if (Auth::guard('customer')->user())
        {
            return redirect(url('home'));
        }
        $country = Country::where("status", "Active")->get();
        
        $data['country'] = $country;
        return view('front.auth.register', $data);
    }

    /**
     * Save the Vendor.
     *
     * @param Request $request
     * @return json
     */
    public function register(Request $request)
    {
        
        if ($request->has_address == "no")
        {
            $this->validate($request, $this->validationRules);
        }else{
            $this->validate($request, array_merge($this->validationRules,$this->validationRulesForAddress));
        }
        $browserId = \Cookie::get('browserId');

        // Validate fields
        $user = new Customer();
        $customer = User::where('email',$request->email)->first();

        if(!empty($customer) && $customer->type != 'guest')
        {
            return redirect(route('customerRegister'))->with('error', trans('messages.users.already_exist'));
        }
        elseif (!empty($customer) && $customer->type == 'guest')
        {
            $user = $customer;
        }

        $user->fill($request->all());
        $user->status = 1;
        $user->type = 'customer';
        $user->gender = !empty($request->gender) ? $request->gender : '';
        $user->password = bcrypt($request->password);
        if($user->save())
        {
            $nameSlug = str_slug($request->first_name . "-" . $request->last_name . "-" . $user->id, "-");
            $user->name_slug = $nameSlug;
            $user->save();

            if ($request->has_address == "yes")
            {
                $customer_address = new UserAddress();
                $customer_address->fill($request->all());
                $customer_address->mobile = $request->mobile_no;
                $customer_address->user_id = $user->id;
                $city_name = NameHelper::getNameBySingleId('city','city_name','id',$request->city_id);
                $country_name = NameHelper::getNameBySingleId('country','country_name','id',$request->country_id);
                $customer_address->city = $city_name;
                $customer_address->country = $country_name;
                $customer_address->is_selected = 'Yes';
                $customer_address->save();

            }
            $previousUrl = \Cookie::get('previousUrl');

            Mail::to($user->email)->send(new WelcomeMail($user));

            ApiHelper::moveCartProducts($browserId, $user);

            Auth::guard('customer')->loginUsingId($user->id);

            return redirect($previousUrl)->with('success', trans('messages.users.register'));
        }
        return redirect(route('customerRegister'))->with('error', trans('messages.users.error'));
    }

}
