<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class UserLoginController extends LoginController
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/my-cart';

    /*public function __construct()
    {
        parent::__construct();
    }*/
    /**
     * Display login form.
     *
     * @return void
     */
    public function showLoginForm()
    {
        
        if (Auth::guard('customer')->user())
        {
            $user = Auth::guard('customer')->user();
            return redirect(route('home'));
        }
        return view('front.auth.login');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if($user->status == 0){
        $this->guard()->logout();

        $cookie = \Cookie::forget('browserId');

        $request->session()->invalidate();

        return redirect()->back()->withCookie($cookie)->with('error', trans('auth.inactive'));
        }

        $previousUrl = Cookie::get('previousUrl');

        return Redirect::to($previousUrl);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $browserId = \Cookie::get('browserId');
        
        $user = Auth::user('customer');

        if(!empty($browserId)){
            ApiHelper::moveCartProducts($browserId,$user);
        }

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $cookie = \Cookie::forget('cookie.cartItem');
        $request->session()->invalidate();

        return redirect('/')->withCookie($cookie);
    }
}
