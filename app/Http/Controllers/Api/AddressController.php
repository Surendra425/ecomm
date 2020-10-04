<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserAddress;

class AddressController extends Controller
{
    private $validationRules = [
        'country_id' => 'required',
        'area_id' => 'required',
        'title' => 'required',
        'block' => 'required',
        'street' => 'required',
        'building' => 'required',
        'mobile' => 'required',
    ];
    
    private $addressFields = 'id,user_id,full_name AS title,block,street,avenue,building,floor,apartment,
                                                 city_id AS area_id,country_id,floor,apartment,mobile,landline,
                                                 IF(is_selected != "Yes", false, true) AS is_default';
    
    /**
     * Gets address list of the user.
     *
     * @return json
     */
    public function index(Request $request)
    {
        $user = session()->get('authUser');
        
        $userAddresses = UserAddress::selectRaw($this->addressFields) 
                                    ->with([
                                        'country' => function ($query) {
                                           return $query->select('id', 'country_name');
                                        },
                                        'area' => function ($query) {
                                          return $query->select('id', 'city_name');
                                        },
                                    ])
                                    ->where('user_id', $request->user_id)
                                    ->get();
        
        if(!$userAddresses->isEmpty())
        {
            return $this->toJson(['addresses' => $userAddresses]);
        }
        
        return $this->toJson(null, trans('api.addresses.not_available'), 0);
    }
    
    /**
     * Add address of the user.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $user = session()->get('authUser');
        $this->validate($request, $this->validationRules); 

        $address = new UserAddress();
        $address->fill($request->all());
        $address->user_id = $request->user_id;
        $address->full_name = $request->title;
        $address->city_id = $request->area_id;
        $address->country_id = $request->country_id;

        $address->save();
        $address->load('cityr','countryr');

        $address->city = $address->cityr->city_name;
        $address->country = $address->countryr->country_name;
        
        if($request->is_set_default == 1)
        {
            UserAddress::where('user_id', $user->id)
                       ->update(['is_selected' => 'No']);
            
            $address->is_selected = 'Yes';
        }

        if($address->save())
        {
            return $this->toJson(null, trans('api.addresses.add.success'), 1);
        }

        return $this->toJson(null, trans('api.addresses.add.error'), 0);
    }
    
    /**
     * Update address list of the user.
     *
     * @param Request $request
     * @return json
     */
    public function update(Request $request)
    {
        $user = session()->get('authUser');
        $this->validationRules['address_id'] = 'required';
        $this->validate($request, $this->validationRules);
        
        $address = UserAddress::find($request->address_id);
        
        if(!empty($address) && $address->user_id == $user->id)
        {
            $address->fill($request->all());

            $address->user_id = $request->user_id;
            $address->full_name = $request->title;
            $address->city_id = $request->area_id;
            $address->country_id = $request->country_id;
            $address->save();
            $address->load('cityr','countryr');

            $address->city = $address->cityr->city_name;
            $address->country = $address->countryr->country_name;


            if($request->is_set_default == 1 && $address->is_selected != 'Yes')
            {
                UserAddress::where('user_id', $user->id)
                           ->update(['is_selected' => 'No']);

                $address->is_selected = 'Yes';
            }
            else if($request->is_set_default == 0)
            {
                $address->is_selected = 'No';
            }

            if($address->save())
            {
                return $this->toJson(null, trans('api.addresses.update.success'), 1);
            }

            return $this->toJson(null, trans('api.address.update.error'), 0);
        }

        return $this->toJson(null, trans('api.address.not_available'), 0);
    }
    
    /**
     * Delete address list of the user.
     *
     * @param Request $request
     * @return json
     */
    public function delete(Request $request)
    {
        $user = session()->get('authUser');
        $validation['address_id'] = 'required';
        $this->validate($request, $validation);
        
        $address = UserAddress::find($request->address_id);

        if(!empty($address) && $address->user_id == $user->id)
        {
            if($address->is_selected == 'No')
            {
                
                if($address->delete())
                {
                    return $this->toJson(null, trans('api.addresses.delete.success'), 1);
                }
                
                return $this->toJson(null, trans('api.addresses.delete.error'), 0);
            }
           
           return $this->toJson(null, trans('api.address.delete.default'), 0);
        }

        return $this->toJson(null, trans('api.address.not_available'), 0);
    }
    
    /**
     * Set default address list of the user.
     *
     * @param Request $request
     * @return json
     */
    public function setDefault(Request $request)
    {
        $user = session()->get('authUser');
        $validation['address_id'] = 'required';

        $this->validate($request, $validation);
        
        $address = UserAddress::find($request->address_id);
        
        if(!empty($address) && $address->user_id == $user->id)
        {
            $userAddresses = UserAddress::where('user_id', $user->id)
                                        ->update(['is_selected' => 'No']);

           $address->is_selected = 'Yes';

           if($address->save())
           {
               return $this->toJson(null, trans('api.addresses.set_default.success'), 1);
           }
            
           return $this->toJson(null, trans('api.address.set_default.error'), 0);
        }
        
        return $this->toJson(null, trans('api.address.not_available'), 0);
    }
}