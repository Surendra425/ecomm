<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\PromoCodeHelper;
use App\User;
use App\Helpers\ApiHelper;
use App\ProductAttrCombination;
use App\UserAddress;
use App\Order;
use Illuminate\Support\Facades\DB;
use App\OrderProduct;
use App\OrderAddress;
use App\ProductCart;
use App\ProductStockHistory;

class OrderController extends Controller
{
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

        $promoCode = $request->promo_code;
        $orderAmount = $request->order_amount;

        $result = PromoCodeHelper::applyPromoCode($orderAmount, $promoCode);

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
     * Gets shipping details.
     *
     * @param Request $request
     * @return json
     */
    public function getShippingDetails(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'address_id' => 'required',
            'device_id' => 'required',
        ]);

        $user = User::find($request->user_id);
        
        $userData = $user;
        
        if(!empty($user) && $user->type == 'guest')
        {
            $userData = null;
        }

        $cart =  new CartController($request);

        $cartProducts = $cart->getCartProducts($request, $userData);
        $address = UserAddress::find($request->address_id);
        if(empty($address))
        {
            return $this->toJson([], trans('api.address.not_available'), 0);
        }
        
        if(!$cartProducts->isEmpty())
        {
            $shippingChargeData = [];
            $totalShippingCharge = 0;
            foreach ($cartProducts as $cartProduct)
            {
               
                $option = ProductAttrCombination::find($cartProduct->option_id);
                
                $result = ApiHelper::checkCartValidation($option, $request->qty, $user, $address);
                
                if($result['status'] == 1)
                {
                    $shippingChargeData[$cartProduct->store_id] = isset($shippingChargeData[$cartProduct->store_id]) ? $shippingChargeData[$cartProduct->store_id] : 0;
                    $shippingChargeData[$cartProduct->store_id] = $result['data']['charge'];
                }
                else 
                {
                    return $this->toJson([], $result['message'], 0);
                }
            }
            
            if(!empty($shippingChargeData))
            {
                foreach ($shippingChargeData as $shippingCharge)
                {
                    $totalShippingCharge += $shippingCharge;
                }
            }
            
            return $this->toJson([
                'shipping_charge' => $totalShippingCharge,
                'is_show_cod' => $address->country_id == 2 ? 1 : 0,
             ]);
        }

        return $this->toJson([], trans('api.carts.empty'), 0);
    }
    
    
    /**
     * Place order api.
     *
     * @param Request $request
     * @return json
     */
    public function placeOrder(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'address_id' => 'required',
            'payment_type' => 'required||in:COD,KNET,CreditCard',
        ]);
        
        $user = User::find($request->user_id);

        if(empty($user))
        {
            return $this->toJson([], trans('api.user.not_available'), 0);
        }
        
        if($user->type == 'guest')
        {
            ApiHelper::moveCartProducts($request->device_id, $user);
        }
        
        $cart =  new CartController($request);
        
        $cartProducts = $cart->getCartProducts($request, $user);
        $address = UserAddress::where([
            'id' => $request->address_id,
            'user_id' => $user->id,
        ])->first();
        
        // Check address is available or not
        if(empty($address))
        {
            return $this->toJson([], trans('api.address.not_available'), 0);
        }
      
        // Check cart is empty or not
        if(!$cartProducts->isEmpty())
        {
            $shippingChargeData = [];
            $totalShippingCharge = 0;
            $orderProducts = [];
            $subTotal = 0;
            foreach ($cartProducts as $key=> $cartProduct)
            {
                $total = $cartProduct->quantity * $cartProduct->price;
                $subTotal += $total;
                
                $orderProducts[$key] = [
                    'product_id' => $cartProduct->product_id,
                    'product_title' => $cartProduct->product_title,
                    'product_slug' => $cartProduct->product_slug,
                    'product_combination_id' => $cartProduct->option_id,
                    'product_vendor_id' => $cartProduct->vendor_id,
                    'quantity' => $cartProduct->quantity,
                    'rate' => $cartProduct->price,
                    'grand_total' => $cartProduct->quantity * $cartProduct->price,
                    'sub_total' => $total,
                    'delivery_status' => 'Pending',
                    'note' => $cartProduct->note,
                ];

                $option = ProductAttrCombination::find($cartProduct->option_id);
                
                $result = ApiHelper::checkCartValidation($option, $cartProduct->quantity, $user, $address);
                
                if($result['status'] == 1)
                {
                    $orderProducts[$key]['shipping_charges'] = $result['data']['charge'];
                    $shippingChargeData[$cartProduct->store_id] = isset($shippingChargeData[$cartProduct->store_id]) ? $shippingChargeData[$cartProduct->store_id] : 0;
                    $shippingChargeData[$cartProduct->store_id] = $result['data']['charge'];
                }
                else
                {
                    return $this->toJson([], $result['message'], 0);
                }
            }
            
            if(!empty($shippingChargeData))
            {
                foreach ($shippingChargeData as $shippingCharge)
                {
                    $totalShippingCharge += $shippingCharge;
                }
            }
            
            $orderProducts = collect($orderProducts);
            
            DB::beginTransaction();
            
            $order =  new Order();
            $order->customer_id = $user->id;
            $order->customer_name = $user->first_name.' '.$user->last_name;
            $order->sub_total = $subTotal;
            $order->shipping_total = $totalShippingCharge;
            $order->grand_total = $subTotal + $totalShippingCharge;
            $order->payment_type = $request->payment_type == 'COD' ? 'Cash on Delivery' : ($request->payment_type == 'KNET'? 'KNet' : 'CreditCard');
            $order->payment_status = $request->payment_type == 'COD' ? 'Completed' : 'Pending';

            // Apply promocode
            if(!empty($request->promo_code))
            {
                $promocodeData = PromoCodeHelper::applyPromoCode($order->sub_total, $request->promo_code);
                
                if($promocodeData['status'] == 0)
                {
                    return $this->toJson([], $promocodeData['msg'], 0);
                }
                
                $order->coupon_code = $promocodeData['promo_code'];
                $order->discount_amount = $promocodeData['promo_code_discount_amount'];
                $order->grand_total = $order->grand_total - $promocodeData['promo_code_discount_amount'];
            }

            $address->load('cityr', 'countryr');

            if($order->save())
            {
                $order->order_no = "ORDER" . str_pad($order->id, 6, mt_rand(100000, 999999), STR_PAD_RIGHT);
                $order->save();

                
                $orderAddress = new OrderAddress();
                $orderAddress->order_id = $order->id;
                $orderAddress->customer_id = $address->user_id;
                $orderAddress->full_name = $address->full_name;
                $orderAddress->block = $address->block;
                $orderAddress->street = $address->street;
                $orderAddress->avenue = $address->avenue;
                $orderAddress->building = $address->building;
                $orderAddress->floor = $address->floor;
                $orderAddress->additional_directions = $address->additional_directions;
                $orderAddress->mobile_no = $address->mobile;
                $orderAddress->landline_no = $address->landline;
                $orderAddress->city = $address->city ?? $address->cityr->city_name;
                $orderAddress->state = '';
                $orderAddress->country = $address->country ?? $address->countryr->country_name;
                $orderAddress->pin_code = $address->pin_code;
                $orderAddress->address_type = 'Shipping';

                $orderAddress->save();  

                $orderProducts  = $orderProducts->map(function ($orderProduct, $key) use ($order) {
                    $orderProduct['order_id'] = $order->id;
                    return $orderProduct;
                })->toArray();
                
                OrderProduct::insert($orderProducts);
            }

            DB::commit();
            $url = '';
            
            if($order->payment_type == 'Cash on Delivery')
            {
                $this->afterSuccessOrder($order);
            }
            elseif($order->payment_type == 'KNet') 
            {
                $url = route('knetPay', [
                    'orders'=> $order->id,
                    'isMobile' => 1,
                ]);
            }
            elseif($order->payment_type == 'CreditCard')
            {
                $url = route('CardPayment', [
                    'orderNumber'=> $order->order_no,
                    'isMobile' => 1,
                    'appLocal' => app()->getLocale()
                ]);
                
            }

            return $this->toJson([
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_no,
                    'url' => $url,
                ]
            ], trans('api.order.success'));
        }
        
        return $this->toJson([], trans('api.carts.empty'), 0);
    }
    
    /**
     * Manage orders qty, remove cart products, send notifications to user. 
     *
     * @param Request $request
     * @return json
     */
    public function afterSuccessOrder(Order $order)
    {
        DB::beginTransaction();
        
        $order->payment_status = 'Completed';
        $order->save();
        $order->load('user','orderProducts.option', 'orderProducts.product');

        $orderProducts = $order->orderProducts;
        ProductCart::where('user_id', $order->user->id)->delete();
        // update product stock
        if(!$orderProducts->isEmpty())
        {
            $productStockHistory = [];
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
        }

        DB::commit();

        if ($order->payment_type == 'Cash on Delivery') 
        {
            $order->is_mail_send = 1;
            $order->save(); 
            
            $cmd = 'cd '.base_path().' && php artisan sendOrderSuccessMail:send '.$order->id;
            exec($cmd. '> /dev/null &');

        }


    }
}