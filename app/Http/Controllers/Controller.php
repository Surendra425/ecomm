<?php

namespace App\Http\Controllers;

use App\Helpers\QueryHelper;
use App\ProductCategory;
use App\StaticPages;
use App\User;
use App\UserAddress;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\View;
use Illuminate\Support\Facades\Auth;
use App\ProductCart;
use App\Customer;
use Illuminate\Support\Facades\Cookie;
use App\Admin;
use App\Vendor;

class Controller extends BaseController
{

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    private $customer;
    private $signed_in;
    private $admin;
    private $vendor;
    
    protected $perPage = 24;

    public function __construct(Request $request)
    {
       $this->middleware(function ($request, $next) {

            $totalCartItem = 0;
            $userAddress = collect();

            $browserId = \Cookie::get('browserId');
            $customer = Auth::guard('customer')->user();
            
            if(empty($customer)){
                if(!empty($browserId)){
                    $totalCartItem = QueryHelper::getCartProductsCount();
                }
            }else{
                $totalCartItem = QueryHelper::getCartProductsCount();
            }

            view()->share('totalCartItem', $totalCartItem);
            $this->signed_in = Auth::check();
            $this->admin = Auth::guard('admin')->user();
            $this->loginVendor = Auth::guard('vendor')->user();
            
            if ( ! empty($this->admin))
            {
                $admins = Admin::where('id',$this->admin->id)->first();
                if(empty($admins)){
                    $this->guard()->logout();
                    
                    $request->session()->invalidate();
                    
                    return redirect('/admin');
                }
            }
            if ( ! empty($this->loginVendor))
            {
                $vendors = Vendor::where('id',$this->loginVendor->id)->first();
                if(empty($vendors)){
                    $this->guard()->logout();
                    
                    $request->session()->invalidate();
                    
                    return redirect(route('vendorLogin'));
                }
            }
            
            if(!empty($customer)){
                $userAddress = UserAddress::where('user_id',$customer->id)->get();
            }
            
            $this->content = StaticPages::get();
            
            $categories = ProductCategory::where('parent_category_id', NULL)
                ->where('status', "=","Active")->with('subCategories')
                ->orderBy('order_no', 'asc')->get();
            view()->share('mainCategory', $categories);

            view()->share('content', $this->content);
            view()->share('userAddress', $userAddress);
            view()->share('customer', $customer);
            view()->share('signed_in', $this->signed_in);
            view()->share('loginVendor', $this->loginVendor);
            view()->share('admin', $this->admin);
            return $next($request);
        });

        /*$this->middleware(function ($request, $next)
        {

            $this->admin = Auth::guard('admin')->user();
            $this->loginVendor = Auth::guard('vendor')->user();
            $this->signed_in = Auth::check();
            $this->customer = Auth::guard('customer')->user();
            $totalCartItem = 0;
            $userAddress = '';
            if ( ! empty($this->admin))
            {
                $admins = Admin::where('id',$this->admin->id)->first();
                if(empty($admins)){
                    $this->guard()->logout();
                    
                    $request->session()->invalidate();
                    
                    return redirect('/admin');
                }              
            }
            if ( ! empty($this->loginVendor))
            {
                $vendors = Vendor::where('id',$this->loginVendor->id)->first();
                if(empty($vendors)){
                    $this->guard()->logout();
                    
                    $request->session()->invalidate();
                    
                    return redirect(route('vendorLogin'));
                }
             }
            if ( ! empty($this->customer))
            {
                $user = Customer::where('id',$this->customer->id)->first();
                if(empty($user)){
                    $this->guard()->logout();
                    $cookie = \Cookie::forget('cookie.cartItem');
                    $request->session()->invalidate();
                    
                    return redirect('/home')->withCookie($cookie);
                }
                $totalCartItem = ProductCart::where("user_id", "=", $this->customer->id)->get()->count();
                $userAddress = UserAddress::where('user_id',$this->customer->id)->get();
            }else{
                $cart = unserialize($request->cookie('cookie_cartItem'));
                if(!is_array($cart)){
                    $cart = json_decode($cart);
                }
                if(!empty($cart)){
                    $totalCartItem = count($cart);
                }
               // dd($cart);die;
            }
            
            $this->content = StaticPages::get();
            view()->share('content', $this->content);
            view()->share('signed_in', $this->signed_in);
            view()->share('loginVendor', $this->loginVendor);
            view()->share('admin', $this->admin);
            view()->share('customer', $this->customer);
            view()->share('totalCartItem', $totalCartItem);
            view()->share('userAddress', $userAddress);
            $categories = ProductCategory::where('parent_category_id', NULL)->where('status', "=","Active")->with('subCategories')->orderBy('order_no', 'asc')->get();
            view()->share('mainCategory', $categories);

            return $next($request);
        });*/
    }

    /**
     * Get cart count
     *
     * @param Request $request
     * @return int|mixed
     */
    public function getCartCount(Request $request){

        if($request->ajax()){

            $browserId = \Cookie::get('browserId');
            $customer = Auth::guard('customer')->user();
            $totalCartItem = 0;
            if(empty($customer)){
                if(!empty($browserId)){
                    $totalCartItem = QueryHelper::getCartProductsCount();
                }
            }else{
                $totalCartItem = QueryHelper::getCartProductsCount();
            }

            return $totalCartItem;
        }
    }
    
    /**
     * send response to user.
     *
     * @return json
     */
    public function toJson($result = [], $message = '', $status = 1)
    {
        return response()->json([
            'status' => $status,
            'result' => !empty($result) ? $result : new \stdClass(),
            'message' => $message,
        ]);
    }
}
