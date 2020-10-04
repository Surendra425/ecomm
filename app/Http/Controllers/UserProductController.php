<?php

namespace App\Http\Controllers;

use App\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Helpers\PlanHelper;
use App\User;
use App\Store;
use App\StoreFollower;
use App\Collections;
use App\CollectionProducts;
use App\ProductImage;
use App\VendorProductCategory;
use App\Order;
use App\OrderProduct;
use App\OrderAddress;
use App\Product;
use App\ProductLike;
use App\ProductCategory;
use App\ProductAttrCombination;
use Illuminate\Support\Facades\Redirect;

class UserProductController extends Controller
{

    public function like(Product $product)
    {
        if (empty($product) || $product->status == "Inactive")
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();
        $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
        if (empty($productLike))
        {
            $productLike = new ProductLike();
            $productLike->user_id = $customer->id;
            $productLike->product_id = $product->id;
        }
        if ($productLike->save())
        {
            return Redirect::back()->with('success', trans('messages.product_like.success'));
        }
        return Redirect::back()->with('error', trans('messages.error'));
    }

    public function likeProduct(Request $request)
    {
        $Status = 0;
        $Msg = "Some error occure";
        $customer = Auth::guard('customer')->user();
        if ( ! empty($customer))
        {
            $product = Product::find($request->product_id);
            $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
            if (empty($productLike))
            {
                $productLike = new ProductLike();
                $productLike->user_id = $customer->id;
                $productLike->product_id = $product->id;
            }
            if ($productLike->save())
            {
                $Status = 1;
                $Msg = "Product like successfully";
            }
        }
        else
        {
            $Status = -1;
            $Msg = "User is not logged in";
        }
        $data = array ("Status" => $Status, "Msg" => $Msg);
        return json_encode($data);
    }

    public function unlikeProduct(Request $request)
    {
        $Status = 0;
        $Msg = "Some error occure";
        $customer = Auth::guard('customer')->user();
        if ( ! empty($customer))
        {
            $product = Product::find($request->product_id);
            $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
            if ($productLike->delete())
            {
                $Status = 1;
                $Msg = "Product dislike successfully";
            }
        }
        else
        {
            $Status = -1;
            $Msg = "User is not logged in";
        }
        $data = array ("Status" => $Status, "Msg" => $Msg);
        return json_encode($data);
    }

    public function unlike(Product $product)
    {
        $customer = Auth::guard('customer')->user();
        $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
        if ($productLike->delete())
        {
            return Redirect::back()->with('success', trans('messages.product_like.success'));
        }
        return Redirect::back()->with('error', trans('messages.error'));
    }

    public function productDetail(Product $product)
    {
        if (empty($product) || $product->status == "Inactive")
        {
            return abort(404);
        }
        $data = [];
        $customer = Auth::guard('customer')->user();
        if ( ! empty($product))
        {
            $product->combination = ProductAttrCombination::where("product_id", "=", $product->id)->where("is_delete", "=", 0)->get();
            $IsLiked = "No";
            if ( ! empty($customer))
            {
                $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
                $IsLiked = ( ! empty($productLike)) ? "Yes" : "No";
            }
            $product->is_liked = $IsLiked;
        }
        //dd($product);
        return view('app.product_detail', $data);
    }

    public function myLikes()
    {
        $data = [];
        $customer = Auth::guard('customer')->user();
        $productIds = ProductLike::where("user_id", "=", $customer->id)->pluck('product_id')->toArray();
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name ,group_concat(store_category_name) as store_category_name,products.id,products.vendor_id, products.featured,products.product_slug, products.status, first_name, last_name,product_title, product_cover_image'))
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                        ->whereIn('products.id', $productIds)
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
        $data['customer'] = $customer;
        $data['products'] = $products;
        return view('app.user.my_likes', $data);
    }

