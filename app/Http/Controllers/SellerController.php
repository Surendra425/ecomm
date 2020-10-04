<?php

namespace App\Http\Controllers;

use App\City;
use App\Helpers\PlanHelper;
use App\ProductVideo;
use App\ProductVisitor;
use App\StoreVisitor;
use App\UserAddress;
use App\VendorShippingDetail;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Helpers\ProductHelper;
use App\Store;
use App\StoreFollower;
use App\StoreRating;
use App\Collections;
use App\CollectionProducts;
use App\VendorProductCategory;
use App\Product;
use App\ProductLike;
use App\ProductImage;
use App\ProductShipping;
use App\ProductCategory;
use App\ProductAttrCombination;
use App\ProductReview;

class SellerController extends Controller
{

    public function index()
    {
        $Plan = new PlanHelper();
        $customer = Auth::guard('customer')->user();
        $data = [];
        $data["customer"] = $customer;
        $data['Plans'] = $Plan->GetPlanListWithDetail();
        return view('app.seller.sell_with_us', $data);
    }

    public function dashboard(Request $request, Store $store)
    {
        if (empty($store) || $store->status == "Inactive")
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();
        $data = [];
        $category = [];
        $shipping = VendorShippingDetail::where("vendor_id", "=", $store->vendor_id)->first();
        $store_product_category = VendorProductCategory::where("vendor_id", $store->vendor_id)->get();
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name,vendor_shipping_detail.charge, ,products.vendor_id,group_concat(store_category_name) as store_category_name,products.id, products.featured,products.product_slug, products.status,vendor_shipping_detail.from,vendor_shipping_detail.time,vendor_shipping_detail.to, first_name, last_name,product_title, product_cover_image'))
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.country_id', '=', 'product_shipping.country_id')
                        ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                        ->where('products.vendor_id', $store->vendor_id)
            ->where("products.status", "=", 'Active')
            ->orderBy('products.id', "DESC")->groupBy('products.id')
            ->get();
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
        $store->user_rating = 0;
        if ( ! empty($customer))
        {
            //insert store visitor count
            $storeVisitor = new StoreVisitor();
            $storeVisitor->store_id = $store->id;
            $storeVisitor->user_id = $customer->id;
            $storeVisitor->save();

            $storeRating = StoreRating::where("store_id", "=", $store->id)->where("user_id", "=", $customer->id)->first();
            if ( ! empty($storeRating))
            {
                $store->user_rating = $storeRating->rating;
            }
            $storeFollower = StoreFollower::where("user_id", "=", $customer->id)->where("store_id", "=", $store->id)->first();
            $store->is_follow = ( ! empty($storeFollower)) ? "Yes" : "No";
        }

        $store->total_user_rate = StoreRating::where("store_id", "=", $store->id)->get()->count();
        $store->rating = StoreRating::where("store_id", "=", $store->id)->avg('rating');
        $store->follower = StoreFollower::select("store_follower.*", 'first_name', 'last_name')
                        ->join('users', 'users.id', '=', 'store_follower.user_id')
                        ->where("store_follower.store_id", "=", $store->id)->latest()->get();
        $data["customer"] = $customer;
        $data["shipping"] = $shipping;
        $data["store"] = $store;
        $data['category'] = $store_product_category;
        $data['products'] = $products;
        return view('app.seller.dashoboard', $data);
    }

    public function bestSeller()
    {
        $customer = Auth::guard('customer')->user();
        $sellers = Store::selectRaw("stores.*,AVG(IFNULL(store_rating.rating,0)) as store_rating,CONCAT(users.first_name,' ',users.last_name) AS vendor_name")
                ->join("users", "users.id", "stores.vendor_id")
                ->leftJoin("store_rating", "store_rating.store_id", "stores.id")
                ->where('stores.status' , 'Active')
                ->where('users.pending_process','No')
                ->groupBy('stores.id')
                ->orderBy('store_rating', "DESC")
                ->get();
        $data = [];
        $data["customer"] = $customer;
        $data["sellers"] = $sellers;
        return view('app.seller.best_seller', $data);
    }

