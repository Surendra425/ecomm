<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 11:58 AM
 */

namespace App\Http\Controllers\Admin;


use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\State;
use App\VendorShippingDetail;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AdminCityController extends Controller
{
    private $validationRules = [

        'city_name' => 'required',
        'country_id' => 'required',
    ];

    /**
     * Display City details.
     *
     * @return json
     */
    public function index()
    {
        return view('admin.city.city_list');
    }

    /**
     * Search City.
     *
     * @return json
     */
    public function search(Request $request)
    {
        
        if($request->ajax()) {
            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $query = City::select('city_name','country_id','city.status','city.id','country.country_name')
                ->leftJoin('country', 'country.id', '=', 'city.country_id');
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterCity($request->search['value'], $query);

            $city = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($city));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $cities) {
                $cities->action = '<a href="'.url(route('city.edit', ['city' => $cities->id ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                    '<a href="'.url(route('profileCity', ['city' => $cities->id ])).'" title="View"><i class="la la-eye"></i></a>';
                //'<a class="delete-data" data-name="city" href="'.url(route('deleteCity', ['city' => $cities->id ])).'" title="Delete"><i class="la la-trash"></i></a>'

                $cities->status = ($cities->status === 'Active') ? '<a href="'.url(route('changeCityStatus', ['city' => $cities->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="'.url(route('changeCityStatus', ['city' => $cities->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }

    }
    /**
     * Filter City listing.
     *
     * @param $search
     * @return $query
     */
    private function filterCity($search, $query)
    {
        $query->where('country_name', 'like', '%'.$search.'%')
            ->orWhere('city.id', 'like', '%'.$search.'%')
            ->orWhere('city.status', 'like', '%'.$search.'%')
            ->orWhere('city_name', 'like', '%'.$search.'%');
    }

    /**
     * Display create city page.
     *
     * @return json
     */
    public function create()
    {
        $country = Country::all()->where('status','Active');
        return view('admin.city.city_create',
            ['country' =>$country ]);
    }
    /**
     * Save the City.
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        //dd($request->all());

        $city = new City();
        $city->fill($request->all());
        $city->city_name = ucfirst($request->city_name);

        if ($city->save())
        {
            $vendorShippingDetails = VendorShippingDetail::where([
                'country_id' => $request->country_id
            ])->whereRaw('city_id is null and city_name is null')
                ->selectRaw('`vendor_id`,`country_id`,`country_name`,`charge`,`from`,`to`,`time`')
                ->get();

            if($vendorShippingDetails->isNotEmpty())
            {
                $shippingData = [];
                foreach ($vendorShippingDetails as $key => $vendor)
                {
                    $shippingData[$key] = [
                        "vendor_id" => $vendor->vendor_id,
                        "city_id" => $city->id,
                        "city_name" => $city->city_name,
                        "country_id" => $vendor->country_id,
                        "country_name" => $vendor->country_name,
                        "charge" => $vendor->charge,
                        "from" => $vendor->from,
                        "to" => $vendor->to,
                        "time" => $vendor->time,
                    ];
                }
                VendorShippingDetail::insert($shippingData);
            }

            return redirect(route('city.index'))->with('success',trans('messages.city.added'));
        }

        return redirect(route('city.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the country.
     *
     * @param City $city
     * @return json
     */
    public function changeStatus(City $city)
    {
        // echo "<pre>";print_r($vendor);die;
        if($city->status == 'Active'){
            $city->status ='Inactive';
        }else{
            $city->status ='Active';
        }

        if($city->save()) {

            return redirect(route('city.index'))->with('success', trans('messages.city.change_status'));
        }

        return redirect(route('city.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show state view page.
     *
     * @param City $city
     * @return json
     */
    public function profile(City $city)
    {
       $country = Country::where('id', $city->country_id)->first();
         //echo $country_name->country_name;die;
        return view('admin.city.profile', [
            'city' => $city,
            'country' => $country->country_name
        ]);
    }

    /**
     * Show City edit page.
     *
     * @param City $city
     * @return json
     */
    public function edit(City $city)
    {
        //  echo "<pre>";print_r($city);die;
        $country = Country::all()->where('status','Active');
        return view('admin.city.city_create', [
            'city' =>$city,
            'country' =>$country,
        ]);
    }

    /**
     * Update the city.
     *
     * @param Request $request
     * @param int $city
     * @return json
     */
    public function update(Request $request, City $city)
    {
        //echo $store->id;die;
        //echo "hi";die;
        // Validate fields
       //dd($request->all());
        //$this->validationRules['city_name'] = 'required|unique:city,city_name,'.$city->id;
        $this->validate($request, $this->validationRules);

        $city->fill($request->all());
        $city->city_name = ucfirst($request->city_name);
        if ($city->save()) {

            return redirect(route('city.index'))->with('success', trans('messages.city.updated'));
        }

        return redirect(route('city.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete city by unique identifier.
     *
     * @return json
     */
    public function destroy(City $city)
    {
        if($city->delete()) {

            return redirect(route('city.index'))->with('success', trans('messages.city.deleted'));
        }

        return redirect(route('city.index'))->with('error', trans('messages.error'));
    }
}