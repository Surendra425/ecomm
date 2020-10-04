<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 15/2/18
 * Time: 10:29 AM
 */

namespace App\Http\Controllers;

use App\Keywords;
use App\Product;
use App\ProductAttrCombination;
use App\ProductImage;
use App\ProductKeyword;
use App\ProductLike;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{

    public function getkeywords(Request $request, $tableName)
    {
        $N = 10;
        $keyword = DB::table($tableName)
                ->select('keyword')
                ->where('keyword', 'like', '%'.$request->value . '%')
                ->get();
        $store = DB::table('stores')
                ->select('store_name as keyword')
                ->where('store_name', 'like',  '%'.$request->value . '%')
                ->get();
        $product = DB::table('products')
            ->select('product_title as keyword')
            ->where('product_title', 'like',  '%'.$request->value . '%')
            ->where("products.status", "=", 'Active')
            ->get();
        $keywords1 = $keyword->merge($store)->unique();

        $keywords = $keywords1->merge($product)->unique();

        $sliced_array = array_slice($keywords->toArray(),0,$N);
        //dd($sliced_array);die;
        return json_encode($sliced_array);
    }
    public function getkeywordVendor(Request $request, $tableName)
    {
       // dd($request->all());die;
        $N = 10;
        //$request = $request->vendorId;
        $keyword = DB::table($tableName)
                ->select('keyword')
                ->where('keyword', 'like',  '%'.$request->value . '%')
                ->get();
        $store = DB::table('stores')
                ->select('store_name as keyword')
                ->where('store_name', 'like',  '%'.$request->value . '%')
                ->where('vendor_id', $request->vendorId)
                ->get();
        $product = DB::table('products')
            ->select('product_title as keyword')
            ->where('product_title', 'like',  '%'.$request->value . '%')
            ->where("products.status", "=", 'Active')
            ->where('vendor_id', 'like',  $request->vendorId)
            ->get();
        $keywords1 = $keyword->merge($store)->unique();

        $keywords = $keywords1->merge($product)->unique();

        $sliced_array = array_slice($keywords->toArray(),0,$N);
        //dd($sliced_array);die;
        return json_encode($sliced_array);
    }

    public function searchStoreProduct(Request $request, $store)
    {
        $vendor = Store::where('store_slug',$store)->first();
        $store = Store::where('store_slug',$store)->get();
    $keyword = $request->keyword;
        $products = ProductKeyword::select('products.id', 'products.product_title','vendor_id','products.product_slug')
            ->leftjoin('products', 'products.id', 'products_keywords.product_id')
            ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
            ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
            ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
            ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
            ->leftjoin('keywords', 'keywords.id', '=', 'products_keywords.keyword_id')
            ->where('products.vendor_id', $vendor->vendor_id)
            ->where("products.status", "=", 'Active')
            ->where(function ($products) use ($keyword) {
                $products->where('keyword', 'like', '%'.$keyword.'%')
                    ->orWhere('products.product_title', 'like', '%'.$keyword.'%');
            })
            ->groupBy('products.id')->get();
      //  dd($products);die;
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
                $products[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
                //$products[$key]->store =$vendor;
                $products[$key]->is_liked = $IsLiked;
                $products[$key]->images = ProductImage::where("product_id", "=", $product->id)->get();
            }
        }

        $recentProducts = ProductKeyword::select('products.id', 'products.product_title','vendor_id','products.product_slug')
            ->leftjoin('products', 'products.id', 'products_keywords.product_id')
            ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
            ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
            ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
            ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
            ->where('products.vendor_id', $vendor->vendor_id)
            ->where("products.status", "=", 'Active')
            ->groupBy('products.id')
            ->orderBy('products.id', 'desc')
            ->limit(4)
            ->get();
        if (count($recentProducts))
        {
            foreach ($recentProducts as $key => $product)
            {
                $recentProducts[$key]->combination = ProductAttrCombination::where("product_id", "=", $product->id)->where("is_delete", "=", 0)->get();
                $IsLiked = "No";
                if ( ! empty($customer))
                {
                    $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
                    $IsLiked = ( ! empty($productLike)) ? "Yes" : "No";
                }
                $recentProducts[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
                $recentProducts[$key]->is_liked = $IsLiked;
                $recentProducts[$key]->images = ProductImage::where("product_id", "=", $product->id)->get();
            }
        }
        //dd($products);die;
        $data["store"] = $store;
        $data["vendor"] = $vendor;
        $data['products'] = $products;
        $data['releted_products'] = $recentProducts;
        $data['keyword'] = $request->keyword;
        $data['searchCount'] =  count($products);
        return view('app.search_product', $data);
        
    }


    public function searchProduct(Request $request)
    {

        $keywordsIds = [];
        $store = Store::where('store_name', 'like', '%' . $request->keyword . '%')
            ->join('products','products.vendor_id','stores.vendor_id')
            ->groupBy('stores.vendor_id')
            ->orderBy('store_name')->get();

        $keyword = Keywords::select('id')->where('keyword', 'like', '%' . $request->keyword . '%')->get();
        foreach ($keyword as $item)
        {
            $keywordsIds[] = $item->id;
        }
        $products = Product::select('products.id', 'products.product_title','vendor_id','product_slug')
                        ->leftjoin('products_keywords', 'products_keywords.product_id', 'products.id')
                        ->leftjoin('keywords', 'keywords.id', 'products_keywords.keyword_id')
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        /* ->leftjoin('users', 'users.id', '=', 'products.vendor_id') */
                        ->where(function ($query) use ($request) {
                            $query->where('products.product_title', 'like','%' . $request->keyword . '%')
                            ->orWhere('keywords.keyword', 'like','%' . $request->keyword . '%');
                        })
                        ->where("products.status", "=", 'Active')
                        ->groupBy('products.id')->get();

        /* $productKeyword = Product::select('products.id', 'products.product_title','vendor_id','product_slug')
            ->leftjoin('products_keywords', 'products_keywords.product_id', 'products.id')
            ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
            ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
            ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
            ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
            ->whereIn('products_keywords.keyword_id', $keywordsIds)
            ->where("products.status", "=", 'Active')->groupBy('products.id')->get();
        $products = $product->merge($productKeyword)->unique(); */
       // dd($products);die;
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
                $products[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
                $products[$key]->is_liked = $IsLiked;
                $products[$key]->images = ProductImage::where("product_id", "=", $product->id)->get();
            }
        }

        $recentProducts = ProductKeyword::select('products.id', 'products.product_title','vendor_id','products.product_slug')
                ->leftjoin('products', 'products.id', 'products_keywords.product_id')
                ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
            ->where("products.status", "=", 'Active')
                ->groupBy('products.id')
                ->orderBy('products.id', 'desc')
                ->limit(4)
                ->get();
        if (count($recentProducts))
        {
            foreach ($recentProducts as $key => $product)
            {
                $recentProducts[$key]->combination = ProductAttrCombination::where("product_id", "=", $product->id)->where("is_delete", "=", 0)->get();
                $IsLiked = "No";
                if ( ! empty($customer))
                {
                    $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
                    $IsLiked = ( ! empty($productLike)) ? "Yes" : "No";
                }
                $recentProducts[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
                $recentProducts[$key]->is_liked = $IsLiked;
                $recentProducts[$key]->images = ProductImage::where("product_id", "=", $product->id)->get();
            }
        }
        //dd($products);die;
        // echo "<pre>";print_r($recentProducts);die;
        //echo count($store) + count($products);die;
        
        $products = $products->sortBy('product_title');
        
        $data["store"] = $store;
        $data['products'] = $products;
        $data['releted_products'] = $recentProducts;
        $data['keyword'] = $request->keyword;
        $data['searchCount'] = count($store) + count($products);
        return view('app.search_product', $data);
    }

}