    public function myOrders()
    {
        $data = [];
        $customer = Auth::guard('customer')->user();
        $orders = Order::where("customer_id", "=", $customer->id)->whereNotIn('payment_status', ['Pending', 'Failed'])->orderBy('id','DESC')->get();
        $data['customer'] = $customer;
        $data['orders'] = $orders;
        return view('app.user.my_orders', $data);
    }

    public function myOrderDetail(Request $request,$orderNo)
    {
        
        $order = Order::where("order_no", "=", $orderNo)->first();
        if (empty($order))
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();
        $order_products = OrderProduct::select("order_products.*", "products.vendor_id", "products.product_slug", "products.product_title", "users.first_name", "users.last_name", "rating", "review_text", "product_attr_combination.combination_title","order_products.product_combination_id")
                ->leftjoin("products", "products.id", "order_products.product_id")
                ->leftjoin("product_attr_combination", "product_attr_combination.id", "order_products.product_combination_id")
                ->leftjoin("users", "users.id", "order_products.product_vendor_id")
                ->leftjoin("product_review", "product_review.product_id", "order_products.product_id")
                ->where("order_id", $order->id)
                ->get();
        if (count($order_products) > 0)
        {
            foreach ($order_products as $key => $product)
            {
                $order_products[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
                $order_products[$key]->images = ProductImage::where("product_id", "=", $product->product_id)->get();
            }
        }
        /*$order->sub_total = array_sum(array_column($order_products->toarray(), 'sub_total'));
        $order->shipping_total = array_sum(array_column($order_products->toarray(), 'shipping_total'));
        $order->grand_total = array_sum(array_column($order_products->toarray(), 'grand_total'));*/
        $shipping_address = OrderAddress::select("order_addresses.*", "city", "state", "country")
                        ->where("order_id", "=", $order->id)->where("address_type", "Shipping")->first();
        $data = [];
        //dd($shipping_address);die;
        $data['customer'] = $customer;
        $data['order'] = $order;
        $data['order_products'] = $order_products;
        $data['shipping_address'] = $shipping_address;
        return view('app.user.order_detail', $data);
    }

    public function bestProducts()
    {
        $customer = Auth::guard('customer')->user();
        $products = Product::selectRaw('group_concat(shopzz_category_name) as shopzz_category_name ,group_concat(store_category_name) as store_category_name,products.id, products.vendor_id, products.featured,products.product_slug, products.status, first_name, last_name,product_title, product_cover_image,AVG(IFNULL(product_review.rating,0)) as product_rating')
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                        ->leftjoin('product_review', 'product_review.product_id', 'products.id')
            ->where("products.status", "=", 'Active')
                        ->groupBy('products.id')
                        ->orderBy('product_rating', "DESC")
                        ->orderBy('products.id', "DESC")
                        ->limit(20)->get();
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
        $data = [];
        $data["customer"] = $customer;
        $data["products"] = $products;
        return view('app.user.best_products', $data);
    }

    public function rateProduct(Request $request)
    {

        $productRating = ProductReview::where("product_id", "=", $request->product_id)->where("user_id", "=", $request->user_id)->first();
        if (empty($productRating))
        {
            $productRating = new ProductReview();
            $productRating->fill($request->all());
        }
        $productRating->rating = $request->rating;
        $productRating->review_text = $request->review;
        if ($productRating->save())
        {
            return Redirect::back()->with('success', trans('messages.product_like.rating'));
        }
        return Redirect::back()->with('error', trans('messages.error'));
    }

    public function myShopzz()
    {
        $data = [];
        $customer = Auth::guard('customer')->user();
        $stores = Store::join("store_follower", "store_follower.store_id", "stores.id")
                        ->selectRaw("stores.*")
                        ->where("store_follower.user_id", "=", $customer->id)->get();
        $orders = Order::where("customer_id", "=", $customer->id)->get();
        $data['customer'] = $customer;
        $data['stores'] = $stores;
        return view('app.user.my_shopzz', $data);
    }

}
