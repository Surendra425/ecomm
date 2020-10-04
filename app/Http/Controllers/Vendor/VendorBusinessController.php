<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\Auth;
use App\Helpers\PlanHelper;
use App\Helpers\ImageHelper;
use App\Vendor;
use App\VendorCategory;
use App\VendorPlanSubscription;
use App\Store;
use App\Country;
use App\StoreCategory;
use App\VendorDepositInfo;
use App\Plans;
use App\PlanOptions;
use App\VendorPlanDetail;
use App\City;
use App\VendorShippingDetail;
use Intervention\Image\Facades\Image;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\CurrencyHelper;
use App\Helpers\PaypalHelper;
use Srmklive\PayPal\Facades\PayPal;
use App\ProductCategory;

class VendorBusinessController extends Controller
{

    public function add()
    {
        $Plan = new PlanHelper();
        $vendor = Auth::guard('vendor')->user();
        if ($vendor->pending_process != "Yes")
        {
            return redirect(route('vendorDashboard'));
        }
        $store = Store::where('vendor_id', $vendor->id)->first();
        $vendorCategory = ProductCategory::where('parent_category_id', NULL)
                                         ->where('status', "=","Active")
                                         ->with('subCategories')->orderBy('order_no', 'asc')->get();
        
        //$vendorCategory = VendorCategory::where("status", "Active")->get();
        $bankdetail = VendorDepositInfo::where('vendor_id', $vendor->id)->first();
        $data['vendorCategory'] = $vendorCategory;
        $data['Vendor'] = $vendor;
        $TimeData = array ();
        $storeCategory = array ();
        $shippingData = array ();
        if ($store)
        {
            $TimeData = DB::table('store_working_time')->where('store_id', '=', $store->id)->get();
            $shippingData = VendorShippingDetail::where('vendor_id', '=', $vendor->id)->get();
            $storeCategory = StoreCategory::where('store_id', '=', $store->id)->pluck('vendor_category_id')->toArray();
        }
        if ( ! empty($shippingData))
        {
            foreach ($shippingData as $k => $v)
            {
                $shippingData[$k]->city_list = City::selectRaw("city.*")
                                ->where("city.country_id", "=", $v->country_id)->get();
            }
        }
        $TimeDetail = array ();
        if ( ! empty($TimeData))
        {
            foreach ($TimeData as $val)
            {
                $TimeDetail[$val->day] = [
                    "open_time" => $val->open_time,
                    "closing_time" => $val->closing_time,
                    "is_fullday_open" => $val->is_fullday_open
                ];
            }
        }
        $area = City::where("status", "Active")
            ->get();
        $country = Country::where("status", "Active")->get();
        $data['country'] = $country;
        $data['area'] = $area;
        $data['storeCategory'] = $storeCategory;
        $data['shippingData'] = $shippingData;
        $data['VendorCategory'] = $vendorCategory;
        $data['working_time'] = $TimeDetail;
        $data['bankdetail'] = $bankdetail;
        $data['store'] = $store;
        $data['Plans'] = $Plan->GetPlanListWithDetail();
        
        return view('vendor.business.index', $data);
    }

    public function save(Request $request)
    {
      
        $vendor = Auth::guard('vendor')->user();
        $store = Store::where('vendor_id', $vendor->id)->first();
        $bankdetail = VendorDepositInfo::where('vendor_id', $vendor->id)->first();
        $ShippingDetail =$request->checkCountry;
        $TimeDetail = $request->time;
        if (empty($store))
        {
            $store = new Store();
            $store->vendor_id = $vendor->id;
        }
        if (empty($bankdetail))
        {
            $bankdetail = new VendorDepositInfo();
            $bankdetail->vendor_id = $vendor->id;
        }
        $vendorValidationRules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users|email',
            'store_name' => 'required|unique:stores,store_name',
            'address' => 'required',
            'category_id' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'store_status' => 'required',
            'benificiary_name' => 'required',
            'account_number' => 'required',
            'bank_name' => 'required',
            'swift_code' => 'required',
            'plan_option' => 'required',
        ];
        if ( ! empty($store))
        {
            $vendorValidationRules['store_name'] = 'required|unique:stores,store_name,' . $store->id;
        }
        $vendorValidationRules['email'] = 'required|unique:users,email,' . $vendor->id . '|email';
        $this->validate($request, $vendorValidationRules);
        $vendor->fill($request->all());
        $store->fill($request->all());
        $store->status = 'Active';
        $store->store_status = $request->store_status;

