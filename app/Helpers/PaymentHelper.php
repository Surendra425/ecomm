<?php

namespace App\Helpers;

use App\Coupons;
use Carbon\Carbon;

class PaymentHelper
{

    /**
     * Apply coupon code.
     *
     * @param $orderAmount
     * @param $couponCode
     * @param $isAllowCurrency
     * @return array
     */
    public static function applyCouponCode($orderAmount, $couponCode, $isAllowCurrency = true)
    {
        $couponCode = Coupons::where(['status' => 1, 'coupon_code' => $couponCode])->first();

        if ( ! empty($couponCode))
        {

            if ( ! empty($couponCode->start_date) && ! empty($couponCode->end_date))
            {

                $now = Carbon::now();
                $startDate = Carbon::parse($couponCode->start_date);
                $endDate = Carbon::parse($couponCode->end_date);

                if ($now < $startDate || $now > $endDate)
                {
                    return [
                        'status' => 0,
                        'msg' => 'your promontional code is expired.',
                    ];
                }
            }

            if ( ! empty($couponCode->min_total_amount))
            {

                if ($orderAmount < $couponCode->min_total_amount)
                {

                    return [
                        'status' => 0,
                        'msg' => 'Minimum order amount must be ' . $couponCode->min_total_amount . ' for this coupon code.',
                    ];
                }
            }

            $countDiscount = 0;

            if ($couponCode->discount_type == 'percentage')
            {

                $couponDiscountAmount = $orderAmount * ($couponCode->discount_amount / 100);

                if ( ! empty($couponCode->max_discount_amount))
                {

                    $maxAmount = $isAllowCurrency ? CurrencyHelper::getPriceWithoutSymbol($couponCode->max_discount_amount) : $couponCode->max_discount_amount;

                    $couponDiscountAmount = ($couponDiscountAmount > $maxAmount) ? $maxAmount : $couponDiscountAmount;
                }
            }
            else
            {

                $couponDiscountAmount = $isAllowCurrency ? CurrencyHelper::getPriceWithoutSymbol($couponCode->discount_amount) : $couponCode->discount_amount;
            }

            return [
                'status' => 1,
                'msg' => 'Coupon has been applied successfully.',
                'couponDiscountAmount' => round($couponDiscountAmount, 2),
                'couponCode' => $couponCode->coupon_code,
            ];
        }

        return [
            'status' => 0,
            'msg' => 'Invalid coupon code.',
        ];
    }

}