    public function store(Request $request)
    {
        $selected_plan_options = $request->selected_plan_options;
        if ($selected_plan_options)
        {
            return redirect(url(route('vendorRegisterWithPlanOption', ['plan_option' => $selected_plan_options])));
        }
        return redirect(url(route('sell-with-us.index')))->with('error', trans('messages.error'));
    }

    public function follow(Request $request, Store $store)
    {
        $customer = Auth::guard('customer')->user();
        if (empty($customer))
        {
            return redirect(route('login'));
        }
        $storeFollower = StoreFollower::where("user_id", "=", $customer->id)->where("store_id", "=", $store->id)->first();
        if (empty($storeFollower))
        {
            $storeFollower = new StoreFollower();
            $storeFollower->user_id = $customer->id;
            $storeFollower->store_id = $store->id;
        }
        if ($storeFollower->save())
        {
            return Redirect::back()->with('success', trans('messages.store_follow.success'));
        }
        return Redirect::back()->with('error', trans('messages.error'));
    }

    public function unfollow(Request $request, Store $store)
    {
        $customer = Auth::guard('customer')->user();
        if (empty($customer))
        {
            return redirect(route('login'));
        }
        $storeFollower = StoreFollower::where("user_id", "=", $customer->id)->where("store_id", "=", $store->id)->first();
        if ($storeFollower->delete())
        {
            return Redirect::back()->with('success', trans('messages.store_follow.unfollow'));
        }
        return Redirect::back()->with('error', trans('messages.error'));
    }

    public function productDetail(Product $product)
    {
        if (empty($product) || $product->status == "Inactive")
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();

        $product = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name ,products.vendor_id,group_concat(store_category_name) as store_category_name,products.id, products.featured,products.product_slug, products.status, first_name, last_name,product_title, product_cover_image'))
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                        ->where('products.id', $product->id)
                        ->where("products.status", "=", 'Active')
                        ->groupBy('products.id')->first();

        $product->combination = ProductAttrCombination::where("product_id", "=", $product->id)->where("is_delete", "=", 0)->get();
        $product->images = ProductImage::where("product_id", "=", $product->id)->get();
        $product->video = ProductVideo::where("product_id", "=", $product->id)->get();
        $IsLiked = "No";
        if ( ! empty($customer))
        {
            //insert product visitor count
            $productVisitor = new ProductVisitor();
            $productVisitor->product_id = $product->id;
            $productVisitor->user_id = $customer->id;
            $productVisitor->save();

            $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
            $IsLiked = ( ! empty($productLike)) ? "Yes" : "No";
        }
        $product->review = ProductReview::where('product_id',$product->id)->avg('rating');
        $product->is_liked = $IsLiked;
        $store = Store::where("vendor_id", "=", $product->vendor_id)->first();
        $shipping = VendorShippingDetail::where("vendor_id", "=", $product->vendor_id)->first();
        $store->is_follow = "No";
        if ( ! empty($customer))
        {
            $storeFollower = StoreFollower::where("user_id", "=", $customer->id)->where("store_id", "=", $store->id)->first();
            $store->is_follow = ( ! empty($storeFollower)) ? "Yes" : "No";
        }
        $product->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
        $releted_products = ProductHelper::getRelatedProducts($product->id);

        $data = [];
        $data['store'] = $store;
        $data["customer"] = $customer;
        $data["shipping"] = $shipping;
        $data["product"] = $product;
        $data["releted_products"] = $releted_products;
        $data["shipping"] = ProductShipping::where("product_id", "=", $product->id)->get();
        //dd($data);die;
        return view('app.product_detail', $data);
    }
    public function videoDetails($productSlug){
        $product = Product::where("product_slug", "=", $productSlug)->first();
        $product->video = ProductVideo::where("product_id", "=", $product->id)->get();
        $data["product"] = $product;
        return view('app.video_test', $data);
    }
    
