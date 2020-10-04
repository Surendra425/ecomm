<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Coupons;

class PromoCodeHelper
{
    
    /**
     * Apply promo code.
     *
     * @param $orderAmount
     * @param $promoCode
     * @param $isAllowCurrency
     * @return array
     */
    public static function applyPromoCode($orderAmount, $promoCode)
    {
        $promoCode = Coupons::where(['status' => 'Active', 'coupon_code' => $promoCode])->first();

        if ( ! empty($promoCode))
        {
            
            if ( ! empty($promoCode->start_date) && ! empty($promoCode->end_date))
            {
                $now = Carbon::now();
                $startDate = Carbon::parse($promoCode->start_date);
                $endDate = Carbon::parse($promoCode->end_date);
                
                if ($now < $startDate || $now > $endDate)
                {
                    return [
                        'status' => 0,
                        'msg' => trans('api.promo_code.expired'),
                        'promo_code_discount_amount' => null,
                        'promo_code' => null,
                    ];
                }
            }
            
            if ( ! empty($promoCode->min_total_amount))
            {
                
                if ($orderAmount < $promoCode->min_total_amount)
                {
                    \Log::debug(app()->getLocale());
                    return [
                        'status' => 0,
                        'msg' => trans('api.promo_code.minimum_order_error', ['amount' => $promoCode->min_total_amount ]),
                        'promo_code_discount_amount' => null,
                        'promo_code' => null, 
                    ]; 
                }
            }
            
            $countDiscount = 0;
            
            if ($promoCode->discount_type == 'percentage')
            {
                
                $couponDiscountAmount = $orderAmount * ($promoCode->discount_amount / 100);
                
                if ( ! empty($promoCode->max_discount_amount))
                {
                    
                    $maxAmount = $promoCode->max_discount_amount;
                    
                    $couponDiscountAmount = ($couponDiscountAmount > $maxAmount) ? $maxAmount : $couponDiscountAmount;
                }
            }
            else
            {
                
                $couponDiscountAmount =$promoCode->discount_amount;
            }
            
            return [
                'status' => 1,
                'msg' => trans('api.promo_code.success'),
                'promo_code_discount_amount' => round($couponDiscountAmount, 2),
                'promo_code' => $promoCode->coupon_code, 
            ];
        }
        
        return [
            'status' => 0,
            'msg' => trans('api.promo_code.error'),
            'promo_code_discount_amount' => null,
            'promo_code' => null, 
        ];
    }
    
}
