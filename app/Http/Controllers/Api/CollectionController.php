<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\Collections;

class CollectionController extends Controller
{
    
    protected $perPage = 20;

    /*
      |--------------------------------------------------------------------------
      | Collection Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles collections data.
     */

    /**
     * Gets collection products.
     * 
     * @param Request $request
     * @return json
     */
    public function getCollectionProducts(Request $request)
    {
        $this->validate($request, [
            'collection_id' => 'required|numeric',
        ]);
        
        $user = session()->get('authUser');
        
        $collection = $this->getCollectionById($request->collection_id);
        
        // Check category is available or not
        if(!empty($collection))
        {
            $productQuery = ApiHelper::getProductsQuery($request, $user);
            
            $products = $productQuery->leftjoin('collection_products', 'collection_products.product_id', '=', 'products.id')
                                     ->where('collection_products.collection_id', $collection->id)
                                     ->orderBy('id', 'desc')
                                     ->paginate($this->perPage)->toArray();
            
            // Check products is available or not
            if(!empty($products['data']))
            {
                $products['data'] = ApiHelper::getProductResponse($products['data']);
                
                $result = [
                    'products' => [
                        'data' => $products['data'],
                        'has_more' => !empty($products['next_page_url']) ? 1 : 0,
                    ]
                ];

                return $this->toJson($result);
            }

            return $this->toJson([], trans('api.products.not_available'), 0);
        }

        return $this->toJson([], trans('api.collection.not_available'), 0);
    }
    
    /**
     * Gets collection by collection id.
     *
     * @param $collectionId
     * @return 
     */
    public function getCollectionById($collectionId)
    {
        return Collections::select('id', 'collection_name',  'collection_tagline', 'background_image')
        ->where([
            'id' => $collectionId,
            'status' => 'Active',
        ])
        ->first();
    }
}
