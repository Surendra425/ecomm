<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 6:31 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\PlanOptions;
use App\Plans;
use App\VendorPlanDetail;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AdminPlanOptionController extends Controller
{
    private $validationRules = [

        'plan_id' => 'required',
        'price' => 'required',
        'duration' => 'required',
    ];
    /**
     * Display PlanOptions details.
     *
     * @return json
     */
    public function index()
    {

        return view('admin.planOption.planOption_list');
    }

    /**
     * Search PlanOptions.
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

            $query = PlanOptions::select('plans.plan_name','plans.sales_percentage','description','plans_options.status','plans_options.id','price','duration')
                ->leftJoin('plans', 'plans.id', '=', 'plans_options.plan_id');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterPlanOptions($request->search['value'], $query);

            $planOption = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($planOption));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $planOptions) {
                $planOptions->action = '<a href="'.url(route('editPlanOption', ['option' => $planOptions->id ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                    '<a href="'.url(route('profilePlanOption', ['option' => $planOptions->id ])).'" title="View"><i class="la la-eye"></i></a>'.
                    '<a class="delete-data" data-name="plan option" href="'.url(route('deletePlanOption', ['option' => $planOptions->id ])).'" title="Delete"><i class="la la-trash"></i></a>';

                $planOptions->status = ($planOptions->status === 'Active') ? '<a href="'.url(route('changePlanOptionStatus', ['option' => $planOptions->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="'.url(route('changePlanOptionStatus', ['option' => $planOptions->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }
    /**
     * Filter PlanOptions listing.
     *
     * @param $search
     * @return $query
     */
    private function filterPlanOptions($search, $query)
    {
        $query->where('plan_name', 'like', '%'.$search.'%')
            ->orWhere('plans_options.status', 'like', '%'.$search.'%')
            ->orWhere('price', 'like', '%'.$search.'%')
            ->orWhere('duration', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%');
    }

    /**
     * Display create state page.
     *
     * @return json
     */
    public function create()
    {
        $plan = Plans::all()->where('status','Active');
        return view('admin.planOption.planOption_create',
            ['plan' =>$plan ]);
    }
    /**
     * Save the PlanOptions.
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $planOptions = new PlanOptions();
        $planOptions->fill($request->all());
        $duration = $request->duration .' '. $request->duration_time;
        $planOptions->duration = $duration;
        $planOptions->plan_id = $request->plan_id;
        if ($planOptions->save()) {

            return redirect(route('plan-options.index'))->with('success',trans('messages.planOptions.added'));
        }

        return redirect(route('plan-options.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the option.
     *
     * @param PlanOptions $planOptions
     * @return json
     */
    public function changeStatus(PlanOptions $option)
    {
         if($option->status == 'Active'){
            $option->status ='Inactive';
        }else{
            $option->status ='Active';
        }

        if($option->save()) {

            return redirect(route('plan-options.index'))->with('success', trans('messages.planOptions.change_status'));
        }

        return redirect(route('plan-options.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show state view page.
     *
     * @param PlanOptions $planOptions
     * @return json
     */
    public function profile(PlanOptions $option)
    {
        $plan_name = Plans::where('id', $option->plan_id)->first();
        $option->plan_name=$plan_name->plan_name;
        return view('admin.planOption.profile', [
            'planOption' => $option,
        ]);
    }

    /**
     * Show PlanOptions edit page.
     *
     * @param PlanOptions $planOptions
     * @return json
     */
    public function edit(PlanOptions $option)
    {
         // echo "<pre>";print_r($option);die;
        $plan = Plans::all()->where('status','Active');
       $planDuration = explode(' ',$option->duration);
       $option->timeDuration = $planDuration[0];
       $option->period = $planDuration[1];
        return view('admin.planOption.planOption_create', [
            'planOption' =>$option,
            'plan' =>$plan,
        ]);
    }

    /**
     * Update the planOptions.
     *
     * @param Request $request
     * @param int $planOptions
     * @return json
     */
    public function update(Request $request, PlanOptions $option)
    {
        //echo $store->id;die;
        //echo "hi";die;
        // Validate fields

       $this->validate($request, $this->validationRules);

        $option->fill($request->all());
        $duration = $request->duration .' '. $request->duration_time;
        $option->duration = $duration;
        $option->plan_id = $request->plan_id;
        if ($option->save()) {

            return redirect(route('plan-options.index'))->with('success', trans('messages.planOptions.updated'));
        }

        return redirect(route('plan-options.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete state by unique identifier.
     *
     * @return json
     */
    public function destroy(PlanOptions $option)
    {
        $vendorPlanOption = VendorPlanDetail::where("plan_option_id", $option->id)->count();
        if($vendorPlanOption < 1){
           if($option->delete()) {

            return redirect(route('plan-options.index'))->with('success', trans('messages.planOptions.deleted'));
             } 
        }else{
            return redirect(route('plan-options.index'))->with('error', trans('messages.planOptions.error'));  
        }
        

        return redirect(route('plan-options.index'))->with('error', trans('messages.error'));
    }
}