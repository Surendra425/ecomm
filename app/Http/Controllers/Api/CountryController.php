<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Country;
use App\City;

class CountryController extends Controller
{
    private $validationRules = [
        'country_id' => 'required|numeric',
    ];

    /*
      |--------------------------------------------------------------------------
      | Country Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles country list.
     */

    /**
     * Gets country list.
     *
     * @return json
     */
    public function index(Request $request)
    {
        $colName = ($request->isAr) ? 'ifnull(country_name_ar,country_name) as country_name' : 'country_name';

        $countries = Country::with('cities')
                            ->selectRaw('id, '.$colName.', short_name')
                            ->where('status', 'Active')
                            ->get();
        
        
        if(!$countries->isEmpty())
        {
            $countryData = [];
            foreach($countries as $country)
            {
                if(!$country->cities->isEmpty())
                {
                    $countryData[] = [
                        'id' => $country['id'],
                        'country_name' => $country['country_name'],
                        'short_name' => $country['short_name'],
                    ];
                }
            }
            return $this->toJson(['countries' => $countryData]);
        }

        return $this->toJson([], trans('api.country.not_available'), 0);
    }   
    
    
    /**
     * Gets area list.
     *
     * @return json
     */
    public function getAreas(Request $request)
    {
        $this->validate($request, $this->validationRules);

        $colName = ($request->isAr) ? 'ifnull(city_name_ar,city_name) as city_name' : 'city_name';

        $areas = City::selectRaw('id, '.$colName)
        ->where([
            'country_id' => $request->country_id,
            'status' => 'Active',
        ])
        ->orderBy('city_name')
        ->get();

        if(!$areas->isEmpty())
        {
            return $this->toJson(['areas' => $areas]);
        }
        
        return $this->toJson([], trans('api.area.not_available'), 0);
    }   
}
