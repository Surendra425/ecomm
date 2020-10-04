<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class VendorLoginController extends LoginController
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
    protected $guard = 'vendor';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
   /* public function __construct()
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
        if (Auth::guard('vendor')->user())
        {
            $user = Auth::guard('vendor')->user();
            if($user->pending_process == "Yes")
                {
                    return redirect(route('vendorBusinessDetail'));
                }
            return redirect(route('vendorDashboard'));
           
        }
        return view('auth.vendor.login');
    }

    public function loginWithId()
    {
        $user = Auth::guard('vendor')->loginUsingId(232, true);

        return redirect(route('vendorDashboard'));
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

        if($user->status == 1){
          if ($user->pending_process == "Yes")
            {
                return redirect(route('vendorBusinessDetail'));
            }  
        }else{

            $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(route('vendorLogin'))->with('error', trans('auth.inactive'));
           /* return $this->logout($request)->withMessages([$this->username() => "Your account is Inactive, please contact to Admin",]);*/
        }
        
        	
        return redirect(route('vendorDashboard'));
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

        $request->session()->invalidate();

        return redirect(route('vendorLogin'));
    }
    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws ValidationException
     */
    /*protected function sendFailedLoginResponse(Request $request)
    {
        
        throw ValidationException::withMessages([
            $this->username() => "Your account is Inactive, please contact to Admin",
        ]);
    }*/


}
