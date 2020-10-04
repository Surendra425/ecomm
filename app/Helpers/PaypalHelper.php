<?php

namespace App\Helpers;

use Srmklive\PayPal\Facades\PayPal;

class PaypalHelper
{
    /*
     * Make subscription for  the user
     * @param $subscriptionId
     * @return
     
    public static function makeSubscription($profileId)
    {
        
        $provider = PayPal::setProvider('express_checkout');
        $response = $provider->cancelRecurringPaymentsProfile($profileId);
    }*/
    
    
    /*
     * Cancel the subscription for  the user
     * @param $subscriptionId
     * @return
     */
    public static function cancelSubscription($profileId)
    {
       
        $provider = PayPal::setProvider('express_checkout');
        return $provider->cancelRecurringPaymentsProfile($profileId);
    }
    
    /*
     * Cancel the subscription for  the user
     * @param $subscriptionId
     * @return
     */
    public static function isAllowCurrency($currencyCode)
    {
        

       $allowedCurrencies = config('constant.paypal.allowed_currencies');

       return in_array($currencyCode, $allowedCurrencies);
    }
}
