<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Keywords;
use App\Store;

class SearchController extends Controller
{
    protected $perPage = 10;

    /*
      |--------------------------------------------------------------------------
      | Search Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles search screen apis.
     */

    /**
     * This is used for search screen.
     *
     * @return json
     */
    public function index(Request $request)
    {
       $user = session()->get('authUser');
       
       $colName = ($request->isAr) ? 'ifnull(collection_name_ar,collection_name)' : 'collection_name';

       $result['collections'] = ApiHelper::getCollections($colName); 
       $result['top_products'] = [
           [
             'title' => trans('api.static_content.popular'),
             'slug' => 'popular',
             'data' => $this->popularProducts($request, 0)
           ]
       ];

       $result['featured_stores'] = [
               'title' => trans('api.static_content.featured_stores'),
               'slug' => 'featured_stores',
               'data' => ApiHelper::getFeaturedStores($request)['data'],
       ];

       $result['bottom_products'] = [
           [
               'title' => trans('api.static_content.just_added'),
               'slug' => 'just_added',
               'data' => $this->justAddedProducts($request, 0),
           ]
       ];

       return $this->toJson($result);
    }
    
    /**
     * This is used to gets search keywords.
     *
     * @return json
     */
    public function getKeywords(Request $request)
    {
        $keyWords = [];
        if(!empty($request->search))
        {
            $keyWords = DB::select("SELECT * FROM
                               ( SELECT keyword AS name
                                 FROM keywords
                                UNION
                                 SELECT store_name as name
                                 FROM stores
                                UNION
                                 SELECT product_title as name
                                 FROM products) AS search
                               WHERE name LIKE '%".$request->search."%'
                               ORDER BY name
                               LIMIT 5");
            
            if(!empty($keyWords))
            {
                $result = collect($keyWords)->pluck('name')->toArray();
                
                if($request->app_type == 1)
                {
                    return $this->toJson(['keywords' => $result]);
                }

                return $this->toJson(['keywords' => $keyWords]);
            }
        }

        $result = [
            'keywords' => $keyWords,
        ];

        return $this->toJson($result);
    }

    /**
     * View more products.
     *
     * @return json
     */
    public function viewMore(Request $request)
    {
        $user = session()->get('authUser');
        
        $result = [];

        $message = trans('api.products.not_available');

        switch ($request->type)
        {
            case 'popular':
                 
                $result = $this->popularProducts($request, 1);
                break;
            
            case 'featured_stores':
                
                $message = trans('api.stores.not_available');
                
                $result = ApiHelper::getFeaturedStores($request);
                break;
                
            case 'just_added':

                $result = $this->justAddedProducts($request, 1);
                break;
           
            case 'related_products':
                
                $result = $this->getRelatedProducts($request);
                break;
            
        }
        

        if(!empty($result['data']))
        {
            return $this->toJson($result);
        }
        
        return $this->toJson([], $message, 0);
    }
    

    /**
     * Gets Popular products.
     *
     * @return json
     */
    public function popularProducts(Request $request, $isPaginate = 1)
    {
        $user = session()->get('authUser');
        
        $productQuery = ApiHelper::getProductsQuery($request, $user)->orderBy('sell_count', 'DESC')->orderBy('created_at', 'desc');
        
        if(!$isPaginate)
        {
            $products = $productQuery->paginate($this->perPage)->toArray();

            return ApiHelper::getProductResponse($products['data']);
        }
        
        $productsData = $productQuery->paginate($this->perPage)->toArray();
        
        $products = ApiHelper::getProductResponse($productsData['data']);

        return [
            'data' => $products,
            'has_more' => !empty($productsData['next_page_url']) ? 1 : 0
        ];
    }
    
    
    /**
     * Featured stores paginations.
     *
     * @return json
     */
    public function featuredStores(Request $request, $isPaginate = 1)
    {
        $user = session()->get('authUser');
        
        $result = ApiHelper::getFeaturedStores($request);
        
        return $this->toJson($result);
    }

    /**
     * Gets just added products.
     *
     * @return json
     */
    public function justAddedProducts(Request $request, $isPaginate = 1)
    {
        $user = session()->get('authUser');
        
        $productQuery = ApiHelper::getProductsQuery($request, $user)->orderBy('created_at', 'DESC');
        
        if(!$isPaginate)
        {
            $products = $productQuery->limit(10)->get()->toArray();
            
            return ApiHelper::getProductResponse($products);
        }
        
        $productsData = $productQuery->paginate($this->perPage)->toArray();
        
        $products = ApiHelper::getProductResponse($productsData['data']);
        
        return [
            'data' => $products,
            'has_more' => !empty($productsData['next_page_url']) ? 1 : 0
        ];
    }

    /**
     * Gets related products.
     *
     * @return json
     */
    public function getRelatedProducts(Request $request, $isPaginate = 1)
    {
        $user = session()->get('authUser');
        
        $product = Product::find($request->product_id);
        $products = [];
        if(!empty($product))
        {
            $productQuery = ApiHelper::getProductsQuery($request, $user)
                                     ->where('products.id', '!=', $product->id)
                                     ->where('products.vendor_id', $product->vendor_id)
                                     ->orderBy('sell_count', 'DESC');

            if(!$isPaginate)
            {
                $products = $productQuery->limit(10)->get()->toArray();
                
                return ApiHelper::getProductResponse($products);
            }

            $productsData = $productQuery->paginate($this->perPage)->toArray();
            
            $products = ApiHelper::getProductResponse($productsData['data']);
            
            return [
                'data' => $products,
                'has_more' => !empty($productsData['next_page_url']) ? 1 : 0
            ];
        }

        return [
            'data' => $products,
            'has_more' => !empty($productsData['next_page_url']) ? 1 : 0
        ];
    }
    
    /**
     * This is used for search products.
     *
     * @return json
     */
    public function searchDetails(Request $request)
    {
        $user = session()->get('authUser');
        
        $result = [
            'count' => 0,
            'title' => $request->search,
        ];

        $result['stores'] = [
            'data' => [],
            'message' => trans('api.stores.not_available'),
        ];
        
        if(empty($request->store_id))
        {
            $stores = Store::selectRaw('stores.id,stores.store_name,stores.store_image')
                            ->join('products','products.vendor_id','stores.vendor_id')
                            ->where('store_name', 'like', '%' . $request->search . '%')
                            ->where('stores.status','Active')
                            ->groupBy('stores.id')
                            ->orderBy('stores.store_name')
                            ->get();
            
            $result['count'] += $stores->count();
        }

        // only first page we display stores
        if((empty($request->page) ||$request->page == 1) && empty($request->store_id))
        {
            $result['stores'] = [
                'data' => $stores,
                'message' => trans('api.stores.not_available'),
            ];
        }

        $productQuery = ApiHelper::getProductsQuery($request, $user)
                                 ->leftjoin('products_keywords', function ($join) {
                                     $join->on('products_keywords.product_id', '=', 'products.id');
                                 })
                                 ->leftjoin('keywords', function ($join) use ($request) {
                                     $join->on('keywords.id', '=', 'products_keywords.keyword_id');
                                 })
                                 ->when(!empty($request->store_id),function ($query) use($request) {
                                     $query->where('stores.id', $request->store_id);
                                 })
                                 ->where(function ($query) use ($request) {
                                     $query->where('products.product_title','like', '%' . $request->search . '%')
                                           ->orWhere('keywords.keyword', 'like','%' . $request->search . '%');
                                 })
                                 ->orderBy('products.product_title', 'asc')
                                 ->paginate($this->perPage);
        
        
        $result['count'] = isset($result['count']) ? $result['count'] + $productQuery->total()  : $productQuery->total(); 
        
        $productsData = $productQuery->toArray();

        //dd($result['count']);
        $products = ApiHelper::getProductResponse($productsData['data']);

        $result['products'] = [
            'data' => $products,
            'has_more' => !empty($productsData['next_page_url']) ? 1 : 0,
            'message' => trans('api.products.not_available'),
        ];

        return $this->toJson($result);
    }
}
