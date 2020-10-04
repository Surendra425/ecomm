<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Guest;
use App\Helpers\NameHelper;
use App\Mail\OrderConfirmationMail;
use App\Mail\WelcomeMail;
use Dompdf\Adapter\PDFLib;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\PlanHelper;
use App\Helpers\ProductHelper;
use App\Helpers\PaymentHelper;
use App\Helpers\Knet;
use App\Country;
use App\State;
use App\City;
use App\User;
use App\UserAddress;
use App\Store;
use App\Collections;
use App\CollectionProducts;
use App\VendorProductCategory;
use App\Product;
use App\ProductLike;
use App\ProductImage;
use App\ProductCart;
use App\ProductShipping;
use App\ProductCategory;
use App\ProductAttrCombination;
use App\Order;
use App\OrderProduct;
use App\OrderAddress;
use App\OrderTraking;
use App\ProductStockHistory;
use Mpdf\Mpdf;
use App\Vendor;
use App\Mail\VendorOrderMail;
use PhpParser\Node\Name;
use Illuminate\Support\Facades\Config;

class CheckoutController1 extends Controller
{
    private $validationRules = [
       // 'full_name' => 'required',
        'country' => 'required',
        'street' => 'required',
        'building' => 'required',
        'block' => 'required',
        'payment_type' => 'required',
    ];
    
    public function __construct()
    {
        parent::__construct();

    }

   
    public function checkouts(Request $request)
    {
        $customer_address = '';
        if (!ProductHelper::isCanCheckout($request)) {
            return redirect('cart')->with('error', trans('messages.checkout.out_of_stock'));
        }

        $cart = unserialize($request->cookie('cookie_cartItem'));
        if (!is_array($cart)) {
            $cart = json_decode($cart);
        }
        //dd($cart);die;
        $CartDetail = [];
        foreach ($cart as $value) {
            $CartDetail[] = Product::select("products.id as product_id", "products.vendor_id", "products.product_slug", "product_attr_combination.id as product_combination_id", "products.product_title", "product_attr_combination.combination_title", "product_attr_combination.discount_price", "product_attr_combination.quantity as combination_qty", "product_attr_combination.rate")
                ->join("product_attr_combination", 'product_attr_combination.product_id', '=', 'products.id')
                ->where("products.id", "=", $value->product_id)
                ->where("product_attr_combination.id", "=", $value->product_combination)
                ->where("products.status", "=", 'Active')
                ->first();
        }
        //dd($CartDetail);die;
        if (is_array($CartDetail)) {
            $CartDetail = array_filter($CartDetail, 'strlen'); //if null value then unset array
            $CartDetail = array_values($CartDetail);
        }
        if (count($CartDetail)) {
            foreach ($CartDetail as $key => $product) {
                $CartDetail[$key]->combination = ProductAttrCombination::where("product_id", "=", $product->product_id)->where("is_delete", "=", 0)->get();
                $IsLiked = "No";
                if (!empty($customer)) {
                    $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->product_id)->first();
                    $IsLiked = (!empty($productLike)) ? "Yes" : "No";
                }
                $CartDetail[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
                $CartDetail[$key]->is_liked = $IsLiked;
                $CartDetail[$key]->images = ProductImage::where("product_id", "=", $product->product_id)->get();
                $CartDetail[$key]->shipping = ProductShipping::where("product_id", "=", $product->product_id)->first();
            }
        } else {
            return redirect('home');
        }
        $vendorIds = array_unique(array_column($CartDetail, "vendor_id"));
        $stores = Store::whereIn("vendor_id", $vendorIds)->get();

        foreach ($stores as $key => $store) {
            $stores[$key]->products = array_filter($CartDetail, function ($arr) use ($store) {
                return $arr['vendor_id'] == $store->vendor_id;
            });
        }
        $SubTotal = 0;
        $ShippingTotal = 0;
        foreach ($CartDetail as $key => $product) {
            $CartDetail[$key]->quantity = $cart[$key]->item_quantity;
            $SubTotal = $SubTotal + ($product['quantity'] * $product['rate']);
            $ShippingTotal = $ShippingTotal + (isset($product['shipping']) ? ($product['shipping']->shipping_charge * $product['quantity']) : 0);
        }
        $TotalAmount = $SubTotal + $ShippingTotal;
        $data = [];
        $data['SubTotal'] = $SubTotal;
        $data['ShippingTotal'] = $ShippingTotal;
        $data['TotalAmount'] = $TotalAmount;
        $country = Country::where("status", "Active")->get();
        $data['country'] = $country;
        $data["customer_address"] = $customer_address;
        $data["stores"] = $stores;
        return view('app.checkout.index', $data);
    }

