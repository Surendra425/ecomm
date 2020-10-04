<?php

namespace App\Http\Controllers\Customer;

use App\Product;
use App\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ProductCart;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\VendorShippingDetail;

/*
 |--------------------------------------------------------------------------
 | Product Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles product details view, product rating.
 */
class ProductController extends Controller
{
    /**
     * Show product details page.
     *
     * @param Request $request
     * @param $productSlug
     * 
     * @return json
     */
    public function showProductDetails(Request $request, $productSlug)
    {
        $user = Auth::guard('customer')->user();

        $product = ApiHelper::getProductsQuery($request, $user)
                            ->where('products.product_slug', $productSlug)
                            ->first();

        if(!empty($product))
        {
            $store = ApiHelper::getStoreDetails($product['vendor_id'], $user);
            if(empty($store)){
                return abort(404);
            }
            $store->rating = (float) $store->rating;
            
            $shipping = VendorShippingDetail::select('from AS delivery_from','to AS delivery_to','vendor_shipping_detail.country_name', 'time')->where("vendor_id", $product['vendor_id'])
                                            ->where('city_id',NULL)
                                            ->leftJoin('product_shipping as ps', 'ps.country_id', 'vendor_shipping_detail.country_id')
                                            ->where('ps.product_id', $product->id)
                                            ->get();

        //    dd($shipping);
            return view('front.products.product_details', [
                'product' => $product,
                'store' => $store,
                'shipping' => $shipping,
                'products' => $this->getRelatedProducts($product)
            ]);
        }

        abort(404);
    }

    /**
     * Gets collection ajax related products.
     *
     * @param Request $request
     *
     * @return view
     */
    public function showRelatedProducts(Request $request)
    {
        $user = Auth::guard('customer')->user();

        $product = ApiHelper::getProductsQuery($request, $user)
                            ->where('id', $request->product_id)
                            ->first();
        if(!empty($product))
        {
            $data['products'] = $this->getRelatedProducts($product);
            
            return view('front.common.products', $data);
        }
        
        abort(404);
    }
    
    /**
     * Gets related products.
     *
     * @param Product $product
     *
     * @return Collections
     */
    private function getRelatedProducts($product)
    {
        $user = Auth::guard('customer')->user();

        return ApiHelper::getProductsQuery(null, $user)
                        ->where('products.id', '!=', $product->id)
                        ->where('products.vendor_id', $product->vendor_id)
                        ->orderBy('sell_count', 'DESC')
                        ->paginate($this->perPage);
    }

    /**
     * Get list of best products
     * @param Request $request
     * @return view
     */
    public function bestProducts(Request $request){
        $customer = Auth::guard('customer')->user();

        $products = ApiHelper::getProductsQuery($request, $customer);

        $products = $products->orderBy('sell_count')
            ->orderBy('products.id', "DESC")
            ->paginate($this->perPage);

        $data = [];
        $data["customer"] = $customer;
        $data["products"] = $products;

        return view('front.user.best_products', $data);
    }

    /**
     * Set product rating
     * @param Request $request
     * @return mixed
     */
    public function rateProduct(Request $request)
    {

        $productRating = ProductReview::
        where(['product_id' => $request->product_id,'product_combination_id'=>$request->product_combination_id])
            ->where("user_id",$request->user_id)
            ->first();

        if (empty($productRating))
        {
            $productRating = new ProductReview();
            $productRating->fill($request->all());
        }

        $productRating->rating = $request->rating;
        $productRating->review_text = $request->review;

        if ($productRating->save())
        {
            return \Redirect::back()->with('success', trans('messages.product_like.rating'));
        }
        return \Redirect::back()->with('error', trans('messages.error'));
    }
}