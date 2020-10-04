<?php

namespace App\Http\Controllers;


use App\City;
use App\ProductShipping;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PlanHelper;
use App\Store;
use App\Collections;
use App\CollectionProducts;
use App\VendorProductCategory;
use App\Product;
use App\ProductLike;
use App\ProductImage;
use App\ProductCart;
use App\ProductCategory;
use App\ProductAttrCombination;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Mpdf\Mpdf;


class CartController extends Controller
{

    public function __construct()
    {
        parent::__construct();
       // $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $stores='';
         $customer = Auth::guard('customer')->user();
        if(!empty($customer)){
            $CartDetail = ProductCart::select("product_cart.id", "product_cart.product_id", "product_cart.quantity", "products.vendor_id", "products.product_slug", "product_cart.product_combination_id", "product_cart.rate", "products.product_title", "product_attr_combination.combination_title", "product_attr_combination.discount_price", "product_attr_combination.quantity as combination_qty", "product_attr_combination.rate as combination_rate")
                ->join("products", 'products.id', '=', 'product_cart.product_id')
                ->join("product_attr_combination", 'product_attr_combination.id', '=', 'product_cart.product_combination_id')
                ->where("user_id", "=", $customer->id)->get();
            $vendorIds = array_unique(array_column($CartDetail->toArray(), "vendor_id"));
            $stores = Store::whereIn("vendor_id", $vendorIds)->get();


        }else{
            $cart = unserialize($request->cookie('cookie_cartItem'));
           if(!is_array($cart)){
                $cart = json_decode($cart);
            }
            //dd($cart);die;
            $CartDetail = [];
            if(!empty($cart)){
                foreach($cart as $value){
                    $CartDetail[] = Product::select( "products.id as product_id", "products.vendor_id", "products.product_slug", "product_attr_combination.id as product_combination_id", "products.product_title", "product_attr_combination.combination_title", "product_attr_combination.discount_price", "product_attr_combination.quantity as combination_qty", "product_attr_combination.rate")
                        ->join("product_attr_combination", 'product_attr_combination.product_id', '=', 'products.id')
                        ->where("products.id", "=", $value->product_id)
                        ->where("product_attr_combination.id", "=", $value->product_combination)
                        ->first();
                }
                $vendorIds = array_unique(array_column($CartDetail, "vendor_id"));
                $stores = Store::whereIn("vendor_id", $vendorIds)->get();
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
                if(isset($cart) && !empty($cart)){
                    $CartDetail[$key]->quantity = $cart[$key]->item_quantity;
                }

                $CartDetail[$key]->combination = ProductAttrCombination::where("product_id", "=", $product->product_id)->where("is_delete", "=", 0)->get();
                $IsLiked = "No";
                if ( ! empty($customer))
                {
                    $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->product_id)->first();
                    $IsLiked = ( ! empty($productLike)) ? "Yes" : "No";
                }
                $CartDetail[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
                $CartDetail[$key]->is_liked = $IsLiked;
                $CartDetail[$key]->images = ProductImage::where("product_id", "=", $product->product_id)->get();
            }
        }
        if(!empty($customer)){
            foreach ($stores as $key => $store)
            {
                //$stores[$key]->products = $CartDetail;
                $stores[$key]->products = array_filter($CartDetail->toArray(), function($arr) use ($store)
                {
                    return $arr['vendor_id'] == $store->vendor_id;
                });
            }
        }else{
            if(!empty($stores)){
                foreach ($stores as $key => $store)
                {
                    $stores[$key]->products = array_filter($CartDetail, function($arr) use ($store)
                    {
                        return $arr['vendor_id'] == $store->vendor_id;
                    });
                }
            }

        }
       // dd($CartDetail);die;

        //dd($stores);die;
        $data = [];
        $data["customer"] = $customer;
        $data["stores"] = $stores;
        return view('app.cart.index', $data);
    }

    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();
    }
    
    public function add(Request $request)
    {
            //dd($request->all());die;
        $today = strtolower(date('l'));
         $todaytime = date('h:i:s');
        $customer = Auth::guard('customer')->user();
        if (isset($request->product_combination) && !empty($request->product_combination)) {
            $combinationId = $request->product_combination;
        } else {
            $combinationId = $request->option_id;
        }
        $combination = ProductAttrCombination::find($combinationId);

        $store = Product::select('open_time', 'closing_time', 'is_fullday_open', 'day','store_status')
            ->leftjoin('stores', 'stores.vendor_id', 'products.vendor_id')
            ->leftjoin('store_working_time', 'store_working_time.store_id', 'stores.id')
            ->where('products.id', $combination->product_id)
            ->where('day', $today)
            ->first();
       // dd($store);die;
        if ($store->open_time != '' && !($todaytime >= $store->open_time )) {
            $data['error'] = $store->store_name.trans('messages.add_to_cart.store_close');
            //return Redirect('home')->with('error', trans('messages.add_to_cart.store_close'));
        }elseif ($store->closing_time != '' && !($todaytime <= $store->closing_time)){
            $data['error'] = $store->store_name.trans('messages.add_to_cart.store_close');
        } elseif($store->store_status != 'Open'){
            $data['error'] = $store->store_name.' is '.$store->store_status.trans('messages.add_to_cart.store_status');
        } else {
        if (!empty($customer)) {

            $quantity = $request->item_quantity;

            if ($quantity < 1) {
                return redirect('cart')->with('error', trans('messages.add_to_cart.invalid_quantity'));
            }
            if ($quantity <= $combination->quantity) {
                $ProductCart = ProductCart::where("product_combination_id", "=", $combinationId)->where("user_id", "=", $customer->id)->first();

                if (empty($ProductCart)) {
                    $ProductCart = new ProductCart();
                    $ProductCart->product_combination_id = $combinationId;
                    $ProductCart->product_id = $combination->product_id;
                    $ProductCart->user_id = $customer->id;
                }
                $totalQty = $quantity + $ProductCart->quantity;
                $ProductCart->quantity = $totalQty;
                $ProductCart->rate = $combination->rate;
                //dd($ProductCart);die;
                if ($ProductCart->save()) {
                    return Redirect::back()->with('success', trans('messages.add_to_cart.success'));
                }
            } else {
                return Redirect::back()->with('error', trans('messages.add_to_cart.out_of_stock'));
            }
            return Redirect::back()->with('error', trans('messages.error'));
        } else {
            /*if(isset($request->product_combination) && !empty($request->product_combination)){
                $combinationId = $request->product_combination;
            }else{
                $combinationId = $request->option_id;
            }
            $combination = ProductAttrCombination::find($combinationId);*/
            $quantity = $request->item_quantity;

            if ($quantity < 1) {
                return redirect('cart')->with('error', trans('messages.add_to_cart.invalid_quantity'));
            }
            if ($quantity <= $combination->quantity) {
                $cartsItem = unserialize($request->cookie('cookie_cartItem'));
                if (!is_array($cartsItem)) {
                    $cartsItem = json_decode($cartsItem);
                }

                $products = array([
                    'product_combination' => $combinationId,
                    'item_quantity' => $request->item_quantity,
                    'product_id' => $combination->product_id
                ]);
                if (isset($cartsItem) && !empty($cartsItem) && $cartsItem != '') {
                    //$product =$products;
                    foreach ($cartsItem as $key => $cart) {

                        if ($cart->product_combination == $combinationId) { //$request->product_combination
                            $cartsItem[$key]->item_quantity = $cartsItem[$key]->item_quantity + $request->item_quantity;
                            $product = $cartsItem;
                            break;
                        } else {
                            $product = array_merge($products, $cartsItem);
                        }
                    }
                    //$product = array_merge($data,$products);
                } else {
                    $product = array([
                        'product_combination' => $combinationId,
                        'item_quantity' => $request->item_quantity,
                        'product_id' => $combination->product_id
                    ]);
                }
                // echo "<pre>";print_r($product);die;
                $cookie = Cookie::forever('cookie.cartItem', serialize(json_encode($product)));
                return Redirect::back()->withCookie($cookie)->with('success', trans('messages.add_to_cart.success'));
            } else {
                return Redirect::back()->with('error', trans('messages.add_to_cart.out_of_stock'));
            }
            return Redirect::back()->with('error', trans('messages.error'));

        }
    }
    }
    public function addToCart(Request $request)
    {
        //dd($request->all());die;
        $cookie='';
        $today = strtolower(date('l'));
        $todaytime = date('h:i:s');
        $customer = Auth::guard('customer')->user();
        if (isset($request->product_combination) && !empty($request->product_combination)) {
            $combinationId = $request->product_combination;
        } else {
            $combinationId = $request->option_id;
        }
        $combination = ProductAttrCombination::find($combinationId);
       // echo $combinationId;die;
//print_r($combination);die;
        $store = Product::select('open_time', 'closing_time', 'is_fullday_open', 'day','stores.vendor_id','store_name','stores.store_status')
            ->leftjoin('stores', 'stores.vendor_id', 'products.vendor_id')
            ->leftjoin('store_working_time', 'store_working_time.store_id', 'stores.id')
            ->where('products.id', $combination->product_id)
            ->where('day', $today)
            ->first();

        // dd($store);die;
        if ($store->open_time != '' && !($todaytime >= $store->open_time )) {
            $data['error'] = $store->store_name.trans('messages.add_to_cart.store_close');
            //return Redirect('home')->with('error', trans('messages.add_to_cart.store_close'));
        }elseif ($store->closing_time != '' && !($todaytime <= $store->closing_time)){
            $data['error'] = $store->store_name.trans('messages.add_to_cart.store_close');
        } elseif($store->store_status != 'Open'){
            $data['error'] = $store->store_name.' is '.$store->store_status.trans('messages.add_to_cart.store_status');
        }
        else
         {
            if (!empty($customer)) {
               $address = UserAddress::where('user_id',$customer->id)->where('is_selected','Yes')->first();
               // dd($address);die;
                if(!empty($address)){
                    $shipping = ProductShipping::select('vendor_shipping_detail.country_name','city_name','vendor_shipping_detail.country_id','vendor_shipping_detail.city_id')
                        ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.country_id', 'product_shipping.country_id')
                        ->leftjoin('country', 'country.id', 'product_shipping.country_id')
                        ->where("product_id", "=", $combination->product_id)
                        ->where('product_shipping.country_id', $address->country_id)
                        ->where('country.status', 'Active')
                        ->where("vendor_shipping_detail.vendor_id", "=", $store->vendor_id)
                        ->get();
                    $shipping1 = ProductShipping::select('vendor_shipping_detail.country_name','city_name','vendor_shipping_detail.country_id','vendor_shipping_detail.city_id')
                        ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.country_id', 'product_shipping.country_id')
                        ->leftjoin('country', 'country.id', 'product_shipping.country_id')
                        ->where("product_id", "=", $combination->product_id)
                        ->where('product_shipping.country_id', $address->country_id)
                        ->where("vendor_shipping_detail.vendor_id", "=", $store->vendor_id)
                        ->where('country.status', 'Active')
                        ->where("vendor_shipping_detail.city_id", "!=", '')
                        ->get();
                    // dd($shipping1);die;
                    if(empty($shipping->toArray())){
                        $data['error'] = trans('messages.checkout.shipping');
                    }else{
                        $countryShipping = array_filter($shipping1->toArray());
                        $city = City::where('country_id', $shipping[0]->country_id)->count();
                        foreach ($shipping as $shipings) {
                           
                            if($city == count($countryShipping) && $shipings->city_name == $address->city){
                                $data['error'] = '';
                                break;
                            }
                            elseif ($shipings->city_name == $address->city) {
                                $data['error'] = '';
                                break;
                            }
                            else{
                                $data['error'] = trans('messages.checkout.shipping');
                            }
                        }
                    }
                }else{
                    $data['error'] = '';
                }
                //echo $data['error'];die;
                if($data['error'] == ''){
                    $quantity = $request->item_quantity;

                    if ($quantity < 1) {
                        $data['error'] = trans('messages.add_to_cart.invalid_quantity');
                        //return redirect('cart')->with('error', trans('messages.add_to_cart.invalid_quantity'));
                    }
                    if ($quantity <= $combination->quantity) {
                        $ProductCart = ProductCart::where("product_combination_id", "=", $combinationId)->where("user_id", "=", $customer->id)->first();

                        if (empty($ProductCart)) {
                            $ProductCart = new ProductCart();
                            $ProductCart->product_combination_id = $combinationId;
                            $ProductCart->product_id = $combination->product_id;
                            $ProductCart->user_id = $customer->id;
                        }
                        $totalQty = $quantity + $ProductCart->quantity;
                        $ProductCart->quantity = $totalQty;
                        $ProductCart->rate = $combination->rate;
                        //dd($ProductCart);die;
                        if ($ProductCart->save()) {
                            $data['success'] =trans('messages.add_to_cart.success');
                            //return Redirect::back()->with('success', trans('messages.add_to_cart.success'));
                        }
                    } else {
                        $data['error'] = trans('messages.add_to_cart.out_of_stock');
                        //return Redirect::back()->with('error', trans('messages.add_to_cart.out_of_stock'));
                    }
                }

                //$data['error'] = trans('messages.error');
               // return Redirect::back()->with('error', trans('messages.error'));
            } else {
                /*if(isset($request->product_combination) && !empty($request->product_combination)){
                    $combinationId = $request->product_combination;
                }else{
                    $combinationId = $request->option_id;
                }
                $combination = ProductAttrCombination::find($combinationId);*/
                $quantity = $request->item_quantity;

                if ($quantity < 1) {
                    $data['error'] = trans('messages.add_to_cart.invalid_quantity');
                    //return redirect('cart')->with('error', trans('messages.add_to_cart.invalid_quantity'));
                }
                if ($quantity <= $combination->quantity) {
                    $cartsItem = unserialize($request->cookie('cookie_cartItem'));
                    if (!is_array($cartsItem)) {
                        $cartsItem = json_decode($cartsItem);
                    }

                    $products = array([
                        'product_combination' => $combinationId,
                        'item_quantity' => $request->item_quantity,
                        'product_id' => $combination->product_id
                    ]);
                    if (isset($cartsItem) && !empty($cartsItem) && $cartsItem != '') {
                        //$product =$products;
                        foreach ($cartsItem as $key => $cart) {

                            if ($cart->product_combination == $combinationId) { //$request->product_combination
                                $cartsItem[$key]->item_quantity = $cartsItem[$key]->item_quantity + $request->item_quantity;
                                $product = $cartsItem;
                                break;
                            } else {
                                $product = array_merge($products, $cartsItem);
                            }
                        }
                        //$product = array_merge($data,$products);
                    } else {
                        $product = array([
                            'product_combination' => $combinationId,
                            'item_quantity' => $request->item_quantity,
                            'product_id' => $combination->product_id
                        ]);
                    }
                  //   echo "<pre>";print_r($product);die;
                    $cookie = Cookie::forever('cookie.cartItem', serialize(json_encode($product)));
                   // dd($cookie);
                    $data['success'] = trans('messages.add_to_cart.success');
                    $data['cookie'] = $cookie;
                    $data['totalCartItem'] = count($product);
                    return response()->json($data)->withCookie(cookie('cookie.cartItem', serialize(json_encode($product))));
                   // return Redirect::back()->withCookie($cookie)->with('success', trans('messages.add_to_cart.success'));
                } else {
                    $data['error'] = trans('messages.add_to_cart.out_of_stock');
                    //return Redirect::back()->with('error', trans('messages.add_to_cart.out_of_stock'));
                }
                //$data['error'] = trans('messages.error');
                //return Redirect::back()->with('error', trans('messages.error'));

            }
        }
        $totalCartItem = 0;
        $userAddress = '';
        if ( ! empty($customer))
        {
            $totalCartItem = ProductCart::where("user_id", "=", $customer->id)->get()->count();
            $userAddress = UserAddress::where('user_id',$customer->id)->get();
        }else{
           /* $cart = Cookie::get('cookie.cartItem');
            //dd($request);
            dd($cart);die;*/

            $cart = unserialize($request->cookie('cookie_cartItem'));
            if(!is_array($cart)){
                $cart = json_decode($cart);
            }
            if(!empty($cart)){
                $totalCartItem = count($cart);
            }
            // dd($cart);die;

        }
        //dd($cookie);die;
       // return response()->json(['previousCookieValue' => Cookie::get('adminActiveCalendars')])->withCookie(cookie($cookieName, $cookieVal));
        $data['totalCartItem'] = $totalCartItem;
        //echo "<pre>";print_r($data);die;
        echo json_encode($data);
    }
    public function remove(Request $request,$cart)
    {
        $customer = Auth::guard('customer')->user();
        if(!empty($customer)){
            $data = DB::table('product_cart')
                ->where('user_id',  $customer->id)
                ->where('product_combination_id',  $cart)->delete();
            if ($data)
            {
                return Redirect::back()->with('success', trans('messages.remove_cart_item.success'));
            }
        }else{
            $carts = unserialize($request->cookie('cookie_cartItem'));
            if(!is_array($carts)){
                $carts = json_decode($carts);
            }

            foreach ($carts as $key => $subArr) {
                if($subArr->product_combination == $cart){
                    unset($carts[$key]);
                }
            }
            $carts = array_values($carts);
            $cookie =  Cookie::forever('cookie.cartItem', serialize($carts));
            return redirect('cart')->withCookie($cookie)->with('success', trans('messages.remove_cart_item.success'));
        }

        return Redirect::back()->with('error', trans('messages.error'));
    }

    public function update(Request $request)
    {
        //dd($request->quantity);die;
        $customer = Auth::guard('customer')->user();
        if (count($request->quantity))
        {
            $today = strtolower(date('l'));
            $todaytime = date('h:i:s');
            if(!empty($customer))
            {
                //echo "hi";
                foreach ($request->quantity as $key => $val)
                {
                   // echo $key.'<br>';
                    $cart = ProductCart::where('product_combination_id',$key)->where('user_id',$customer->id)->first();
                   // print_r($cart);

                    $cobination = ProductAttrCombination::where("id", "=", $cart->product_combination_id)->first();
                    /*if (!empty($cart)) {

                        $ProductCart = new ProductCart();
                        $ProductCart->product_combination_id = $key;
                        $ProductCart->product_id = $cobination->product_id;
                        $ProductCart->user_id = $customer->id;
                        $totalQty = $val + $cobination->quantity;
                        $ProductCart->quantity = $totalQty;
                        $ProductCart->rate = $cobination->rate;
                        //dd($ProductCart);die;
                        $ProductCart->save();
                    }
*/

                    $cart->quantity = $val;

                    $store = Product::select('open_time', 'closing_time', 'is_fullday_open', 'day','store_name','store_status')
                        ->leftjoin('stores', 'stores.vendor_id', 'products.vendor_id')
                        ->leftjoin('store_working_time', 'store_working_time.store_id', 'stores.id')
                        ->where('products.id', $cobination->product_id)
                        ->where('day', $today)
                        ->first();

                    if ($store->open_time != '' && !($todaytime >= $store->open_time )) {
                        return Redirect('cart')->with('error', $store->store_name.trans('messages.add_to_cart.store_close'));
                        //return Redirect('home')->with('error', trans('messages.add_to_cart.store_close'));
                    }elseif ($store->closing_time != '' && !($todaytime <= $store->closing_time)){
                        return Redirect('cart')->with('error', $store->store_name.trans('messages.add_to_cart.store_close'));
                    }elseif($store->store_status != 'Open'){
                        return Redirect('cart')->with('error', $store->store_name.' is '.$store->store_status.trans('messages.add_to_cart.store_status'));
                    }
                    else {
                        if ($val < 1)
                        {
                            return redirect('cart')->with('error', trans('messages.add_to_cart.invalid_quantity'));
                        }
                        else if ($cobination->quantity < $val)
                        {
                            return redirect('cart')->with('error', trans('messages.add_to_cart.out_of_stock'));
                        }
                        if ( ! $cart->save())
                        {
                            return redirect('cart')->with('error', trans('messages.error'));
                        }
                    }
                }
                return redirect('checkout');
            }
        else{
            //dd($request->quantity);die;
            foreach ($request->quantity as $key => $val)
                {

                    //dd(json_decode(unserialize($request->cookie('cookie_cartItem'))));
                    $carts = unserialize($request->cookie('cookie_cartItem'));
                    if(!is_array($carts)){
                        $carts = json_decode($carts);
                    }
                   // $product = $carts;
                    foreach ($carts as $key => $cart) {

                            if ($cart->product_combination == $key) { //$request->product_combination
                                $carts[$key]->item_quantity =  $request->item_quantity;
                                $product = $carts;
                                break;
                            } else {
                                $product = $carts;
                            }
                        }
                   // dd($carts);die;
                  //  $cart->quantity = $val;

                    //$carts = json_decode($carts);
                    //print_r($carts);die;
                    foreach ($carts as $keys => $subArr) {
                        $subArr->item_quantity = $val;
                       // dd($subArr);
                        $cobination = ProductAttrCombination::where("id", "=", $subArr->product_combination)->first();
                       // dd($cobination);die;
                        if($subArr->product_combination == $key){
                          $cobination = ProductAttrCombination::where("id", "=", $subArr->product_combination)->first();
                            $store = Product::select('open_time', 'closing_time', 'is_fullday_open', 'day','store_name','store_status')
                                ->leftjoin('stores', 'stores.vendor_id', 'products.vendor_id')
                                ->leftjoin('store_working_time', 'store_working_time.store_id', 'stores.id')
                                ->where('products.id', $cobination->product_id)
                                ->where('day', $today)
                                ->first();
                            /*if ($store->open_time != '' && $store->closing_time != '') {
                                if (!($store->open_time >= $todaytime) && !($store->closing_time <= $todaytime)) {
                                    return Redirect('cart')->with('error', $store->store_name.trans('messages.add_to_cart.store_close'));
                                }
                            }*/

                            if ($store->open_time != '' && !($todaytime >= $store->open_time )) {
                                return Redirect('cart')->with('error', $store->store_name.trans('messages.add_to_cart.store_close'));
                                //return Redirect('home')->with('error', trans('messages.add_to_cart.store_close'));
                            }elseif ($store->closing_time != '' && !($todaytime <= $store->closing_time)){
                                return Redirect('cart')->with('error', $store->store_name.trans('messages.add_to_cart.store_close'));
                            }elseif($store->store_status != 'Open'){
                                return Redirect('cart')->with('error', $store->store_name.' is '.$store->store_status.trans('messages.add_to_cart.store_status'));
                            }
                            else {

                                if ($val < 1) {
                                    return redirect('cart')->with('error', trans('messages.add_to_cart.invalid_quantity'));
                                } else if ($cobination->quantity < $val) {
                                    return redirect('cart')->with('error', trans('messages.add_to_cart.out_of_stock'));
                                }
                            }
                        }elseif($subArr->product_id == $key){
                            $cobination = ProductAttrCombination::where("id", "=", $subArr->product_combination)->first();
                            $store = Product::select('open_time', 'closing_time', 'is_fullday_open', 'day','store_name','store_status')
                                ->leftjoin('stores', 'stores.vendor_id', 'products.vendor_id')
                                ->leftjoin('store_working_time', 'store_working_time.store_id', 'stores.id')
                                ->where('products.id', $cobination->product_id)
                                ->where('day', $today)
                                ->first();

                            if ($store->open_time != '' && !($todaytime >= $store->open_time )) {
                                return Redirect('cart')->with('error', $store->store_name.trans('messages.add_to_cart.store_close'));
                                //return Redirect('home')->with('error', trans('messages.add_to_cart.store_close'));
                            }elseif ($store->closing_time != '' && !($todaytime <= $store->closing_time)){
                                return Redirect('cart')->with('error', $store->store_name.trans('messages.add_to_cart.store_close'));
                            }elseif($store->store_status != 'Open'){
                                return Redirect('cart')->with('error', $store->store_name.' is '.$store->store_status.trans('messages.add_to_cart.store_status'));
                            } else {

                                if ($val < 1) {
                                    return redirect('cart')->with('error', trans('messages.add_to_cart.invalid_quantity'));
                                } else if ($cobination->quantity < $val) {
                                    return redirect('cart')->with('error', trans('messages.add_to_cart.out_of_stock'));
                                }
                            }
                        }

                    }
                    //die;
                }
                return redirect('checkouts')->withCookie(cookie('cookie.cartItem', serialize(json_encode($product))));
            }
        }
        return redirect('cart')->with('error', trans('messages.error'));
    }

}
