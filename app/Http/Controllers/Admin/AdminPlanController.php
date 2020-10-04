<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 5:25 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\PlanOptions;
use App\Plans;
use App\VendorPlanDetail;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class AdminPlanController extends Controller
{
    private $validationRules = [

        'plan_name' => 'required|unique:plans,plan_name',
    ];


    /**
     * Display Plan details.
     *
     * @return json
     */
    public function index()
    {

        return view('admin.plan.plan_list');
    }

    /**
     * Search Plan.
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

            $query = Plans::select('plan_name','sales_percentage','status','id');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterPlan($request->search['value'], $query);

            $plan = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($plan));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $plans) {
                $plans->action = '<a href="'.url(route('plans.edit', ['plan' => $plans->id ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                    '<a href="'.url(route('profilePlan', ['plan' => $plans->id ])).'" title="View"><i class="la la-eye"></i></a>'.
                    '<a class="delete-data" data-name="plan" href="'.url(route('deletePlan', ['plan' => $plans->id ])).'" title="Delete"><i class="la la-trash"></i></a>';

                $plans->status = ($plans->status === 'Active') ? '<a href="'.url(route('changePlanStatus', ['plan' => $plans->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="'.url(route('changePlanStatus', ['plan' => $plans->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }
    /**
     * Filter Plan listing.
     *
     * @param $search
     * @return $query
     */
    private function filterPlan($search, $query)
    {
        $query->where('plan_name', 'like', '%'.$search.'%')
            ->orWhere('status', 'like', '%'.$search.'%')
            ->orWhere('sales_percentage', 'like', '%'.$search.'%');
    }

    /**
     * Display create plan page.
     *
     * @return json
     */
    public function create()
    {
        return view('admin.plan.plan_create');
    }
    /**
     * Save the Plan.
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $plan = new Plans();
        $plan->fill($request->all());
        $plan->plan_name = ucfirst($request->plan_name);
        if ($plan->save()) {
            $planSlug = str_slug($request->plan_name.'-'.$plan->id, "-");
            DB::table('plans')
                ->where('id', $plan->id)
                ->update(['plan_slug' => $planSlug]);
            return redirect(route('plans.index'))->with('success',trans('messages.plan.added'));
        }

        return redirect(route('plans.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the plan.
     *
     * @param Plan $plan
     * @return json
     */
    public function changeStatus(Plans $plan)
    {
        // echo "<pre>";print_r($vendor);die;
        if($plan->status == 'Active'){
            $plan->status ='Inactive';
        }else{
            $plan->status ='Active';
        }

        if($plan->save()) {

            return redirect(route('plans.index'))->with('success', trans('messages.plan.change_status'));
        }

        return redirect(route('plans.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show plan view page.
     *
     * @param Plan $plan
     * @return json
     */
    public function profile(Plans $plan)
    {
       return view('admin.plan.profile', [
            'plan' => $plan
        ]);
    }

    /**
     * Show Plan edit page.
     *
     * @param Plan $plan
     * @return json
     */
    public function edit(Plans $plan)
    {
        //  echo "<pre>";print_r($plan);die;
       return view('admin.plan.plan_create', [
            'plan' =>$plan
        ]);
    }

    /**
     * Update the plan.
     *
     * @param Request $request
     * @param int $plan
     * @return json
     */
    public function update(Request $request, Plans $plan)
    {
        //echo $store->id;die;
        //echo "hi";die;
        // Validate fields

        $this->validationRules['plan_name'] = 'required|unique:plans,plan_name,'.$plan->id;
        $this->validate($request, $this->validationRules);

        $plan->fill($request->all());
        $plan->plan_name = ucfirst($request->plan_name);
        if ($plan->save()) {
            $planSlug = str_slug($request->plan_name.'-'.$plan->id, "-");
            DB::table('plans')
                ->where('id', $plan->id)
                ->update(['plan_slug' => $planSlug]);
            return redirect(route('plans.index'))->with('success', trans('messages.plan.updated'));
        }

        return redirect(route('plans.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete plan by unique identifier.
     *
     * @return json
     */
    public function destroy(Plans $plan)
    {
        $vendorPlan = VendorPlanDetail::where("plan_id", $plan->id)->count();
        if($vendorPlan < 1){
            PlanOptions::where("plan_id", $plan->id)->delete();
        if($plan->delete()) {

            return redirect(route('plans.index'))->with('success', trans('messages.plan.deleted'));
            }
        }else{
          return redirect(route('plans.index'))->with('error', trans('messages.plan.error'));  
        }
        

        return redirect(route('plans.index'))->with('error', trans('messages.error'));
    }
}