<?php

namespace App\Helpers;

use App\Order;
use App\OrderAddress;
use App\OrderProduct;
use Carbon\Carbon;

use DB;
use App\Event;
use App\Store;
use App\Product;
use App\Collections;
use App\ProductAttrCombination;
use App\StoreWorkingTime;
use App\ProductCart;

class QueryHelper
{
    
    /*
     * Gets Stores data query.
     *
     * @param Request $request
     *
     * @return $query
     */
    public static function getFeaturedStores($request)
    {
        $stores = Store::selectRaw('stores.id, stores.store_slug,stores.created_at, stores.store_image,stores.country, users.id AS vendor_id, stores.store_name, stores.banner_image, CAST(AVG(IFNULL(store_rating.rating, 0)) AS DECIMAL(10,2)) AS rating , users.first_name, users.last_name')
        ->leftjoin('store_rating', 'store_rating.store_id', '=', 'stores.id')
        ->leftjoin('users', 'users.id', '=', 'stores.vendor_id')
        ->rightjoin('products', function ($join) {
            $join->on('products.vendor_id', '=', 'stores.vendor_id')
            ->where('products.status', '=', 'Active');
        })
        ->where("stores.status", "=", "Active")->where("stores.featured", "=", "Yes")
        ->where("products.status", "=", 'Active')
        ->groupBy('stores.vendor_id')
        ->orderBy('stores.updated_at', 'desc')
        ->orderBy('products.id', 'desc')
        ->paginate(20)->toArray();
        
        if(!empty($stores['data']))
        {
            foreach ($stores['data'] as $key => $store)
            {
                $stores['data'][$key]['rating'] = (float) number_format($store['rating'], 2);
                
                $productQuery = Product::select('id', 'product_title', 'product_slug')->with(['images' => function ($query) {
                    return $query->select('id', 'product_id', 'image_url');
                }])
                ->where('vendor_id', $store['vendor_id'])
                ->where('status', 'Active')
                ->orderBy('id', 'desc');

                $stores['data'][$key]['total_products'] = $productQuery->count();
                
                $products = $productQuery->get();
                foreach($products as $product)
                {
                    
                    $stores['data'][$key]['products'][] = [
                        'product_title' => $product->product_title,
                        'id' => $product->id,
                        'product_slug' => $product->product_slug,
                        'image' => $product->images->pluck('image_url')->first()
                    ];
                }
            }
        }
        
        return $stores['data'];
    }
    
    /**
     * Gets all the active collections.
     *
     * @return json
     */
    public static function getCollections()
    {
        return Collections::where('status', 'Active')
        ->orderBy('id', 'desc')
        ->get();
    }

    /**
     * get cart products count
     * @return mixed
     */

    public static function getCartProductsCount(){
        return self::getCartProductsQuery()->count();
    }

    /**
     * get cart products 
     * @return mixed
     */

    public static function getCartProducts(){
        return self::getCartProductsQuery();
    }
    /**
     * Get cart details query.

     * @return Collection
     */
    public static function getCartProductsQuery()
    {
        $browserId = \Cookie::get('browserId');

        $user = \Auth::guard('customer')->user();


        $where = [
            'device_id' => $browserId
        ];

        // Check user login or not.
        if(!empty($user))
        {
            $where = [
                'user_id' => $user->id
            ];
        }

        $productCart = ProductCart::selectRaw('product_cart.id,stores.vendor_id,product_cart.user_id,product_cart.device_id, product_cart.product_combination_id AS option_id,
                                    product_cart.product_id,product_cart.quantity, stores.store_name,stores.store_slug,stores.id AS store_id,
                                    products.product_title,products.product_slug,product_attr_combination.combination_title,product_attr_combination.rate AS price, product_attr_combination.quantity as available_qty')
                ->join('products', 'products.id', '=', 'product_cart.product_id')
                ->join('stores', 'stores.vendor_id', '=', 'products.vendor_id')
                ->join('product_attr_combination', 'product_attr_combination.id', '=', 'product_cart.product_combination_id')
                ->where($where)
                ->with([
                    'product' => function ($query) {
                        return $query->select('id');
                    },
                    'product.image' => function ($query) {
                        return $query->select('product_id', 'image_url AS file_name')->where("status", 'Active');
                    },
                ]);

        return $productCart->get();
    }

    /**
     * get cart products
     *
     * @param  $request
     * @return
     */
    public static function getOrderDetail($order)
    {
        return  OrderProduct::select("order_products.*","order_products.product_id"
            ,"order_products.product_combination_id","order_products.order_id")
            ->with([
                'product' => function($query) {
                    return $query->select('id','product_title');
                },
                'product.image' => function($query) {
                    return $query->select('product_id', 'image_url');
                },
                'option' => function($query) {
                    return $query->select('id','combination_title','quantity');
                }
            ])
            ->groupBy("order_products.id")
            ->where("order_id",$order->id)
            ->get();
    }


    /**
     * get order with full detail
     */

    public static function getOrderFullDetail($order){

        $order = Order::find($order);

        $orderProducts = self::getOrderDetail($order);

        $shippingAddress = OrderAddress::select("order_addresses.*","order_addresses.customer_id","order_addresses.city","order_addresses.country")
            ->where("order_id", "=", $order->id)->where("address_type", "Shipping")->first();

        $data['order'] = $order;
        $data['order_products'] = $orderProducts;
        $data['shipping_address'] = $shippingAddress;

        return $data;
    }

    /**
     * check login user
     */

    public static function checkLoginUser($request,$user=null)
    {
        $browserId = \Cookie::get('browserId');

        $userInfo = \Auth::user('customer');

        return !empty($userInfo) ? ['user_id' => $userInfo->id ] : ['device_id' => $browserId];


    }
}