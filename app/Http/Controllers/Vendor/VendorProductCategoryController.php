<?php

namespace App\Http\Controllers\Vendor;

use App\Collections;
use App\CollectionProducts;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Product;
use App\ProductCategory;
use App\VendorProductCategory;
use App\ProductImage;
use Illuminate\Support\Facades\DB;

class VendorProductCategoryController extends Controller
{

    private $validationRules = [

        'category_name' => 'required',
        'category_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
    ];

    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $data = [];
        $data['vendor'] = $vendor;
        return view('vendor.categories.categories_list', $data);
    }

    /**
     * Search product category.
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

            $query = VendorProductCategory::select('id', 'category_name', 'featured', 'status');

            /*if($loginUser->type == 'vendor'){
                $query->where('vendor_id', $loginUser->id);
            }*/
            if($loginUser->type == 'vendor'){
                $query->where('vendor_id',$loginUser->id);
            }
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterStore($request->search['value'], $query);

            $product = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($product));
            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $category)
            {
                $category->action = '<a href="'.url(route('editVendorProductCategory', ['vendor_product_category' => $category->id ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                '<a class="delete-data" data-name="product category" href="' . url(route('removeVendorProductCategory', ['vendor_product_category' => $category->id])) . '" title="Delete"><i class="la la-trash"></i></a>';

                $category->featured = ($category->featured === 'Yes') ? '<a class="m-badge m-badge--success m-badge--wide" title="Active">Yes</a>' : '<a class="m-badge m-badge--danger m-badge--wide" title="Inactive">No</a>';

                $category->status = ($category->status === 'Active') ? '<a class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>' : '<a class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }

    /**
     * Filter Store listing.
     *
     * @param $search
     * @return $query
     */
    private function filterStore($search, $query)
    {
        $query->where(function ($query) use($search) {

            $query->where('vendor_product_categories.category_name', 'like', '%'.$search.'%')
                ->orWhere('vendor_product_categories.status', 'like', '%'.$search.'%')
                ->orWhere('vendor_product_categories.featured', 'like', '%'.$search.'%');
        });
    }

    public function show()
    {
        
    }
    public function remove(VendorProductCategory $vendor_product_category)
    {
       // dd($vendor_product_category);die;
        if ($vendor_product_category->delete())
        {
            return redirect(url('vendor/store-products-category'))->with('success', trans('messages.collection_products.removed'));
        }
        return redirect(url(route('vendor/store-products-category')))->with('error', trans('messages.error'));
    }

    public function create()
    {
        $vendor = Auth::guard('vendor')->user();
        //$vendorProductCategory = VendorProductCategory::where('vendor_id', $vendor->id)->pluck('product_category_id');
        //$data = [];
        //$categories = ProductCategory::where("status", "Active")->whereNotIn("id", $vendorProductCategory)->get();
        $data['vendor'] = $vendor;
        //$data['categories'] = $categories;
        return view('vendor.categories.categories_add', $data);
    }

    public function store(Request $request)
    {
        //dd($request);die;

        $this->validate($request, $this->validationRules);
        $vendor = Auth::guard('vendor')->user();
        $vendorproductCategory = new VendorProductCategory();

        $vendorproductCategory->fill($request->all());
        $image = $request->file('category_image');
        $destinationPath = public_path('doc/category_image');
        $user_img = $request->category_image_1;
        $data = ImageHelper::imageSave($image,$destinationPath,$user_img);
        //echo "<pre>";print_r($data);
        if (!empty($data) && $data != 'false') {
            $vendorproductCategory->category_image = $data;
        }
        $vendorproductCategory->vendor_id = $vendor->id;
        $vendorproductCategory->featured = $request->featured;
        $vendorproductCategory->status = $request->status;

        if ($vendorproductCategory->save()) {
            $categorySlug = str_slug($request->category_name.'-'.$vendorproductCategory->id, "-");
            DB::table('vendor_product_categories')
                ->where('id', $vendorproductCategory->id)
                ->update(['vendor_category_slug' => $categorySlug]);
            return redirect(route('store-products-category.index'))->with('success', trans('messages.vendor_product_category.added'));
        }

        return redirect(route('store-products-category.index'))->with('error', trans('messages.error'));
    }
    /**
     * Show category edit page.
     *
     * @param VendorProductCategory $vendor_product_category
     * @return json
     */
  public function edit(VendorProductCategory $vendor_product_category)
    {
        //dd($vendor_product_category);
        $vendor = Auth::guard('vendor')->user();
        $data['vendor'] = $vendor;
        $data['category_data'] = $vendor_product_category;
        //$data['categories'] = $categories;
        return view('vendor.categories.categories_add', $data);

    }

    /**
     * Update the vendor.
     *
     * @param Request $request
     * @param int $vendorId
     * @return json
     */
    public function update(Request $request, VendorProductCategory $category)
    {

        $this->validate($request, $this->validationRules);
        $vendor = Auth::guard('vendor')->user();

        $image = $request->file('category_image');
        $destinationPath = public_path('doc/category_image');
        $user_img = $request->category_image_1;
        $data = ImageHelper::imageSave($image,$destinationPath,$user_img);
        if (!empty($data) && $data != 'false') {
            $category->category_image = $data;
        }
        $category->fill($request->all());
        $category->vendor_id = $vendor->id;
        if ($category->save()) {
            $categorySlug = str_slug($request->category_name.'-'.$category->id, "-");
            DB::table('vendor_product_categories')
                ->where('id', $category->id)
                ->update(['vendor_category_slug' => $categorySlug]);
            return redirect(route('store-products-category.index'))->with('success', trans('messages.vendor_product_category.updated'));
        }

        return redirect(route('store-products-category.index'))->with('error', trans('messages.error'));
    }
}
