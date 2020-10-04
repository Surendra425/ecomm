<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Helpers\MasterCardHelper;
use Illuminate\Support\Facades\Log;
use App\Order;
use App\Http\Controllers\Customer\CheckoutController;

class MasterCardController extends Controller
{
    public function index(Request $request)
    {
         $order = [
            'id' => time(),
            'amount' => 100,
            'currency' => 'KWD'
        ];
        $data = MasterCardHelper::generateMasterCardSession($order['id'], $order['amount'], $order['currency']);
        
        //dd($data, $order);
        if($data->result == 'SUCCESS')
        {
            $sessionId = $data->session->id;

            return view('mastercard.checkout' , [
                'sessionId' => $sessionId,
                'order' => $order,
            ] );
         }
        
        abort(500);
    }
    
    public function notification(Request $request)
    {
        $orderId = $request->orderId;
        $orderId = '1537882049';

        $data = MasterCardHelper::getOrderStatus($orderId);
    }
    
    /*
     * Show payment card options
     * 
     * @param $orderNumber order unique number
     * @param Request $request
     * 
     * @return view
     */
    public function cardPaymentPage($orderNumber,$token, Request $request)
    {


        // echo $orderNumber;
        // echo "<br>";
        // echo $token;

        //   echo "<pre>";
        //             print_r($request->all());
        //             exit;

        $order = Order::where('order_no', $orderNumber)->first();


        

        if(!empty($order))
        {
            if($order->payment_status == 'Pending' && $order->payment_type == 'CreditCard')
            {

               //  Include Stripe PHP library 
                    require_once base_path('vendor/stripe-php-master/init.php'); 
                     
                    // Set API key 
                    \Stripe\Stripe::setApiKey("sk_test_51HY3VWI4FjP0xpF8ZWtLp6srqdI4mJXg263YybiOokFsFXIqwhDZ9ebEk9nw5uAWTt2jyQLRZ7SikAAjKATSofTQ00ekqVmqJw"); 

                     $orderData =   \Stripe\Charge::create ([
                                "amount" => ($order->grand_total * 100),
                                "currency" => "inr",
                                "source" => $token,
                             
                            ]);


                      //   $customer = \Stripe\Customer::create(array( 
                      //               'email' => 'aa@gmail.com', 
                      //               'source'  => $request->stripeToken 
                      //           )); 

                      // $itemPriceCents = ($order->grand_total*100); 


                      //  $orderData = \Stripe\Charge::create(array( 
                      //       'customer' => $customer->id, 
                      //       'amount'   => $itemPriceCents, 
                      //       'currency' => 'KWD', 
                      //       'description' => 'ff description' 
                      //   )); 


                    //  echo "<pre>";
                    // print_r($orderData);
                    // exit;





                // $orderData = [
                //     'id' => $orderNumber,
                //     'amount' => $order->grand_total,
                //     'currency' => 'KWD'
                // ];
            
                // $data = MasterCardHelper::generateMasterCardSession($orderData['id'], $orderData['amount'], $orderData['currency']);

                
                if($orderData->status == 'succeeded')
                {
                    $sessionId = $orderData->id;

                    Order::where('order_no', $orderNumber)->update([
                        'payment_status'=>'Completed',
                        'credit_card_transaction_id'=>$sessionId
                        ]);

                     return redirect(url(route('myOrderDetail', ["orderNo" => $orderNumber])))->with('success', trans('messages.order.success'));                     

                    // return view('mastercard.checkout', [
                    //     'locale' => isset($request->appLocal) ? $request->appLocal : '' ,
                    //     'sessionId' => $sessionId,
                    //     'order' => $orderData,
                    //     'isMobile' => isset($request->isMobile) ? $request->isMobile : '',
                    // ]);
                }
            }
        }

        return abort(404);
    }


    public function OldcardPaymentPage($orderNumber, Request $request)
    {


  
        $order = Order::where('order_no', $orderNumber)->first();



        if(!empty($order))
        {
            if($order->payment_status == 'Pending' && $order->payment_type == 'CreditCard')
            {
                $orderData = [
                    'id' => $orderNumber,
                    'amount' => $order->grand_total,
                    'currency' => 'KWD'
                ];
                //dd($orderData);
                $data = MasterCardHelper::generateMasterCardSession($orderData['id'], $orderData['amount'], $orderData['currency']);

                
                if(!empty($data) && $data->result == 'SUCCESS')
                {
                    $sessionId = $data->session->id;

                    return view('mastercard.checkout', [
                        'locale' => $request->appLocal,
                        'sessionId' => $sessionId,
                        'order' => $orderData,
                        'isMobile' => $request->isMobile,
                    ]);
                }
            }
        }

        return abort(404);
    }


    /*
     * Card payment notifications.
     *
     * @param $orderNumber order unique number
     * @param Request $request
     *
     * @return view
     */
    public function cardPaymentNotification($orderNumber, Request $request)
    {
        $order = Order::where('order_no', $orderNumber)->first();

        $data = MasterCardHelper::getOrderStatus($orderNumber);
 
        $isMobile = $request->isMobile;
        if(!empty($order))
        {
            if($order->payment_status == 'Pending' && $order->payment_type == 'CreditCard')
            {
                $data = MasterCardHelper::getOrderStatus($orderNumber);

                if(!empty($data) && $data->result == 'SUCCESS')
                {
                    $order->credit_card_transaction_id = $data->transaction[0]->transaction->id;
                    $order->credit_card_transaction_receipt = $data->transaction[0]->transaction->receipt;
                    $order->payment_status = 'Completed';
                    $order->save();
                    $checkOutController = new CheckoutController($request);
                    $checkOutController->afterSuccessOrder($order);
                    $cookie = \Cookie::forget('cookie.cartItem');
                    
                    
                    if($isMobile == 1)
                    {
                        return  redirect(url(route('OrderStatus', ["orderNumber" => $orderNumber])))->withCookie($cookie);
                    }
                    return  redirect(url(route('myOrderDetail', ["orderNo" => $orderNumber])))->withCookie($cookie)->with('success', trans('messages.order.success'));
                }
                
                $order->payment_status = 'Failed';
                $order->save();

                if($isMobile == 1)
                {
                    return  redirect(url(route('OrderStatus', ["orderNumber" => $orderNumber])));
                }
                
                return redirect(route('checkouts'))->with('error', trans('messages.order.error'));
            }

            return redirect(route('checkouts'))->with('error', trans('messages.order.error'));
        }

        return abort(404);
    }

    /*
     * Show order status page
     *
     * @param $orderNumber order unique number
     *
     * @return view
     */
    public function orderStatus($orderNumber)
    {
        $order = Order::where('order_no', $orderNumber)->first();

        $userLang = User::where('id', $order->customer_id)->select('language')->first();
        \Log::debug('user lang');
        \Log::debug($userLang->language);
        if(!empty($order))
        {
            
            return view('orders.order_status', [
                'order' => $order,
                'userLang' => $userLang->language
            ]);
        }
        
        return abort(404);
    }
}
