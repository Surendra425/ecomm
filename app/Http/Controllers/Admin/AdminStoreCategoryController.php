<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 10/1/18
 * Time: 5:33 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\StoreCategory;
use App\VendorCategory;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminStoreCategoryController extends Controller
{
    private $validationRules = [

        'vendor_category_name' => 'required|unique:vendor_category,vendor_category_name',
    ];

    /**
     * Display Store category details.
     *
     * @return json
     */
    public function index()
    {

        return view('admin.storeCategory.store_category_list');
    }

    /**
     * Display create store category page.
     *
     * @return json
     */
    public function create()
    {
        return view('admin.storeCategory.store_category_create');
    }

    /**
     * Save the Store Category.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        $this->validate($request, $this->validationRules);
        $VendorCategory = new VendorCategory();
        $VendorCategory->fill($request->all());
        $VendorCategory->featured = $request->featured;
        $VendorCategory->status = $request->status;
        $VendorCategory->added_by_user_id = $loginUser->id;
        if ($VendorCategory->save()) {
            $vendorCategorySlug = str_slug($request->plan_name.'-'.$VendorCategory->id, "-");
            DB::table('vendor_category')
                ->where('id', $VendorCategory->id)
                ->update(['vendor_slug' => $vendorCategorySlug]);
            return redirect(route('stores-category.index'))->with('success', trans('messages.storesCategory.added'));
        }

        return redirect(route('stores-category.index'))->with('error', trans('messages.error'));
    }

    /**
     * Search store.
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

            $query = VendorCategory::select('vendor_category_name','featured','status','id');
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterStoreCategory($request->search['value'], $query);

            $storeCategory = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($storeCategory));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $category) {
                $category->action = '<a href="'.url(route('adminStoreEdit', ['category' => $category->id ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                    '<a href="'.url(route('adminviewStoreCategory', ['category' => $category->id ])).'" title="View"><i class="la la-eye"></i></a>'.
                    '<a class="delete-data" data-name="category" href="'.url(route('admindeleteStoreCategory', ['category' => $category->id ])).'" title="Delete"><i class="la la-trash"></i></a>';

                $category->featured = ($category->featured === 'Yes') ? '<a href="'.url(route('adminchangeStoreCategoryfeatured', ['store' => $category->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Yes</a>'
                    : '<a href="'.url(route('adminchangeStoreCategoryfeatured', ['category' => $category->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">No</a>';

                $category->status = ($category->status === 'Active') ? '<a href="'.url(route('adminchangeStoreCategoryStatus', ['store' => $category->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="'.url(route('adminchangeStoreCategoryStatus', ['category' => $category->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }
    /**
     * Filter Store Category listing.
     *
     * @param $search
     * @return $query
     */
    private function filterStoreCategory($search, $query)
    {
        $query->where('vendor_category_name', 'like', '%'.$search.'%')
            ->orWhere('status', 'like', '%'.$search.'%')
            ->orWhere('featured', 'like', '%'.$search.'%');
    }



    /**
     * Delete store by unique identifier.
     *
     * @return json
     */
    public function destroy(VendorCategory $category)
    {
        StoreCategory::where("vendor_category_id", $category->id)->delete();

        if($category->delete()) {

            return redirect(route('stores-category.index'))->with('success', trans('messages.storesCategory.deleted'));
        }

        return redirect(route('stores-category.index'))->with('error', trans('messages.error'));
    }



    /**
     * Change status of the store.
     *
     * @param VendorCategory $category
     * @return json
     */
    public function changeStatus(VendorCategory $category)
    {
        if($category->status == 'Active'){
            $category->status ='Inactive';
        }else{
            $category->status ='Active';
        }
        if($category->save()) {

            return redirect(route('stores-category.index'))->with('success', trans('messages.storesCategory.change_status'));
        }

        return redirect(route('stores-category.index'))->with('error', trans('messages.error'));
    }
    /**
     * Change featured status of the store.
     *
     * @param VendorCategory $category
     * @return json
     */
    public function changeFeaturedStatus(VendorCategory $category)
    {
       // echo "<pre>";print_r($category);die;
        if($category->featured == 'No'){
            $category->featured ='Yes';
        }else{
            $category->featured ='No';
        }
        if($category->save()) {

            return redirect(route('stores-category.index'))->with('success', trans('messages.storesCategory.change_featured'));
        }

        return redirect(route('stores-category.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show Store category view page.
     *
     * @param VendorCategory $category
     * @return json
     */
    public function profile(VendorCategory $category)
    {
        return view('admin.storeCategory.profile', [
            'vendorCategory' => $category
        ]);
    }

    /**
     * Show Store category edit page.
     *
     * @param VendorCategory $category
     * @return json
     */
    public function edit(VendorCategory $category)
    {
      //  echo "<pre>";print_r($category);die;
       return view('admin.storeCategory.store_category_create', [
            'vendorCategory' =>$category
        ]);
    }

    /**
     * Update the vendor.
     *
     * @param Request $request
     * @param int $vendorId
     * @return json
     */
    public function update(Request $request, VendorCategory $category)
    {
        //echo $store->id;die;
        //echo "hi";die;
        // Validate fields
      /*  $this->validate($request, [
            'vendor_category_name' => 'required|unique:vendor_category,vendor_category_name,'.$category->id,
        ]);*/

        $this->validationRules['vendor_category_name'] = 'required|unique:vendor_category,vendor_category_name,'.$category->id;
       $this->validate($request, $this->validationRules);

        $category->fill($request->all());
        $category->featured = $request->featured;
        $category->status = $request->status;
        if ($category->save()) {
            $vendorCategorySlug = str_slug($request->plan_name.'-'.$category->id, "-");
            DB::table('vendor_category')
                ->where('id', $category->id)
                ->update(['vendor_slug' => $vendorCategorySlug]);
            return redirect(route('stores-category.index'))->with('success', trans('messages.storesCategory.updated'));
        }

        return redirect(route('stores-category.index'))->with('error', trans('messages.error'));
    }
}