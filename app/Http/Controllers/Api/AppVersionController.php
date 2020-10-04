<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AppVersions;
use App\ProductCart;
use App\AppUser;
use App\UserTokens;
use Carbon\Carbon;


class AppVersionController extends Controller
{
    private $validationRules = [
        'app_type' => 'required',
        'app_version' => 'required',
        'device_id' => 'required',
        'fcm_token' => 'nullable'
    ];
    
    /*
      |--------------------------------------------------------------------------
      | App Version Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles current version of app
     */

    /**
     * Check app version.
     *
     * @return json
     */
    public function checkAppVersion(Request $request)
    {
        $user = session()->get('authUser');

        $this->validate($request, $this->validationRules); 
        $appData = AppVersions::selectRaw('app_url, app_version, app_update_msg, app_maintenance_msg, app_is_update AS isUpdateType')
                              ->where('app_type', $request->app_type)
                              ->orderBy('id','desc')
                              ->first();
        
        $where = !empty($user) ? ['user_id' => $user->id] : [ 'device_id' => $request->device_id];

        $appUser = AppUser::firstOrCreate([
            'user_id' =>  !empty($user) ? $user->id : 0,
            'device_id' => $request->device_id,
            'app_version' => $request->app_version,
            'app_type' => $request->app_type
        ], [
            'device_name' => $request->device_name,
            'device_model' => $request->device_model,
        ]);



           /*if($now == $updateDate)
           {
               $isNotShowPopup = 1;
           }*/
  
           $appUser->updated_at = Carbon::now();
           $appUser->save();

            $userToken = UserTokens::where('fcm_token', $request->fcm_token)->first();

            $userToken = !empty($userToken) ? $userToken : new UserTokens();

            $userToken->user_id = $appUser->user_id;
            $userToken->device_id = $request->device_id;
            $userToken->notification_tag = ($request->device_model == 'iPhone') ? 'notification' : 'data';
            $userToken->fcm_token = $request->fcm_token;
            $userToken->language = $request->language;
            $userToken->save();


        //    \DB::raw('INSERT INTO user_tokens (user_id, fcm_token, language) 
        //    VALUES('.$appUser->user_id.', "'.$request->fcm_token.'", "'.$request->language. '") 
        //    ON DUPLICATE KEY UPDATE fcm_token = '.$request->fcm_token.','.$request->language);


        $cartCount = ProductCart::where($where)->count();
        if(!empty($appData))
        {

            /*if($appData->app_version != $request->app_version)
            {
                $users = AppUser::where(['device_id' => $request->device_id, 'is_show_update' => 1])->first();

                if(!empty($users))
                {
                    $appData->isUpdateType = 1;
                }

                AppUser::where('device_id', $request->device_id)->update(['is_show_update' => 1]);
            }
            else
            {
                $appData->isUpdateType = 1;
            }*/
            $appData->isUpdateType = 1;
            $appData->cart_count = $cartCount;
            $appData->is_active = !empty($user) ? $user->status : 0;
            $appData->contact_no = '+96599229889';
            return $this->toJson($appData);
        }

        return $this->toJson([], trans('api.app_version.not_available'), 0);
    }
}
