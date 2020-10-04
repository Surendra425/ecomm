<?php

namespace App\Http\Controllers\Auth;


use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\One\TwitterProvider;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Customer;
use App\User;
class SocialController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Social Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    /**
     * Redirect to  social login page.
     *
     * @return void
     */
    public function index($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function store($provider)
    {
        $previousUrl = \Cookie::get('previousUrl');
        
        $user = Socialite::driver($provider)->user();
            
        if(!empty($user->email)) {

            $customer = User::where('email', $user->email)->first();

            //check user is customer
            if(!empty($customer) && $customer->type != 'customer'){

               return redirect($previousUrl)->with('error', trans('messages.social_login.vendor_error'));

            }

            $browserId = \Cookie::get('browserId');



            if(empty($customer)) {

                $username = explode(' ', $user->name);

                $customer = new Customer();
                $customer->email = $user->email;
                $customer->type = 'customer';
                $customer->first_name = !empty($username[0]) ? $username[0] : ' ';
                $customer->last_name = !empty($username[1]) ? $username[1] : ' ';
                $customer->password = bcrypt(str_random(8));

                $columnId = $provider . '_id';
                $customer->$columnId = $user->id;

                if ($customer->save()) {

                    $nameSlug = str_slug($customer->first_name . "-" . $customer->last_name . "-" . $customer->id, "-");
                    $customer->name_slug = $nameSlug;
                    $customer->save();

                    Mail::to($user->email)->send(new WelcomeMail($customer));

                    Auth::guard('customer')->loginUsingId($customer->id);

                    ApiHelper::moveCartProducts($browserId,$customer);

                    return redirect($previousUrl)->with('success', trans('messages.users.register'));
                }
                return redirect(route('login'))->with('error', trans('messages.users.error'));

            }else{

                Auth::guard('customer')->loginUsingId($customer->id);

                ApiHelper::moveCartProducts($browserId,$customer);

                return redirect($previousUrl);
            }
        }

        return redirect(route('login'))->with('error', trans('messages.social_login.error'));
    }
}
