<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 11/1/18
 * Time: 2:36 PM
 */

namespace App\Helpers;



use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PushNotificationHelper
{


    /**
     * Send Pushnotification
     *
     * @param $fields
     * @return mixed
     */
    public static function sendPushNotification($fields)
    {
        
        $headers = [
            'Authorization:key='.config('constant.notifications.TOKEN'),
            'Content-Type: application/json',
            'project_id:'.config('constant.notifications.PROJECT_ID'),
        ];
        
            try {

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, config('constant.notifications.URL'));
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                Log::debug('----------');
                Log::debug($result);
                $result = json_decode($result, TRUE);
                curl_close($ch);
                $status = (isset($result['success']) && $result['success'] !=0) ? true : false;
            }
            catch (\Exception $e)
            {
                $result = null;
                Log::debug('-----------');
                Log::debug(json_encode($e->getMessage()));
                $status =  false;
            }

        $data['data'] = [
            'status'=>$status,
            'response'=>$result
        ];

        return $data;
    }
}