<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends LoginController
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
    protected $guard = 'admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
       // Auth::guard('admin')->loginUsingId(977);

        if (Auth::guard('admin')->user())
        {
            return redirect(route('adminDashboard'));
        }
        return view('auth.admin.login');
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
        
        $request->session()->invalidate();
        
        return redirect('/admin')->with('error', trans('auth.inactive'));
        }
        
        return redirect('admin/dashboard');
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
        
        return redirect('/admin');
    }

}
