<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 11:41 AM
 */

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Controller;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AdminStateController extends Controller
{
    private $validationRules = [

        'state_name' => 'required|unique:state,state_name',
        'country_id' => 'required',
    ];

    /**
     * Display State details.
     *
     * @return json
     */
    public function index()
    {

        return view('admin.state.state_list');
    }

    /**
     * Search State.
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

            $query = State::select('state_name','country_id','state.status','state.id','country.country_name')
                ->leftJoin('country', 'country.id', '=', 'state.country_id');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterState($request->search['value'], $query);

            $state = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($state));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $states) {
                $states->action = '<a href="'.url(route('state.edit', ['state' => $states->id ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                    '<a href="'.url(route('profileState', ['state' => $states->id ])).'" title="View"><i class="la la-eye"></i></a>';
                //'<a class="delete-data" data-name="state" href="'.url(route('deleteState', ['state' => $states->id ])).'" title="Delete"><i class="la la-trash"></i></a>'

                $states->status = ($states->status === 'Active') ? '<a href="'.url(route('changeStateStatus', ['state' => $states->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="'.url(route('changeStateStatus', ['state' => $states->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }

    }
    /**
     * Filter State listing.
     *
     * @param $search
     * @return $query
     */
    private function filterState($search, $query)
    {
        $query->where('country_name', 'like', '%'.$search.'%')
            ->orWhere('state.status', 'like', '%'.$search.'%')
            ->orWhere('state_name', 'like', '%'.$search.'%');
    }

    /**
     * Display create state page.
     *
     * @return json
     */
    public function create()
    {
        $country = Country::all()->where('status','Active');
        return view('admin.state.state_create',
            ['country' =>$country ]);
    }
    /**
     * Save the State.
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $state = new State();
        $state->fill($request->all());
        $state->state_name = ucfirst($request->state_name);
        if ($state->save()) {

            return redirect(route('state.index'))->with('success',trans('messages.state.added'));
        }

        return redirect(route('state.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the country.
     *
     * @param State $state
     * @return json
     */
    public function changeStatus(State $state)
    {
        // echo "<pre>";print_r($vendor);die;
        if($state->status == 'Active'){
            $state->status ='Inactive';
        }else{
            $state->status ='Active';
        }

        if($state->save()) {

            return redirect(route('state.index'))->with('success', trans('messages.state.change_status'));
        }

        return redirect(route('state.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show state view page.
     *
     * @param State $state
     * @return json
     */
    public function profile(State $state)
    {
       $country_name = Country::where('id', $state->country_id)->first();
       // echo $country_name[0]->country_name;die;
        return view('admin.state.profile', [
            'state' => $state,
             'country' => $country_name->country_name
        ]);
    }

    /**
     * Show State edit page.
     *
     * @param State $state
     * @return json
     */
    public function edit(State $state)
    {
        //  echo "<pre>";print_r($state);die;
        $country = Country::all()->where('status','Active');
        return view('admin.state.state_create', [
            'state' =>$state,
            'country' =>$country,
        ]);
    }

    /**
     * Update the state.
     *
     * @param Request $request
     * @param int $state
     * @return json
     */
    public function update(Request $request, State $state)
    {
        //echo $store->id;die;
        //echo "hi";die;
        // Validate fields

        $this->validationRules['state_name'] = 'required|unique:state,state_name,'.$state->id;
        $this->validate($request, $this->validationRules);

        $state->fill($request->all());
         $state->state_name = ucfirst($request->state_name);
        if ($state->save()) {

            return redirect(route('state.index'))->with('success', trans('messages.state.updated'));
        }

        return redirect(route('state.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete state by unique identifier.
     *
     * @return json
     */
    public function destroy(State $state)
    {
        if($state->delete()) {

            return redirect(route('state.index'))->with('success', trans('messages.state.deleted'));
        }

        return redirect(route('state.index'))->with('error', trans('messages.error'));
    }
}