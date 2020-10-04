<?php

namespace App\Http\Controllers\Admin;

use App\Mail\WelcomeMailVendor;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\Mail\VendorAccountActivation;
use App\Product;
use App\Shipping;
use App\Subscribers;
use App\Store;
use App\StoreCategory;
use App\Vendor;
use App\VendorShippingDetail;
use App\EmailUsers;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\VendorPlanDetail;
use Braintree\Plan;

class AdminVendorController extends Controller
{
    private $validationRules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed',
        'profile_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
    ];
    private $validationRulesForSale = [
        'vendor' => 'required',
        'sales_percentage' => 'required',
    ];
    
    /**
     * Display Vendor details.
     *
     * @return json
     */
    public function index()
    {
        return view('admin.vendors.vendors_list');
    }

    /**
     * Display Vendor sale update page.
     *
     * @return json
     */
    public function vendorSalesEdit()
    {
        $data['vendor'] = Vendor::select('first_name','last_name','vendor_plan_info.*')
        ->join('vendor_plan_info','vendor_plan_info.vendor_id','users.id')
        ->where('vendor_plan_info.status','Active')
        ->where('users.status','1')
        ->get();
        //dd($data['vendor']);die;    
        return view('admin.vendors.vendors_sale_update',$data);
    }
    
    /**
     *  Vendor sale update.
     *
     * @return json
     */
    public function vendorSaleUpdate(Request $request)
    {
        //dd($request);die;
        $this->validate($request, $this->validationRulesForSale);
        VendorPlanDetail::where('id',$request->plan_info_id)->update(['status'=>'Inactive']);
        $vendorPlan = VendorPlanDetail::where('id',$request->plan_info_id)->first();
       $vendorPlanDetail = new VendorPlanDetail();
       $vendorPlanDetail->vendor_id = $request->vendor;
       $vendorPlanDetail->plan_id = $vendorPlan->plan_id;
       $vendorPlanDetail->plan_option_id = $vendorPlan->plan_option_id;
       $vendorPlanDetail->plan_name = $vendorPlan->plan_name;
       $vendorPlanDetail->plan_periods = $vendorPlan->plan_periods;
       $vendorPlanDetail->sales_percentage = $request->sales_percentage;
       $vendorPlanDetail->price = $vendorPlan->price;
       $vendorPlanDetail->start_at = $vendorPlan->start_at;
       $vendorPlanDetail->end_at = $vendorPlan->end_at;
       $vendorPlanDetail->description = $vendorPlan->description;
       $vendorPlanDetail->status = 'Active';
       if($vendorPlanDetail->save()){
        return redirect(route('vendorSalesEdit'))->with('success', trans('messages.vendor.sale'));
       }
       return redirect(route('vendorSalesEdit'))->with('error', trans('messages.error'));
    }
    
    
    /**
     * Search Vendor.
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

            $query = Vendor::select('id', 'first_name',  'last_name', 'email','mobile_no', 'status');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterVendor($request->search['value'], $query);

            $vendor = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($vendor));
            //dd($data);die;
            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $vendors) {

                $vendors->action = '<a href="'.url(route('vendors.edit', ['vendor' => $vendors->id ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                    '<a href="'.url(route('viewVendor', ['vendor' => $vendors->id ])).'" title="View"><i class="la la-eye"></i></a>'.
                    '<a href="'.url(route('viewProductVendor', ['vendor' => $vendors->id ])).'" title="View Product"><i class="la la-list-ul"></i></a>';
                    /*'<a class="block-data" data-name="vendor" href="'.url(route('deleteVendor', ['vendor' => $vendors->id ])).'" title="Block"><i class="la la-trash"></i></a>';*/

                $vendors->status = $vendors->status ? '<a href="'.url(route('changeVendorStatus', ['vendor' => $vendors->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="'.url(route('changeVendorStatus', ['vendor' => $vendors->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
        
    }
    
    /**
     * Gets all Vendor.
     *
     * @return json
     */
    public function create()
    {
        // $data['contactTypes'] = ContactType::all();
        return view('admin.vendors.vendor_create');
    }

    /**
     * Save the Vendor.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        // Validate fields
        $this->validate($request, $this->validationRules);

        $vendor = new Vendor();

        $vendor->fill($request->all());
        $image = $request->file('profile_image');
        $destinationPath = public_path('doc/vendor_logo');
        $user_img = $request->profile_image_1;
        $data = ImageHelper::imageSave($image,$destinationPath,$user_img); //image save
        if (!empty($data) && $data != 'false') {
            $vendor->profile_image = $data;
        }
        $vendor->status = 1;
        $vendor->pending_process = 'Yes';
        $vendor->type = 'vendor';
        $vendor->password = bcrypt($request->password);
        if ($vendor->save()) {
            $nameSlug = str_slug($request->first_name ."-". $request->last_name."-".$vendor->id, "-");
            DB::table('users')
                ->where('id', $vendor->id)
                ->update(['name_slug' => $nameSlug]);
            try
            {
                Mail::to($vendor->email)->send(new WelcomeMailVendor($vendor));
            }
            catch (Exception $exc)
            {
//                echo $exc->getTraceAsString();
            }
            return redirect(route('vendors.index'))->with('success', trans('messages.vendor.added'));
        }
       return redirect(route('vendors.create'))->with('error', trans('messages.error'));
    }
    
    
    /**
     * Change status of the vendors.
     * 
     * @param Vendor $vendor
     * @return json
     */
    public function changeStatus(Vendor $vendor)
    {

       $vendor->status = !$vendor->status;

        if($vendor->save()) {
            if($vendor->status == 1){
                DB::table('products')
                    ->where('vendor_id', $vendor->id)
                    ->update(['status' => 'Active']);
                Store::where('vendor_id', $vendor->id)
                    ->update(['status' => 'Active']);
            }else{
                DB::table('products')
                    ->where('vendor_id', $vendor->id)
                    ->update(['status' => 'Inactive']);
                Store::where('vendor_id', $vendor->id)
                    ->update(['status' => 'Inactive']);
            }
            if($vendor->status === 0) {
                $users=$vendor;
                Mail::to($vendor->email)->send(new VendorAccountActivation($users));
            }

            return redirect(route('vendors.index'))->with('success', trans('messages.vendor.change_status'));
        }

        return redirect(route('vendors.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show Vendor view page.
     *
     * @param Vendor $users
     * @return json
     */
    public function profile(Vendor $vendor)
    {
        //echo "<pre>";print_r($vendor);die;
        // echo "hi";die;
        return view('admin.vendors.profile', [
            'vendor' => $vendor
        ]);
    }

    /**
     * Show Vendor edit page.
     * 
     * @param Vendor $users
     * @return json
     */
    public function edit(Vendor $vendor)
    {
        
       // echo "hi";die;
        return view('admin.vendors.vendor_create', [
            'vendors' => $vendor
        ]);
    }

    /**
     * Update the vendor.
     *
     * @param Request $request
     * @param int $vendorId
     * @return json
     */
    public function update(Request $request, Vendor $vendor)
    {
        $this->validationRules['email'] = 'unique:users,email,'.$vendor->id.'|email';
        unset($this->validationRules['password']);
        $this->validate($request, $this->validationRules);

        $image = $request->file('profile_image');
        $destinationPath = public_path('doc/vendor_logo');
        $user_img = $request->profile_image_1;
        $data = ImageHelper::imageSave($image,$destinationPath,$user_img); //image save
        if (!empty($data) && $data != 'false') {
            $vendor->profile_image = $data;
        }

        $response = [
            'status' => 0,
            'message' => trans('messages.failed'),
        ];

            $vendor->fill($request->all());

            if ($vendor->save()) {
                $nameSlug = str_slug($request->first_name ."-". $request->last_name."-".$vendor->id, "-");
                DB::table('users')
                    ->where('id', $vendor->id)
                    ->update(['name_slug' => $nameSlug]);
                return redirect(route('vendors.index'))->with('success', trans('messages.vendor.updated'));
            }

            return redirect(route('vendors.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete Vendor by unique identifier.
     *
     * @return json
     */
    public function destroy(Vendor $vendor)
    {
        /*$vendor->status = !$vendor->status;

        if($vendor->save()) {
            DB::table('products')
                ->where('vendor_id', $vendor->id)
                ->update(['status' => 'Inactive']);

            DB::table('stores')
                ->where('vendor_id', $vendor->id)
                ->update(['status' => 'Inactive']);

        }*/
        $storeId = Store::where("vendor_id", $vendor->id)->first();
       // Store::where("vendor_id", $vendor->id)->delete();
        $product = Product::where('vendor_id',$vendor->id)->count();
        /*if(isset($storeId) && !empty($storeId)){
            StoreCategory::where("store_id", $storeId->id)->delete();
        }*/


        $shipping = VendorShippingDetail::where('vendor_id',$vendor->id)->count();
        if($product < 1 && $shipping < 1){
            Store::where("vendor_id", $vendor->id)->delete();
                if(isset($storeId) && !empty($storeId)){
                    StoreCategory::where("store_id", $storeId->id)->delete();
                }
                Subscribers::where("user_id", $vendor->id)->delete();
                $emilUser = EmailUsers::where('user_id',$vendor->id)->delete();
            if($vendor->delete()) {

                return redirect(route('vendors.index'))->with('success', trans('messages.vendor.deleted'));
            }
        }else{
            return redirect(route('vendors.index'))->with('error', trans('messages.vendor.error'));
        }

        return redirect(route('vendors.index'))->with('error', trans('messages.error'));
    }

    /**
     * Filter Vendor listing.
     *
     * @param $search
     * @return $query
     */
    private function filterVendor($search, $query)
    {
        $query->where('first_name', 'like', '%'.$search.'%')
             ->orWhere('last_name', 'like', '%'.$search.'%')
             ->orWhere('mobile_no', 'like', '%'.$search.'%')
              ->orWhere('email', 'like', '%'.$search.'%');
    }
    
}
