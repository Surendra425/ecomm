<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Country;
use App\State;
use App\City;
use App\User;
use App\UserAddress;

class UserProfileController extends Controller
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
        'first_name' => 'required',
        'last_name' => 'required',
        'gender' => 'required',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
   /* public function __construct()
    {
        $this->middleware('auth');
    }*/

    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $address = UserAddress::where("user_id", $customer->id)->first();
        $city = $state = [];
        $country = Country::where("status", "Active")->get();
        /*if ( ! empty($address))
        {
            $state = State::where("status", "Active")->where("country_id", "=", $address->country_id)->get();
            $city = City::where("status", "Active")->where("state_id", "=", $address->state_id)->get();
        }*/
        $data = [];
        $data["customer"] = $customer;
        $data["address"] = $address;
        $data["country"] = $country;
        $data["state"] = $state;
        $data["city"] = $city;
        
        return view('app.user_profile', $data);
    }

    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $this->validate($request, $this->validationRules);
        $customer->gender = $request->gender;
        $customer->age = $request->age;
        $customer->fill($request->all());
        if ($customer->save())
        {
            $nameSlug = str_slug($request->first_name ."-". $request->last_name."-".$customer->id, "-");
            DB::table('users')
                ->where('id', $customer->id)
                ->update(['name_slug' => $nameSlug]);
//            $addressSlug = str_slug($request->full_name.'-'.$address->id, "-");
//            DB::table('user_addresses')
//                ->where('id', $address->id)
//                ->update(['address_slug' => $addressSlug]);
            return redirect('profile')->with('success', trans('messages.profile_update.success'));
        }
        return redirectback('profile')->with('error', trans('messages.error'));
    }

}
