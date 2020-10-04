<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart;
use App\ProductCart;
use App\ProductAttrCombination;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
    /**
     * Gets carts products of the user.
     *
     * @param Request $request
     * @return json
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'device_id' => 'required_if:user_id,""',
        ]);

        $user = session()->get('authUser');

        $cartProducts = $this->getCartProducts($request, $user);
                       
        if(!$cartProducts->isEmpty())
        {
            foreach ($cartProducts as $key => $cartProduct)
            {
      
               /*  $cartProducts[$key]->options = $cartProduct->product->options; */
                $cartProducts[$key]->image = !empty($cartProduct->product->image) ? $cartProduct->product->image->file_name : '';
                unset($cartProducts[$key]->product);
            }
                
            return $this->toJson(['cartProducts' => $cartProducts]);
        }

        return $this->toJson(null, trans('api.carts.empty'), 0);
    }
    
    /**
     * Add product into cart.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'device_id' => 'required_if:user_id,""',
            'option_id' => 'required|numeric',
            'qty' => 'numeric',
        ]);
     
        $user = session()->get('authUser');
        
        $option = ProductAttrCombination::find($request->option_id);

        if(empty($option)){
            return $this->toJson(null, trans('api.carts.option_not_available'), 0);
        }

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
                'device_id' => $request->device_id,
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
            $qty = $productCart->quantity + $requestQty;
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
            $productCart->device_id = empty($user) ? $request->device_id : null;
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
                $where = !empty($user) ? ['user_id' => $user->id] : [ 'device_id' => $request->device_id];
                
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
     * Update product in cart.
     *
     * @param Request $request
     * @return json
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'cart_id' => 'required|numeric',
            'device_id' => 'required_if:user_id,""',
            'option_id' => 'required|numeric',
            'qty' => 'required|numeric|min:1',
        ]);

        $user = session()->get('authUser');
        
        $productCart = $this->getCartProduct($request, $user);
        
        if(!empty($productCart))
        {
            $productCart->product_combination_id = $request->option_id;
            $productCart->quantity = $request->qty;
            $productCart->note = $request->note;
            if($productCart->save())
            {
                return $this->toJson(null, trans('api.carts.note.success'), 1);
            }
            
            return $this->toJson(null, trans('api.carts.note.error'), 0);
            
        }
        
        return $this->toJson(null, trans('api.carts.product.not_available'), 0);
    }
    
    /**
     * Delete product from cart.
     *
     * @param Request $request
     * @return json
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'cart_ids' => 'required',
            'device_id' => 'required_if:user_id,""',
        ]);

        $user = session()->get('authUser');
        
        $where = [
            'device_id' => $request->device_id,
        ];
        
        if(!empty($user))
        {
            $where = [
                'user_id' => $user->id,
            ];
        }

        $cartIds = explode(',', $request->cart_ids);
        
        if(!empty($cartIds))
        {
            Log::debug($cartIds);
            $cartProducts = ProductCart::whereIn('id', $cartIds);
            
            if($cartProducts->count() > 0)
            {
                ProductCart::whereIn('id', $cartIds)->delete();
                
                return $this->toJson(null, trans('api.carts.delete.success'), 1);
            }
            
            return $this->toJson(null, trans('api.carts.delete.error'), 0);       
            
        }
        
        return $this->toJson(null, trans('api.carts.product.not_available'), 0);
    }
    
    /**
     * Get cart details.
     *
     * @param Request $request
     * @param $user
     * 
     * @return json
     */
    private function getCartProduct($request, $user)
    {
        $where = [
            'id' => $request->cart_id,
            'device_id' => $request->device_id,
        ];
        
        if(!empty($user))
        {
            unset($where['device_id']);
            $where['user_id'] = $user->id;
        }
        
        return ProductCart::where($where)->first();
    }
    
    /**
     * Get cart details.
     *
     * @param Request $request
     * @param $user
     *
     * @return json
     */
    public function getCartProducts($request, $user)
    {
        $where = [
            'device_id' => $request->device_id
        ];
        
        // Check user login or not.
        if(!empty($user))
        {
            $where = [
                'user_id' => $request->user_id
            ];
        }
        
        return ProductCart::selectRaw('product_cart.id,stores.vendor_id,product_cart.user_id,product_cart.device_id, product_cart.product_combination_id AS option_id,
                                    product_cart.product_id,product_cart.quantity, stores.store_name,stores.id AS store_id,
                                    products.product_title,product_attr_combination.combination_title,product_attr_combination.rate AS price, product_attr_combination.quantity as available_qty, product_cart.note')
                                    ->join('products', 'products.id', '=', 'product_cart.product_id')
                                    ->join('stores', 'stores.vendor_id', '=', 'products.vendor_id')
                                    ->join('product_attr_combination', 'product_attr_combination.id', '=', 'product_cart.product_combination_id')
                                    ->where($where)
                                    ->with([
                                        'product' => function($query) {
                                        return $query->select('id','product_title','product_slug');
                                        },
                                        /* 'options' => function($query) {
                                           return $query->select('id','product_id', 'combination_title', 'quantity', 'rate');
                                         }, */
                                        'product.image' => function($query) {
                                        return $query->select('product_id', 'image_url AS file_name')->where("status", 'Active');
                                        },
                                        ])
                                        ->get();
    }
}