<?php

/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 19/1/18
 * Time: 3:59 PM
 */

namespace App\Http\Controllers;

use App\Country;
use App\Shipping;
use App\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\City;
use App\State;
use App\Helpers\NameHelper;

class ShippingController extends Controller
{

    private $validationRules = [
        'shipping_class' => 'required',
        'vendor_id' => 'required',
    ];

    /**
     * Display shipping class details.
     *
     * @return json
     */
    public function index()
    {
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        return view('shipping.shipping_list', ['loginUser' => $loginUser]);
    }

    /**
     * Display create shipping class page.
     *
     * @return json
     */
    public function create()
    {
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        $country = Country::all();
        $vendor = User::where('type', 'vendor')->get();
        return view('shipping.shipping_create', [
            'loginUser' => $loginUser,
            'country' => $country,
            'vendor' => $vendor
        ]);
    }

    /**
     * Search store.
     *
     * @return json
     */
    public function search(Request $request)
    {
        if ($request->ajax())
        {
            $url = request()->segment(1);
            $loginUser = Auth::guard($url)->user();
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $query = Shipping::select('shipping.id', 'shipping.shipping_class', 'shipping.country_name', 'first_name', 'last_name',  'shipping.status', 'shipping.city_name', 'shipping_charge', 'delivery_day_1', 'delivery_day_2'
                    )->leftjoin('users', 'users.id', '=', 'shipping.vendor_id');
            if ($loginUser->type == 'vendor')
            {
                $query->where('shipping.vendor_id', $loginUser->id);
            }
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterShippingClass($request->search['value'], $query);

            $shipping = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);

            $data = json_decode(json_encode($shipping));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $shippings)
            {
                $shippings->vendor_name = $shippings->first_name . ' ' . $shippings->last_name;
                $shippings->action = '<a href="' . url(route('shippingClassEdit', ['shipping' => $shippings->id])) . '" title="Edit"><i class="la la-edit"></i></a>' .
                        '<a href="' . url(route('shippingClassProfile', ['shipping' => $shippings->id])) . '" title="View"><i class="la la-eye"></i></a>' .
                        '<a class="delete-data" data-name="shipping class" href="' . url(route('shippingClassDelete', ['shipping' => $shippings->id])) . '" title="Delete"><i class="la la-trash"></i></a>';

                $shippings->status = ($shippings->status === 'Active') ? '<a href="' . url(route('changeShippingClass', ['shipping' => $shippings->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>' : '<a href="' . url(route('changeShippingClass', ['shipping' => $shippings->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }

    /**
     * Filter Shipping Class listing.
     *
     * @param $search
     * @return $query
     */
    private function filterShippingClass($search, $query)
    {
        $query->where('shipping_class', 'like', '%' . $search . '%')
                ->orWhere('shipping_charge', 'like', '%' . $search . '%')
                ->orWhere('delivery_day_1', 'like', '%' . $search . '%')
                ->orWhere('delivery_day_2', 'like', '%' . $search . '%')
                ->orWhere('shipping.id', 'like', '%' . $search . '%')
                ->orWhere('shipping.status', 'like', '%' . $search . '%')
                ->orWhere('country_name', 'like', '%' . $search . '%')
                ->orWhere('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('city_name', 'like', '%' . $search . '%');
    }

    /**
     * Save the Shipping Class.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);

        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();


        foreach ($request->shipping as $list)
        {
            $shippingClass = new Shipping();
            // print_r($list);
            $shippingClass->fill($request->all());

            $shippingClass->status = $request->status;
            $shippingClass->added_by_user_id = $loginUser->id;

            if (isset($list['city_id']) && ! empty($list['city_id']))
            {
                $shippingClass->city_id = implode(',', $list['city_id']);
                ;
                $city = NameHelper::getNameById('city', 'city_name', 'id', $list['city_id']);
                $shippingClass->city_name = implode(',', $city);
            }
            else
            {
                $shippingClass->city_id = NULL;
                $shippingClass->city_name = $list['city'];
            }
            $shippingClass->country_id = $list['country_id'];
            $country = $this->getCountryNameById('country', 'country_name', 'id', $list['country_id']);
            $shippingClass->country_name = $country;

            $shippingClass->save();
        }
        return redirect(route('shipping-class.index'))->with('success', trans('messages.shippingClass.added'));

        // return redirect(route('shipping-class.index'))->with('error', trans('messages.error'));
    }

    /* get country name by id */

    public function getCountryNameById($table, $field, $columnName, $columnValue)
    {

        $data = DB::table($table)
                ->select($field)
                ->where($columnName, "=", $columnValue)
                ->get();
        return $data[0]->{$field};
    }

    /**
     * Delete Shipping by unique identifier.
     *
     * @return json
     */
    public function destroy(Shipping $shipping)
    {


        if ($shipping->delete())
        {

            return redirect(route('shipping-class.index'))->with('success', trans('messages.shippingClass.deleted'));
        }

        return redirect(route('shipping-class.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the shipping Class.
     *
     * @param Shipping $shipping
     * @return json
     */
    public function changeStatus(Shipping $shipping)
    {
        if ($shipping->status == 'Active')
        {
            $shipping->status = 'Inactive';
        }
        else
        {
            $shipping->status = 'Active';
        }
        if ($shipping->save())
        {

            return redirect(route('shipping-class.index'))->with('success', trans('messages.shippingClass.change_status'));
        }

        return redirect(route('shipping-class.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show Shipping Class view page.
     *
     * @param Shipping $shipping
     * @return json
     */
    public function profile(Shipping $shipping)
    {
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        $vendor = User::select('first_name', 'last_name')->where('id', $shipping->vendor_id)->first();
        $shipping->vendor_name = $vendor->first_name . ' ' . $vendor->last_name;
        return view('shipping.profile', [
            'loginUser' => $loginUser->type,
            'shipping' => $shipping
        ]);
    }

    /**
     * Show Shipping Class edit page.
     *
     * @param Shipping $shipping
     * @return json
     */
    public function edit(Shipping $shipping)
    {
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        $country = Country::all();
        $vendor = User::where('type', 'vendor')->get();
        $cityID = array ();
        $city = City::select('city.id','city_name')
            ->leftjoin('state', 'state.id', '=', 'city.state_id')
            ->where('country_id',$shipping->country_id)
            ->where('city.status','Active')
            ->where('state.status','Active')
            ->get();
        //echo "<pre>";print_r($shipping);die;
        if ( ! empty($shipping->city_id))
        {
            $cityId = explode(',', $shipping->city_id);
            $cityName = explode(',', $shipping->city_name);
            foreach ($cityId as $key => $val)
            { // Loop though one array
                $val2 = $cityName[$key]; // Get the values from the other array
                $cityID[$key] = $val;
               // $cityID[$key]['city'] = $val2;
            }
        }
        $shipping->city = $cityID;
        return view('shipping.shipping_edit', [
            'country' => $country,
            'cities' => $city,
            'vendor' => $vendor,
            'shipping' => $shipping,
            'loginUser' => $loginUser
        ]);
    }

    /**
     * Update the Shipping Class.
     *
     * @param Request $request
     * @param int $vendorId
     * @return json
     */
    public function update(Request $request, Shipping $shipping)
    {
        //dd($request->all());die;
        $this->validate($request, $this->validationRules);
        //$this->validate($request, $this->validationRules);
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();

        $shipping->fill($request->all());

        $shipping->added_by_user_id = $loginUser->id;

        if (isset($request['city_id']) && ! empty($request['city_id']))
        {
            $shipping->city_id = implode(',', $request['city_id']);
            ;
            $city = NameHelper::getNameById('city', 'city_name', 'id', $request['city_id']);
            $shipping->city_name = implode(',', $city);
        }
        else
        {
            $shipping->city_id = NULL;
            $shipping->city_name = $request['city'];
        }
        $shipping->country_id = $request['country_id'];
        $country = $this->getCountryNameById('country', 'country_name', 'id', $request['country_id']);
        $shipping->country_name = $country;
        
        if ($shipping->save())
        {
            return redirect(route('shipping-class.index'))->with('success', trans('messages.shippingClass.updated'));
        }

        return redirect(route('shipping-class.index'))->with('error', trans('messages.error'));
    }

}