    /*
     * redirect product id to product details
     */
    public function sellerProductsDetails($productId)
    {
        $product = Product::where("id", "=", $productId)->first();
        
        if(!empty($product))
        {
            return redirect(route('sellerProductsDetail', ['productSlug' => $product->product_slug]));
        }

        return abort(404);
    }
    public function sellerProductsDetail($productSlug)
    {
        
        //dd($productSlug);die;
        $product = Product::where("product_slug", "=", $productSlug)->first();
        
        if (empty($product) || $product->status == "Inactive")
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();

        $product = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name ,products.vendor_id,products.long_description,group_concat(store_category_name) as store_category_name,products.id, products.featured,products.product_slug, products.status, first_name, last_name,product_title, product_cover_image'))
                        ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                        ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                        ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                        ->where('products.id', $product->id)
                        ->where("products.status", "=", 'Active')
                        ->groupBy('products.id')->first();

        $product->combination = ProductAttrCombination::where("product_id", "=", $product->id)->where("is_delete", "=", 0)->get();
         $images = ProductImage::where("product_id", "=", $product->id)->get();
        $IsLiked = "No";
        if ( ! empty($customer))
        {
            //insert product visitor count
            $productVisitor = new ProductVisitor();
            $productVisitor->product_id = $product->id;
            $productVisitor->user_id = $customer->id;
            $productVisitor->save();

            $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->id)->first();
            $IsLiked = ( ! empty($productLike)) ? "Yes" : "No";
        }
        $product->is_liked = $IsLiked;
        $store = Store::where("vendor_id", "=", $product->vendor_id)->first();
        $shipping = VendorShippingDetail::where("vendor_id", "=", $product->vendor_id)->where('city_id',NULL)->get();
        $store->is_follow = "No";
        if ( ! empty($customer))
        {
            $storeFollower = StoreFollower::where("user_id", "=", $customer->id)->where("store_id", "=", $store->id)->first();
            $store->is_follow = ( ! empty($storeFollower)) ? "Yes" : "No";
        }
        $product->review = ProductReview::where('product_id',$product->id)->avg('rating');
        $product->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
        $releted_products = ProductHelper::getRelatedProducts($product->id);
        $video = ProductVideo::where("product_id", "=", $product->id)->get();
        $product->image = $images->merge($video);
        $product->video = ProductVideo::where("product_id", "=", $product->id)->get();
        $product->images = $images;
        $data = [];
        //dd($product);die;
        //dd($product->video);die;
        //dd($product->image);die;
        $data['store'] = $store;
        $data['shipping'] = $shipping;
        $data["customer"] = $customer;
        $data["product"] = $product;
        $data["releted_products"] = $releted_products;
       // $data["shipping"] = ProductShipping::where("product_id", "=", $product->id)->get();
        return view('app.product_detail', $data);
    }

    public function getRelatedProducts(Request $request, Product $product)
    {
        $limitStart = $request->start;
        $limit = $request->limit;
        $releted_products = ProductHelper::getRelatedProducts($product->id, $limitStart, $limit);
        $data['products'] = $releted_products;
        $data['is_related_view'] = "Yes";
        return view('app.common.products', $data);
    }
    public function getMoreProducts(Request $request)
    {
        $limitStart = $request->start;
        $limit = $request->limit;
        $releted_products = ProductHelper::getMoreProducts( $limitStart, $limit);
        //dd($releted_products);die;
        $data['products'] = $releted_products;
        $data['is_related_view'] = "Yes";
        return view('app.common.products', $data);
    }

    public function rateSeller(Request $request, Store $store)
    {
        $storeRating = StoreRating::where("store_id", "=", $store->id)->where("user_id", "=", $request->user_id)->first();
        if (empty($storeRating))
        {
            $storeRating = new StoreRating();
            $storeRating->store_id = $store->id;
            $storeRating->user_id = $request->user_id;
        }
        $storeRating->rating = $request->rating;
        $storeRating->description = $request->description;
        if ($storeRating->save())
        {
            return Redirect::back()->with('success', trans('messages.store_rating.success'));
        }
        return Redirect::back()->with('error', trans('messages.error'));
    }

    public function showCategory(Request $request, VendorProductCategory $category)
    {
        $customer = Auth::guard('customer')->user();
        if (empty($category) || $category->status == "Inactive")
        {
            return abort(404);
        }
        $shipping = VendorShippingDetail::where("vendor_id", "=", $category->vendor_id)->first();
        $data['category'] = $category;
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name ,group_concat(store_category_name) as store_category_name,products.id,products.featured,products.product_slug, products.status, first_name, last_name, product_title, product_cover_image'))
                ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                ->where("store_product_categories.store_category_id", "=", $category->id)
            ->where("products.status", "=", 'Active')
                ->groupBy('products.id')
                ->orderBy('products.id', "DESC")
                ->get();
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
        $data['products'] = $products;
        $data['shipping'] = $shipping;
        return view('app.seller.categories_detail', $data);
    }

    public function showSellerCategory(Request $request, $sellerCategorySlug)
    {
        //
        $categorySearch = VendorProductCategory::where("vendor_category_slug", "=", $sellerCategorySlug)->first();
        
        $store = Store::where("vendor_id", "=", $categorySearch->vendor_id)->first();
         // dd($store);die;
        if (empty($categorySearch) || $categorySearch->status == "Inactive")
        {
            return abort(404);
        }
        if (empty($store) || $store->status == "Inactive")
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();

        if(!empty($customer)){
            $address = UserAddress::where('user_id',$customer->id)->where('is_selected','Yes')->first();
            if(!empty($address)){
                $shippings = VendorShippingDetail::where("vendor_id", "=", $store->vendor_id)->where('country_name',$address->country)->get();

                $countryShipping = array_filter($shippings->toArray());
                if(!empty($countryShipping)){
                    $city = City::where('country_id', $shippings[0]['country_id'])->count();
                    foreach($shippings as $item){
                        if ($item->city_name == $address->city) {
                            $shipping = $item;
                            break;
                        } elseif ($city == count($countryShipping) &&  $item->city_name == $address->city) {
                            $shipping = $item;
                            break;
                        } elseif (count($countryShipping) == 0 && $item->city_name == '') {
                            $shipping = $item;
                            break;
                        }/*else{
                            $shipping = $item;
                        }*/
                    }
                }else{
                    $shipping = $shippings->toArray();
                }
            }else{
                $shipping = [];
            }
            //$shipping = VendorShippingDetail::where("vendor_id", "=", $store->vendor_id)->where('country_name',$address->country)->first();


        }else{
            $address = '';
            $shipping = VendorShippingDetail::where("vendor_id", "=", $store->vendor_id)->first();
        }

        $data = [];
        $category = [];
        $store_product_category = VendorProductCategory::where("vendor_id", $store->vendor_id)->get();
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name 
        ,products.vendor_id,group_concat(store_category_name) as store_category_name,
        products.id, products.featured,products.product_slug,
         products.status,vendor_shipping_detail.charge,vendor_shipping_detail.from,vendor_shipping_detail.time,vendor_shipping_detail.to, 
         first_name, last_name,product_title, product_cover_image'))
            ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
            ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
            ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
            ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.vendor_id', '=', 'products.vendor_id')
            ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
            ->where('products.vendor_id', $store->vendor_id)
            ->where("products.status", "=", 'Active')
            ->where("store_product_categories.store_category_id", "=", $categorySearch->id)
            ->orderBy('products.id', "DESC")
            ->groupBy('products.id')->get();
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
        $store->user_rating = 0;
        if ( ! empty($customer))
        {
            //insert store visitor count
            $storeVisitor = new StoreVisitor();
            $storeVisitor->store_id = $store->id;
            $storeVisitor->user_id = $customer->id;
            $storeVisitor->save();

            $storeRating = StoreRating::where("store_id", "=", $store->id)->where("user_id", "=", $customer->id)->first();
            if ( ! empty($storeRating))
            {
                $store->user_rating = $storeRating->rating;
            }
            $storeFollower = StoreFollower::where("user_id", "=", $customer->id)->where("store_id", "=", $store->id)->first();
            $store->is_follow = ( ! empty($storeFollower)) ? "Yes" : "No";
        }
        //dd($address);die;
        $store->total_user_rate = StoreRating::where("store_id", "=", $store->id)->get()->count();
        $store->rating = StoreRating::where("store_id", "=", $store->id)->avg('rating');
        $store->follower = StoreFollower::select("store_follower.*", 'first_name', 'last_name')
            ->join('users', 'users.id', '=', 'store_follower.user_id')
            ->where("store_follower.store_id", "=", $store->id)->latest()->get();
        $data["customer"] = $customer;
        $data["shipping"] = $shipping;
        $data["address"] = $address;
        $data["store"] = $store;
        $data['category'] = $store_product_category;
        $data['products'] = $products;


        /*$category = VendorProductCategory::where("vendor_category_slug", "=", $sellerCategorySlug)->first();
        if (empty($category) || $category->status == "Inactive")
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();
        $data['category'] = $category;
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name ,group_concat(store_category_name) as store_category_name,products.id,products.featured,products.vendor_id,products.product_slug, products.status, first_name, last_name, product_title, product_cover_image'))
                ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
                ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
                ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                ->where("store_product_categories.store_category_id", "=", $category->id)
                ->groupBy('products.id')
                ->get();
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
        }*/
        //$data['products'] = $products;
        return view('app.seller.categories_detail', $data);
    }

    public function sellerDetail($storeSlug)
    {
        $store = Store::where("store_slug", "=", $storeSlug)->first();
        if (empty($store) || $store->status == "Inactive")
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();
        $data = [];
        $category = [];
        $shipping = [];
        if(!empty($customer)){
            $address = UserAddress::where('user_id',$customer->id)->where('is_selected','Yes')->first();
            if(!empty($address)){
                $shippings = VendorShippingDetail::where("vendor_id", "=", $store->vendor_id)->where('country_name',$address->country)->get();
                $shippingsCountry = VendorShippingDetail::where("vendor_id", "=", $store->vendor_id)->where('country_name',$address->country)->where('city_name','!=','')->get();
                $countryShipping = array_filter($shippingsCountry->toArray());
//dd($countryShipping);die;
                if(!empty($countryShipping)){
                    $city = City::where('country_id', $shippings[0]['country_id'])->count();
                    foreach($shippings as $item){
                        if ($item->city_name == $address->city) {
                            $shipping = $item;
                            break;
                        } elseif ($city == count($countryShipping) &&  $item->city_name == $address->city) {
                            $shipping = $item;
                            break;
                        } elseif (count($countryShipping) == 0 && $item->city_name == '') {
                            $shipping = $item;
                            break;
                        }
                    }
                }else{
                    $shipping = $shippings->toArray();
                }
            }else{
                $shipping = [];
            }
            //$shipping = VendorShippingDetail::where("vendor_id", "=", $store->vendor_id)->where('country_name',$address->country)->first();


        }else{
            $address = '';
            $shipping = VendorShippingDetail::where("vendor_id", "=", $store->vendor_id)->first();
        }
//dd($shipping);die;
        $store_product_category = VendorProductCategory::where("vendor_id", $store->vendor_id)->get();
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name 
        ,products.vendor_id,group_concat(store_category_name) as store_category_name,
        products.id, products.featured,products.product_slug,
         products.status,vendor_shipping_detail.charge,vendor_shipping_detail.from,vendor_shipping_detail.time,vendor_shipping_detail.to, 
         first_name, last_name,product_title, product_cover_image'))
            ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
            ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
            ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
            ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.vendor_id', '=', 'products.vendor_id')
            ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
            ->where('products.vendor_id', $store->vendor_id)
            ->where("products.status", "=", 'Active')
            ->groupBy('products.id')
            ->orderBy('products.created_at','DESC')
            ->orderBy('products.updated_at','DESC')
            ->get();
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
        $store->user_rating = 0;
        if ( ! empty($customer))
        {
            //insert store visitor count
            $storeVisitor = new StoreVisitor();
            $storeVisitor->store_id = $store->id;
            $storeVisitor->user_id = $customer->id;
            $storeVisitor->save();

            $storeRating = StoreRating::where("store_id", "=", $store->id)->where("user_id", "=", $customer->id)->first();
            if ( ! empty($storeRating))
            {
                $store->user_rating = $storeRating->rating;
                $store->user_review = $storeRating->description;
            }
            $storeFollower = StoreFollower::where("user_id", "=", $customer->id)->where("store_id", "=", $store->id)->first();
            $store->is_follow = ( ! empty($storeFollower)) ? "Yes" : "No";
        }
        $store->total_user_rate = StoreRating::where("store_id", "=", $store->id)->get()->count();
        $store->rating = StoreRating::where("store_id", "=", $store->id)->avg('rating');
        $store->follower = StoreFollower::select("store_follower.*", 'first_name', 'last_name')
                        ->join('users', 'users.id', '=', 'store_follower.user_id')
                        ->where("store_follower.store_id", "=", $store->id)->latest()->get();
        //dd($address);die;
        $data["customer"] = $customer;
        $data["address"] = $address;
        $data["store"] = $store;
        $data['category'] = $store_product_category;
        $data['products'] = $products;
        $data['shipping'] = null;
        
        return view('app.seller.dashoboard', $data);
    }

    public function userAddressChange(Request $request){
//echo $request->storeId;die;
        $store = Store::where("vendor_id", "=", $request->storeId)->first();
        //dd($store);die;
        $customer = Auth::guard('customer')->user();
        if(!empty($customer)){
            $address = UserAddress::where('user_id',$customer->id)->where('is_selected','Yes')->first();
           // dd($address);die;
            if(!empty($address)){
                $shippings = VendorShippingDetail::select('vendor_shipping_detail.*')
                ->leftjoin('country', 'country.id', 'vendor_shipping_detail.country_id')
                ->where("vendor_id", "=", $store->vendor_id)
                ->where('country_id',$address->country_id)
                ->where('country.status', 'Active')->get();
                $shippingsCountry = VendorShippingDetail::select('vendor_shipping_detail.*')
                ->leftjoin('country', 'country.id', 'vendor_shipping_detail.country_id')
                ->where('country.status', 'Active')
                ->where("vendor_id", "=", $store->vendor_id)->where('vendor_shipping_detail.country_name',$address->country)->where('city_name','!=','')->get();
                $countryShipping = array_filter($shippingsCountry->toArray());
                //dd($shippingsCountry);
                if(!empty($countryShipping)){
                    $city = City::where('country_id', $shippings[0]['country_id'])->count();
                    foreach($shippings as $item){
                        if ($item->city_name == $address->city) {
                            $data['shipping'] = $item;
                            break;
                        } elseif ($city == count($countryShipping) &&  $item->city_name == $address->city) {
                            $data['shipping'] = $item;
                            break;
                        } elseif (count($countryShipping) == 0 && $item->city_name == '') {
                            $data['shipping'] = $item;
                            break;
                        }else{
                            $data['address'] = $address;
                        }
                    }
                }else{
                    $data['address'] = $address;
                }
            }else{
                $data['error'] = [];
            }
          }

          return response()->json($data);
    }
    public function sellerAboutUs($storeSlug)
    {
        $store = Store::where("store_slug", "=", $storeSlug)->first();        
        if (empty($store) || $store->status == "Inactive")
        {
            return abort(404);
        }
        $customer = Auth::guard('customer')->user();
        $data["customer"] = $customer;
        $data["store"] = $store;
//        $data['category'] = $store_product_category;
//        $data['products'] = $products;
        return view('app.seller.about_us', $data);
    }

    
    public function ajaxStoreCategoryProducts(Request $request)
    {
        $storeId = $request->storeId;
        $vendorId = $request->vendorId;
        $storeCategoryId = $request->storeCategoryId;
        
        $store = Store::where("vendor_id",$storeId)->first();

        if(empty($store))
        {
           return abort(404);
        }
        
        $products = Product::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name
        ,products.vendor_id,group_concat(store_category_name) as store_category_name,
        products.id, products.featured,products.product_slug,
         products.status,vendor_shipping_detail.charge,vendor_shipping_detail.from,vendor_shipping_detail.time,vendor_shipping_detail.to,
         first_name, last_name,product_title, product_cover_image'))
         ->leftjoin('shopzz_product_categories', 'shopzz_product_categories.product_id', '=', 'products.id')
         ->leftjoin('store_product_categories', 'store_product_categories.product_id', '=', 'products.id')
         ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
         ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.vendor_id', '=', 'products.vendor_id')
         ->leftjoin('users', 'users.id', '=', 'products.vendor_id')
         ->where('products.vendor_id', $store->vendor_id)
         ->where("products.status", "=", 'Active')
         ->where("store_product_categories.store_category_id", "=", $storeCategoryId)
         ->orderBy('products.id', "DESC")
         ->groupBy('products.id')->get();

         if (!$products->isEmpty())
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
         
         
         return view('app.common.products' , [
             'products' => $products
         ])->render();
    }
}
