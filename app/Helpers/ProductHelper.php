<?php

/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 11/1/18
 * Time: 2:36 PM
 */

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Store;
use App\Product;
use App\ProductCart;
use App\ProductLike;
use App\ProductImage;
use App\ProductCategory;
use App\ProductAttrCombination;

class ProductHelper
{

    public static function getRelatedProducts($products_id =null, $limitStart = 0, $limit = 20)
    {
        $customer = Auth::guard('customer')->user();
      //  if(!empty($products_id) ){
            $productDetail = Product::find($products_id);
        //}
        
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name ,group_concat(store_category_name) as store_category_name,products.id, products.vendor_id,  products.featured,products.product_slug, products.status, first_name, last_name,product_title, product_cover_image'))
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                        ->where('products.id', '!=', $productDetail->id)
                        ->where('products.vendor_id', '=', $productDetail->vendor_id)
                        ->where("products.status", "=", 'Active')
                        ->groupBy('products.id')
                        ->orderBy('products.id', "DESC")->offset($limitStart)->limit($limit)->get();
        if (count($products))
        {
            foreach ($products as $key => $product)
            {
                $products[$key]->combination = ProductAttrCombination::where("product_id", "=", $product->id)->where("is_delete", "=", 0)->get();
                $IsLiked = "No";
                if ( ! empty($customer))
                {
                    $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
                    $IsLiked = ( ! empty($productLike)) ? "Yes" : "No";
                }
                $products[$key]->is_liked = $IsLiked;
                $products[$key]->images = ProductImage::where("product_id", "=", $product->id)->get();
                $products[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
            }
        }
        return $products;
    }
    public static function getMoreProducts( $limitStart = 0, $limit = 20)
    {
        $customer = Auth::guard('customer')->user();
      
        
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name ,group_concat(store_category_name) as store_category_name,products.id, products.vendor_id,  products.featured,products.product_slug, products.status, first_name, last_name,product_title, product_cover_image'))
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                        ->where("products.status", "=", 'Active')
                        ->groupBy('products.id')
                        ->orderBy('products.id', "DESC")->offset($limitStart)->limit($limit)->get();
        if (count($products))
        {
            foreach ($products as $key => $product)
            {
                $products[$key]->combination = ProductAttrCombination::where("product_id", "=", $product->id)->where("is_delete", "=", 0)->get();
                $IsLiked = "No";
                if ( ! empty($customer))
                {
                    $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
                    $IsLiked = ( ! empty($productLike)) ? "Yes" : "No";
                }
                $products[$key]->is_liked = $IsLiked;
                $products[$key]->images = ProductImage::where("product_id", "=", $product->id)->get();
                $products[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
            }
        }
        return $products;
    }

    public static function getRecentProducts($limitStart = 0, $limit = 20)
    {
        $customer = Auth::guard('customer')->user();
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name ,group_concat(store_category_name) as store_category_name,products.id, products.vendor_id, products.featured,products.product_slug, products.status, first_name, last_name,product_title, product_cover_image'))
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                        ->where("products.status", "=", 'Active')
                        ->groupBy('products.id')
                        ->orderBy('products.id', "DESC")->offset($limitStart)->limit($limit)->get();
        if (count($products))
        {
            foreach ($products as $key => $product)
            {
                $products[$key]->combination = ProductAttrCombination::where("product_id", "=", $product->id)->where("is_delete", "=", 0)->get();
                $IsLiked = "No";
                if ( ! empty($customer))
                {
                    $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
                    $IsLiked = ( ! empty($productLike)) ? "Yes" : "No";
                }
                $products[$key]->is_liked = $IsLiked;
                $products[$key]->images = ProductImage::where("product_id", "=", $product->id)->get();
                $products[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
            }
        }
        return $products;
    }

    public static function isCanCheckout($request='')
    {
        $IsCheckout = TRUE;
        $customer = Auth::guard('customer')->user();
        if($customer){
            $CartDetail = ProductCart::select("product_cart.id", "product_cart.product_id", "product_cart.quantity", "products.vendor_id", "products.product_slug", "product_cart.product_combination_id", "product_cart.rate", "products.product_title", "product_attr_combination.combination_title", "product_attr_combination.discount_price", "product_attr_combination.quantity as combination_qty", "product_attr_combination.rate as combination_rate")
                ->join("products", 'products.id', '=', 'product_cart.product_id')
                ->join("product_attr_combination", 'product_attr_combination.id', '=', 'product_cart.product_combination_id')
                ->where("products.status", "=", 'Active')
                ->where("product_attr_combination.is_delete", "=", 0)
                ->where("user_id", "=", $customer->id)->get();
        }else{
            $cart = unserialize($request->cookie('cookie_cartItem'));
            if(!is_array($cart)){
                $cart = json_decode($cart);
            }
            //dd($cart);die;
            $CartDetail = [];
            foreach($cart as $value){
                $CartDetail[] = Product::select( "products.id as product_id", "products.vendor_id", "products.product_slug", "product_attr_combination.id as product_combination_id", "products.product_title", "product_attr_combination.combination_title", "product_attr_combination.discount_price", "product_attr_combination.quantity as combination_qty", "product_attr_combination.rate")
                    ->join("product_attr_combination", 'product_attr_combination.product_id', '=', 'products.id')
                    ->where("products.id", "=", $value->product_id)
                    ->where("products.status", "=", 'Active')
                    ->where("product_attr_combination.is_delete", "=", 0)
                    ->where("product_attr_combination.id", "=", $value->product_combination)
                    ->first();
            }
        }
        if(is_array($CartDetail)){
            $CartDetail = array_filter($CartDetail, 'strlen'); //if null value then unset array
            $CartDetail = array_values($CartDetail);
        }
        if (count($CartDetail))
        {
            foreach ($CartDetail as $key => $product)
            {
                if ($product->quantity > $product->combination_qty)
                {
                    $IsCheckout = FALSE;
                    break;
                }
            }
        }
        return $IsCheckout;
    }

}
