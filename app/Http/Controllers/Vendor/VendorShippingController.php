<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\PlanHelper;
use App\Helpers\ImageHelper;
use App\Vendor;
use App\Store;
use App\Country;
use App\City;
use App\VendorShippingDetail;
use App\Helpers\NameHelper;
use Illuminate\Support\Facades\DB;

class VendorShippingController extends Controller
{

    /*public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $data['Vendor'] = $vendor;
        return view('vendor.shipping.index', $data);
    }*/

    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $data = [];
        $countryIds = Country::where("status", "Active")->pluck("id")->toArray();
        $countrySelected = VendorShippingDetail::where("vendor_id", $vendor->id)->groupBy('country_id')->get();
        $countryCitySelected = VendorShippingDetail::where("vendor_id", $vendor->id)->where("city_id", "!=", Null)->get();
        $country = Country::where("status", "Active")->get();
        $area = City::where("status", "Active")
            ->get();
       // echo $vendor->id;
        //dd($countryCitySelected);die;
        $data['area'] = $area;
        $data['country'] = $country;
        $data['countryIds'] = $countryIds;
        $data['countrySelected'] = $countrySelected;
        $data['countryCitySelected'] = $countryCitySelected;

        return view('vendor.shipping.create', $data);
    }

    public function store(Request $request)
    {
        //dd($request->all());die;
        $vendor = Auth::guard('vendor')->user();
        //echo $vendor->id;die;
        $countrySelected = VendorShippingDetail::where("vendor_id", $vendor->id)->pluck("country_id")->toArray();
        DB::table('vendor_shipping_detail')->whereIn('country_id', $countrySelected)->where('vendor_id', $vendor->id)->delete();
        $ShippingData = [];
        $ShippingDetail = $request->checkCountry;
        $vendorShipping = new VendorShippingDetail();
       //dd($request->all());
        //echo "<pre>";
        if (!empty($ShippingDetail)) {
            if(!empty($request->checkCountry)){

                    foreach ($request->checkCountry as $key => $shipping) {
                        if (!empty($request->charge[$shipping]) && !empty($request->from[$shipping]) && $request->to[$shipping])
                        {
                            //echo "hi";
                                $ShippingData[] = array(
                                    "vendor_id" => $vendor->id,
                                    "city_id" => null,
                                    "city_name" => null,
                                    "country_id" => $shipping,
                                    "country_name" => $request->country_name[$shipping][0],
                                    "charge" => $request->charge[$shipping][0],
                                    "from" => $request->from[$shipping][0],
                                    "to" => $request->to[$shipping][0],
                                    "time" => $request->to_time[$shipping][0],
                                );

//print_r($request->checkCity[$shipping]);
                            if (!empty($request->checkCity[$shipping])) {
                               // dd($request->fromCity[$shipping]);
                                //echo "hi";
                                foreach ($request->checkCity[$shipping] as $keys => $area) {

                                    //echo $area;
                                    if (!empty($request->chargeCity[$shipping][$area]) && !empty($request->fromCity[$shipping][$area]) && $request->toCity[$shipping][$area]) {
                                        //echo "hello";
                                        $ShippingData[] = array(
                                            "vendor_id" => $vendor->id,
                                            "country_id" => $shipping,
                                            "country_name" => $request->country_name[$shipping][0],
                                            "city_id" => $area,
                                            "city_name" => $request->city_name[$shipping][$area][0],
                                            "charge" => $request->chargeCity[$shipping][$area][0],
                                            "from" => $request->fromCity[$shipping][$area][0],
                                            "to" => $request->toCity[$shipping][$area][0],
                                            "time" => $request->city_to_time[$shipping][$area][0],
                                        );
                                        //print_r($ShippingData1);
                                    }

                                }
                            }
                            //print_r($ShippingData);die;
                        } else {
                            //echo "hello";
                            if (!empty($request->checkCity[$shipping])) {
                                foreach ($request->checkCity[$shipping] as $keys => $area) {
                                    if (!empty($request->chargeCity[$shipping][$area]) && !empty($request->fromCity[$shipping][$area]) && $request->toCity[$shipping][$area]) {
                                        $ShippingData[] = array(
                                            "vendor_id" => $vendor->id,
                                            "country_id" => $shipping,
                                            "country_name" => $request->country_name[$shipping][0],
                                            "city_id" => $area,
                                            "city_name" => $request->city_name[$shipping][$area][0],
                                            "charge" => $request->chargeCity[$shipping][$area][0],
                                            "from" => $request->fromCity[$shipping][$area][0],
                                            "to" => $request->toCity[$shipping][$area][0],
                                            "time" => $request->city_to_time[$shipping][$area][0],
                                        );
                                    }
                                }

                            }
                        }
                    }

            }
           // echo "<pre>";
           // print_r($ShippingData);die;
            $vendorShipping->insert($ShippingData);
            return redirect(route('shipping.index'))->with('success', trans('messages.vendorShipping.added'));
        } else {
            return redirect(route('shipping.index'))->with('error', trans('messages.error'));
        }
        
        

    }

    public function edit(VendorShippingDetail $shipping)
    {
        return view('vendor.shipping.edit', [
            'shipping' => $shipping
        ]);
    }

    public function search(Request $request)
    {
        $loginUser = Auth::guard('vendor')->user();
//        dd($loginUser);
        if ($request->ajax()) {
            $url = request()->segment(1);
            $loginUser = Auth::guard($url)->user();
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $query = VendorShippingDetail::where('vendor_id', '=', $loginUser->id)
                ->where('city_id', '=', null)->select('id', 'country_name', 'charge', 'from', 'to');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterSearch($request->search['value'], $query);

            $deal = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($deal));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            return response()->json($data);
        }
    }

    /**
     * Filter Deal listing.
     *
     * @param $search
     * @return $query
     */
    private function filterSearch($search, $query)
    {
        $query->where(function ($query) use ($search) {
            $query->where('country_name', 'like', '%' . $search . '%')
                ->orWhere('charge', 'like', '%' . $search . '%')
                ->orWhere('from', 'like', '%' . $search . '%')
                ->orWhere('to', 'like', '%' . $search . '%');
        });
    }

    public function update(Request $request, VendorShippingDetail $shipping)
    {
        $shipping->charge = $request->charge;
        $shipping->from = $request->from;
        $shipping->to = $request->to;
        if ($shipping->save()) {
            return redirect(route('shipping.index'))->with('success', trans('messages.vendorShipping.updated'));
        }
        return redirect(route('shipping.index'))->with('error', trans('messages.error'));
    }

    public function getCountryNameById($table, $field, $columnName, $columnValue)
    {

        $data = DB::table($table)
            ->select($field)
            ->where($columnName, "=", $columnValue)
            ->get();
        return $data[0]->{$field};
    }

    public function delete(VendorShippingDetail $shipping)
    {
        if ($shipping->delete()) {
            return redirect(route('shipping.index'))->with('success', trans('messages.vendorShipping.deleted'));
        }
        return redirect(route('shipping.index'))->with('error', trans('messages.error'));
    }

}
