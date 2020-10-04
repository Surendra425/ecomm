<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Store;
use App\StoreFollower;
use App\Collections;
use App\CollectionProducts;
use App\VendorProductCategory;
use App\Product;
use App\ProductImage;
use App\ProductCart;
use App\ProductLike;
use App\ProductCategory;
use App\ProductAttrCombination;

class CollectionController extends Controller
{

    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $collections = Collections::where("status", "=", "Active")->latest()->get();
        if ( ! empty($collections))
        {
            foreach ($collections as $key => $collection)
            {
                $products = CollectionProducts::where("collection_id", "=", $collection->id)->get();
            }
        }
        $data = [];
        $data['collections'] = $collections;
        $data["customer"] = $customer;
        return view('app.collection.collection_list', $data);
    }

    public function show(Request $request, Collections $collection)
    {
        if (empty($collection) || $collection->status == "Inactive")
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();
        $vendor_ids = CollectionProducts::where("collection_id", "=", $collection->id)->pluck('vendor_id')->toArray();
        $product_ids = CollectionProducts::where("collection_id", "=", $collection->id)->pluck('product_id')->toArray();
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name ,group_concat(store_category_name) as store_category_name,products.id,products.featured,products.product_slug, products.status, first_name, last_name, product_title, product_cover_image'))
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                        ->whereIn("products.id", $product_ids)
                        ->where("products.status", "=", 'Active')
                        ->groupBy('products.id')
                        ->orderBy('products.id', "DESC")->get();
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
            }
        }
        $stores = Store::select("stores.*", 'first_name', 'last_name')
                        ->leftjoin('users', 'users.id', '=', 'stores.vendor_id')
                        ->where("stores.status", "=", "Active")
                        ->whereIn("stores.vendor_id", $vendor_ids)
                        ->latest()->get();
        if (count($stores))
        {
            foreach ($stores as $key => $store)
            {
                $stores[$key]->follower = StoreFollower::select("store_follower.*", 'first_name', 'last_name')
                                ->join('users', 'users.id', '=', 'store_follower.user_id')
                                ->where("store_follower.store_id", "=", $store->id)->latest()->get();
            }
        }
        $data = [];
        $data['collection'] = $collection;
        $data["customer"] = $customer;
        $data["releted_products"] = $products;
        $data["stores"] = $stores;
        return view('app.collection.collection_detail', $data);
    }


    public function collectionDetail($collectionSlug)
    {
        $collection = Collections::where("collection_slug", "=", $collectionSlug)->first();
        if (empty($collection) || $collection->status == "Inactive")
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();
        $vendor_ids = CollectionProducts::where("collection_id", "=", $collection->id)->pluck('vendor_id')->toArray();
        $product_ids = CollectionProducts::where("collection_id", "=", $collection->id)->pluck('product_id')->toArray();
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name ,group_concat(store_category_name) as store_category_name,products.id,products.featured,products.vendor_id,products.product_slug, products.status, first_name, last_name, product_title, product_cover_image'))
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                        ->whereIn("products.id", $product_ids)
            ->where("products.status", "=", 'Active')
                        ->groupBy('products.id')
                        ->orderBy('products.id', "DESC")->get();
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
        $stores = Store::select("stores.*", 'first_name', 'last_name')
                        ->leftjoin('users', 'users.id', '=', 'stores.vendor_id')
                        ->join('products', 'products.vendor_id', 'stores.vendor_id')
                        ->where("stores.status", "=", "Active")
                        ->whereIn("stores.vendor_id", $vendor_ids)
                        ->groupBy('stores.vendor_id')
                        ->latest()->get();
        if (count($stores))
        {
            foreach ($stores as $key => $store)
            {
                $stores[$key]->follower = StoreFollower::select("store_follower.*", 'first_name', 'last_name')
                                ->join('users', 'users.id', '=', 'store_follower.user_id')
                                ->where("store_follower.store_id", "=", $store->id)->latest()->get();
            }
        }
        $data = [];
        $data['collection'] = $collection;
        $data["customer"] = $customer;
        $data["releted_products"] = $products;
        $data["stores"] = $stores;
        return view('app.collection.collection_detail', $data);
    }

}
