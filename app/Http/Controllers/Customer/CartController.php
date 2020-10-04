<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\QueryHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\ProductAttrCombination;
use App\ProductCart;
use App\Helpers\ApiHelper;

/*
 |--------------------------------------------------------------------------
 | Cart Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles add to cart, carts lists, remove from carts.
 */
class CartController extends Controller
{
    /**
     * Gets carts products of the user.
     *
     * @param Request $request
     * @return json
     */
    
    public function showCarts(Request $request)
    {
        $cartProducts = collect();
        $browserId = \Cookie::get('browserId');

        if(!(Auth::guard('customer')->check())){
            if(!empty($browserId)){
                $cartProducts = QueryHelper::getCartProducts();
            }
        }else{
            $cartProducts = QueryHelper::getCartProducts();
        }
        
        $cartProducts = $cartProducts->groupBy('store_id');

        return view('front.user.my_cart', [
            'cartProducts' => $cartProducts,
        ]);
    }

    /**
     * Add product into cart.
     *
     * @param Request $request
     * @return json
     */
    public function addToCart(Request $request)
    {
        
        $this->validate($request, [
            'option_id' => 'required|numeric',
            'qty' => 'numeric',
        ]);
     
        $browserId = \Cookie::get('browserId');
        
        $user = Auth::guard('customer')->user();

        $option = ProductAttrCombination::find($request->option_id);

        $where = [];

        if(!empty($user))
        {
            $where = [
                'user_id' => $user->id,
            ];
        }
        else
        {
            $where = [
                'device_id' => $browserId,
            ];
        }

        $where['product_id'] = $option->product_id;
        $where['product_combination_id'] = $option->id;

        $productCart = ProductCart::where($where)->first();
        
        $result['status'] = 1;
        $qty = $request->qty;

        if(empty($productCart))
        {
            // check cart validation
            $result = ApiHelper::checkCartValidation($option, $request->qty, $user);

        }

        if(!empty($productCart))
        {
            $requestQty = !empty($request->qty) ? $request->qty : 1;
            $qty = !empty($request->cartId) ?  $request->qty : $productCart->quantity + $qty;

            //$qty = $productCart->quantity + $requestQty;
            if($request->has('is_update'))
            {
                $qty = $requestQty;
            }
           
            // Check qty is available or not
            if($option->quantity < $qty)
            {
                return $this->toJson([], trans('api.carts.qty_not_available'), 0);
            }
        }
        
        if($result['status'] == 1)
        {
            $productCart = !empty($productCart) ? $productCart : new ProductCart();
            
            $productCart->user_id = !empty($user) ? $user->id : 0;
            $productCart->device_id = empty($user) ? $browserId : null;
            $productCart->product_id = $option->product_id;
            $productCart->product_combination_id = $request->option_id;
            
            if(!empty($request->qty))
            {
                $productCart->quantity = $qty;
            }
            else 
            {
                
                $productCart->quantity = !empty($productCart->quantity) ? ++$productCart->quantity : 1;
                
            }
            
            
            $productCart->rate = $option->rate;

            if($productCart->save())
            {
                $where = !empty($user) ? ['user_id' => $user->id] : [ 'device_id' => $browserId];
                
                $cartCount = ProductCart::where($where)->count();
                
                return $this->toJson([
                    'cart_count' => $cartCount,
                ], trans('api.carts.add.success'), 1);
            }

            return $this->toJson(null, trans('api.carts.add.error'), 0);
        }
        
        return $this->toJson(null, $result['message'], 0);
    }
    
    
    

    /**
     * Remove product from cart.
     *
     * @param Request $request
     * @return json
     */
    public function removeProductFromCart(Request $request)
    {

        $this->validate($request, [
            'cart_id' => 'required'
        ]);

        $browserId = \Cookie::get('browserId');
        
        $user = Auth::guard('customer')->user();
        
        $where = empty($user) ? ['device_id' => $browserId] : ['user_id' => $user->id];

        $cartProducts = ProductCart::where('id', $request->cart_id)->where($where);

        if($cartProducts->count() > 0)
        {
            $cartProducts->delete();

            return $this->toJson(null, trans('api.carts.delete.success'), 1);
        }

        return $this->toJson(null, trans('api.carts.delete.error'), 0);
    }
}