    public function index()
    {
        $customer = Auth::guard('customer')->user();
        if (!ProductHelper::isCanCheckout()) {
            return redirect('cart')->with('error', trans('messages.checkout.out_of_stock'));
        }
        $customer_address = UserAddress::where("user_id", "=", $customer->id)->get();
        $address = UserAddress::where("user_id", "=", $customer->id)->where('is_selected','Yes')->first();
        //dd($address);die;
        $CartDetail = ProductCart::select("product_cart.id", "product_cart.product_id", "product_cart.quantity", "products.vendor_id", "product_cart.product_combination_id", "product_cart.rate", "products.product_title", "product_attr_combination.combination_title", "product_attr_combination.discount_price", "product_attr_combination.quantity as combination_qty", "product_attr_combination.rate as combination_rate")
            ->join("products", 'products.id', '=', 'product_cart.product_id')
            ->join("product_attr_combination", 'product_attr_combination.id', '=', 'product_cart.product_combination_id')
            ->where("user_id", "=", $customer->id)
            ->where("products.status", "=", 'Active')->get();
        if (is_array($CartDetail)) {
            $CartDetail = array_filter($CartDetail, 'strlen'); //if null value then unset array
            $CartDetail = array_values($CartDetail);
        }
        if (count($CartDetail)) {
            foreach ($CartDetail as $key => $product) {
                $CartDetail[$key]->combination = ProductAttrCombination::where("product_id", "=", $product->product_id)->where("is_delete", "=", 0)->get();
                $IsLiked = "No";
                if (!empty($customer)) {
                    $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->product_id)->first();
                    $IsLiked = (!empty($productLike)) ? "Yes" : "No";
                }
                $CartDetail[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
                $CartDetail[$key]->is_liked = $IsLiked;
                $CartDetail[$key]->images = ProductImage::where("product_id", "=", $product->product_id)->get();
                $CartDetail[$key]->shipping = ProductShipping::select('vendor_shipping_detail.country_name', 'charge', 'from', 'to', 'city_name', 'time')
                    ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.country_id', 'product_shipping.country_id')
                    ->where("product_id", "=", $product->product_id)->get();
            }
        } else {
            return redirect('home');
        }
        $vendorIds = array_unique(array_column($CartDetail->toArray(), "vendor_id"));
        $stores = Store::whereIn("vendor_id", $vendorIds)->get();
        foreach ($stores as $key => $store) {
            $stores[$key]->products = array_filter($CartDetail->toArray(), function ($arr) use ($store) {
                return $arr['vendor_id'] == $store->vendor_id;
            });
        }
        $SubTotal = 0;
        $ShippingTotal = 0;
        foreach ($CartDetail as $product) {
            $SubTotal = $SubTotal + ($product['quantity'] * $product['rate']);
            $ShippingTotal = 0.00;
            //$ShippingTotal = $ShippingTotal + (isset($product['shipping']) ? ($product['shipping']->shipping_charge * $product['quantity']) : 0);
        }

        $TotalAmount = $SubTotal + $ShippingTotal;
        $data = [];
        $data['SubTotal'] = $SubTotal;
        $data['ShippingTotal'] = $ShippingTotal;
        $data['TotalAmount'] = $TotalAmount;
        $country = Country::where("status", "Active")->get();
        $data['country'] = $country;
        $data["customer"] = $customer;
        if(!empty($address)){
            $data['city'] = City::where('id',$address->city_id)->first();
        }

        $data["customer_address"] = $customer_address;
        $data["stores"] = $stores;
        return view('app.checkout.index', $data);
    }

