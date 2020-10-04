<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Auth;
use App\Vendor;
use App\Store;
use App\VendorDepositInfo;
use App\Plans;
use App\PlanOptions;
use App\VendorPlanDetail;
use App\Helpers\CurrencyHelper;
use App\Helpers\PaypalHelper;
use Srmklive\PayPal\Services\ExpressCheckout;
use Carbon\Carbon;
use Srmklive\PayPal\Facades\PayPal;

class PaymentController extends Controller
{

    public function successVendor(Request $request, $vendor_plan_id)
    {
        $token = $request->token;
        $PayerID = $request->PayerID;
        $provider = new ExpressCheckout();
        $data = $provider->getExpressCheckoutDetails($token);
        $VendorPlanDetail = VendorPlanDetail::find($vendor_plan_id);
    
        if ( ! empty($VendorPlanDetail))
        {
            $startdate = Carbon::now()->toAtomString();
            $currencyCode = CurrencyHelper::getCurrencyCode();
            $price = CurrencyHelper::getPriceWithoutSymbol($VendorPlanDetail->price);
            $isAllowCurrency = PaypalHelper::isAllowCurrency($currencyCode);
            $conversionRate = CurrencyHelper::getCurrencyConverter();
            $currencySymbol = CurrencyHelper::getSymbol();
            // Check paypal allow that currency or not.
            if ( ! $isAllowCurrency)
            {
                $currencyCode = 'USD';
                $price = $VendorPlanDetail->price;
                $conversionRate = 1;
                $currencySymbol = '&#36;';
            }
            $data = [
                'PROFILESTARTDATE' => Carbon::now()->toAtomString(),
                'DESC' => "Plan #{$VendorPlanDetail->id} Invoice",
                'BILLINGPERIOD' => config('constant.paypal.billing_period.' . "monthly"),
                'BILLINGFREQUENCY' => 1,
                'AMT' => $price,
                'CURRENCYCODE' => $currencyCode,
            ];

            $provider = PayPal::setProvider('express_checkout');
            $response = $provider->createRecurringPaymentsProfile($data, $token);
            if ( ! empty($response['ACK']) && $response['ACK'] == 'Success')
            {
                $customerId = Auth::user()->id;
                $paymentSubscriptionId = $response['PROFILEID'];
                $paymentCustomerId = $response['CORRELATIONID'];
                $jsonSubscriptionResponse = json_encode($response);

                $isSubscription = $this->makeSubscription($request->payment_type, $customerId, $paymentSubscriptionId, $paymentCustomerId, $VendorPlanDetail->id, $VendorPlanDetail->name, $VendorPlanDetail->price, 0, $conversionRate, $currencyCode, $currencySymbol, $jsonSubscriptionResponse);
//
//                if ($isSubscription)
//                {
                    $vendor = Auth::guard('vendor')->user();
                    $vendor->pending_process = NULL;
                    $VendorPlanDetail->payment_status = "Approved";
                    $VendorPlanDetail->description = $jsonSubscriptionResponse;

                    if ($VendorPlanDetail->save() && $vendor->save())
                    {
                        return redirect(route('vendorDashboard'))->with('success', trans('messages.vendor.payment.success'));
                    }
//                }
//                else
//                {
//                    $VendorPlanDetail->payment_status = "Failed";
//                    $VendorPlanDetail->description = $jsonSubscriptionResponse;
//                    if ($VendorPlanDetail->save())
//                    {
//                        return redirect(route('vendorDashboard'))->with('error', trans('messages.error'));
//                    }
//                }
            }
            return redirect(route('vendorDashboard'))->with('error', trans('messages.error'));
        }
    }
    
    /**
     * Add subscription entry.
     * 
     * @param $paymentType
     * @param $customerId
     * @param $paymentSubscriptionId
     * @param $paymentCustomerId
     * @param $planId
     * @param $planName
     * @param $amount
     * @param $discount
     * @param $jsonSubscriptionResponse
     * @param $conversionRate
     * @param $currencyCode
     *
     * @return \Illuminate\Http\Response
     */
    private function makeSubscription($paymentType, $customerId, $paymentSubscriptionId, $paymentCustomerId, $planId, $planName, $amount, $discount, $conversionRate, $currencyCode, $currencySymbol, $jsonSubscriptionResponse) 
    {
        $subscriptionResponse = new UserSubscription();
        $subscriptionResponse->customer_id = $customerId;
        $subscriptionResponse->payment_type = $paymentType;
        $subscriptionResponse->payment_subscription_id = $paymentSubscriptionId;
        $subscriptionResponse->payment_customer_id = $paymentCustomerId;
        $subscriptionResponse->plan_id = $planId;
        $subscriptionResponse->plan_name = $planName;
        $subscriptionResponse->amount = $amount;
        $subscriptionResponse->conversion_rate = $conversionRate;
        $subscriptionResponse->currency_code = $currencyCode;
        $subscriptionResponse->discount = $discount;
        $subscriptionResponse->currency_symbol = $currencySymbol;
        
        
        $subscriptionResponse->subscription_response = $jsonSubscriptionResponse;

        return $subscriptionResponse->save();
    }

    //
}
