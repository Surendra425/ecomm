<?php

/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 14/2/18
 * Time: 10:20 AM
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use App\Product;
use App\ProductAttrCombination;
use App\ProductCategory;
use App\ProductCategoryAttribute;
use App\ProductImage;
use App\ProductLike;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function showCategory(Request $request, $categorySlug='')
    {
        if (empty($categorySlug))
        {
            return abort(404);
        }
        //echo $categorySlug;die;
        $customer = Auth::guard('customer')->user();
        $category = ProductCategory::where('category_slug', $categorySlug)->first();
        if (empty($category) || $category->status == "Inactive")
        {
            return abort(404);
        }
//echo $category->id;die;
        $childCategory = ProductCategory::where("parent_category_id", "=", $category->id)->get();
       // dd($childCategory);die;
        $childCategoryIds = array ();
        if ( ! empty($childCategory))
        {
            $childCategoryIds = array_column($childCategory->toarray(), "id");
        }
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name,products.status ,group_concat(store_category_name) as store_category_name,products.id,products.vendor_id,products.featured,products.product_slug, products.status, first_name, last_name, product_title, product_cover_image'))
                ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
            ->where('products.status', 'Active')
            ->where(function ($query) use ($category,$childCategoryIds) {
                return $query->orWhere("shopzz_category_id", "=", $category->id)
                    ->orWhereIn("shopzz_category_id", $childCategoryIds);
            })
                ->groupBy('products.id')
                ->get();
        //dd($products);die;

        /*->where('products.status', 'Active')
        ->where(function ($query) use ($category,$childCategoryIds) {
            return $query->orWhere("shopzz_category_id", "=", $category->id)
                ->orWhereIn("shopzz_category_id", $childCategoryIds);
        })*/

        $vendorIds = array ();
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
                $products[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
                if ( ! in_array($product->vendor_id, $vendorIds))
                {
                    $vendorIds[] = $product->vendor_id;
                }
            }
        }
        $data = [];
        $data['category'] = $category;
        $data['childCategory'] = $childCategory;
        $data["customer"] = $customer;
        $data['products'] = $products;
        $data['stores'] = Store::whereIn('vendor_id', $vendorIds)->where('status', 'Active')->get();
        return view('app.category.category', $data);
    }

}
