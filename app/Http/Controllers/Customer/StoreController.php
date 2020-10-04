<?php

namespace App\Http\Controllers\Customer;

use App\StoreWorkingTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\Collections;
use App\VendorProductCategory;
use App\Store;
use App\StoreRating;
use App\StoreFollower;

/*
 |--------------------------------------------------------------------------
 | Store Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles store page.
 */

class StoreController extends Controller
{
    /**
     * Show store details page.
     *
     * @param Request $request
     * @param string $storeSlug
     *
     * @return view
     */
    public function storeDetails(Request $request, $storeSlug)
    {
        $user= Auth::guard('customer')->user();

        $storeQuery = ApiHelper::getStoreDetailsQuery($user);
        
        $store = $storeQuery->where('stores.store_slug', $storeSlug)->first();
       // dd($store);
        // Check store is available or not
        if(!empty($store))
        {
            // Check store in & out time andset store status
            $storeWorkingDay = StoreWorkingTime::where([
                'store_id' => $store->id,
                'day' => Carbon::now()->format('l')
            ])->first();

            if(!empty($storeWorkingDay))
            {
                if($storeWorkingDay->is_fullday_open == 'No')
                {
                    $today = Carbon::now()->format('Y-m-d H:i:s');

                    $openTime =  date('Y-m-d H:i:s', strtotime(date('Y-m-d '.$storeWorkingDay->open_time)));
                    $closeTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d '.$storeWorkingDay->closing_time)));

                    if(!($today > $openTime && $today < $closeTime))
                    {
                        $store->store_status = 'Close';
                        $store->save();
                    }else{
                        $store->store_status = 'Open';
                        $store->save();
                    }
                }
            }

            // Gets store categories
            $storeCategories = VendorProductCategory::selectRaw('vendor_product_categories.id,
             vendor_product_categories.category_name, vendor_product_categories.category_image,count(p.id) as product_count')
                                                    ->where([
                                                        'vendor_product_categories.vendor_id' => $store->vendor_id,
                                                        'vendor_product_categories.status' => 'Active'
                                                    ])->leftJoin('store_product_categories as sc','sc.store_category_id','=','vendor_product_categories.id')
                                                    ->leftJoin('products as p','p.id','=','sc.product_id')->where('p.status','Active')
                                                    ->groupBy('vendor_product_categories.id')
                                                    ->get();

            /*$storeCategories = VendorProductCategory::select('id', 'category_name', 'category_image')
                ->where([
                    'vendor_id' => $store->vendor_id,
                    'status' => 'Active'
                ])->get();*/

            $store->delivery_time = "";
            $store->delivery_charge = "";

            $address = !empty($user) ? $user->defaultAddress : null;

            if(!empty($address))
            {
                $city = $store->shippingCities()->where('city_id', $address->city_id)->first();

                if(!empty($city))
                {
                    $store->delivery_time = $city->from.'-'.$city->to.' '.$city->time;
                    $store->delivery_charge = !empty($city->charge) ? $city->charge.' KD' : 'Free';
                }
            }
            
            $request->store_id = $store->id;
            $products = $this->getStoreProducts($request);

            return view('front.stores.store_details', [
                'perPage' => $this->perPage,
                'store' => $store,
                'storeCategories' => $storeCategories,
                'products' => $products,
            ]);
        }
        
        abort(404);
    }
    
    
    /**
     * Gets store products.
     *
     * @param Request $request
     *
     * @return view
     */
    public function showStoreProducts(Request $request)
    {
        $data['products'] = $this->getStoreProducts($request);

        return view('front.common.products', $data);
    }
    
    
    /**
     * Gets store products.
     *
     * @param Request $request
     * @param array $subCatIds
     *
     * @return Collections
     */
    private function getStoreProducts(Request $request)
    {
        $user= Auth::guard('customer')->user();

        // Gets store products
        $productsQuery = ApiHelper::getProductsQuery($request, $user);

        $where['stores.id'] = $request->store_id;
        
        if(!empty($request->store_category_id))
        {
            $where['store_product_categories.store_category_id'] = $request->store_category_id;
        }

        return $productsQuery->where($where)
                             //->orderBy('sell_count', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate($this->perPage);
    }
	 /**
     * Show store about us page.
     *
     * @param Request $request
     * @param string $storeSlug
     *
     * @return view
     */
    public function storeAboutUs(Request $request, $storeSlug)
    {
        $user= Auth::guard('customer')->user();

        $storeQuery = ApiHelper::getStoreDetailsQuery($user);

        $store = $storeQuery->where('stores.store_slug', $storeSlug)->first();

        if(!empty($store))
        {
           return view('front.stores.store_about_us' , [
               'store' => $store
           ]);
        }
        
        abort(404);
    }
    
    /**
     * Follow and unfollow store.
     *
     * @param Request $request
     * @return json
     */
    public function followOrUnfollowStore(Request $request)
    {
        $user= Auth::guard('customer')->user();
        
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
        
        $user = Auth::guard('customer')->user();
        
        $store = Store::find($request->store_id);

        // Check store is available or not
        if(!empty($store))
        {
            $storeRating  = StoreRating::firstOrCreate([
                'store_id' => $request->store_id,
                'user_id' => $request->user_id,
            ]);

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
        
        return $this->toJson(null, trans('api.store.not_available'), 0);
    }

    /**
     * Gets best stores details. 
     * 
     * @param Request $request
     *
     * @return view
     */
    public function getBestStore(Request $request)
    {
        $stores['stores'] = $this->getStores();

        return view('front.best-seller', $stores);
    }

    /**
     * @param Request $request
     *
     * @return view
     */
    public function getStoresList(Request $request) 
    {
        $stores['stores'] = $this->getStores();
        
        return view('front.common.store_box', $stores);
    }

    /**
     * Gets stores details.
     * 
     * @return stores
     */
    private function getStores() 
    {
        $storeQuery = ApiHelper::getStoreDetailsQuery()->where('stores.status','Active');

        return $storeQuery->orderBy('rating','desc')->orderBy('id')->paginate(100);
    }
}