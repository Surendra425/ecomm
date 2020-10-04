<?php

namespace App\Http\Controllers\Customer;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{

    /**
     * validation rules for update profile.
     *
     * @var string
     */
    private $validationRules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'gender' => 'required',
    ];


    /**
     * Display a profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['customer'] = Auth::guard('customer')->user();
        return view('front.user.profile', $data);

    }

    /**
     * update user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, $this->validationRules);
        $customer = Auth::guard('customer')->user();

        $nameSlug = str_slug($request->first_name ."-". $request->last_name."-".$customer->id, "-");
        $customer->name_slug = $nameSlug;
        $customer->gender = $request->gender;
        $customer->landline_no = $request->landline_no;
        $customer->fill($request->all());

        if($customer->save()){
            return redirect('profile')->with('success', trans('messages.profile_update.success'));
        }
            return redirect('profile')->with('error',trans('messages.profile_update.error'));
    }

    /**
     * Display the change password view.
     *
     * @return \Illuminate\Http\Response
     */
    public function getChangePassword()
    {
        $customer = Auth::guard('customer')->user();
        return view('front.user.change_password',['loginUser'=>$customer]);
    }

    /**
     * Update the coustomer password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postChangePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);
        $customer = Auth::guard('customer')->user();
        $user = User::where("email", $customer->email)->first();

        if(!Hash::check($request->old_password,$user->password)){
           return redirect('change-password')->with('error',trans('messages.customer.change_password.old_not_match'));
        }
        $user->forceFill([
            'password' => bcrypt($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        return redirect('change-password')->with('success',trans('messages.customer.change_password.success'));

    }
    
}