        $storeImage = $request->file('store_image');
        $destinationPath = public_path('doc/store_image');
        $store_image = $request->store_image;
        $data = ImageHelper::imageSave($storeImage, $destinationPath, $store_image);
        if ( ! empty($data) && $data != 'false')
        {
            $store->store_image = $data;
        }
        $bannerImage = $request->file('banner_image');
        $destinationPath = public_path('doc/store_banner_image');
        $banner_image = $request->banner_image;
        $data = ImageHelper::imageSave($bannerImage, $destinationPath, $banner_image); //image save
        if ( ! empty($data) && $data != 'false')
        {
            $store->banner_image = $data;
            $imgUrl = public_path('doc/store_banner_image/'.$data);

            $Path = public_path('doc/store_banner_images_front');
            $thumb_img = Image::make($imgUrl)->fit(360, 145, function ($constraint) { $constraint->aspectRatio(); } );
            // $thumb_img->save($Path, $data);
            $thumb_img->save($Path.'/'.$data,80);
        }
        $bankdetail->fill($request->all());
        $SelectedPlanOption = $request->input("plan_option");
        $planOptionDetail = PlanOptions::find($SelectedPlanOption);
        $planDetail = Plans::find($planOptionDetail->plan_id);
        $vendor->selected_plan_option_id = $planOptionDetail->plan_id;
        $VendorPlan = new VendorPlanDetail();
        $VendorPlan->vendor_id = $vendor->id;
        $VendorPlan->plan_id = $planDetail->id;
        $VendorPlan->plan_option_id = $planOptionDetail->id;
        $VendorPlan->plan_periods = $planOptionDetail->duration;
        $VendorPlan->price = $planOptionDetail->price;
        $VendorPlan->plan_name = $planDetail->plan_name;
        $VendorPlan->sales_percentage = $planDetail->sales_percentage;
        $VendorPlan->start_at = date("Y-m-d H:i:s", time());
        $VendorPlan->end_at = date("Y-m-d H:i:s", strtotime("+" . $planOptionDetail->duration));
        $VendorPlan->status = "Inactive";
        $VendorPlan->payment_status = "Pending";

