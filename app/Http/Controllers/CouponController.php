<?php

/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 16/1/18
 * Time: 4:13 PM
 */

namespace App\Http\Controllers;

use App\Coupons;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{

    private $validationRules = [
        'coupon_code' => 'required|unique:coupons',
        'discount_type' => 'required',
        'discount_amount' => 'required|numeric',
        'max_discount_amount' => 'numeric|nullable',
        'min_total_amount' => 'numeric|nullable',
        'start_date' => 'date|nullable',
        'end_date' => 'date|nullable',
    ];

    /*
     * Show admin coupon listing.
     */

    public function index()
    {
        $loginUser = Auth::guard('admin')->user();
        return view('coupons.coupons_list', [
            'loginUser' => $loginUser->type
        ]);
    }

    /*
     * Show admin create coupon page.
     */

    public function create()
    {
        $loginUser = Auth::guard('admin')->user();
        return view('coupons.coupons_create', [
            'loginUser' => $loginUser
        ]);
    }

    /*
     * Show edit coupon page.
     */

    public function edit(Coupons $coupon)
    {
        $loginUser = Auth::guard('admin')->user();
        return view('coupons.coupons_create', [
            'loginUser' => $loginUser,
            'coupon' => $coupon
        ]);
    }

    /**
     * Show state view page.
     *
     * @param PlanOptions $planOptions
     * @return json
     */
    public function profile(Coupons $coupon)
    {
        //echo "<pre>";print_r($coupon);die;
        $loginUser = Auth::guard('admin')->user();
        return view('coupons.profile', [
            'loginUser' => $loginUser,
            'coupon' => $coupon
        ]);
    }

    /**
     * Search Customers.
     *
     * @return json
     */
    public function search(Request $request)
    {
        if ($request->ajax())
        {
            $loginUser = Auth::guard('admin')->user();
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $query = Coupons::select('id', 'coupon_code', 'discount_type', 'discount_amount', 'min_total_amount', 'max_discount_amount', 'start_date', 'end_date', 'status');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterCoupon($request->search['value'], $query);

            $coupon = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);

            $data = json_decode(json_encode($coupon));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $coupons)
            {
                $coupons->action = '<a href="' . url(route('coupons.edit', ['coupon' => $coupons->id])) . '" title="Edit"><i class="la la-edit"></i></a>' .
                        '<a href="' . url(route('couponProfile', ['coupon' => $coupons->id])) . '" title="View"><i class="la la-eye"></i></a>' .
                        '<a class="delete-data" data-name="coupon" href="' . url(route('couponDelete', ['coupon' => $coupons->id])) . '" title="Delete"><i class="la la-trash"></i></a>';

                $coupons->status = ($coupons->status === 'Active') ? '<a href="' . url(route('changeCouponStatus', ['coupon' => $coupons->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>' : '<a href="' . url(route('changeCouponStatus', ['coupon' => $coupons->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
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
    private function filterCoupon($search, $query)
    {
        $query->where('coupon_code', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->orWhere('discount_type', 'like', '%' . $search . '%')
                ->orWhere('min_total_amount', 'like', '%' . $search . '%')
                ->orWhere('max_discount_amount', 'like', '%' . $search . '%')
                ->orWhere('end_date', 'like', '%' . $search . '%')
                ->orWhere('start_date', 'like', '%' . $search . '%')
                ->orWhere('discount_amount', 'like', '%' . $search . '%');
    }

    /**
     * Change status of the coupon.
     *
     * @param Employee $employee
     * @return json
     */
    public function changeStatus(Coupons $coupon)
    {
        if ($coupon->status == 'Active')
        {
            $coupon->status = 'Inactive';
        }
        else
        {
            $coupon->status = 'Active';
        }


        if ($coupon->save())
        {

            return redirect(route('coupons.index'))->with('success', trans('messages.coupons.change_status'));
        }

        return redirect(route('coupons.index'))->with('error', trans('messages.error'));
    }

    /**
     * Save the coupon.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        // Validate fields
        $this->validate($request, $this->validationRules);
        //dd($request);die;
        $coupon = new Coupons();
        $coupon->fill($request->all());
        $dateRange = $request->daterange;

        //echo "<pre>"; print_r($dateRange);die;
        if (strpos($dateRange, ' / ') !== false)
        {
            $dateRange = explode(' / ', $dateRange);
        }
        else
        {
            $dateRange = explode(' - ', $dateRange);
        }
        if($dateRange[0]!="") {
            $coupon->start_date = date('Y-m-d', strtotime($dateRange[0]));
            $coupon->end_date = date('Y-m-d', strtotime($dateRange[1]));
        }
        if ($coupon->save())
        {
            $couponSlug = str_slug($request->coupon_code.'-'.$coupon->id, "-");
            DB::table('coupons')
                ->where('id', $coupon->id)
                ->update(['coupon_slug' => $couponSlug]);
            return redirect(route('coupons.index'))->with('success', trans('messages.coupons.added'));
        }

        return redirect(route('coupons.index'))->with('error', trans('messages.error'));
    }

    /**
     * Save the coupon.
     *
     * @param Request $request
     * @return json
     */
    public function update(Request $request, Coupons $coupon)
    {
        $this->validationRules['coupon_code'] = 'required|unique:coupons,coupon_code,' . $coupon->id;

        // Validate fields
        $this->validate($request, $this->validationRules);

        $coupon->fill($request->all());
        $dateRange = $request->daterange;
        if (strpos($dateRange, ' / ') !== false)
        {
            $dateRange = explode(' / ', $dateRange);
        }
        else
        {
            $dateRange = explode(' - ', $dateRange);
        }
        if($dateRange[0]!=""){
            $coupon->start_date = date('Y-m-d', strtotime($dateRange[0]));
            $coupon->end_date = date('Y-m-d', strtotime($dateRange[1]));
        }

        if ($coupon->save())
        {
            $couponSlug = str_slug($request->coupon_code.'-'.$coupon->id, "-");
            DB::table('coupons')
                ->where('id', $coupon->id)
                ->update(['coupon_slug' => $couponSlug]);
            return redirect(route('coupons.index'))->with('success', trans('messages.coupons.updated'));
        }
        return redirect(route('coupons.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete coupon by unique idetifier.
     *
     * @return json
     */
    public function destroy(Coupons $coupon)
    {
        if ($coupon->delete())
        {

            return redirect(route('coupons.index'))->with('success', trans('messages.coupons.deleted'));
        }

        return redirect(route('coupons.index'))->with('error', trans('messages.error'));
    }

}
