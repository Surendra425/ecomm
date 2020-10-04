<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\StoreFollower;
use App\Store;
use App\VendorProductCategory;
use App\StoreRating;

class StoreController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     |--------------------------------------------------------------------------
     |
     | This controller handles follow, unfollow, gets follow stores apis.
     */
    
    protected $perPage = 20;

    /**
     * Gets store details.
     *
     * @param Request $request
     * @return json
     */
    public function storeDetails(Request $request)
    {
        $this->validate($request, [
            'store_id' => 'required|numeric',
        ]);
        
        $user = session()->get('authUser');
        $userId = !empty($user) ? $user->id : null;
        $storeQuery = ApiHelper::getStoreDetailsQuery($user);
        
        $store = $storeQuery->where('stores.id', $request->store_id)->first();


        $store->store_status = trans('api.static_content.'.$store->store_status);

        $store->rating = (float) $store->rating;
        
        $storeRating = StoreRating::where([
            'store_id' => $store->id,
            'user_id' => $userId,
        ])->first();
        
        $store->userRating = (int) !empty($storeRating) ? $storeRating->rating : 0;
        if(!empty($store))
        {
            $store->delivery_time = "-";
            $store->delivery_charge = "-";
            
            $storeCategories = VendorProductCategory::select('vendor_product_categories.id', 'vendor_product_categories.category_name',
                                                            'vendor_product_categories.category_image')
                                                    ->where([
                                                        'vendor_product_categories.vendor_id' => $store->vendor_id,
                                                        'vendor_product_categories.status' => 'Active'
                                                    ])->leftJoin('store_product_categories as sc','sc.store_category_id','=','vendor_product_categories.id')
                                                    ->leftJoin('products as p','p.id','=','sc.product_id')->where('p.status','Active')
                                                    ->groupBy('vendor_product_categories.id')
                                                    ->get();
            
            $address = !empty($user) ? $user->defaultAddress : null;
            
            if(!empty($address))
            {

                $city = $store->shippingCities()->where('city_id', $address->city_id)->first();

                if(!empty($city))
                {
                    $store->delivery_time = $city->from.'-'.$city->to.' '.trans('api.static_content.'.$city->time);
                    $store->delivery_charge = (!empty($city->charge) && $city->charge!='0.00') ? $city->charge.' '.trans('api.static_content.KD') : trans('api.static_content.free');
                }
            }

            return $this->toJson([
                'store' => $store,
                'storeCategories' => $storeCategories,
            ]);
            
        }

        return $this->toJson(null, trans('api.store.not_available'), 0);
    }
    
    
    /**
     * Gets store products.
     *
     * @param Request $request
     * @return json
     */
    public function storeProducts(Request $request)
    {
        $this->validate($request, [
            'store_id' => 'required|numeric',
            'store_category_id' => 'numeric',
        ]);
        
        $user = session()->get('authUser');
        
        $productsQuery = ApiHelper::getProductsQuery($request, $user);
        
        $where['stores.id'] = $request->store_id;
        
        if(!empty($request->store_category_id))
        {
            $where['store_product_categories.store_category_id'] = $request->store_category_id;
        }

        $productsData = $productsQuery->where($where)
            //->orderBy('sell_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage)->toArray();
       
        $products = ApiHelper::getProductResponse($productsData['data']);

        if(!empty($products))
        {
            return $this->toJson([
                'products' => [
                    'data' => $products,
                    'has_more' => !empty($productsData['next_page_url']) ? 1 : 0
                ]
            ]);
        }
        
        return $this->toJson(null, trans('api.products.not_available'), 0);
    }
    
    /**
     * Gets myshopzz stores.
     *
     * @param Request $request
     * @return json
     */
    public function myShopzz(Request $request)
    {
        $user = session()->get('authUser');

        $stores  = Store::where('stores.status','Active')
                        ->selectRaw('stores.id, stores.store_name, stores.store_image')
                        ->join('store_follower', function ($join) use ($user) {
                           $join->on('store_follower.store_id', '=', 'stores.id')
                           ->where('store_follower.user_id', '=', $user->id);
                           
                       })
                       ->orderBy('store_follower.id','desc')
                       ->get();
        
       if(!$stores->isEmpty())
       {
           return $this->toJson([
               'stores' => [
                   'data' => $stores,
               ]
           ]);
       }

       return $this->toJson(null, trans('api.stores.not_available'), 0);
        
    }

    /**
     * Follow and unfollow store.
     *
     * @param Request $request
     * @return json
     */
    public function followOrUnfollowStore(Request $request)
    {
        $user = session()->get('authUser');
        
        $this->validate($request, [
            'store_id' => 'required|numeric',
        ]);
        
        $store = Store::where([
            'id' => $request->store_id,
            'status' => 'Active',
        ])->first();
        
        if(!empty($store))
        {
            $storeFollow = StoreFollower::where([
                'store_id' => $request->store_id,
                'user_id' => $user->id,
            ])->first();
            
            // Check store is follow or not
            if(empty($storeFollow))
            {
                $storeFollow = new StoreFollower();
                $storeFollow->user_id = $user->id;
                $storeFollow->store_id = $request->store_id;
                
                // save as follow store or not
                if($storeFollow->save())
                {
                    return $this->toJson([
                        'is_follow' => 1
                    ]);
                }
                
                return $this->toJson([], trans('api.follow_store.error'), 0);
            }

            // UnFollow store
            if($storeFollow->delete())
            {
                return $this->toJson([
                    'is_follow' => 0
                ]);
            }

            return $this->toJson([], trans('api.follow_store.error'), 0);
        }
        
        return $this->toJson([], trans('api.store.not_available'), 0);
    }
    
    /**
     * Add rating of the store.
     *
     * @param Request $request
     * @return json
     */
    public function storeRating(Request $request)
    {
        $this->validate($request, [
            'store_id' => 'required|numeric',
            'rating' => 'required|numeric|min:1|max:5',
        ]);
        
        $user = session()->get('authUser');
        
        $store = Store::find($request->store_id);
        
        // Check store is available or not
        if(!empty($store))
        {
            $storeRating  = StoreRating::where([
                'store_id' => $request->store_id,
                'user_id' => $request->user_id,
            ])->first();
            
            // Check review is already available or not
            if(empty($storeRating))
            {
                $storeRating =  new StoreRating();
                $storeRating->store_id = $request->store_id;
                $storeRating->user_id = $request->user_id;
                $storeRating->rating = $request->rating;
                $storeRating->save();
                
                $storeQuery = ApiHelper::getStoreDetailsQuery($user);
                
                $store = $storeQuery->where('stores.id', $request->store_id)->first();

                $data['rateCount'] = $store->rate_count;
                $data['rating'] = (float) $store->rating;
                
                return $this->toJson($data, trans('api.store_rating.success'), 1);
            }
            
            return $this->toJson(null, trans('api.store_rating.already_available'), 0);
        }
        
        return $this->toJson(null, trans('api.store.not_available'), 0);
    }
}