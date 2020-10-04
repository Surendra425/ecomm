<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\Helpers\QueryHelper;
use App\Collections;

/*
 |--------------------------------------------------------------------------
 | Collection Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles collection page.
 */
class CollectionController extends Controller
{
    
    /**
     * Show collection list page.
     *
     * @return view
     */
    public function index()
    {
        $data['collections'] = QueryHelper::getCollections();

        return view('front.collections.collection_list', $data);
    }
    
    
    /**
     * Show collection details page.
     *
     * @param Request $request
     * @param string $collectionSlug
     * 
     * @return view
     */
    public function collectionDetails(Request $request, $collectionSlug)
    {
        $collection = Collections::where([
            'collection_slug' => $collectionSlug,
            'Status' => 'Active'
        ])->first();

        if(!empty($collection))
        {
            $request->collection_id = $collection->id;
            
            $products = $this->getCollectionProducts($request);
            
            return view('front.collections.collection_details', [
                'collection' => $collection,
                'products' => $products,
            ]);
        }

        abort(404);
    }

    /**
     * Gets collection ajax products view.
     *
     * @param string $collectionSlug
     * 
     * @return view
     */
    public function showCollectionProducts(Request $request)
    {
        $data['products'] = $this->getCollectionProducts($request);

        return view('front.common.products', $data)->render();
    }

    /**
     * Gets collection products.
     *
     * @param Request $request
     * 
     * @return collection
     */
    private function getCollectionProducts(Request $request)
    {
        $user= Auth::guard('customer')->user();
        
        $productQuery = ApiHelper::getProductsQuery($request, $user);
        
        return $productQuery->leftjoin('collection_products', 'collection_products.product_id', '=', 'products.id')
                            ->where('collection_products.collection_id', $request->collection_id)
                            ->orderBy('id', 'desc')
                            ->paginate($this->perPage);
        
    }
}