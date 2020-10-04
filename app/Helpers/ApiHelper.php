<?php

namespace App\Helpers;

use App\Mail\AdminOrderPlacedMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\VendorOrderMail;
use App\Order;
use App\OrderAddress;
use App\ProductStockHistory;
use App\Vendor;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use App\Event;
use App\Store;
use App\Product;
use App\Collections;
use App\ProductAttrCombination;
use App\StoreWorkingTime;
use App\ProductCart;
use Illuminate\Support\Facades\Mail;

class ApiHelper
{
    /*
     * Gets Stores data query.
     * 
     * @param Request $request
     * 
     * @return $query
     */
    public static function getCategoryStoreQuery($request, $user =null, $catIds = [])
    {
        $stores = Store::selectRaw('stores.id, stores.store_name, stores.store_image,stores.store_slug')
                       ->join('products', 'products.vendor_id', '=', 'stores.vendor_id')
                       ->join('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                       ->when(empty($catIds),function ($query) use($request) {
                          $query->where([
                              'stores.status' => 'Active',
                              'shopzz_product_categories.shopzz_category_id' => $request->category_id,
                          ]);
                      })
                      ->when(!empty($catIds),function ($query) use($catIds) {
                          $query->where(['stores.status' => 'Active'])
                                ->whereIn('shopzz_product_categories.shopzz_category_id', $catIds);
                      })
                      ->groupBy('stores.id')
                      ->orderBy('stores.id','desc')
                      ->get()->toArray();
 
