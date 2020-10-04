<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\VendorShippingDetail;
use App\ProductReview;
use App\Product;

class ProductController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Product Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles home screen apis.
     */ 
    
    /** 
     * Gets product details api.
     *
     * @return json
     */
    public function productDetails(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required_without:product_slug',
            'product_slug' => 'required_without:product_id',
        ]);

       $where = [
           'products.id' => $request->product_id
       ];
       
       if(!empty($request->product_slug))
       {
           $where = [
               'products.product_slug' => $request->product_slug
           ];
       }
       $user = session()->get('authUser');
        
       $products = ApiHelper::getProductsQuery($request, $user,'products.long_description AS description,')
                            ->where($where)
                            ->get();
       
       if(!$products->isEmpty())
       {
           $searchController = new SearchController($request);
          
           $products = ApiHelper::getProductResponse($products->toArray());
           
          
           $product = $products[0];

           $productImages = $product['product_images']->toArray();
           
           if(!empty($productImages))
           {
               
               foreach($productImages as $key => $image)
               {
                   unset($productImages[$key]['product_id']);
               }
           }
           
           $product['files'] = $productImages;
           $product['type'] = 'product';
           unset($product['product_images']);
           $store = ApiHelper::getStoreDetails($product['vendor_id'], $user);
           $store->rating = (float) $store->rating;

           $colName = ($request->isAr) ? 'ifnull(country.country_name_ar,country.country_name) as country_name' : 'country.country_name';

           $shipping = VendorShippingDetail::select('from AS delivery_from','to AS delivery_to','time')
                                           ->selectRaw($colName)
                                           ->where("vendor_id", $product['vendor_id'])
                                          ->leftJoin('country', 'country.id', 'vendor_shipping_detail.country_id')
                                           ->where('city_id',NULL)
                                           ->leftJoin('product_shipping as ps', 'ps.country_id', 'vendor_shipping_detail.country_id')
                                           ->where('ps.product_id', $product['id'])
                                           ->get();

           $shipping = $shipping->map(function($item) use($request){
               $item['time'] = ($request->isAr) ? trans('api.static_content.'.$item['time']) : $item['time'] ;
               return $item;
           });

           $request->product_id = $product['id'];
           $result = [
               'product' => $product,
               'shipping_details' => $shipping,
               'store' => $store,
               'related_products' => [
                   'title' => trans('api.static_content.related_products'),
                   'slug' => 'related_products',
                   'data' => $searchController->getRelatedProducts($request, 0)
               ],
           ];

           return $this->toJson($result);
       }
       
       return $this->toJson(null, trans('api.product.not_available'), 0);
    }
    
    /**
     * Add rating of the product.
     * 
     * @param Request $request
     * @return json
     */
    public function productRating(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|numeric',
            'product_combination_id' => 'required|numeric',
            'rating' => 'required|numeric|min:1|max:5',
        ]);
        
        $user = session()->get('authUser');
        
        $product = Product::find($request->product_id);
        
        // Check product is available or not
        if(!empty($product))
        {
            $productReview  = ProductReview::where([
                'product_id' => $request->product_id,
                'product_combination_id' => $request->product_combination_id,
                'user_id' => $request->user_id,
            ])->first();
            
            // Check review is already available or not
            if(empty($productReview))
            {
                $productReview =  new ProductReview();
                $productReview->product_id = $request->product_id;
                $productReview->product_combination_id = $request->product_combination_id;
                $productReview->user_id = $request->user_id;
                $productReview->rating = $request->rating;
                $productReview->save();
                
                return $this->toJson(null, trans('api.product_rating.success'), 1);
            }
            
            return $this->toJson(null, trans('api.product_rating.already_available'), 0);
        }
        
        return $this->toJson(null, trans('api.product.not_available'), 0);
    }
}