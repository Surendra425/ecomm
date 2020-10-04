<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Order;

/*
 |--------------------------------------------------------------------------
 | MyOrder Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles users orders and order details.
 */

class MyOrderController extends Controller
{
    protected $perPage = 20;
    
    /**
     * My orders listing.
     *
     * @param Request $request
     * @return json
     */
    public function myOrders(Request $request)
    {
        $user = session()->get('authUser');

        $orders = Order::selectRaw('id, order_no, payment_status, created_at,grand_total, IF(payment_type != "KNet", (IF(payment_type != "CreditCard", "Cash", "Credit Card")),  "Knet") AS payment_type')
                       ->where('customer_id', $user->id)
                       ->where('payment_status', '!=','Pending')
                       ->orderBy('id', 'desc')
                       ->paginate($this->perPage);

        $hasMore = 1;
        $request->page = !empty($request->page) ? $request->page : 1;

        if($orders->lastPage() == $request->page)
        {
            $hasMore = 0;
        }
        
        $ordersData = $orders->toArray()['data'];
        
        if(!empty($ordersData))
        {
            return $this->toJson([
                'orders' => [
                    'data' => $ordersData,
                    'has_more' => $hasMore
                ]
            ]);
        }

        return $this->toJson(null, trans('api.orders.not_available'), 0);
    }

    /**
     * Gets order details.
     *
     * @param Request $request
     * @return json
     */
    public function orderDetails(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required',
        ]);

        $user = session()->get('authUser');
        $userId = $user->id;
        $order = Order::selectRaw('id, order_no,sub_total,discount_amount,shipping_total,payment_status, created_at,grand_total, IF(payment_type != "KNet", IF(payment_type != "CreditCard", "Cash",  "Credit Card"),  "Knet") AS payment_type')
                       ->where([
                           'id' => $request->order_id,
                           'customer_id' => $user->id
                       ])->first();

        if(!empty($order))
        {
            $order->load([
                'orderProducts' => function ($query) use ($userId) {
                    $query->selectRaw('products.id AS product_id,order_products.id,product_images.image_url AS image,
                                       order_products.order_id,order_products.note,order_products.product_combination_id,products.product_title, order_products.quantity, order_products.rate AS price,
                                       order_products.sub_total,product_attr_combination.combination_title, 
                                       stores.id AS store_id,stores.store_name AS store_name, CAST(IF(product_review.id != "NULL", product_review.rating, 0) AS UNSIGNED) AS user_rating')
                          ->leftjoin('products', 'products.id', '=', 'order_products.product_id')
                          ->leftjoin('product_images', 'products.id', '=', 'product_images.product_id')
                          ->leftjoin('stores', 'stores.vendor_id', '=', 'products.vendor_id')
                        ->leftJoin('product_review', function ($join) use ($userId) {
                            $join->on('product_review.product_combination_id', '=', 'order_products.product_combination_id')
                                ->where('product_review.user_id', '=', $userId);
                        })
                          ->leftjoin('product_attr_combination', 'order_products.product_combination_id', '=', 'product_attr_combination.id')
                          ->groupBy('order_products.id');
                },
                'address' => function ($query) {
                    $query->selectRaw('order_addresses.id, order_id,CONCAT(u.first_name, " ", u.last_name) as full_name,
                    block,street,avenue,building,additional_directions,floor,apartment,order_addresses.mobile_no AS mobile,
                     order_addresses.landline_no AS landline,city,state,country')
                        ->leftJoin('users as u', 'u.id', 'order_addresses.customer_id');
                }
             ]);

            return $this->toJson([
                'order' => $order
            ]);
        }

        return $this->toJson([], trans('api.order.not_available'), 0);
    }
}