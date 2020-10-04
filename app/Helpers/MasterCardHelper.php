<?php
/**
 * Created by Jayesh Rathod.
 * User: ashwin
 * Date: 11/1/18
 * Time: 2:36 PM
 */

namespace App\Helpers;


class MasterCardHelper
{

    public static $userName = '';
    public static $merchantId = '';
    public static $password = '';
    public static $endPoint = '';

    public static function setConfig(){

        self::$userName = env('USERNAME');
        self::$merchantId = env('MERCHANTID');
        self::$password = env('PASSWORD');
        self::$endPoint = env('ENDPOINT');
    }

    /*
     * Generate master card sesson.
     *
     * @param $orderId
     * @param $amount
     * @param $currency
     *
     * @return object
     */
    public static function generateMasterCardSession($orderId, $amount, $currency = 'KWD')
    {
        self::setConfig();

        try {
            
            $client = new \GuzzleHttp\Client();
            
            //dd(self::$endPoint,self::$merchantId,self::$userName,self::$password);
            $request = $client->post(self::$endPoint.'/'.self::$merchantId.'/session', [
                'auth' => [self::$userName, self::$password],
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'data' => [
                    "apiOperation" => 'CREATE_CHECKOUT_SESSION',
                    'order' => [
                        'id' => $orderId,
                        'amount' => $amount,
                        'currency' => 'KWD',
                    ]
                ],
                'verify'=> false
            ]);
            
            //return $request;
            return json_decode($request->getBody());
        }
        catch (\Exception $e)
        {
            return  null;
        }
        
    }

    /*
     * Get order status.
     *
     * @param $orderId
     *
     * @return object
     */
    public static function getOrderStatus($orderId)
    {
        self::setConfig();
        try {
            
            $client = new \GuzzleHttp\Client();
            
            $request = $client->get(self::$endPoint.'/'.self::$merchantId.'/order/'.$orderId, [
                'auth' => [self::$userName, self::$password],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            
            return json_decode($request->getBody());
        }
        catch (\Exception $e)
        {
            return  null;
        }
        
    }
}