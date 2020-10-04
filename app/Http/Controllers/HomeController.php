<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionMail;
use App\ProductCart;
use App\ProductCategory;
use App\StaticPages;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Collections;
use App\User;
use App\Subscribers;
use App\Store;
use App\StoreRating;
use App\Product;
use App\StoreFollower;
use App\ProductLike;
use App\ProductImage;
use App\ProductAttrCombination;
use App\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Helpers\ProductHelper;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cartsItem = unserialize($request->cookie('cookie_cartItem'));
           if(!is_array($cartsItem)){
               $cartsItem = json_decode($cartsItem);
           }

        $customer = Auth::guard('customer')->user();
        if(!empty($customer)){
            if(!empty($cartsItem)){
                foreach($cartsItem as $item){


                    $combination = ProductAttrCombination::find($item->product_combination);
                     $quantity = $item->item_quantity;
                   // print_r($combination);
                    if ($quantity <= $combination['quantity']) {
                         $ProductCart = ProductCart::where("product_combination_id", "=", $item->product_combination)->where("user_id", "=", $customer->id)->first();
                         if (empty($ProductCart))
                         {
                             $ProductCart = new ProductCart();
                             $ProductCart->product_combination_id = $item->product_combination;
                             $ProductCart->product_id = $combination['product_id'];
                             $ProductCart->user_id = $customer->id;
                         }
                         $ProductCart->quantity = $quantity + $ProductCart->quantity;
                         $ProductCart->rate = $combination->rate;
                         $ProductCart->save();
                    }
                }
                $cookie = \Cookie::forget('cookie.cartItem');
                return redirect('/home')->withCookie($cookie);
            }

        }

        $SliderCollection = Collections::where("status", "=", "Active")->where("display_status", "=", "Yes")->latest()->get();
        $LatestCollection = Collections::where("status", "=", "Active")->latest()->limit(7)->get();
        $FeatureStore = Store::select("stores.*", "first_name", "last_name")
                        ->leftjoin('users', 'users.id', '=', 'stores.vendor_id')
                        ->rightjoin('products', 'products.vendor_id', 'stores.vendor_id')
                        ->where("stores.status", "=", "Active")->where("stores.featured", "=", "Yes")
                        ->where("products.status", "=", 'Active')
                        ->groupBy('stores.vendor_id')->latest()->get();
        //dd($FeatureStore);die;
        if (count($FeatureStore))
        {
            foreach ($FeatureStore as $k => $store)
            {
                $store->products = Product::select('products.id', 'products.product_slug', 'products.product_cover_image', 'products.featured', 'products.status', 'product_title')
                                ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                                ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                                ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                                ->where("products.vendor_id", "=", $store->vendor_id)
                                ->where("products.status", "=", 'Active')
                                ->groupBy('products.id')->get();
                $store->follower = StoreFollower::select("store_follower.*", 'first_name', 'last_name')
                                ->join('users', 'users.id', '=', 'store_follower.user_id')
                                ->where("store_follower.store_id", "=", $store->id)->latest()->get();
                if (count($store->products) > 0)
                {
                    foreach ($store->products as $key => $product)
                    {
                        $store->products[$key]->combination = ProductAttrCombination::where("product_id", "=", $product->id)->where("is_delete", "=", 0)->get();
                        $IsLiked = "No";
                        if ( ! empty($customer))
                        {
                            $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
                            $IsLiked = ( ! empty($productLike)) ? "Yes" : "No";
                        }
                        $store->products[$key]->is_liked = $IsLiked;
                        $store->products[$key]->images = ProductImage::where("product_id", "=", $product->id)->get();
                    }
                }
                $store->rating = StoreRating::where("store_id", "=", $store->id)->avg('rating');
                $FeatureStore[$k] = $store;
            }
        }
        $data = [];
        $data['products'] = ProductHelper::getRecentProducts();
        $data['advertisement'] = Advertisement::where("status", "=", "Active")
                        ->where("display_status", "=", "Yes")
                        ->where("start_at", "<=", date("Y-m-d"))
                        ->where("end_at", ">=", date("Y-m-d"))->get();
        $data["customer"] = $customer;
        $data["SliderCollection"] = $SliderCollection;
        $data["LatestCollection"] = $LatestCollection;
        $data["FeatureStore"] = $FeatureStore;
        //dd($FeatureStore);die;
        return view('home', $data);
    }

    public function aboutUs()
    {
        $customer = Auth::guard('customer')->user();
        $data = [];
        $data["customer"] = $customer;
        $data["aboutUs"] = StaticPages::where('page_name','About Us')->first();
        return view('app.about_us', $data);
    }

    public function siteMap()
    {
        $customer = Auth::guard('customer')->user();
        $data = [];
        $data["customer"] = $customer;
        $data["siteMap"] = StaticPages::where('page_name','Site Map')->first();
        return view('app.site_map', $data);
    }

    public function userAgrement()
    {
        $customer = Auth::guard('customer')->user();
        $data = [];
        $data["customer"] = $customer;
        $data["userAgrement"] = StaticPages::where('page_name','User Agreement')->first();
        return view('app.user_agrement', $data);
    }

    public function termCondtions()
    {
        $customer = Auth::guard('customer')->user();
        $data = [];
        $data["customer"] = $customer;
        $data["term"] = StaticPages::where('page_name','Terms & Conditions')->first();
        return view('app.term_conditions', $data);
    }
    public function privacy()
    {
        $customer = Auth::guard('customer')->user();
        $data = [];
        $data["customer"] = $customer;
        $data["privacy"] = StaticPages::where('page_name','Privacy Policy')->first();
        return view('app.privacy', $data);
    }
    public function subscribe(Request $request)
    {
        $Status = 0;
        $Msg = trans('messages.error');
        $email = $request->email;
        $customer = User::where("email", "=", $email)->first();
        $subscriber = Subscribers::where("email", "=", $email)->first();
        if ( ! empty($subscriber))
        {
            $Msg = trans('messages.subscribe.already_subscribed');
        }
        else
        {
            $subscriber = new Subscribers();
            $subscriber->email = $request->email;
            if ( ! empty($customer))
            {
                $subscriber->user_id = $customer->id;
            }
            if ($subscriber->save())
            {
                try
                {
                    Mail::to($email)->send(new SubscriptionMail($subscriber));
                    $Status = 1;
                    $Msg = trans('messages.subscribe.success');
                }
                catch (Exception $exc)
                {
                    $Msg = trans('messages.error');
                }
            }
        }
        $data = array ("status" => $Status, "msg" => $Msg);
        return json_encode($data);
    }

}