    public function invoice()
    {
        $order = Order::where('id', '17')->first();
        $order_products = OrderProduct::select("order_products.*", "products.vendor_id", "products.product_slug", "products.product_title", "users.first_name", "users.last_name", "rating", "review_text", "product_images.image_url")
            ->join("products", "products.id", "order_products.product_id")
            ->join("product_attr_combination", "product_attr_combination.id", "order_products.product_combination_id")
            ->join("users", "users.id", "order_products.product_vendor_id")
            ->join("product_images", "product_images.product_id", "order_products.product_id")
            ->leftjoin("product_review", "product_review.product_id", "order_products.product_id")
            ->groupBy("order_products.id")
            ->where("order_id", $order->id)
            ->get();
        $shipping_address = OrderAddress::select("order_addresses.*", "city.city_name", "country.country_name")
            ->leftjoin("city", "city.id", "order_addresses.city")
            ->leftjoin("country", "country.id", "order_addresses.country")
            ->where("order_id", "=", $order->id)->where("address_type", "Shipping")->first();
        $data = [];
        $data['order'] = $order;
        $data['order_products'] = $order_products;
        $data['shipping_address'] = $shipping_address;
        //dd($data);die;
        $view = view('app.order_invoice', $data);
        //echo $view->render();
        //echo $view;die;
        //die;
        $invoiceName = $order->order_no . '_' . time($order->created_at) . '.pdf';

        /*  require_once DIR_CLASS . "mpdf/mpdf.php";
              $mpdf = new mPDF('c', 'A4', '', '', 0, 0, 0, 0, 0, 0);
              $mpdf->SetDisplayMode('fullpage');
              $mpdf->list_indent_first_level = 0;
              $mpdf->WriteHTML($PrintHTML);
              $FileName = "Attendance_Report_".time()."_".rand(1111, 99999).".pdf";*/
            //echo $FileName;
            //$mpdf->Output();
            //$mpdf->Output('filename.pdf','');
                    //$mpdf->Output('../doc/Reports/AttendanceReports/'.$FileName,'F');
            //$mpdf->Output('../doc/filename.pdf', \Mpdf\Output\Destination::FILE);

        $mpdf = new Mpdf(['format' => 'A4', [190, 236]]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($view);
        $mpdf->Output('doc/invoice/' . $invoiceName, \Mpdf\Output\Destination::FILE);
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        //dd($request);die;

           /* if($request->payment_type == 'KNet'){
                $order = Order::where('id',82)->first();
                //dd($order);die;
                 $this->knetPay($order);
            }
        die;*/
        if(!empty($request->city_ids)){
            $cityIds = $request->city_ids;
        }else{
            $cityIds = $request->city;
        }
        $customer = Auth::guard('customer')->user();
        if (!ProductHelper::isCanCheckout($request)) {
            return redirect(route('cart'))->with('error', trans('messages.checkout.out_of_stock'));
        }

        $selected_address_id = NULL;
        $user_address = array();
        if (!empty($customer)) {
            $CartDetail = ProductCart::select("product_cart.id", "product_cart.product_id", "product_cart.quantity", "products.vendor_id", "product_cart.product_combination_id", "product_cart.rate", "products.product_title", "product_attr_combination.combination_title", "product_attr_combination.discount_price", "product_attr_combination.quantity as combination_qty", "product_attr_combination.rate as combination_rate")
                ->join("products", 'products.id', '=', 'product_cart.product_id')
                ->join("product_attr_combination", 'product_attr_combination.id', '=', 'product_cart.product_combination_id')
                ->where("user_id", "=", $customer->id)->get();
            $vendorIds = array_unique(array_column($CartDetail->toArray(), "vendor_id"));
            $stores = Store::whereIn("vendor_id", $vendorIds)->get();
        } else {
            $cart = unserialize($request->cookie('cookie_cartItem'));
            if (!is_array($cart)) {
                $cart = json_decode($cart);
            }
            //dd($cart);die;
            $CartDetail = [];
            foreach ($cart as $value) {
                $CartDetail[] = Product::select("products.id as product_id", "products.vendor_id", "products.product_slug", "product_attr_combination.id as product_combination_id", "products.product_title", "product_attr_combination.combination_title", "product_attr_combination.discount_price", "product_attr_combination.quantity as combination_qty", "product_attr_combination.rate")
                    ->join("product_attr_combination", 'product_attr_combination.product_id', '=', 'products.id')
                    ->where("products.id", "=", $value->product_id)
                    ->where("products.status", "=", 'Active')
                    ->where("product_attr_combination.id", "=", $value->product_combination)
                    ->first();
            }
            $vendorIds = array_unique(array_column($CartDetail, "vendor_id"));
            $stores = Store::whereIn("vendor_id", $vendorIds)->get();

            $count = User::where('email', $request->email)->where('type', 'guest')->count();
            $guestDetails = DB::table('users')
                ->select('users.*')
                ->where('email', $request->email)
                ->where('type', 'guest')
                ->first();
            // echo $request->email;
            // echo $count;die;
            if(isset($request->account)){
                if ($count < 1) {
                    $guest = new Customer();

                    $guest->fill($request->all());
                    $guest->type = 'customer';
                    $guest->password = bcrypt($request->password);
                    $guest->save();
                    $guestId = $guest->id;
                    $guestEmail = $guest->email;
                    $guestName = $request->first_name . ' ' . $request->last_name;
                    $post = array ('password' => $request->password, 'email' => $request->email);
                    $data = Auth::guard('customer')->loginUsingId($guestId);
                    try
                    {
                        Mail::to($guest->email)->send(new WelcomeMail($guest));
                    }
                    catch (Exception $exc)
                    {

                    }
                } else {
                    //  echo "hi";die;
                    $customer = User::where('email',$request->email)->where('type', 'guest')->first();
                    $customer->type = 'customer';
                    $customer->password = bcrypt($request->password);
                    $guestId = $guestDetails->id;
                    $guestName = $guestDetails->first_name . ' ' . $guestDetails->last_name;
                    $guestEmail = $guestDetails->email;
                    $post = array ('password' => $request->password, 'email' => $request->email);
                    $data = Auth::guard('customer')->loginUsingId($customer->id);
                    try
                    {
                        Mail::to($customer->email)->send(new WelcomeMail($customer));
                    }
                    catch (Exception $exc)
                    {

                    }
                }
            }else{
                if ($count < 1) {
                    $guest = new Guest();

                    $guest->fill($request->all());
                    $guest->type = 'guest';
                    $guest->save();
                    $guestId = $guest->id;
                    $guestEmail = $guest->email;
                    $guestName = $request->first_name . ' ' . $request->last_name;
                } else {
                    //  echo "hi";die;
                    $guestId = $guestDetails->id;
                    $guestName = $guestDetails->first_name . ' ' . $guestDetails->last_name;
                    $guestEmail = $guestDetails->email;
                }
            }

        }
        //dd($CartDetail);die;
        /*if (empty($CartDetail))
        {
            return redirect(route('cart'))->with('error', trans('messages.checkout.invalid_order'));
        }*/
        if (!empty($CartDetail)) {
            $today = strtolower(date('l'));
            $todaytime = date('h:i:s');
            foreach ($CartDetail as $key => $product) {
                if (isset($cart) && !empty($cart)) {
                    // dd($cart);die;
                    $CartDetail[$key]->quantity = $cart[$key]->item_quantity;
                }

                $CartDetail[$key]->combination = ProductAttrCombination::where("product_id", "=", $product->product_id)->where("is_delete", "=", 0)->get();

                $store = Product::select('open_time', 'closing_time', 'is_fullday_open', 'day','store_name','store_status')
                    ->leftjoin('stores', 'stores.vendor_id', 'products.vendor_id')
                    ->leftjoin('store_working_time', 'store_working_time.store_id', 'stores.id')
                    ->where('products.id', $product->product_id)
                    ->where('day', $today)
                    ->first();
                if ($store->open_time != '' && !($todaytime >= $store->open_time )) {
                    return Redirect('cart')->with('error', $store->store_name.trans('messages.add_to_cart.store_close'));
                    //return Redirect('home')->with('error', trans('messages.add_to_cart.store_close'));
                }elseif ($store->closing_time != '' && !($todaytime <= $store->closing_time)){
                    return Redirect('cart')->with('error', $store->store_name.trans('messages.add_to_cart.store_close'));
                }elseif($store->store_status != 'Open'){
                    return Redirect('cart')->with('error', $store->store_name.' is '.$store->store_status.trans('messages.add_to_cart.store_status'));
                }else{
                    $IsLiked = "No";
                    if (!empty($customer)) {
                        $productLike = ProductLike::where("user_id", "=", $customer->id)->where("product_id", "=", $product->product_id)->first();
                        $IsLiked = (!empty($productLike)) ? "Yes" : "No";
                    }
                    $CartDetail[$key]->store = Store::where("vendor_id", "=", $product->vendor_id)->first();
                    $CartDetail[$key]->is_liked = $IsLiked;
                    $CartDetail[$key]->images = ProductImage::where("product_id", "=", $product->product_id)->get();
                    
                    $CartDetail[$key]->shipping = ProductShipping::select('vendor_shipping_detail.country_name', 'charge', 'from', 'to', 'city_name', 'time', 'time','vendor_shipping_detail.city_id')
                        ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.country_id', 'product_shipping.country_id')
                        ->leftjoin('country', 'country.id', 'product_shipping.country_id')
                        ->where("product_id", "=", $product->product_id)
                        ->where('product_shipping.country_id', $request->country)
                        ->where('country.status', 'Active')
                        ->where("vendor_shipping_detail.vendor_id", "=", $product->vendor_id)
                        ->get();
                    $CartDetail[$key]->shippings = ProductShipping::select('vendor_shipping_detail.country_name', 'vendor_shipping_detail.country_id', 'charge', 'from', 'to', 'city_name', 'time','vendor_shipping_detail.city_id')
                        ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.country_id', 'product_shipping.country_id')
                        ->leftjoin('country', 'country.id', 'product_shipping.country_id')
                        ->where("product_id", "=", $product->product_id)
                        ->where("vendor_shipping_detail.vendor_id", "=", $product->vendor_id)
                        ->where('product_shipping.country_id', $request->country)
                        ->where('country.status', 'Active')
                        ->where("vendor_shipping_detail.city_id", "!=", '')
                        ->get();
                }

            }

        } else {
            return redirect(route('cart'))->with('error', trans('messages.checkout.invalid_order'));
            //return redirect('home');
        }


        if (!empty($customer)) {
            $userEmail = $customer->email;
            $userId = $customer->id;
        } else {
            $userEmail = $guestEmail;
            $userId = $guestId;
        }
        //echo $userEmail;die;
        $PaymentType = $request->payment_type;

        if (!empty($customer)) {
            foreach ($stores as $key => $store) {
                $stores[$key]->products = array_filter($CartDetail->toArray(), function ($arr) use ($store) {
                    return $arr['vendor_id'] == $store->vendor_id;
                });
            }

        } else {
            foreach ($stores as $key => $store) {

                $stores[$key]->products = array_filter($CartDetail, function ($arr) use ($store) {
                    return $arr['vendor_id'] == $store->vendor_id;
                });
            }
        }
         $city_name = NameHelper::getNameBySingleId('city','city_name','id',$cityIds);
        $shipping = [];
       // dd($CartDetail);die;
        foreach ($stores as $product) {
            $vendorId = [];
            $total = [];

            foreach ($product['products'] as $key => $val) {

                if (!empty($val['shipping']) && count($val['shipping']) > 0) {
                    if (!in_array($val['vendor_id'], $vendorId)) {
                        $city = City::where('country_id', $val['shipping'][0]['country_id'])->count();
                        $vendorId[] = $val['vendor_id'];
                        $countryShipping = array_filter($val['shippings']->toArray());
                        //dd($val['shipping']);die;
                        foreach ($val['shipping'] as $shiping) {
                          //  dd($shiping->charge);die;
                            // echo $val['vendor_id']."=". $shiping->city_name.'='.$cityName;
                            //$city == count($countryShipping) &&
                            if ($shiping->city_id == $cityIds) {
                                $shipping[] = $shiping->charge;
                                break;
                            } /*elseif ($city == count($countryShipping) && $shiping->city_id == '') {
                               // echo $shiping->charge.'hi2';die;
                                $shipping[] = $shiping->charge;
                                //break;
                            } */elseif ($city == count($countryShipping) && $shiping->city_id == $cityIds) {
                                $shipping[] = $shiping->charge;
                                break;
                            }elseif(count($countryShipping) == 0 && $shiping->city_id == ''){
                                $shipping[] = $shiping->charge;
                                break;
                            }

                        }

                    }
                    $total[] = ($val['quantity'] * $val['rate']);

                }else {
                     return Redirect::back()->with('error', 'Sorry this is not delivering products to ' . $city_name);
                   // return Redirect::back()->with('error', trans('messages.checkout.shipping'));
                }

                /*if (!empty($val['shipping'])) {
                    if (!in_array($val['vendor_id'], $vendorId)) {
                        //  echo $val['vendor_id'];
                        $vendorId[] = $val['vendor_id'];
                        if ($val['shipping']->city_name != '' && $val['shipping']->city_id == $request->city) {
                            $shipping[] = $val['shipping']->charge;
                        } else {
                            $shipping[] = $val['shipping']->charge;
                        }


                    }
                    $total[] = ($val['quantity'] * $val['rate']);
                } else {
                    return Redirect::back()->with('error', trans('messages.checkout.shipping'));
                }*/
            }
        }
        //echo "<pre>";print_r($shipping);die;
        if (!empty($shipping)) {
            $ShippingTotal = array_sum($shipping);

        }else {
            //echo "hello";die;
            return Redirect::back()->with('error', 'Sorry this is not delivering products to ' . $city_name);
        }
        //dd($shipping);die;
        $SubTotal = array_sum($total);
        $ShippingTotal = array_sum($shipping);
        $TotalAmount = $ShippingTotal + $SubTotal;
        $order = new Order();
        $order->fill($request->all());
        //dd($ShippingTotal);die;
        if (!empty($customer)) {
            $order->customer_name = $customer->first_name . " " . $customer->last_name;
        } else {
            $order->customer_name = $guestName;
        }
        if(!empty($customer)){
            UserAddress::where('user_id' ,$customer->id)->update(['is_selected' => 'No']);
        }

        if (isset($request->selected_address_id) && !empty($request->selected_address_id)) {
            $selected_address_id = $request->selected_address_id;
            DB::table('user_addresses')
                ->where('id', $selected_address_id)
                ->update(['is_selected' => 'Yes']);
        } else {
            $user_address = new UserAddress();
            $user_address->fill($request->all());
            $user_address->mobile = $request->mobile_no;
            $user_address->landline = $request->landline;
            $user_address->city_id = $request->city;
            $user_address->country_id = $request->country;
            $city_name = NameHelper::getNameBySingleId('city','city_name','id',$cityIds);
            $country_name = NameHelper::getNameBySingleId('country','country_name','id',$request->country);
            $user_address->city = $city_name;
            $user_address->country = $country_name;
            $user_address->user_id = $userId;
            $user_address->is_selected = 'Yes';
            $user_address->save();
            $selected_address_id = $user_address->id;
        }
//echo $selected_address_id; $user_address = UserAddress::find($selected_address_id);
            // dd($user_address);die;
        $order->customer_id = $userId;
        //$order->payment_status = "Pending";
        $order->tax = 0;



        if ($order->save()){
            $order->payment_status = "Pending";
            $order->order_no = "ORDER" . str_pad($order->id, 6, mt_rand(100000, 999999), STR_PAD_RIGHT);
            $order->save();
            // echo $order->id;die;
            $OrderProduct = array();
            $shipping = 0;
                    foreach ($stores as $product) {
                        $vendorId = [];
                        $total = 0 ;

                        foreach ($product['products'] as $key => $val) {
                            if (!empty($val['shipping']) && count($val['shipping']) > 0) {
                                if (!in_array($val['vendor_id'], $vendorId)) {
                                    $city = City::where('country_id', $val['shipping'][0]['country_id'])->count();
                                    $vendorId[] = $val['vendor_id'];
                                    $countryShipping = array_filter($val['shippings']->toArray());
                                    foreach ($val['shipping'] as $shiping) {
                                      
                                        if ($shiping->city_id == $cityIds) {
                                            $shipping = $shiping->charge;
                                            break;
                                        } elseif ($city == count($countryShipping) && $shiping->city_id == $cityIds) {
                                            $shipping = $shiping->charge;
                                            break;
                                        }elseif(count($countryShipping) == 0 && $shiping->city_id == ''){
                                            $shipping = $shiping->charge;
                                            break;
                                        }

                                    }

                                }
                                $sub_total = ($val['quantity'] * $val['rate']);
                                $total = $sub_total;
                                $OrderProduct[] = array(
                                "order_id" => $order->id,
                                "product_id" => $val['product_id'],
                                "product_combination_id" => $val['product_combination_id'],
                                "rate" => $val['rate'],
                                "quantity" => $val['quantity'],
                                "product_vendor_id" => $val['vendor_id'],
                                "shipping_charges" => $shipping,
                                "sub_total" => $sub_total,
                                "tax" => 0,
                                "grand_total" => $total,
                            );
                            }
                                
                                
                        }
                    }
            /*foreach ($CartDetail as $product) {
                $sub_total = ($product['quantity'] * $product['rate']);
                $OrderProduct[] = array(
                    "order_id" => $order->id,
                    "product_id" => $product->product_id,
                    "product_combination_id" => $product->product_combination_id,
                    "rate" => $product->rate,
                    "quantity" => $product->quantity,
                    "product_vendor_id" => $product->vendor_id,
                    "shipping_charges" => $ShippingTotal,
                    "sub_total" => $sub_total,
                    "tax" => 0,
                    "grand_total" => $sub_total,
                );
            }*/
            $user_address = UserAddress::find($selected_address_id);
            // dd($user_address);die;
            $userAddress = $user_address->toArray();
            $order_address = new OrderAddress();
            $order_address->fill($user_address->toArray());
            $order_address->customer_id = $userId;
            $order_address->order_id = $order->id;
            $order_address->mobile_no = $user_address->mobile;
            $order_address->landline_no = $user_address->landline;
            $order_address->address_type = "Shipping";
            $order_address->save();
            OrderProduct::insert($OrderProduct);
            if (!empty($customer)) {
                ProductCart::where("user_id", "=", $customer->id)->delete();
                $cookie = \Cookie::forget('cookie.cartItem');
            } else {
                $cookie = \Cookie::forget('cookie.cartItem');
            }

            

            /*start invoice create*/

            /*end invoice create*/
            
            $ProductStockHistory = array();
            foreach ($OrderProduct as $key => $val) {
                $productCombination = ProductAttrCombination::find($val['product_combination_id']);
                $productCombination->quantity = $productCombination->quantity - $val['quantity'];
                $productCombination->save();
                $ProductStockHistory[] = array(
                    "product_id" => $val['product_id'],
                    "product_combination_id" => $val['product_combination_id'],
                    "user_id" => $userId,
                    "quantity" => -$val['quantity'],
                    "rate" => $val['rate'],
                    "type" => "Order",
                    "description" => $order->id
                );
            }
            ProductStockHistory::insert($ProductStockHistory);
            /*if($request->payment_type == 'KNet'){
                //$this->knetPay($order);
                 $this->knetPay($order);
            }else{
                $order->payment_status = "Completed";
                $order->save();
                return redirect(url(route('myOrderDetail', ["orderNo" => $order->order_no])))->withCookie($cookie)->with('success', trans('messages.order.success'));
            }*/
           // die;
            if($request->payment_type == 'KNet'){
                $orders = Order::where('id',$order->id)->first();
                //dd($order);die;
                 return $this->knetPay($orders);
            }
            else {
                $order->payment_status = "Completed";
                $order->save();
                    try {
                        Mail::to($userEmail)->send(new OrderConfirmationMail($order->id));
                    $this->sendOrderVendorMail($order->id);
                    OrderProduct::where("order_id", "=", $order->id);
                } catch (Exception $exc) {

                }
                return redirect(url(route('myOrderDetail', ["orderNo" => $order->order_no])))->withCookie($cookie)->with('success', trans('messages.order.success'));
            }
        }

        return redirect('checkout')->with('error', trans('messages.error'));
    }

    public function knetPay(Order $orders){
        //dd($orders);die;
        $user = User::select('email')->where('id',$orders->customer_id)->first();
        //dd($user->email);die;
         $orderID = $orders->order_no;
                $Pipe = new Knet;
                $Pipe->setAction(1);
               $Pipe->setCurrency(414);
               $Pipe->setLanguage("ENG"); //change it to "ARA" for arabic language
               $Pipe->setResponseURL("https://shopzz.com/response.php"); 
                //$Pipe->setResponseURL(url(route('knetResponse',['orderId'=>$orderID]))); 
               //$Pipe->setErrorURL("http://192.168.1.129/shopzz/error.php"); 
               $Pipe->setErrorURL("https://shopzz.com/response.php"); 
               $Pipe->setAmt($orders->grand_total); 
               //$Pipe->setAmt(510); 
               $Pipe->setResourcePath(public_path('resource/')); 
               $Pipe->setAlias("project"); //set your alias name here
               $Pipe->setTrackId($orderID);//generate the random number here
             
               $Pipe->setUdf1($user->email); //set User defined value
               $Pipe->setUdf2($orders->id); //set User defined value
               $Pipe->setUdf3("UDF 3"); //set User defined value
               $Pipe->setUdf4("UDF 4"); //set User defined value
               $Pipe->setUdf5("UDF 5"); //set User defined value
               //$Pipe->setOrderId($orderID);
               //echo $Pipe->performPaymentInitialization();
               //dd($Pipe);die;
            /*   $customer = Auth::guard('customer')->user();
        if (!empty($customer)) {
                ProductCart::where("user_id", "=", $customer->id)->delete();
                $cookie = \Cookie::forget('cookie.cartItem');
            } else {
                $cookie = \Cookie::forget('cookie.cartItem');
            }*/
             if($Pipe->performPaymentInitialization()!=$Pipe->SUCCESS){
              //  echo "hi";die;
                //echo "Result=".$Pipe->SUCCESS;
                //echo "<br>".$Pipe->getErrorMsg();
                //echo "<br>".$Pipe->getDebugMsg();
                $order = Order::where('order_no',$orderID)->update(['payment_status'=> 'Failed'
            ]);
                //return redirect(url(route('myOrderDetail', ["orderNo" => $orderID])))->with('error', $Pipe->getErrorMsg());

                }else {
                       $payID = $Pipe->getPaymentId();
                     $payURL = $Pipe->getPaymentPage();
            
//die;
                return redirect($payURL."?PaymentID=".$payID);
                }
    }
    public function knetResponse(Request $request){
       //dd($request);die;
        $PaymentID = $request->PaymentID;
        $presult = $request->Result;
        $postdate = $request->PostDate;
        $tranid =$request->TranID;
        $auth = $request->Auth;
        $ref = $request->Ref;
        $trackid = $request->TrackID;
        $userEmail = $request->UDF1; //get user mail
        $orderId = $request->UDF2; //get order id
        $udf3 = $request->UDF3;
        $udf4 = $request->UDF4;
        $udf5 = $request->UDF5;
        $response = json_encode($request->all());
        /*$customer = Auth::guard('customer')->user();
        if (!empty($customer)) {
                ProductCart::where("user_id", "=", $customer->id)->delete();
                $cookie = \Cookie::forget('cookie.cartItem');
            } else {
                $cookie = \Cookie::forget('cookie.cartItem');
            }*/
        if ( $presult == "CAPTURED" ){
            $order = Order::where('order_no',$trackid)->update(['payment_status'=> 'Completed',
            'knet_payment_id'=>$PaymentID,
            'kent_track_id'=>$trackid,
            'knet_tran_id'=>$tranid,
            'respons_parm'=>$response,
            ]);
            try {
                    Mail::to($userEmail)->send(new OrderConfirmationMail($orderId));
                    $this->sendOrderVendorMail($orderId);
                    OrderProduct::where("order_id", "=", $orderId);
                } catch (Exception $exc) {

                }
               return  redirect(url(route('myOrderDetail', ["orderNo" => $trackid])))->with('success', trans('messages.order.success'));
        }else{
            $order = Order::where('order_no',$trackid)->update(['payment_status'=> 'Failed',
                'knet_payment_id'=>$PaymentID,
                'kent_track_id'=>$trackid,
                'knet_tran_id'=>$tranid,
                'respons_parm'=>$response,
            ]);
                return redirect(url(route('myOrderDetail', ["orderNo" => $trackid])))->with('error', trans('messages.order.error'));
        }
        
       
    }
   

    public function knetError(Request $request, Order $orderNo){
        
        $PaymentID = $request->PaymentID;
        $presult = $request->Result;
        $postdate = $request->PostDate;
        $tranid =$request->TranID;
        $auth = $request->Auth;
        $ref = $request->Ref;
        $trackid = $request->TrackID;
        $userEmail = $request->UDF1; //get user mail
        $orderId = $request->UDF2; //get order id
        $udf3 = $request->UDF3;
        $udf4 = $request->UDF4;
        $udf5 = $request->UDF5;
        $response = json_encode($request->all());
        
        //dd($orderId);die;
                //$orderId->payment_status = "Failed";
                //$orderId->save();
                $order = Order::where('order_no',$orderNo->order_no)->update([
                    'payment_status'=> 'Failed',
                    'knet_payment_id'=>$PaymentID,
                    'kent_track_id'=>$trackid,
                    'knet_tran_id'=>$tranid,
                    'respons_parm'=>$response,
                ]);
                return redirect(url(route('myOrderDetail', ["orderNo" => $orderNo->order_no])))->with('error', trans('messages.order.error'));
    }

    /*public function knetError(Order $orderNo){
        //dd($orderId);die;
                //$orderId->payment_status = "Failed";
                //$orderId->save();
                $order = Order::where('order_no',$orderNo->order_no)->update(['payment_status'=> 'Failed']);
                return redirect(url(route('myOrderDetail', ["orderNo" => $orderNo->order_no])))->with('error', trans('messages.order.error'));
    }
*/
    public function applyCouponCode(Request $request)
    {
        $couponCode = $request->coupon;
        $orderAmount = $request->order_total;
        $data = PaymentHelper::applyCouponCode($orderAmount, $couponCode);
        echo json_encode($data);
    }

    public function applyShippping(Request $request)
    {
        $countryName = $request->countryName;
        $cityName = $request->cityName;
        $coupon = $request->coupon;
        $citiesName = NameHelper::getNameBySingleId('city','city_name','id',$cityName);
        $customer = Auth::guard('customer')->user();
        if (!empty($customer)) {
            $CartDetail = ProductCart::select("product_cart.id","stores.store_name", "product_cart.product_id", "product_cart.quantity", "products.vendor_id", "product_cart.product_combination_id", "product_cart.rate", "products.product_title", "product_attr_combination.combination_title", "product_attr_combination.discount_price", "product_attr_combination.quantity as combination_qty", "product_attr_combination.rate as combination_rate")
                ->join("products", 'products.id', '=', 'product_cart.product_id')
                ->join("stores", 'stores.vendor_id', '=', 'products.vendor_id')
                ->join("product_attr_combination", 'product_attr_combination.id', '=', 'product_cart.product_combination_id')
                ->where("user_id", "=", $customer->id)->get();
            $vendorIds = array_unique(array_column($CartDetail->toArray(), "vendor_id"));
            $stores = Store::whereIn("vendor_id", $vendorIds)->get();

        } else {
            $cart = unserialize($request->cookie('cookie_cartItem'));
            if (!is_array($cart)) {
                $cart = json_decode($cart);
            }
            //dd($cart);die;
            $CartDetail = [];
            foreach ($cart as $value) {
                $CartDetail[] = Product::select("products.id as product_id","stores.store_name", "products.vendor_id", "products.product_slug", "product_attr_combination.id as product_combination_id", "products.product_title", "product_attr_combination.combination_title", "product_attr_combination.discount_price", "product_attr_combination.quantity as combination_qty", "product_attr_combination.rate")
                    ->join("product_attr_combination", 'product_attr_combination.product_id', '=', 'products.id')
                    ->join("stores", 'stores.vendor_id', '=', 'products.vendor_id')
                    ->where("products.id", "=", $value->product_id)
                    ->where("products.status", "=", 'Active')
                    ->where("product_attr_combination.id", "=", $value->product_combination)
                    ->first();
            }
            $vendorIds = array_unique(array_column($CartDetail, "vendor_id"));
            $stores = Store::whereIn("vendor_id", $vendorIds)->get();

        }
        if (is_array($CartDetail)) {
            $CartDetail = array_filter($CartDetail, 'strlen'); //if null value then unset array
            $CartDetail = array_values($CartDetail);
        }
        if (count($CartDetail)) {
            foreach ($CartDetail as $key => $product) {
                if (isset($cart) && !empty($cart)) {
                    // dd($cart);die;
                    $CartDetail[$key]->quantity = $cart[$key]->item_quantity;
                }
                $CartDetail[$key]->shipping = ProductShipping::select('vendor_shipping_detail.country_name', 'vendor_shipping_detail.country_id', 'charge', 'from', 'to', 'city_name', 'time','vendor_shipping_detail.city_id')
                    ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.country_id', 'product_shipping.country_id')
                    ->leftjoin('country', 'country.id', 'product_shipping.country_id')
                    ->where("product_id", "=", $product->product_id)
                    ->where("vendor_shipping_detail.vendor_id", "=", $product->vendor_id)
                    ->where('product_shipping.country_id', $countryName)
                    ->where('country.status', 'Active')
                    ->get();
                $CartDetail[$key]->shippings = ProductShipping::select('vendor_shipping_detail.country_name', 'vendor_shipping_detail.country_id', 'charge', 'from', 'to', 'city_name', 'time','vendor_shipping_detail.city_id')
                    ->leftjoin('vendor_shipping_detail', 'vendor_shipping_detail.country_id', 'product_shipping.country_id')
                    ->leftjoin('country', 'country.id', 'product_shipping.country_id')
                    ->where("product_id", "=", $product->product_id)
                    ->where("vendor_shipping_detail.vendor_id", "=", $product->vendor_id)
                    ->where('product_shipping.country_id', $countryName)
                    ->where('country.status', 'Active')
                    ->where("vendor_shipping_detail.city_id", "!=", '')
                    ->get();
            }
        }
        if (!empty($customer)) {
            foreach ($stores as $key => $store) {
                $stores[$key]->products = array_filter($CartDetail->toArray(), function ($arr) use ($store) {
                    return $arr['vendor_id'] == $store->vendor_id;
                });
            }
        } else {
            foreach ($stores as $key => $store) {

                $stores[$key]->products = array_filter($CartDetail, function ($arr) use ($store) {
                    return $arr['vendor_id'] == $store->vendor_id;
                });
            }
        }
        $data = '';
        $shippings = '';
        $shipping = [];
        $total = [];
        foreach ($stores as $product) {

            $vendorId = [];

            $shipped = [];
            $storesName = [];
            foreach ($product['products'] as $key => $val) {
              //  dd($val);
                $storesName[] = $val['store_name'];
                if (!empty($val['shipping']) && count($val['shipping']) > 0) {
                    if (!in_array($val['vendor_id'], $vendorId)) {
                        $city = City::where('country_id', $val['shipping'][0]['country_id'])->count();
                        $vendorId[] = $val['vendor_id'];
                        $countryShipping = array_filter($val['shippings']->toArray());
                        foreach ($val['shipping'] as $shiping) {
                            //dd($shiping);die;
                            // echo $val['vendor_id']."=". $shiping->city_name.'='.$cityName;
                            //$city == count($countryShipping) &&

                            if ($shiping->city_id == $cityName) {
                                $shipping[] = $shiping->charge;
                                $shipped[] = $shiping->charge;

                                break;
                            } /*elseif ($city == count($countryShipping) && $shiping->city_id == '') {
                               // echo $shiping->charge.'hi2';die;
                                $shipping[] = $shiping->charge;
                                //break;
                            } */elseif ($city == count($countryShipping) && $shiping->city_id == $cityName) {
                                $shipping[] = $shiping->charge;
                                $shipped[] = $shiping->charge;

                                break;
                            }elseif(count($countryShipping) == 0 && $shiping->city_id == ''){
                                $shipping[] = $shiping->charge;
                                $shipped[] = $shiping->charge;
                                break;
                            }
                        }

                    }
                    $total[] = ($val['quantity'] * $val['rate']);

                } else {
                    $shippings = 'No';
                }
            }
            //dd($shipping);die;
            
             //echo $shippings;die;
           // echo $coupon;die;
            if (!empty($shipping) && !empty($shipped)) {
                $totalSum = array_sum($total);
                $ShippingTotal = array_sum($shipping);
                if(!empty($coupon)){
                    $data['coupon'] = $coupon;
                    $TotalAmount = $ShippingTotal + $totalSum - $coupon;
                }else{
                    $data['coupon'] = 0;
                    $TotalAmount = $ShippingTotal + $totalSum;
                }
                $data['SubTotal'] = $totalSum;
                $data['ShippingTotal'] = $ShippingTotal;
                $data['TotalAmount'] = $TotalAmount;
            }elseif ($shippings == 'No'){
                $data['error'] = 'Sorry '.$storesName[0].' is not delivering products to ' . $citiesName;
                $data['city'] = $cityName;
            } else {
                $data['error'] = 'Sorry '.$storesName[0].' is not delivering products to ' . $citiesName;
                $data['city'] = $cityName;
            }
        }
        //print_r($shipping);die;
       // dd($data);die;
         //echo "<pre>";print_r($data);die;
        echo json_encode($data);
    }

    public function sendOrderVendorMail($OrderId)
    {
        $order = Order::where("id", "=", $OrderId)->first();
        if ( ! empty($order))
        {
            
            $VendorIds = OrderProduct::where("order_id", "=", $OrderId)->distinct()->pluck('product_vendor_id')->toArray();
            foreach($VendorIds as $vendor_id)
            {
                 $vendor = Vendor::where("id", "=", $vendor_id)->first();

                $order_products = OrderProduct::selectRaw("order_products.*,products.vendor_id,products.product_slug,products.product_title,rating,review_text,product_images.image_url,product_attr_combination.combination_title")
                ->join("products", "products.id", "order_products.product_id")
                ->join("product_attr_combination", "product_attr_combination.id", "order_products.product_combination_id")
                ->leftjoin("product_images","product_images.product_id","order_products.product_id")
                ->leftjoin("product_review", "product_review.product_id", "order_products.product_id")
                ->groupBy("order_products.id")
                ->where("order_id", $order->id)
                ->where("product_vendor_id", $vendor_id)
                ->get();
                
        $total = $order_products->pluck('grand_total')->toArray();
        //$shipping_charges = $order_products->pluck('shipping_charges')->toArray();
        $order->subTotal = $subTotal = array_sum($total);  
        //$order->shippingTotal = $hipping = $order_products[0];
        //$order->grandTotal = $subTotal + $hipping; 
        $shipping_address = OrderAddress::select("order_addresses.*", "city", "state", "country","users.first_name", "users.last_name")
        ->leftjoin("users", "users.id", "order_addresses.customer_id")
        ->where("order_id", "=", $order->id)->where("address_type", "Shipping")->first();

          $data['vendor'] = $vendor;
        $data['order'] = $order;
        $data['order_products'] = $order_products;
        $data['shipping_address'] = $shipping_address;
             $view = view('app.order_invoice', $data);
       // $view->render();
        
        //echo $view;die;
        $invoiceName = $order->order_no . '_' . $vendor_id . '.pdf';

        $mpdf = new Mpdf(['format' => 'A4', [190, 236]]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($view);
        $mpdf->Output('doc/invoice/' . $invoiceName, \Mpdf\Output\Destination::FILE);
        $pathToFile = public_path('doc/invoice/' . $invoiceName);

               
                Mail::to($vendor->email)->send(new VendorOrderMail($order,$vendor));
              
               
            }
        }
    }
}
