<?php
/**
 * Created by PhpStorm.
 * User: Angat
 * Date: 2018-02-23
 * Time: 3:25 PM
 */

namespace App\Http\Controllers;


use App\City;
use App\Country;
use App\Helpers\NameHelper;
use App\State;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class UserAddressController extends Controller
{
    private $validationRules = [
        'full_name' => 'required',
        'country' => 'required',
        'city' => 'required',
        'street' => 'required',
        'building' => 'required',
        'block' => 'required',
        'mobile' => 'required',
    ];

    /**
     * Display create address page.
     *
     * @return json
     * Developed by Nikita
     */
    public function create(Request $request){
        
        $country = Country::where("status", "Active")->get();
        $data['country'] = $country;
        $data['store_slug'] = $request->store_slug;
        return view('app.create_address',$data);
    }
    /**
     * Display user address details.
     * UserAddress $address
     * Developed by Nikita
     */
    public function view(Request $request,UserAddress $address,$store_slug=''){
        $country = Country::where("status", "Active")->get();
        $data['address'] = $address;
        $data['city'] = City::where('id',$address->city_id)->first();
        $data['country'] = $country;
        $data['store_slug'] = $store_slug;
        //dd($data);die;
        return view('app.create_address',$data);
    }
    public function userAddressSelect(Request $request){
        //$url = Request::segment(1);die;

        $customer = Auth::guard('customer')->user();
        UserAddress::where('user_id' ,$customer->id)->update(['is_selected' => 'No']);

        $address = DB::table('user_addresses')
            ->where('id', $request->address_id)
            ->update(['is_selected' => 'Yes']);

        if($address) {
            $data['success'] = 'Address selected successfully.';
        }else{
            $data['error'] = trans('messages.error');
        }
        echo json_encode($data);
    }
    /**
     * store user address details.
     *
     * @return json
     * Developed by Nikita
     */
    public function store(Request $request){
        //dd($request->all());die;
        $this->validate($request, $this->validationRules);
        $customer = Auth::guard('customer')->user();
        UserAddress::where('user_id' ,$customer->id)->update(['is_selected' => 'No']);
        $customer_address = new UserAddress();
        $customer_address->fill($request->all());
        $customer_address->full_name = $request->full_name;
        $customer_address->user_id = $customer->id;
        $customer_address->city_id = $request->city;
        $customer_address->country_id = $request->country;
        $city_name = NameHelper::getNameBySingleId('city','city_name','id',$request->city);
        $country_name = NameHelper::getNameBySingleId('country','country_name','id',$request->country);
        $customer_address->city = $city_name;
        $customer_address->country = $country_name;
        $customer_address->is_selected = 'Yes';

        if ($customer_address->save()) {
            if(!empty($request->store_slug)){
                return redirect(route('sellerDetail',['storeSlug'=>$request->store_slug]))->with('success',trans('messages.userAddress.added'));
            }else{
                return redirect('/home')->with('success',trans('messages.userAddress.added'));
            }
           // return redirect(route('userAddressView',['address'=>$customer_address->id]))->with('success',trans('messages.userAddress.added'));
            //return redirect(route('userAddressView',['address'=>$customer_address->id]))->with('success',trans('messages.userAddress.added'));
        }
        return redirect(route('address.create'))->with('error', trans('messages.error'));
    }
    public function update(Request $request, UserAddress $address){
        //echo $request->store_slug;die;
        $customer = Auth::guard('customer')->user();
        $this->validate($request, $this->validationRules);
        UserAddress::where('user_id' ,$customer->id)->where('id','!=',$address->id)->update(['is_selected' => 'No']);
        $address->fill($request->all());
        $address->full_name = $request->full_name;
        $address->user_id = $customer->id;
        $address->city_id = $request->city;
        $address->country_id = $request->country;
        $city_name = NameHelper::getNameBySingleId('city','city_name','id',$request->city);
        $country_name = NameHelper::getNameBySingleId('country','country_name','id',$request->country);
        $address->city = $city_name;
        $address->country = $country_name;
        $address->is_selected = 'Yes';

        if ($address->save()) {
            if(!empty($request->store_slug)){
                return redirect(route('sellerDetail',['storeSlug'=>$request->store_slug]))->with('success',trans('messages.userAddress.updated'));
            }else{
                return redirect('/home')->with('success',trans('messages.userAddress.added'));
            }
           //return redirect(route('userAddressView',['address'=>$address->id]))->with('success',trans('messages.userAddress.updated'));
        }
        return redirect(route('address.create'))->with('error', trans('messages.error'));
    }

    public function delete(UserAddress $address)
    {
        dd($address);
        if($address->delete()) {

            return redirect(route('checkout.index'))->with('success', trans('messages.userAddress.deleted'));
        }

        return redirect(route('checkout.index'))->with('error', trans('messages.error'));
    }
}