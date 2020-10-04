<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiHelper;
use App\ProductLike;
use App\Product;


class LikeController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Like Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles like, unlike, gets unlikes.
     */

    /**
     * Update like or unlike product for user.
     *
     * @return json
     */
    public function likeOrUnlikeProduct(Request $request)
    {
        $user= Auth::guard('customer')->user();
        
        $this->validate($request, [
            'product_id' => 'required|numeric',
        ]);

        $product = Product::where([
                      'id' => $request->product_id,
                      'status' => 'Active',
                   ])->first();

        if(!empty($product))
        {
             $productLike = ProductLike::where([
                 'product_id' => $request->product_id,
                 'user_id' => $user->id,
             ])->first();

             // Check product is like or not
             if(empty($productLike))
             {
                 $productLike = new ProductLike();
                 $productLike->user_id = $user->id;
                 $productLike->product_id = $request->product_id;

                 // save as like product or not
                 if($productLike->save())
                 {
                     return $this->toJson([
                         'is_liked' => 1
                     ]);
                 }
                 
                 return $this->toJson([], trans('api.like_product.error'), 0);
             }

             // Unlike product
             if($productLike->delete())
             {
                 return $this->toJson([
                     'is_liked' => 0
                 ]);
             }

             return $this->toJson([], trans('api.like_product.error'), 0);
        }

        return $this->toJson([], trans('api.product.not_available'), 0);
    }

    /**
     * show my likes products page.
     *
     * @param Request $request
     * 
     * @return view
     */
    public function myLikes(Request $request)
    {
        $products = $this->myLikesProducts($request);
        
        return view('front.user.my_likes', [
            'products' => $products
        ]);
    }

    /**
     * Gets my likes ajax products.
     *
     * @param Request $request
     *
     * @return view
     */
    public function showMyLikesProducts(Request $request)
    {
        $data['products'] = $this->myLikesProducts($request);

        return view('front.common.products', $data);
    }
    
    /**
     * show my shopzz page.
     *
     * @param Request $request
     *
     * @return view
     */
    public function myShopzz(Request $request)
    {
        $user = Auth::guard('customer')->user();
        
        $stores = ApiHelper::getStoreDetailsQuery($user)
                           ->where('stores.status','Active')
                           ->where('store_follower.id', '!=', null)
                           ->orderBy('store_follower.id','desc')
                           ->get();

        return view('front.user.my_shopzz', [
            'stores' => $stores
        ]);
    }

    /**
     * Gets my likes products.
     *
     * @param Request $request
     *
     * @return Collection
     */
    private function myLikesProducts(Request $request)
    {
        $user = Auth::guard('customer')->user();
        
        $productQuery = ApiHelper::getProductsQuery($request, $user);
        
        return $productQuery->where('product_likes.id', '!=', null)
                            ->orderBy('product_likes.id', 'desc')
                            ->paginate($this->perPage);
    }
}