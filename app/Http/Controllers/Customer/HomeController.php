<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\Collections;
use App\Helpers\QueryHelper;
use Illuminate\Support\Facades\Auth;

/*
 |--------------------------------------------------------------------------
 | Home Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles home page.
 */

class HomeController extends Controller
{
    /**
     * Show home page.
     *
     * Request $request
     * @return view
     */
    public function index(Request $request)
    {
        $data['collections'] = QueryHelper::getCollections();
        $data['sliders'] = Collections::where('status', 'Active')
        ->where('display_status', 'Yes')->orderBy('id','desc')->get();
        $data['featuredStores'] = QueryHelper::getFeaturedStores($request);
        //dd($data['featuredStores']);

        $data['products'] = $this->getHomeProducts($request);
        //dd($data['products']);
        return view('front.home', $data);
    }

    /**
     * Show home products view.
     *
     * @param Request $request
     * @return view
     */
    public function showHomeProducts(Request $request)
    {
        $data['products'] = $this->getHomeProducts($request);
        
        return view('front.common.products', $data);
    }
    
    
    /**
     * Gets home products.
     *
     * @param Request $request
     * @return collection
     */
    private function getHomeProducts(Request $request)
    {
        $user = Auth::guard('customer')->user();
        
        return ApiHelper::getProductsQuery($request, $user)
                 ->orderBy('created_at', 'desc')
                 ->paginate($this->perPage);
        
    }
}
