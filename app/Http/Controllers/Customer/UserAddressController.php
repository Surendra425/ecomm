<?php

namespace App\Http\Controllers\Customer;

use App\Country;
use App\UserAddress;
use App\Helpers\NameHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserAddressController extends Controller
{

    /**
     * validation rules for create / update address.
     *
     * @var string
     */
    private $validationRules = [
        'full_name' => 'required',
        'country_id' => 'required|numeric',
        'country' => 'required',
        'city_id' => 'required|numeric',
        'city' => 'required',
        'block' => 'required',
        'street' => 'required',
        'building' => 'required',
        'mobile' => 'required|max:17|min:7',
    ];
    
    /**
     * Show the form of create address.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $country = Country::active()->get();
        $data['country'] = $country;
        $data['store_slug'] = $request->store_slug;

        return view('front.address.create_address',$data);
    }

    /**
     * Store a newly created user address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request,$this->validationRules);

        $customer = Auth::guard('customer')->user();

        $is_selected = 'Yes';

        $userAddress = UserAddress::where('user_id' ,$customer->id);
        if($userAddress){
            $is_selected = 'No';
        }
        //dd($request->all());
        $customer_address = new UserAddress();
        $customer_address->fill($request->all());
        $customer_address->user_id = $customer->id;
        $city_name = $request->city;
        $country_name = $request->country;
        $customer_address->city = $city_name;
        $customer_address->country = $country_name;
        $customer_address->is_selected = $is_selected;

        if ($customer_address->save()) {
            if(!empty($request->store_slug)){
                return redirect(route('sellerDetail',['storeSlug'=>$request->store_slug]))->with('success',trans('messages.userAddress.added'));
            }else{
                return redirect('/home')->with('success',trans('messages.userAddress.added'));
            }
        }
        return redirect(route('address.create'))->with('error', trans('messages.userAddress.error'));

    }

    /**
     * set default customer address
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function userAddressSelect(Request $request){

        $customerId = Auth::guard('customer')->user()->id;

        UserAddress::where('user_id',$customerId)->update(['is_selected'=>'No']);

        UserAddress::where(['user_id'=>$customerId,'id'=>$request->get('address_id')])->update(['is_selected'=>'Yes']);

        return response()->json(['status'=>1,'msg' => trans('messages.userAddress.updated')]);
    }

    /**
     * delete customer address
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function delete(Request $request)
    {

        $address = UserAddress::find($request->get('address_id'));

        if($address->is_selected == 'Yes'){
            return response()->json(['status'=>0,'msg' => trans('messages.userAddress.default')]);
        }
        if($address->delete()) {

            return response()->json(['status'=>1,'msg' => trans('messages.userAddress.deleted')]);
        }
        return response()->json(['status'=>0,'msg' => trans('messages.userAddress.delete_error')]);
    }
}
