<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 12/1/18
 * Time: 6:26 PM
 */

namespace App\Http\Controllers\Admin;


use App\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AdminCountryController extends Controller
{
    private $validationRules = [

        'country_name' => 'required|unique:country,country_name',
    ];

    /**
     * Display Country details.
     *
     * @return json
     */
    public function index()
    {

        return view('admin.country.country_list');
    }

    /**
     * Search Country.
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

            $query = Country::select('country_name','status','id','short_name','country_code');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterCountry($request->search['value'], $query);

            $country = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($country));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $countries) {
                $countries->action = '<a href="'.url(route('country.edit', ['country' => $countries->id ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                    '<a href="'.url(route('profileCountry', ['country' => $countries->id ])).'" title="View"><i class="la la-eye"></i></a>';
//'<a class="delete-data" data-name="country" href="'.url(route('deleteCountry', ['country' => $countries->id ])).'" title="Delete"><i class="la la-trash"></i></a>
                $countries->status = ($countries->status === 'Active') ? '<a href="'.url(route('changeCountryStatus', ['country' => $countries->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="'.url(route('changeCountryStatus', ['country' => $countries->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }

    }
    /**
     * Filter Country listing.
     *
     * @param $search
     * @return $query
     */
    private function filterCountry($search, $query)
    {
        $query->where('country_name', 'like', '%'.$search.'%')
            ->orWhere('status', 'like', '%'.$search.'%')
            ->orWhere('short_name', 'like', '%'.$search.'%')
            ->orWhere('country_code', 'like', '%'.$search.'%');
    }

    /**
     * Display create country page.
     *
     * @return json
     */
    public function create()
    {
        return view('admin.country.country_create');
    }
    /**
     * Save the Country.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $country = new Country();
        $country->fill($request->all());
       //$country->iso3 = $request->has('iso3') ? strtoupper($request->iso3) : null;
        $country->short_name = $request->has('short_name') ? strtoupper($request->short_name) : null;
        $country->country_name = $request->has('short_name') ? ucfirst($request->country_name) : null;
        if ($country->save()) {

            return redirect(route('country.index'))->with('success',trans('messages.country.added'));
        }

        return redirect(route('country.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the country.
     *
     * @param Country $country
     * @return json
     */
    public function changeStatus(Country $country)
    {
        // echo "<pre>";print_r($vendor);die;
        if($country->status == 'Active'){
            $country->status ='Inactive';
        }else{
            $country->status ='Active';
        }

        if($country->save()) {

            return redirect(route('country.index'))->with('success', trans('messages.country.change_status'));
        }

        return redirect(route('country.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show contry view page.
     *
     * @param Country $country
     * @return json
     */
    public function profile(Country $country)
    {
        return view('admin.country.profile', [
            'country' => $country
        ]);
    }

    /**
     * Show Country edit page.
     *
     * @param Country $country
     * @return json
     */
    public function edit(Country $country)
    {
        //  echo "<pre>";print_r($country);die;
        return view('admin.country.country_create', [
            'country' =>$country
        ]);
    }

    /**
     * Update the country.
     *
     * @param Request $request
     * @param int $country
     * @return json
     */
    public function update(Request $request, Country $country)
    {
        //echo $store->id;die;
        //echo "hi";die;
        // Validate fields

        $this->validationRules['country_name'] = 'required|unique:country,country_name,'.$country->id;
        //$this->validationRules['iso3'] = 'unique:country,iso3,'.$country->id;
        
       // $this->validationRules['country_code'] = 'unique:country,country_code,'.$country->id;
        $this->validate($request, $this->validationRules);

        $country->fill($request->all());
        $country->short_name = $request->has('short_name') ? strtoupper($request->short_name) : null;

        if ($country->save()) {

            return redirect(route('country.index'))->with('success', trans('messages.country.updated'));
        }

        return redirect(route('country.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete country by unique identifier.
     *
     * @return json
     */
    public function destroy(Country $country)
    {
        if($country->delete()) {

            return redirect(route('country.index'))->with('success', trans('messages.country.deleted'));
        }

        return redirect(route('country.index'))->with('error', trans('messages.error'));
    }

}