        if ($vendor->save() && $store->save() && $bankdetail->save() && $VendorPlan->save())
        {
            $nameSlug = str_slug($request->first_name . "-" . $request->last_name . "-" . $vendor->id, "-");
            DB::table('users')
                    ->where('id', $vendor->id)
                    ->update(['name_slug' => $nameSlug]);
            $storeSlug = str_slug($request->store_name, "-");
            DB::table('stores')
                    ->where('id', $store->id)
                    ->update(['store_slug' => $storeSlug]);

            $ShippingData = [];
            $TimeData = [];
            $store = Store::where('vendor_id', $vendor->id)->first();
            $storeCategory = new StoreCategory();
            DB::table('store_category')->where('store_id', '=', $store->id)->delete();
            $storeCategoryData = array ();
            foreach ($request->category_id as $category_id)
            {
                $storeCategoryData[] = [
                    "vendor_category_id" => $category_id,
                    "store_id" => $store->id,
                    "added_by_user_id" => $vendor->id
                ];
            }
            $storeCategory->insert($storeCategoryData);
            if ( ! empty($TimeDetail))
            {
                DB::table('store_working_time')->where('store_id', '=', $store->id)->delete();
                foreach ($TimeDetail as $key => $val)
                {
                    if (isset($val['is_fullday_open']))
                    {
                        $TimeData[] = [
                            "store_id" => $store->id,
                            "day" => $key,
                            "is_fullday_open" => "Yes",
                            "open_time" => null,
                            "closing_time" => null,
                        ];
                    }
                    else
                    {
                        $TimeData[] = [
                            "store_id" => $store->id,
                            "day" => $key,
                            "is_fullday_open" => "No",
                            "open_time" => date("H:i:s", strtotime($val['open_time'])),
                            "closing_time" => date("H:i:s", strtotime($val['closing_time'])),
                        ];
                    }
                }
                DB::table('store_working_time')->insert($TimeData);
            }
            $vendorShipping = new VendorShippingDetail();
            if ( ! empty($ShippingDetail))
            {
                DB::table('vendor_shipping_detail')->where('vendor_id', '=', $vendor->id)->delete();
                if(!empty($request->checkCountry)){
                        foreach ($request->checkCountry as $key => $shipping) {
                            if (!empty($request->charge[$shipping]) && !empty($request->from[$shipping]) && $request->to[$shipping])
                            {
                                $ShippingData[] = array(
                                    "vendor_id" => $vendor->id,
                                    "city_id" => null,
                                    "city_name" => null,
                                    "country_id" => $shipping,
                                    "country_name" => $request->country_name[$shipping][0],
                                    "charge" => $request->charge[$shipping][0],
                                    "from" => $request->from[$shipping][0],
                                    "to" => $request->to[$shipping][0],
                                    "time" => $request->to_time[$shipping][0],
                                );

                                if (!empty($request->checkCity[$shipping])) {
                                    foreach ($request->checkCity[$shipping] as $keys => $area) {
                                        echo $keys;
                                        if (!empty($request->chargeCity[$shipping][$area][0]) && !empty($request->fromCity[$shipping][$area][0]) && $request->toCity[$shipping][$area][0]) {
                                            $ShippingData[] = array(
                                                "vendor_id" => $vendor->id,
                                                "country_id" => $shipping,
                                                "country_name" => $request->country_name[$shipping][0],
                                                "city_id" => $area,
                                                "city_name" => $request->city_name[$shipping][$area][0],
                                                "charge" => $request->chargeCity[$shipping][$area][0],
                                                "from" => $request->fromCity[$shipping][$area][0],
                                                "to" => $request->toCity[$shipping][$area][0],
                                                "time" => $request->city_to_time[$shipping][$area][0],
                                            );

                                        }

                                    }
                                }
                                //print_r($ShippingData);die;
                            } else {
                                if (!empty($request->checkCity[$shipping])) {
                                    foreach ($request->checkCity[$shipping] as $keys => $area) {
                                        if (!empty($request->chargeCity[$shipping][$area][0]) && !empty($request->fromCity[$shipping][$area][0]) && $request->toCity[$shipping][$area][0]) {
                                            $ShippingData[] = array(
                                                "vendor_id" => $vendor->id,
                                                "country_id" => $shipping,
                                                "country_name" => $request->country_name[$shipping][0],
                                                "city_id" => $area,
                                                "city_name" => $request->city_name[$shipping][$area][0],
                                                "charge" => $request->chargeCity[$shipping][$area][0],
                                                "from" => $request->fromCity[$shipping][$area][0],
                                                "to" => $request->toCity[$shipping][$area][0],
                                                "time" => $request->city_to_time[$shipping][$area][0],
                                            );
                                        }
                                    }

                                }
                            }
                        }

                }

                $vendorShipping->insert($ShippingData);
                if (count(array_column($ShippingDetail, "country_id")) != count(array_unique(array_column($ShippingDetail, "country_id"))))
                {
                    return redirect(route('vendorBusinessDetail'))->with('error', trans('messages.shipping_detail.country_error'));
                }
            }

            /* $provider = new ExpressCheckout();
            $data = [];
            $data['items'] = [
                [
                    'name' => $VendorPlan->plan_name,
                    'price' => $VendorPlan->price,
                    'qty' => 1
                ]
            ];
            $data['invoice_id'] = $VendorPlan->id;
            $data['invoice_description'] = "Plan #{$VendorPlan->id} Invoice";
            $data['return_url'] = route('VendorPlanPaymentSuccess', ['vendor_plan_id' => $VendorPlan->id]);
            $data['cancel_url'] = route('vendorBusinessDetail');

            $total = 0;
            foreach ($data['items'] as $item)
            {
                $total += $item['price'] * $item['qty'];
            }
            $data['total'] = $total;
            $options = [
                'BRANDNAME' => '',
                'LOGOIMG' => url("assets/demo/demo2/media/img/logo/logo.png"),
                'CHANNELTYPE' => 'Merchant'
            ];
            $response = $provider->setCurrency('USD')->addOptions($options)->setExpressCheckout($data, true);
            if ( ! empty($response['paypal_link']))
            {
                return redirect($response['paypal_link']);
            } */
            
            $start_at = Carbon::now()->format('Y-m-d H:i:s');
            $end_at = Carbon::now()->addMonth()->format('Y-m-d H:i:s');
            
            $vendorPlanSubscription = new VendorPlanSubscription();
            $vendorPlanSubscription->vendor_id = $vendor->id;
            $vendorPlanSubscription->vendor_plan_id = $VendorPlan->id;
            $vendorPlanSubscription->payment_type = 'offline';
            $vendorPlanSubscription->payment_subscription_id = NULL;
            $vendorPlanSubscription->payment_customer_id = NULL;
            $vendorPlanSubscription->amount = $planOptionDetail->price;
            $vendorPlanSubscription->conversion_rate = NULL;
            $vendorPlanSubscription->currency_code = 'KD';
            $vendorPlanSubscription->discount = 0;
            $vendorPlanSubscription->currency_symbol = 'KD';
            $vendorPlanSubscription->subscription_response = NULL;
            $vendor->pending_process = "No";
            
            $VendorPlan->status = "Active";
            $VendorPlan->start_at = $start_at;
            $VendorPlan->end_at = $end_at;
            $VendorPlan->payment_status = "Approved";
            if ($vendorPlanSubscription->save() && $vendor->save() && $VendorPlan->save())
            {
                return redirect(route('vendorDashboard'))->with('success', trans('messages.subscription.success'));
            }
            
            //return $vendorPlanSubscription->save();
            //return redirect(route('vendorBusinessDetail'))->with('error', 'sorry');
        }
        
