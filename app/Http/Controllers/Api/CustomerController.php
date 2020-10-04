<?php

namespace App\Http\Controllers\Api;

use App\UserTokens;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\Collections;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Customer Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles customer get profile, update profile, change password.
     */

    /**
     * Update profile details.
     * 
     * @param Request $request
     * @return json
     */
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_no' => 'nullable',
            'gender' => 'nullable|in:Male,Female',
            'device_id' => 'required',
        ]);
        
        $user = session()->get('authUser');
        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->mobile_no = $request->mobile_no;
        $user->gender = $request->gender;
        $user->device_id = $request->device_id;
        
        if($user->save())
        {
            return $this->toJson(null, trans('api.update_profile.success'), 1);
        }
        
        return $this->toJson(null, trans('api.update_profile.error'), 0);
    }

    /**
     * Change password of the user.
     *
     * @param Request $request
     * @return json
     */
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required',
        ]);
        
        $user = session()->get('authUser');

        if (Hash::check($request->current_password, $user->password)) {
            
            $user->password = bcrypt($request->password);
            $user->save();

            return $this->toJson(null, trans('api.change_password.success'), 1);
        }

        return $this->toJson(null, trans('api.change_password.error'), 0);
    }


    /**
     * Change Language api
     * @param Request $request
     * @return Response Json
     * 
     */
    public function changeLanguage(Request $request)
    {
        $this->validate($request, [
            'language' => 'required|in:en,ar',
            'device_id' => 'required'
        ]);

        $user = session()->get('authUser');

        if(!empty($user))
        {
            $user->language = $request->language;
            $user->save();

            UserTokens::where('user_id', $user->id)->update(['language' => $user->language]);
            

            return $this->toJson(null, trans('api.user.language_success'), 1);
        }
        else
        {
            UserTokens::where('device_id', $request->device_id)->update(['language' => $request->language]);

            return $this->toJson(null, trans('api.user.language_success'), 1);
        }
    }

    /**
     * Logout API
     *
     * @param Request $request
     * @return \App\Http\Controllers\json
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'device_id' => 'required',
            'user_id' => 'required'
        ]);

        $user = session()->get('authUser');

        if(!empty($user))
        {
            UserTokens::where(['user_id', $user->id])->update(['user_id' => 0]);
        }

        \Session::flush();

        return $this->toJson(null,trans('api.auth.logout'), 1);

    }
}