        return [
            'data' => $stores,
            'has_more' => !empty($stores['next_page_url']) ? 1 : 0,
        ];
    }
    
    /*
     * Gets Product query for gets product Listing.
     * 
     * @param Request $request
     * @param Customer $user
     * 
     * @return $query
     */
    public static function getProductsQuery($request, $user =null, $extrafields = '') 
    {
        $userId = !empty($user) ? $user->id : null;
        
        return Product::selectRaw('products.id,'.$extrafields.'products.vendor_id, IF(products.featured != "Yes", true, false) AS is_featured,products.product_slug, 
                                    products.product_title, products.long_description, products.created_at, COUNT(order_products.product_id) AS sell_count, 
                                    IF(product_likes.id != "NULL", true, false) AS is_liked,product_likes.created_at AS product_liked_date, stores.store_name, stores.store_slug')
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('users AS vendors', 'vendors.id', '=', 'products.vendor_id')
                        ->leftjoin('stores', 'stores.vendor_id', '=', 'products.vendor_id')
                        ->leftjoin('order_products', 'order_products.product_id', '=', 'products.id')
                        ->leftJoin('product_likes', function ($join) use ($userId) {
                            $join->on('product_likes.product_id', '=', 'products.id')
                            ->where('product_likes.user_id', '=', $userId);
                        })
//                        ->join('vendor_product_categories as vpc', function($query){
//                            $query->on('vpc.id', 'store_product_categories.store_category_id')
//                                ->where('vpc.status', 'Active');
//                        })
                        ->with([
                            'options' => function($query) {
                            return $query->select('id','product_id', 'combination_title', 'quantity', 'rate');
                            },
                            'images' => function($query) {
                               return $query->select('product_id', 'image_url AS file_name','image_url')->where("status", "=", 'Active');
                            },
                            'videos' => function($query) {
                            return $query->select('product_id', 'video_url AS file_name','video_url');
                            },
                        ])
                        ->where("products.status", "=", 'Active')
                        ->groupBy('products.id');

    }

    /*
     * Make common response for all the products apis.
     * 
     * @param $products
     * 
     * @return $data
     */
    public static function getProductResponse($products)
    {
        $data = [];
        foreach($products as $product)
        {
            $product['created_at']= Carbon::parse($product['created_at'])->getTimestamp();
            $product['price'] = !empty($product['options'][0]['rate']) ? $product['options'][0]['rate']  : 0;
            $product['images'] = collect(array_merge($product['images'], $product['videos']));
            $product['product_images'] = $product['images'];
            $product['images'] = $product['images']->pluck('file_name');
            
            unset($product['videos']);
            $data[] = $product;
        }
        
        return $data;
    }

    
    /*
     * Gets events query.
     *
     * @param $user
     *
     * @return $events
     */
    public static function getEventQuery($user)
    {
        $userId = !empty($user) ? $user->id : null;
        return Event::selectRaw('events.id, events.slug, events.start_date_time,events.title,events.description, events.event_type,
                                 events.address,events.contact_number, events.latitude, events.longitude,
                                 events.created_at, IF(event_likes.id != "NULL", true, false) AS is_liked,event_likes.created_at AS event_liked_date')
                                 ->leftJoin('event_likes', function ($join) use ($userId) {
                                     $join->on('event_likes.event_id', '=', 'events.id')
                                     ->where('event_likes.user_id', '=', $userId);
                                 })
                                 ->with(['images' => function ($query) {
                                     return $query->select('event_id', 'file AS file_name');
                                 }])
                                 ->groupBy('events.id');
    }
    
    /*
     * Gets events based on after product start.
     *
     * @param $user
     * @param $startDate
     *
     * @return $events
     */
    public static function getlatestEventAfterProducts($user, $startDate)
    {
        $query = self::getEventQuery($user);
        
        return $query->where('events.start_date_time', '>',$startDate)
                     ->where('status', 1)
                     ->orderBy('events.start_date_time', 'desc')
                     ->get();
    }
    
    /*
     * Gets events based on products start and end date.
     * 
     * @param $user
     * @param $startDate
     * @param $endDate
     * 
     * @return $events
     */
    public static function getEvents($user, $startDate, $endDate)
    {
        $query = self::getEventQuery($user);
                              
        return $query->whereBetween('events.start_date_time', [$startDate, $endDate])
                              ->where('status', 1)
                              ->orderBy('events.start_date_time', 'desc')
                              ->get();
    }

    /*
     * Get collections.
     *
     * @return Collection $collections
     */
    public static function getCollections($colName)
    {
        $collections = Collections::selectRaw('id, '.$colName.' AS name, collection_tagline AS tagline, background_image AS image')
                                  ->where('status', 'Active')
                                  ->orderBy('created_at', 'desc')
                                  ->get();
        
        return $collections;
    }
    
    /*
     * Gets Stores data query.
     *
     * @param Request $request
     *
     * @return $query
     */
    public static function getFeaturedStores($request)
    {
        $stores = Store::selectRaw('stores.id, stores.store_image,stores.country, users.id AS vendor_id, stores.store_name, stores.banner_image, CAST(AVG(IFNULL(store_rating.rating, 0)) AS DECIMAL(10,2)) AS rating , users.first_name, users.last_name')
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
                       ->paginate(4)->toArray();

       if(!empty($stores['data']))
       {
           foreach ($stores['data'] as $key => $store)
           {
               $stores['data'][$key]['rating'] = (float) number_format($store['rating'], 2);

               $productQuery = Product::select('id', 'product_title')->with(['images' => function ($query) {
                   return $query->select('id', 'product_id', 'image_url');
               }])
               ->where('vendor_id', $store['vendor_id'])
               ->where('status', 'Active')
               ->orderBy('id','desc'); 
               
               $stores['data'][$key]['total_products'] = $productQuery->count();
               
               $products = $productQuery->limit(4)->get();
               foreach($products as $product)
               {
                  
                   $stores['data'][$key]['products'][] = [
                       'product_title' => $product->product_title,
                       'id' => $product->id,
                       'image' => $product->images->pluck('image_url')->first()
                   ];
               }
           }
       }
       
       return [
           'data' => $stores['data'],
           'has_more' => !empty($stores['next_page_url']) ? 1 : 0,
       ];
    }

    /*
     * Gets Stores data query.
     *
     * @param Request $request
     *
     * @return $query
     */
    public static function checkCartValidation($option, $qty, $user, $address = null)
    {

        // check option and product is available or not
        if(!empty($option) && !empty($option->product))
        { 
            // Check qty is available or not
            if($option->quantity < $qty)
            {
                return [
                    'status' => 0,
                    'message' => trans('api.carts.qty_not_available'),
                ];
            }

            $store = Store::where('vendor_id', $option->product->vendor_id)->first();

            // Check store status is available or not
            if($store->store_status != 'Open')
            {
                return [
                    'status' => 0,
                    'message' => trans('api.carts.store_status_error', ['store' => $store->store_name, 'status' => strtolower($store->store_status)]),
                ];
            }

            $storeWorkingDay = StoreWorkingTime::where([
                                    'store_id' => $store->id,
                                    'day' => Carbon::now()->format('l')
                                ])->first();

            if(!empty($storeWorkingDay))
            {
                //dd($storeWorkingDay);
                if($storeWorkingDay->is_fullday_open == 'No')
                {
                    $today = Carbon::now()->format('Y-m-d H:i:s');

                    $openTime =  date('Y-m-d H:i:s', strtotime(date('Y-m-d '.$storeWorkingDay->open_time)));
                    $closeTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d '.$storeWorkingDay->closing_time)));

                    if(!($today > $openTime && $today < $closeTime))
                    {
                        return [
                            'status' => 0,
                            'message' => trans('api.carts.store_status_error', ['store' => $store->store_name, 'status' => 'close']),
                        ];
                    }
                }

                $address = !empty($address) ? $address : (!empty($user) ? $user->defaultAddress : null);

                    if(!empty($address)) {

                        $productShippingCountry = $option->product->productShipping()->where('country_id', $address['country_id'])->first();

                        if(empty($productShippingCountry))
                        {
                            return [
                                'status' => 0,
                                'message' => trans('api.carts.delivery_not_available'),
                            ];
                        }
                        $city = $store->shippingCities()->where('city_id', $address['city_id'])->first();

                        if(empty($city))
                        {
                            return [
                                'status' => 0,
                                'message' => trans('api.carts.delivery_not_available'),
                            ];
                        }
                    }

                return [
                    'status' => 1,
                    'data' => [
                        'charge' => !empty($city->charge) ? $city->charge : 0,
                    ],
                    'message' => '',
                ];
            }

            return [
                'status' => 0,
                'message' => trans('api.carts.store_status_error', ['store' => $store->store_name, 'status' => strtolower($store->store_status)]),
            ];
        }

        // check product is available or not
        if(empty($option->product))
        {
            return [
                'status' => 0,
                'message' => trans('api.product.not_available'),
            ];
        }

        return [
            'status' => 0,
            'message' => trans('api.product_option.not_available'),
        ];
    }
    
    /*
     * Move carts product to user account.
     *
     * @param Request $user
     * @param $deviceId
     *
     * @return $query
     */
    public static function moveCartProducts($deviceId, $user)
    {
        $deviceProductCarts = ProductCart::where('device_id', $deviceId)->get();

        $userProductCarts = ProductCart::where('user_id', $user->id)->get();

        $deviceProductIds = $deviceProductCarts->pluck('product_combination_id')->toArray();
        
        $deviceProductCarts = $deviceProductCarts->keyBy('product_combination_id')->toArray();

        if(!$userProductCarts->isEmpty() && $user->type != 'guest')
        {
            foreach ($userProductCarts as $userProductCart)
            {
                if(in_array($userProductCart->product_combination_id, $deviceProductIds))
                {
                    $deviceCart = $deviceProductCarts[$userProductCart->product_combination_id];
                    $userProductCart->quantity += $deviceCart['quantity'];
                    $userProductCart->device_id = null;
                    $userProductCart->save();
                    ProductCart::where('id', $deviceCart['id'])->delete();
                }
            }
        }

        $updateData = [
           'user_id' => $user->id
        ];

        if($user->type != 'guest')
        {
            $updateData['device_id'] = null;
        }

        ProductCart::where('device_id', $deviceId)
                   ->update($updateData); 
    }
    
    /*
     * Gets Stores Details api.
     *
     * @param $vendorId
     * @param Request $request
     *
     * @return $user
     */
    public static function getStoreDetails($vendorId, $user =null)
    {
        
        $userId = !empty($user) ? $user->id : null;

        $store  = Store::selectRaw('stores.id, stores.city,stores.store_name, stores.store_image,stores.store_slug, stores.country,IF(store_follower.id != "NULL", true, false) AS is_follow, COUNT(store_rating.id) AS rate_count, CAST(AVG(IFNULL(store_rating.rating, 0)) AS DECIMAL(10,2)) AS rating')
                       ->leftJoin('store_follower', function ($join) use ($userId) {
                           $join->on('store_follower.store_id', '=', 'stores.id')
                                ->where('store_follower.user_id', '=', $userId);
                       })
                       ->leftJoin('store_rating', function ($join) use ($userId) {
                            $join->on('store_rating.store_id', '=', 'stores.id');
                       })
                       ->where([
                            'stores.status' => 'Active',
                           'stores.vendor_id' => $vendorId
                       ])
                       ->groupBy('stores.id')
                       ->first();
                       
       return $store;
    }

    /*
     * Gets Stores Details query.
     *
     * @param Request $request
     *
     * @return $user
     */
    public static function getStoreDetailsQuery($user =null)
    {
        $userId = !empty($user) ? $user->id : null;

        return Store::selectRaw('stores.id, stores.about_us, stores.vendor_id,stores.store_status,stores.banner_image,stores.store_name, stores.store_image, stores.store_slug, stores.country,IF(store_follower.id != "NULL", true, false) AS is_follow, COUNT(store_rating.id) AS rate_count, CAST(AVG(IFNULL(store_rating.rating, 0)) AS DECIMAL(10,2)) AS rating,rs.rating as user_store_rating')
                    ->leftJoin('store_follower', function ($join) use ($userId) {
                        $join->on('store_follower.store_id', '=', 'stores.id')
                        ->where('store_follower.user_id', '=', $userId);
                    })
                    ->leftJoin('store_rating', function ($join) use ($userId) {
                        $join->on('store_rating.store_id', '=', 'stores.id');
                    })
                    ->leftJoin('store_rating as rs', function ($join) use ($userId) {
                        $join->on('rs.store_id', '=', 'stores.id')->where('rs.user_id',$userId);
                    })
                    ->groupBy('stores.id');
    }


    /**
     * After success order
     * Managed product stock and mail send
     *
     * @param $order
     */
    public static function afterSuccessOrderMail($order)
    {

        DB::beginTransaction();
       
       // \Log::debug('afterSuccessOrderMailFromApiHelperCalled');    
       // \Log::debug('clientEmail '.$order->user->email);
        $order->load('user', 'orderProducts.option', 'orderProducts.product');

        $orderProducts = $order->orderProducts;

        $shipping_address = OrderAddress::select("order_addresses.*", "city", "state", "country","users.first_name", "users.last_name")
            ->leftjoin("users", "users.id", "order_addresses.customer_id")
            ->where("order_id", "=", $order->id)->where("address_type", "Shipping")->first();

        // create invoice for vendor pdf
        $vendorIds = $orderProducts->pluck('product_vendor_id')->unique()->toArray();

        $vendors = Vendor::whereIn('id', $vendorIds)->get();

        foreach ($vendors as $vendor)
        {
            $vendorProducts = $orderProducts->where('product_vendor_id', $vendor->id);

            $data['order'] = $order;
            $data['vendorOrderProducts'] = $vendorProducts;
            $data['address'] = $shipping_address;
            $data['vendor'] = $vendor;

            $html = view('app.order_vendor_invoice', $data);

            $filePath = public_path('doc/invoice/'.$order->order_no . '_' . $vendor->id . '.pdf');

            PDFHelper::generatePdfFile($filePath, $html);
        }

        // create order invoice for admin

        $vendors = \App\Vendor::selectRaw('users.id, CONCAT(first_name," ", last_name) as vendorName, s.store_name,email')
            ->whereIn('users.id', $vendorIds)->leftJoin('stores as s', 's.vendor_id', 'users.id')->get();

        $vendorStores = [];
        foreach ($vendors as $key => $vendor)
        {
            $vendorStores[$key]['stores'] = $vendor->store_name;
            $vendorStores[$key]['vendor_name'] = $vendor->vendorName;

            $vendorProducts = $orderProducts->where('product_vendor_id', $vendor->id);

            $vendorStores[$key]['products'] = $vendorProducts;
        }

        
        $invoice['order'] = $order;
        $invoice['shipping_address'] = $shipping_address;
        $invoice['vendorStores'] = $vendorStores;
        
        $html = view('app.order_invoice', $invoice);

        $filePath = public_path('doc/invoice/'.$order->order_no . '.pdf');

        PDFHelper::generatePdfFile($filePath, $html);
        $shippingTotal = $order->shipping_total;

        try {

            //Send notification mail to user
            Mail::to($order->user->email)->send(new OrderConfirmationMail($order->id));

            Mail::to(env('ADMIN_EMAIL_ADDRESS'))->send(new AdminOrderPlacedMail($order->id));

            
            // Send notification mail to vendor
            foreach($vendors as $vendor)
            {
                Mail::to($vendor->email)->send(new VendorOrderMail($order,$vendor));
            }
           
            $order->is_mail_send = 1;
            $order->shipping_total = $shippingTotal;
            $order->save(); 

        }
        catch(\Exception $e)
        {
            $order->is_mail_send = 2;
            $order->shipping_total = $shippingTotal;
            $order->save();
        }

        DB::commit();
    }
}