        return redirect(route('vendorBusinessDetail'))->with('error', trans('messages.error'));
    }

    public function successVendorPayment(Request $request, $vendor_plan_id)
    {
        $vendor = Auth::guard('vendor')->user();
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
                $paymentSubscriptionId = $response['PROFILEID'];
                $paymentCustomerId = $response['CORRELATIONID'];
                $jsonSubscriptionResponse = json_encode($response);
                $vendor = Auth::guard('vendor')->user();
                $vendor->pending_process = NULL;
                $VendorPlanDetail->payment_status = "Approved";
                $VendorPlanDetail->description = $jsonSubscriptionResponse;

                $isSubscription = $this->makeSubscription("paypal", $vendor->id, $VendorPlanDetail->id, $paymentSubscriptionId, $paymentCustomerId, $VendorPlanDetail->price, 0, $conversionRate, $currencyCode, $currencySymbol, $jsonSubscriptionResponse);
                if ($isSubscription)
                {
                    $start_at = Carbon::now()->format('Y-m-d H:i:s');
                    $end_at = Carbon::now()->addMonth()->format('Y-m-d H:i:s');
                    $vendor = Auth::guard('vendor')->user();
                    $vendor->pending_process = "No";
                    $VendorPlanDetail->status = "Active";
                    $VendorPlanDetail->start_at = $start_at;
                    $VendorPlanDetail->end_at = $end_at;
                    $VendorPlanDetail->payment_status = "Approved";
                    $VendorPlanDetail->description = $jsonSubscriptionResponse;
                    if ($VendorPlanDetail->save() && $vendor->save())
                    {
                        return redirect(route('vendorDashboard'))->with('success', trans('messages.subscription.success'));
                    }
                }
                else
                {
                    $VendorPlanDetail->payment_status = "Failed";
                    $VendorPlanDetail->description = $jsonSubscriptionResponse;
                    if ($VendorPlanDetail->save())
                    {
                        return redirect(route('vendorBusinessDetail'))->with('error', trans('messages.error'));
                    }
                }
            }
            return redirect(route('vendorBusinessDetail'))->with('error', trans('messages.error'));
        }
    }

    private function makeSubscription($paymentType, $vendor_id, $vendor_plan_id, $paymentSubscriptionId, $paymentCustomerId, $amount, $discount, $conversionRate, $currencyCode, $currencySymbol, $jsonSubscriptionResponse)
    {
        $vendorPlanSubscription = new VendorPlanSubscription();
        $vendorPlanSubscription->vendor_id = $vendor_id;
        $vendorPlanSubscription->vendor_plan_id = $vendor_plan_id;
        $vendorPlanSubscription->payment_type = $paymentType;
        $vendorPlanSubscription->payment_subscription_id = $paymentSubscriptionId;
        $vendorPlanSubscription->payment_customer_id = $paymentCustomerId;
        $vendorPlanSubscription->amount = $amount;
        $vendorPlanSubscription->conversion_rate = $conversionRate;
        $vendorPlanSubscription->currency_code = $currencyCode;
        $vendorPlanSubscription->discount = $discount;
        $vendorPlanSubscription->currency_symbol = $currencySymbol;
        $vendorPlanSubscription->subscription_response = $jsonSubscriptionResponse;
        return $vendorPlanSubscription->save();
    }

    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $data = [];
        $data['vendor'] = $vendor;
        $vendor_subscription = VendorPlanSubscription::where("vendor_id", $vendor->id)->latest()->first();
        $activePlan = VendorPlanDetail::find($vendor_subscription->vendor_plan_id);
        $data['subscription'] = $vendor_subscription;
        $data['activePlan'] = $activePlan;
        return view('vendor.business.subscription_view', $data);
    }

    public function unSubscribe(Request $request, VendorPlanSubscription $vendor_subscription)
    {
        /* dd($request->all());die;
        $response = PaypalHelper::cancelSubscription($vendor_subscription->payment_subscription_id);
        if ($response['ACK'] == 'Success')
        { */
            $vendor_subscription->status = "Canceled";
            if ($vendor_subscription->save())
            {
                return redirect(route('subscription.index'))->with('success', trans('messages.subscription.unsubscribe'));
            }
        //}
        return redirect(route('subscription.index'))->with('error', trans('messages.subscription.error'));
    }

    public function edit()
    {
        $Plan = new PlanHelper();
        $vendor = Auth::guard('vendor')->user();
        $data = [];
        $data['vendor'] = $vendor;
        $vendor_subscription = VendorPlanSubscription::where("vendor_id", $vendor->id)->latest()->first();
        $activePlan = VendorPlanDetail::find($vendor_subscription->vendor_plan_id);
        $data['subscription'] = $vendor_subscription;
        $data['activePlan'] = $activePlan;
        $data['Plans'] = $Plan->GetPlanListWithDetail();
        return view('vendor.business.update_subscription', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request,['plan_option'=>'required']);
        $vendor = Auth::guard('vendor')->user();
        $SelectedPlanOption = $request->input("plan_option");
        $planOptionDetail = PlanOptions::find($SelectedPlanOption);
        $planDetail = Plans::find($planOptionDetail->plan_id);
        VendorPlanDetail::where('vendor_id',$vendor->id)->update(['status' => 'Inactive']);
        $VendorPlan = new VendorPlanDetail();
        
        $VendorPlan->vendor_id = $vendor->id;
        $VendorPlan->plan_id = $planDetail->id;
        $VendorPlan->plan_option_id = $planOptionDetail->id;
        $VendorPlan->plan_periods = $planOptionDetail->duration;
        $VendorPlan->price = $planOptionDetail->price;
        $VendorPlan->plan_name = $planDetail->plan_name;
        $VendorPlan->sales_percentage = $planDetail->sales_percentage;
        $VendorPlan->start_at = date("Y-m-d H:i:s", time());
        $VendorPlan->end_at = date("Y-m-d H:i:s", strtotime("+" . $planOptionDetail->duration));
        $VendorPlan->status = "Inactive";
        $VendorPlan->payment_status = "Pending";
        if ($VendorPlan->save())
        {
            /* $provider = new ExpressCheckout();
            $data = [];
            $data['items'] = [
                [
                    'name' => $VendorPlan->plan_name,
                    'price' => $VendorPlan->price,
                    'qty' => 1
                ]
            ];
            $data['invoice_id'] = $VendorPlan->id;
            $data['invoice_description'] = "Plan #{$VendorPlan->id} Invoice";
            $data['return_url'] = route('VendorUpdatePlanPaymentSuccess', ['vendor_plan_id' => $VendorPlan->id]);
            $data['cancel_url'] = route('subscription.index');

            $total = 0;
            foreach ($data['items'] as $item)
            {
                $total += $item['price'] * $item['qty'];
            }
            $data['total'] = $total;
            $options = [
                'BRANDNAME' => '',
                'LOGOIMG' => url("assets/demo/demo2/media/img/logo/logo.png"),
                'CHANNELTYPE' => 'Merchant'
            ];

            $response = $provider->setCurrency('USD')->addOptions($options)->setExpressCheckout($data, true);
//            dd($response);
            if ( ! empty($response['paypal_link']))
            {
                return redirect($response['paypal_link']);
            } */
            
            $start_at = Carbon::now()->format('Y-m-d H:i:s');
            $end_at = Carbon::now()->addMonth()->format('Y-m-d H:i:s');
            
            $vendorPlanSubscription = new VendorPlanSubscription();
            $vendorPlanSubscription->vendor_id = $vendor->id;
            $vendorPlanSubscription->vendor_plan_id = $planDetail->id;
            $vendorPlanSubscription->payment_type = 'offline';
            $vendorPlanSubscription->payment_subscription_id = NULL;
            $vendorPlanSubscription->payment_customer_id = NULL;
            $vendorPlanSubscription->amount = $planOptionDetail->price;
            $vendorPlanSubscription->conversion_rate = NULL;
            $vendorPlanSubscription->currency_code = 'KD';
            $vendorPlanSubscription->discount = 0;
            $vendorPlanSubscription->currency_symbol = 'KD';
            $vendorPlanSubscription->subscription_response = NULL;
            
            $VendorPlan->status = "Active";
            $VendorPlan->start_at = $start_at;
            $VendorPlan->end_at = $end_at;
            $VendorPlan->payment_status = "Approved";
            
            if ($vendorPlanSubscription->save() && $VendorPlan->save())
            {
                return redirect(route('subscription.index'))->with('success', trans('messages.subscription.update'));
            }
        }
        return redirect(route('subscription.index'))->with('error', trans('messages.error'));
    }

    public function successUpdatePlanPayment(Request $request, $vendor_plan_id)
    {
        $vendor = Auth::guard('vendor')->user();
        $data = [];
        $data['vendor'] = $vendor;
        $vendor_subscription = VendorPlanSubscription::where("vendor_id", $vendor->id)->latest()->first();
        $activePlan = VendorPlanDetail::find($vendor_subscription->vendor_plan_id);
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
                $paymentSubscriptionId = $response['PROFILEID'];
                $paymentCustomerId = $response['CORRELATIONID'];
                $jsonSubscriptionResponse = json_encode($response);
                $vendor = Auth::guard('vendor')->user();
                $vendor->pending_process = NULL;
                $VendorPlanDetail->payment_status = "Approved";
                $VendorPlanDetail->description = $jsonSubscriptionResponse;

                $isSubscription = $this->makeSubscription("paypal", $vendor->id, $VendorPlanDetail->id, $paymentSubscriptionId, $paymentCustomerId, $VendorPlanDetail->price, 0, $conversionRate, $currencyCode, $currencySymbol, $jsonSubscriptionResponse);
                if ($isSubscription)
                {
                    $start_at = Carbon::now()->format('Y-m-d H:i:s');
                    $end_at = Carbon::now()->addMonth()->format('Y-m-d H:i:s');
                    $vendor = Auth::guard('vendor')->user();
                    $vendor->pending_process = "No";
                    $VendorPlanDetail->status = "Active";
                    $VendorPlanDetail->start_at = $start_at;
                    $VendorPlanDetail->end_at = $end_at;
                    $VendorPlanDetail->payment_status = "Approved";
                    $VendorPlanDetail->description = $jsonSubscriptionResponse;
                    $response1 = PaypalHelper::cancelSubscription($vendor_subscription->payment_subscription_id);
                    $vendor_subscription->status = "Canceled";
                    if ($VendorPlanDetail->save() && $vendor_subscription->save())
                    {
                        return redirect(route('subscription.index'))->with('success', trans('messages.subscription.update'));
                    }
                }
                else
                {
                    $VendorPlanDetail->payment_status = "Failed";
                    $VendorPlanDetail->description = $jsonSubscriptionResponse;
                    if ($VendorPlanDetail->save())
                    {
                        return redirect(route('subscription.index'))->with('error', trans('messages.error'));
                    }
                }
            }
            return redirect(route('subscription.index'))->with('error', trans('messages.error'));
        }
    }

}
