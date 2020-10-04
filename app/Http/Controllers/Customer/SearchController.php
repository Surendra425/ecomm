<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Auth;
/*
 |--------------------------------------------------------------------------
 | Search Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles search page.
 */

class SearchController extends Controller
{
    /**
     * Show search results.
     *
     * Request $request
     * @return view
     */
    public function searchDetails(Request $request, $store = null)
    {
        $searchCount = 0;

        $storeSearch = false;

        $searchKeyword = $request->keyword;

        if(!empty($store)){
            $storeSearch = true;
        }

        if(!empty($request->keyword))
        {
            $stores = Store::selectRaw('stores.id,stores.store_name,stores.store_image,stores.store_slug')
                           ->join('products','products.vendor_id','stores.vendor_id')
                           ->where('store_name', 'like', '%' . $searchKeyword . '%')
                           ->where('stores.status','Active')
                           ->groupBy('stores.id')
                           ->orderBy('stores.store_name');

            $stores = $stores->get();

            $searchCount += $stores->count();

            $searchProducts = $this->getSearchProducts($request, $store);

            $searchCount += $searchProducts->total();
            
            return view('front.search', [
                'keyword' => $request->keyword,
                'searchCount' => $searchCount,
                'storeSearch' => $storeSearch,
                'stores' => $stores,
                'products' => $searchProducts,
            ]);
        }
        
        abort(404);
    }
    
    /**
     * Gets search products.
     *
     * @param Request $request
     *
     * @return view
     */
    public function showSearchProducts(Request $request)
    {
        $data['products'] = $this->getSearchProducts($request);

        return view('front.common.products', $data);
    }

    /**
     * Gets search products.
     *
     * @param Request $request
     * @return collection
     */
    private function getSearchProducts(Request $request, $store = null)
    {
        $user= Auth::guard('customer')->user();

        $data = ApiHelper::getProductsQuery($request, $user)
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
                            $query->where('products.product_title','like', '%' . $request->keyword . '%')
                            ->orWhere('keywords.keyword', 'like','%' . $request->keyword . '%');
                        })
                        ->orderBy('products.product_title', 'asc');

                        if(!empty($store)){
                            $data = $data->where('stores.store_slug',$store);
                        }

                        $data = $data->paginate($this->perPage);

        return $data;
    }

}