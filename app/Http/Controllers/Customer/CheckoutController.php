<?php

namespace App\Http\Controllers\Customer;

use App\City;
use App\Helpers\Knet;
use App\Mail\WelcomeMail;
use App\Order;
use App\OrderAddress;
use App\OrderProduct;
use App\ProductCart;
use App\ProductStockHistory;
use App\Store;
use Illuminate\Support\Facades\Auth;
use App\Country;
use App\Helpers\ApiHelper;
use App\Helpers\PromoCodeHelper;
use App\Helpers\QueryHelper;
use App\ProductAttrCombination;
use App\User;
use App\UserAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Validation rules array for guest user
     * @var array
     */
    private $validationRulesForGuest = [

        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|unique:users,email',
        'full_name' => 'required',
        'country' => 'required',
        'city' => 'required',
        'block' => 'required',
        'street' => 'required|string',
        'mobile' => 'required|min:7|max:17',
        'building' => 'required',
        'account' => 'nullable',
        'gender' => 'required_if:account,Yes|nullable',
        'password' => 'required_if:account,Yes|nullable|required_if:account,Yes,confirmed|min:6',
        'password_confirmation' => 'required_if:account,Yes|nullable|min:6',

    ];

    /**
     * Common validation rules array for customer
     * @var array
     */
    private $validationRules = [

        'sub_total' => 'required',
        'grand_total' => 'required',
        'shipping_total' => 'required',
        'payment_type' => 'required',

    ];

    /**
     * load checkout page
     * @param Request $request
     * @return View
     */
    public function checkouts(Request $request){

        $customer = \Auth::user('customer');
        $customerAddress = null;
        $userAddressInfoCount  = null;

        if(!empty($customer)) {
            $customerAddress = UserAddress::where("user_id", $customer->id)->get();
        }

        $cartProducts = QueryHelper::getCartProducts();
        
        // if product not available in cart
        if($cartProducts->isEmpty())
        {
            return redirect(route('cart.index'))->with('error', trans('messages.carts.not_available'));
        }
       // dd($cartProducts);
        $subTotal = 0;
        foreach($cartProducts as $products)
        {
            $subTotal = $subTotal + ($products->quantity * $products->price);
        }
        $countries = Country::where('status','Active')->get();

        $totalAmount = $subTotal;
        $data = [];
        $data['subTotal'] = $subTotal;
        $data['totalAmount'] = $totalAmount;
        $data['country'] = $countries;
        $data["customerAddress"] = $customerAddress;

        return view('front.checkout.index',$data);
    }

    /**
     * Apply shipping 
     * @param Request $request
     * @return json
     */
    public function applyShipping(Request $request)
    {
        //dd($request->all());
        $customer = \Auth::guard('customer')->user();

        if (!empty($customer) && !empty($request->addressId)) {
            $address = UserAddress::find($request->addressId);

        }else{
            $address = [
                'country_id' => $request->countryId,
                'city_id' => $request->cityId
            ];
        }

        /*if(empty($address)){
            $address = [
                'country_id' => $request->countryId,
                'city_id' => $request->cityId
            ];
        }*/
        $cartProducts = QueryHelper::getCartProducts();

        $shippingChargeData = [];
        $totalShippingCharge = 0;

        if(!$cartProducts->isEmpty()) {

            foreach ($cartProducts as $cartProduct) {

                $option = ProductAttrCombination::find($cartProduct->option_id);
                $result = ApiHelper::checkCartValidation($option, $cartProduct->quantity, $customer, $address);

                if ($result['status'] == 1) {
                    $shippingChargeData[$cartProduct->store_id] = isset($shippingChargeData[$cartProduct->store_id]) ? $shippingChargeData[$cartProduct->store_id] : 0;
                    $shippingChargeData[$cartProduct->store_id] = $result['data']['charge'];
                } else {
                    return $this->toJson([], $result['message'], 0);
                }
            }

            if (!empty($shippingChargeData)) {
                foreach ($shippingChargeData as $shippingCharge) {
                    $totalShippingCharge += $shippingCharge;
                }
            }
            return $this->toJson([
                'shipping_charge' => $totalShippingCharge,
                'is_show_cod' => $address['country_id'] == 2 ? 1 : 0,
            ]);
        }
        return $this->toJson([], trans('api.carts.empty'), 0);
    }

    /**
     * Apply promo code.
     *
     * @param Request $request
     * @return json
     */
    public function applyPromoCode(Request $request)
    {
        $this->validate($request, [
            'promo_code' => 'required',
            'order_amount' => 'required',
        ]);

        $result = PromoCodeHelper::applyPromoCode($request->order_amount, $request->promo_code);

        if($result['status'])
        {
            return $this->toJson([
                'promocode' => $result['promo_code'],
                'promo_code_discount_amount' => $result['promo_code_discount_amount'],
            ]);
        }

        return $this->toJson(null, $result['msg'], 0);
    }

    /**
     * get cartproducts and make a orderProducts array
     * @param $cartProducts
     * @return array
     */
    public function getOrderProductsArray($cartProducts, $cityId){

        $orderProducts = [];
        $shipping = 0;

        foreach ($cartProducts as $cartProduct)
        {
            $store = Store::where('vendor_id', $cartProduct->vendor_id)->first();

            $city = $store->shippingCities()->where('city_id', $cityId)->first();

            $shipping = $city['charge'];

            $total = $cartProduct->quantity * $cartProduct->price;

            $orderProducts[]= [
                'product_id' => $cartProduct->product_id,
                'product_title'=> $cartProduct->product_title,
                'product_slug'=> $cartProduct->product_slug,
                'product_combination_id' => $cartProduct->option_id,
                'product_vendor_id' => $cartProduct->vendor_id,
                'quantity' => $cartProduct->quantity,
                'rate' => $cartProduct->price,
                'shipping_charges' => $shipping,
                'sub_total' => $total,
                'grand_total' => $total + $shipping,
                'tax' => 0,
                'delivery_status' => 'Pending',
            ];

        }
        return $orderProducts;
    }

    /**
     * store order address
     * @param $userAddress
     * @param $orderId
     * @return mixed
     */

    public function storeOrderAddress($userAddress, $orderId){


        $orderAddress = new OrderAddress();
        $orderAddress->fill($userAddress->toArray());
        $orderAddress->customer_id = $userAddress->user_id;
        $orderAddress->order_id = $orderId;
        $orderAddress->mobile_no = $userAddress->mobile;
        $orderAddress->landline_no = $userAddress->landline;
        $orderAddress->address_type = "Shipping";
        $orderAddress->save();

        return $orderAddress->id;
    }

    /**
     * Create new order instance
     * @param $request
     * @param $user
     * @return Order
     */
    public function createNewOrder($request, $user){

        $order =  new Order();
        $order->customer_id = $user->id;
        $order->customer_name = $user->first_name.' '.$user->last_name;
        $order->sub_total = $request->sub_total;
        $order->order_total = $request->sub_total;
        $order->shipping_total = (float) $request->shipping_total;
        $order->grand_total = $request->grand_total;
        $order->discount_amount = !empty($request->discount_amount) ? $request->discount_amount : 0.0;
        $order->coupon_code = !empty($request->coupon_code) ? $request->coupon_code : '';

        return $order;
    }

    /**
     * Store userinfo and addresss details and order details
     * @Param $request
     * @Response view
     */
    public function store(Request $request){

        // $request->city_id = 2;
        // dd($request->all());

        $validate = $this->validationRules;

        if(!Auth::user('customer')){

            $this->validationRulesForGuest['email'] = 'required';

            $validate = array_merge($this->validationRules,$this->validationRulesForGuest);
        }

        //check validation
        $this->validate($request, $validate);

        $browserId = \Cookie::get('browserId');

        $userId = null;
        $user =  User::where('email',$request->email)->first();

        $userInfo = Auth::user('customer');

        $userAddressId = null;

        if(Auth::guard('customer')->check())
        {
            $user = $userInfo;
            $userId = $user->id;
            $userAddressId = ($request->selected_address_id) ? $request->selected_address_id : null;
        }

        if(empty($user))
        {
            $user = new User();
            $user->type = 'guest';
            $user->fill($request->all());
        }

        if(!empty($user) && $user->type == 'guest'){

            $user->fill($request->all());
        }

        if($request->account == 'Yes')
        {
            $user->type = 'customer';
            $user->password =bcrypt($request->password);
            $user->save();

            Auth::guard('customer')->loginUsingId($user->id);

            Mail::to($user->email)->send(new WelcomeMail($user));
        }

        if($user->save())
        {
            $userId = $user->id;
        }

        if(empty($userAddressId))
        {

            $cityId = City::select('id','city_name')->where('country_id',$request->country_id)->first();
            $countryName = Country::select('id','country_name')->where('id',$request->country_id)->first();

            // echo $cityId->id;
            // echo "<pre>";
            // print_r($cityId);
            // exit;

            // Store address
            $userAddress = new UserAddress();
            $userAddress->user_id = $userId;
            $userAddress->fill($request->all());
            $userAddress->city_id = $cityId->id;
            $userAddress->city = $cityId->city_name;
            $userAddress->country = $countryName->country_name;
            $userAddress->is_selected = 'Yes';
            $userAddress->save();
            $userAddressId = $userAddress->id;
        }

        $userAddress = UserAddress::where('id',$userAddressId)->first();

        ApiHelper::moveCartProducts($browserId, $user);

        $cartProducts = QueryHelper::getCartProducts();

        if($cartProducts->isNotEmpty())
        {

            \DB::beginTransaction();

            // Make new order
            $order = self::createNewOrder($request, $user);

            if($order->save())
            {
                $order->order_no = "ORDER" . str_pad($order->id, 6, mt_rand(100000, 999999), STR_PAD_RIGHT);
                $order->save();

                // Store order address
                self::storeOrderAddress($userAddress,$order->id);

                $orderProducts = self::getOrderProductsArray($cartProducts, $userAddress->city_id);

                $orderProducts = collect($orderProducts);

                $orderProducts  = $orderProducts->map(function ($orderProduct, $key) use ($order) {
                    $orderProduct['order_id'] = $order->id;
                    return $orderProduct;
                })->toArray();

                OrderProduct::insert($orderProducts);
            }

            // Store Payment Detail

            \DB::commit();
            $order->payment_type = $request->payment_type;
            $order->payment_status = $request->payment_type == 'Cash on Delivery' ? 'Completed' : 'Pending';
            $order->save();

            if($request->payment_type == 'Cash on Delivery')
            {
                $this->afterSuccessOrder($order);
            }
            elseif($request->payment_type == 'KNet')
            {
                return $this->kNetPayment($request, $order);
            }
            elseif($request->payment_type == 'CreditCard')
            {

                // echo base_path('stripe-php-master/init.php');
                // exit;
                // echo "<pre>";
                // print_r($request->all());
                // exit;

                // Include Stripe PHP library 
                    // require_once base_path('vendor/stripe-php-master/init.php'); 
                     
                    // // Set API key 
                    // \Stripe\Stripe::setApiKey("sk_test_51HY3VWI4FjP0xpF8ZWtLp6srqdI4mJXg263YybiOokFsFXIqwhDZ9ebEk9nw5uAWTt2jyQLRZ7SikAAjKATSofTQ00ekqVmqJw"); 


                    //   $customer = \Stripe\Customer::create(array( 
                    //                 'email' => 'aa@gmail.com', 
                    //                 'source'  => $request->stripeToken 
                    //             )); 

                    //   $itemPriceCents = (200*100); 


                    //    $charge = \Stripe\Charge::create(array( 
                    //         'customer' => $customer->id, 
                    //         'amount'   => $itemPriceCents, 
                    //         'currency' => 'inr', 
                    //         'description' => 'ff description' 
                    //     )); 

                    //   echo "<pre>";
                    //   print_r($charge);
                    //   exit;

                return redirect(route('CardPayment', [
                    'orderNumber' => $order->order_no,
                    'token' =>$request->stripeToken
                ]));
            }
            return redirect(url(route('myOrderDetail', ["orderNo" => $order->order_no])))->with('success', trans('messages.order.success'));
            //return redirect('order-success/'.base64_encode($order->id));
        }

        return redirect('checkout')->with('error', trans('messages.carts.empty'));

    }

    /**
     * Called after order placed , managed stock
     * @param Order $order
     */
    public function afterSuccessOrder(Order $order)
    {
        \DB::beginTransaction();
        
        $order->load('user','orderProducts.option', 'orderProducts.product');

        //$productCombination = ProductAttrCombination::find($val['product_combination_id']);
        $orderProducts = $order->orderProducts;

        ProductCart::where('user_id', $order->user->id)->delete();

        if(!$orderProducts->isEmpty())
        {
            //$productStockHistory = [];
            foreach ($orderProducts as $orderProduct)
            {

                $option = $orderProduct->option;
                $option->quantity = $option->quantity - $orderProduct->quantity;

                $option->save();

                $productStockHistory[] = [
                    'product_id' => $orderProduct->product_id,
                    'product_combination_id' => $orderProduct->product_combination_id,
                    'user_id' => $order->customer_id,
                    'quantity' => $orderProduct->quantity,
                    'rate' => $orderProduct->rate,
                    'type' => 'Order',
                    'description' => $order->id
                ];
            }
            ProductStockHistory::insert($productStockHistory);

            \DB::commit();
        }

            $order->is_mail_send = 1;
            $order->save(); 
            
            $cmd = 'cd '.base_path().' && php artisan sendOrderSuccessMail:send '.$order->id;
            exec($cmd. '> /dev/null &');   

    }


    /**
     * Knet Payment
     *
     * @param Request $request
     * @param Order $orders
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function kNetPayment(Request $request, Order $orders)
    {
        $user = User::select('email')->where('id',$orders->customer_id)->first();

        $orderID = $orders->order_no;

        $ErrorUrl = route('newKnetResponse');
        $ResponseUrl= route('newKnetResponse');
        $TranportalId = "162101";
        $ReqTranportalId="id=".$TranportalId;
        $ReqTranportalPassword="password=162101pg";
        $ReqAction = "action=1";
        $ReqLangid = "langid=USA";
        $ReqCurrency = "currencycode=414";
        $ReqAmount = "amt=".$orders->grand_total;
        $ReqResponseUrl = "responseURL=".$ResponseUrl;
        $ReqErrorUrl = "errorURL=".$ErrorUrl;
        $ReqTrackId = "trackid=".$orderID;
        $ReqUdf1="udf1=".$user->email;
        $ReqUdf2="udf2=".$orders->id;
        $ReqUdf3="udf3=".($request->isMobile == 1) ? 1 : 0;
        $ReqUdf4="udf4=Test4";
        $ReqUdf5="udf5=Test5";

        $param = $ReqTranportalId."&".$ReqTranportalPassword."&".$ReqAction."&".$ReqLangid."&".$ReqCurrency."&".$ReqAmount."&".$ReqResponseUrl."&".$ReqErrorUrl."&".$ReqTrackId."&".$ReqUdf1."&".$ReqUdf2."&".$ReqUdf3."&".$ReqUdf4."&".$ReqUdf5;

        $termResourceKey=config('constant.knet.resourceKey');

        $param = Knet::encryptAES($param,$termResourceKey)."&tranportalId=".$TranportalId."&responseURL=".$ResponseUrl."&errorURL=".$ErrorUrl;

        $payURL = config('constant.knet.base_URL').$param;

        return redirect($payURL);

    }


    /**
     * Knet payment response
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function newKnetResponse(Request $request)
    {
        $response = json_encode($request->all());

        \Log::debug('knet response--------');
        \Log::debug($response);


        $resErrorText= $request->ErrorText; 	  	//Error Text/message
        $resPaymentId = $request->paymentid;		//Payment Id
        $resTrackID = $request->trackid;       	//Merchant Track ID
        $resErrorNo = $request->Error;           //Error Number
        $resResult =  $request->result;          //Transaction Result
        $resPosdate = $request->postdate;        //Postdate
        $resTranId = $request->tranid;           //Transaction ID
        $resAuth = $request->auth;               //Auth Code
        $resAVR = $request->avr;                 //TRANSACTION avr
        $resRef = $request->ref;                 //Reference Number also called Seq Number
        $resAmount = $request->amt;              //Transaction Amount
        $email = $request->udf1;               //UDF1
        $orderId = $request->udf2;               //UDF2
        $isMobile = $request->udf3;               //UDF3
        $resudf4 = $request->udf4;               //UDF4
        $resudf5 = $request->udf5;               //UDF5

        $order = Order::where('order_no', $resTrackID)->first();

        if(empty($order))
        {
            return redirect(route('checkouts'))->with('error', trans('messages.order.error'));
        }

        $termResourceKey = config('constant.knet.resourceKey');

        if(empty($resErrorText) && empty($resErrorNo))
        {
            $resTranData = $request->trandata;

            if(!empty($resTranData))
            {
                //Decryption logic starts
                $decryptedData = Knet::decrypt($resTranData, $termResourceKey);

                return $this->testhandleKnetResponse($decryptedData, $order, $isMobile);

            }
        }
        else
        {
            $order->payment_status = 'Failed';
            $order->knet_payment_id = $resPaymentId;
            $order->kent_track_id = $resTrackID;
            $order->knet_tran_id = $resTranId;
            $order->respons_parm = $response;
            $order->save();

            return redirect(url(route('myOrderDetail', ["orderNo" => $order->order_no])))
                ->with('error', trans('messages.order.error'));
        }
    }

    /**
     * Handle Knet Response
     *
     * @param $decryptedData
     * @param $order
     * @param $isMobile
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function testHandleKnetResponse($decryptedData, $order, $isMobile)
    {

        $resultData = [];
        parse_str($decryptedData, $resultData);

        \Log::debug('resultData');
        \Log::debug($resultData);

        if ( $resultData['result'] == "CAPTURED" )
        {

            $order->payment_status = 'Completed';
            $order->knet_payment_id = $resultData['paymentid'];
            $order->kent_track_id = $resultData['trackid'];
            $order->knet_tran_id = $resultData['tranid'];
            $order->respons_parm = json_encode($resultData);
            $order->save();

            if(!empty($order))
            {
                $this->afterSuccessOrder($order);
            }

            if($isMobile == 1)
            {
                return  redirect(url(route('OrderStatus', ["orderNumber" => $order->order_no])));
            }

            return  redirect(url(route('myOrderDetail', ["orderNo" => $resultData['trackid']])))->with('success', trans('messages.order.success'));

        }
        else
        {
            $order->payment_status = 'Failed';
            $order->knet_payment_id = $resultData['paymentid'];
            $order->kent_track_id = $resultData['trackid'];
            $order->knet_tran_id = !empty($resultData['tranid']) ? $resultData['tranid'] : null;
            $order->respons_parm = json_encode($resultData);
            $order->save();

            if($isMobile == 1)
            {
                return  redirect(url(route('OrderStatus', ["orderNumber" => $resultData['trackid']] )));
            }

            return redirect(route('checkouts'))->with('error', trans('messages.order.error'));
        }

    }

    /**
     * payment using knet
     * @param Request $request
     * @param Order $orders
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function knetPay(Request $request, Order $orders){
        
        $user = User::select('email')->where('id',$orders->customer_id)->first();

        $orderID = $orders->order_no;
        $Pipe = new Knet();
        $Pipe->setAction(1);
        $Pipe->setCurrency(414);
        $Pipe->setLanguage("ENG"); //change it to "ARA" for arabic language
        if($request->isMobile == 0)
        {
            $Pipe->setResponseURL('https://shopzz.com/response.php');
            $Pipe->setErrorURL('https://shopzz.com/response.php');
        }
        else
        {
            $Pipe->setResponseURL('https://shopzz.com/mobile_response.php');
            $Pipe->setErrorURL('https://shopzz.com/mobile_response.php');
        }

        $Pipe->setAmt($orders->grand_total);

        $Pipe->setResourcePath(public_path('resource/'));
        $Pipe->setAlias("project"); //set your alias name here
        $Pipe->setTrackId($orderID);//generate the random number here

        $Pipe->setUdf1($user->email); //set User defined value
        $Pipe->setUdf2($orders->id); //set User defined value
        $Pipe->setUdf3("UDF 3"); //set User defined value
        $Pipe->setUdf4("UDF 4"); //set User defined value
        $Pipe->setUdf5("UDF 5"); //set User defined value
        //$Pipe->setOrderId($orderID);

        //dd($Pipe->performPaymentInitialization());

        if($Pipe->performPaymentInitialization() != $Pipe->SUCCESS){

            /*$order = Order::where('order_no',$orderID)->update(['payment_status'=> 'Failed'
            ]);*/
            return redirect(url(route('checkouts')))->with('error', $Pipe->getErrorMsg());

        }else {
            $payID = $Pipe->getPaymentId();
            $payURL = $Pipe->getPaymentPage();

            return redirect($payURL."?PaymentID=".$payID);
        }
    }

    /**
     * handle KNet payment response
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function knetResponse(Request $request)
    {
        $response = json_encode($request->all());

        \Log::debug('knet response--------');
        \Log::debug($response);

        /*if(empty($request->TrackID)){

            return redirect('checkouts')->with('error',trans('messages.order.error'));
        }*/

        $PaymentID = $request->PaymentID;
        $presult = $request->Result;
        $postdate = $request->PostDate;
        $tranid =$request->TranID;
        $auth = $request->Auth;
        $ref = $request->Ref;
        $trackid = $request->TrackID;
        $userEmail = $request->UDF1; //get user mail
        $orderId = $request->UDF2; //get order id
        $udf3 = $request->UDF3;
        $udf4 = $request->UDF4;
        $udf5 = $request->UDF5;
        $isMobile = $request->isMobile;

        $order = Order::where('order_no',$trackid)->first();
		
		if(empty($order)){
            return redirect(route('checkouts'))->with('error', trans('messages.order.error'));
        }
        
        if ( $presult == "CAPTURED" ){

            $order->payment_status = 'Completed';
            $order->knet_payment_id = $PaymentID;
            $order->kent_track_id = $trackid;
            $order->respons_parm = $response;
            $order->save();

            if(!empty($order))
            {
                $this->afterSuccessOrder($order);
            }

            if($isMobile == 1)
            {
                return  redirect(url(route('OrderStatus', ["orderNumber" => $order->order_no])));
            }

            return  redirect(url(route('myOrderDetail', ["orderNo" => $trackid])))->with('success', trans('messages.order.success'));

        }else{
            $order->payment_status = 'Failed';
            $order->knet_payment_id = $PaymentID;
            $order->kent_track_id = $trackid;
            $order->knet_tran_id = $tranid;
            $order->respons_parm = $response;
            $order->save();

            if($isMobile == 1)
            {
                return  redirect(url(route('OrderStatus', ["orderNumber" => $trackid])));
            }

            return redirect(route('checkouts'))->with('error', trans('messages.order.error'));
        }
    }

    public function knetError(Request $request, Order $orderNo){
        
        $PaymentID = $request->PaymentID;
        $presult = $request->Result;
        $postdate = $request->PostDate;
        $tranid =$request->TranID;
        $auth = $request->Auth;
        $ref = $request->Ref;
        $trackid = $request->TrackID;
        $userEmail = $request->UDF1; //get user mail
        $orderId = $request->UDF2; //get order id
        $udf3 = $request->UDF3;
        $udf4 = $request->UDF4;
        $udf5 = $request->UDF5;
        $response = json_encode($request->all());
        
        //dd($orderId);die;
                //$orderId->payment_status = "Failed";
                //$orderId->save();
                $order = Order::where('order_no',$orderNo->order_no)->update([
                    'payment_status'=> 'Failed',
                    'knet_payment_id'=>$PaymentID,
                    'kent_track_id'=>$trackid,
                    'knet_tran_id'=>$tranid,
                    'respons_parm'=>$response,
                ]);
                return redirect(url(route('myOrderDetail', ["orderNo" => $orderNo->order_no])))->with('error', trans('messages.order.error'));
    }
}
