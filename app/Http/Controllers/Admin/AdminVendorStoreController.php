<?php

/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 9/1/18
 * Time: 11:18 AM
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Helpers\NameHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use App\StoreCategory;
use App\User;
use App\Vendor;
use App\VendorProductCategory;
use Illuminate\Http\Request;
use App\VendorCategory;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Store;
use Intervention\Image\Facades\Image;
use App\ProductCategory;

class AdminVendorStoreController extends Controller
{

    private $validationRules = [
        'store_name' => 'required|unique:stores,store_name',
        'address' => 'required',
        'city' => 'required',
        'state' => 'required',
        'country' => 'required',
        'store_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
    ];

    /**
     * Display Store details.
     *
     * @return json
     */
    public function index()
    {

        return view('admin.stores.stores_list');
    }

    /**
     * Display create store page.
     *
     * @return json
     */
    public function create()
    {
        /* $vendor = Vendor::with('store')
          ->whereHas('store', function($q) {
          return $q->where('vendor_id', null);
          })->get();

          dd($vendor); */
        $data['vendor'] = Vendor::all()->where('has_store', 'No');
        $data['vendorCategory'] = ProductCategory::where('parent_category_id', NULL)
                                                 ->where('status', "=","Active")
                                                 ->with('subCategories')->orderBy('order_no', 'asc')->get();
                                                
        return view('admin.stores.store_create', $data);
    }

    /**
     * Save the Store.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $store = new Store();
        $vendor = new Vendor();
        $storeCategory = new StoreCategory();

        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();

        $this->validate($request, $this->validationRules);

        $storeImage = $request->file('store_image');
        $destinationPath = public_path('doc/store_image');
        $store_image = $request->store_image_1;
        $data = ImageHelper::imageSave($storeImage, $destinationPath, $store_image);
        if ( ! empty($data) && $data != 'false')
        {
            $store->store_image = $data;
        }
        $bannerImage = $request->file('banner_image');
        $destinationPath = public_path('doc/store_banner_image');
        $banner_image = $request->banner_image_1;
        $data = ImageHelper::imageSave($bannerImage, $destinationPath, $banner_image); //image save
        if ( ! empty($data) && $data != 'false')
        {
            $store->banner_image = $data;
            $imgUrl = public_path('doc/store_banner_image/').'/'.$data;
            $Path = public_path('doc/store_banner_images_front');
            $thumb_img = Image::make($imgUrl)->fit(360, 145, function ($constraint) { $constraint->aspectRatio(); } );
            /*$thumb_img = Image::make($imgUrl)->heighten(145, function ($constraint) {
                $constraint->upsize();
            });*/
            // $thumb_img->save($Path, $data);
            $thumb_img->save($Path.'/'.$data,80);
        }

        $store->fill($request->all());
        $store->store_status = $request->store_status;
        $store->vendor_id = $request->vendor_id;

        if ($store->save())
        {
            $storeSlug = str_slug($request->store_name, "-");
            DB::table('stores')
                    ->where('id', $store->id)
                    ->update(['store_slug' => $storeSlug]);
            $vendor->has_store = 'Yes';
            $vendor->save();
            $storeCategory = new StoreCategory();
            $storeCategoryData = array ();
            foreach ($request->category_id as $category_id)
            {
                $storeCategoryData[] = [
                    "vendor_category_id" => $category_id,
                    "store_id" => $store->id,
                    "added_by_user_id" => $loginUser->id
                ];
            }
            $storeCategory->insert($storeCategoryData);
            $TimeData = array ();
            $TimeDetail = $request->time;
            if (count($TimeDetail))
            {
                foreach ($TimeDetail as $key => $val)
                {
                    if (isset($val['is_fullday_open']))
                    {
                        $TimeData[] = [
                            "store_id" => $store->id,
                            "day" => $key,
                            "is_fullday_open" => "Yes",
                            "open_time" => null,
                            "closing_time" => null,
                        ];
                    }
                    else
                    {
                        $TimeData[] = [
                            "store_id" => $store->id,
                            "day" => $key,
                            "is_fullday_open" => "No",
                            "open_time" => date("H:i:s", strtotime($val['open_time'])),
                            "closing_time" => date("H:i:s", strtotime($val['closing_time'])),
                        ];
                    }
                }
            }
            DB::table('store_working_time')->insert($TimeData);
            return redirect(route('stores.index'))->with('success', trans('messages.store.added'));
        }
        return redirect(route('stores.index'))->with('error', trans('messages.error'));
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
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $query = Store::select('stores.id', 'store_name', 'stores.description', 'city', 'stores.featured', 'stores.status', DB::raw('group_concat(category_name) as vendor_category_name , CONCAT(first_name," ",last_name) AS vendor_name'))
                    ->leftJoin('store_category', 'store_category.store_id', '=', 'stores.id')
                    ->leftJoin('product_category', 'product_category.id', '=', 'store_category.vendor_category_id')
                    ->leftJoin('users', 'users.id', '=', 'stores.vendor_id')
                    ->groupBy('stores.id');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterStore($request->search['value'], $query);

            $store = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);

            $data = json_decode(json_encode($store));

            $data->recordsFiltered = $data->recordsTotal = $data->total;
            //echo "<pre>";print_r($data);die;
            foreach ($data->data as $stores)
            {
                //$stores->vendor_name = $stores->first_name .' '.$stores->last_name;
                $stores->action = '<a href="' . url(route('stores.edit', ['store' => $stores->id])) . '" title="Edit"><i class="la la-edit"></i></a>' .
                        '<a href="' . url(route('adminviewStore', ['store' => $stores->id])) . '" title="View"><i class="la la-eye"></i></a>' ;
                        /*'<a class="delete-data" data-name="store" href="' . url(route('admindeleteStore', ['store' => $stores->id])) . '" title="Delete"><i class="la la-trash"></i></a>';*/

                $stores->featured = ($stores->featured === 'Yes') ? '<a href="' . url(route('adminchangeStorefeatured', ['store' => $stores->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Yes</a>' : '<a href="' . url(route('adminchangeStorefeatured', ['store' => $stores->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">No</a>';

                $stores->status = ($stores->status === 'Active') ? '<a href="' . url(route('adminchangeStoreStatus', ['store' => $stores->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>' : '<a href="' . url(route('adminchangeStoreStatus', ['store' => $stores->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
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
        $query->where('store_name', 'like', '%' . $search . '%')
                ->orWhere('stores.status', 'like', '%' . $search . '%')
                ->orWhere('city', 'like', '%' . $search . '%')
                ->orWhere('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('stores.featured', 'like', '%' . $search . '%');
    }

    /**
     * Filter Store category listing.
     *
     * @param $search
     * @return $query
     */
    private function filterStoreCategory($search, $query)
    {
        $query->where(function ($query) use($search) {

            $query->where('vendor_product_categories.category_name', 'like', '%'.$search.'%')
                ->orWhere('vendor_product_categories.status', 'like', '%'.$search.'%')
                ->orWhere('vendor_product_categories.featured', 'like', '%'.$search.'%');
        });
    }

    /**
     * Delete store by unique identifier.
     *
     * @return json
     */
    public function destroy(Store $stored)
    {
        DB::table('users')
                ->where('id', $stored->vendor_id)
                ->update(['has_store' => 'No']);
        StoreCategory::where("store_id", $stored->id)->delete();
        if ($stored->delete())
        {

            return redirect(route('stores.index'))->with('success', trans('messages.store.deleted'));
        }

        return redirect(route('stores.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the store.
     *
     * @param Store $vendor
     * @return json
     */
    public function changeStatus(Store $stored)
    {
        if ($stored->status == 'Active')
        {
            $stored->status = 'Inactive';
            DB::table('products')
                ->where('vendor_id', $stored->vendor_id)
                ->update(['status' => 'Inactive']);
            Vendor::where('id',$stored->vendor_id)
                ->update(['status' => 0]);
        }
        else
        {
            $stored->status = 'Active';
            DB::table('products')
                ->where('vendor_id', $stored->vendor_id)
                ->update(['status' => 'Active']);
            Vendor::where('id',$stored->vendor_id)
                ->update(['status' => 1]);
        }
        if ($stored->save())
        {

            return redirect(route('stores.index'))->with('success', trans('messages.store.change_status'));
        }

        return redirect(route('stores.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change featured status of the store.
     *
     * @param Store $vendor
     * @return json
     */
    public function changeFeaturedStatus(Store $stored)
    {
        //dd($stored);die;
        if ($stored->featured === 'No')
        {
            $stored->featured = 'Yes';
        }
        else
        {
            $stored->featured = 'No';
        }
        if ($stored->save())
        {

            return redirect(route('stores.index'))->with('success', trans('messages.store.change_featured'));
        }

        return redirect(route('stores.index'))->with('error', trans('messages.error'));
    }


    /**
     * Show Store view page.
     *
     * @param Store $vendor
     * @return json
     */
    public function profile(Store $stored)
    {

        //dd($stored);die;
        $store_data = new Store();
        $store = $store_data->getStoreById($stored->id);
        $TimeData = DB::table('store_working_time')->where('store_id', '=', $stored->id)->get();
        $storeCategory = StoreCategory::where('store_id', '=', $stored->id)->pluck('vendor_category_id')->toArray();

        $category = NameHelper::getNameById('product_category', 'category_name', 'id', $storeCategory);

        $storeCategories = VendorProductCategory::select('vendor_product_categories.id', 'vendor_product_categories.category_name',
            'vendor_product_categories.category_image')
            ->where([
                'vendor_product_categories.vendor_id' => $stored->vendor_id,
                'vendor_product_categories.status' => 'Active'
            ])->leftJoin('store_product_categories as sc','sc.store_category_id','=','vendor_product_categories.id')
            ->leftJoin('products as p','p.id','=','sc.product_id')->where('p.status','Active')
            ->groupBy('vendor_product_categories.id')
            ->get();


        $TimeDetail = array ();
        if (count($TimeData))
        {
            foreach ($TimeData as $val)
            {
                $TimeDetail[$val->day] = [
                    "open_time" => $val->open_time,
                    "closing_time" => $val->closing_time,
                    "is_fullday_open" => $val->is_fullday_open
                ];
            }
        }
        $vendorDetail = Vendor::where("id","=",$stored->vendor_id)->first();
        $stored->first_name = $vendorDetail->first_name;
        $stored->last_name = $vendorDetail->last_name;
        $data['store'] = $stored;
        $data['storeCategory'] = implode(',', $category);
        $data['vendorStoreCategories'] = $storeCategories;
        $data['working_time'] = $TimeDetail;
        // dd($TimeDetail);die;
        return view('admin.stores.profile', $data);
    }




    /**
     * Show Store edit page.
     *
     * @param Store $users
     * @return json
     */
    public function edit(Store $store)
    {
        $store_data = new Store();
        $vendorDetails = Vendor::select('first_name', 'last_name')->where('id', $store->vendor_id)->first();
        $store->first_name = $vendorDetails->first_name;
        $store->last_name = $vendorDetails->last_name;

        //$Storedata = $store_data->getStoreById($store->id);
        $data['vendor'] = Vendor::where('status', '1')->get();
        $data['vendorCategory'] = ProductCategory::where('parent_category_id', NULL)
        ->where('status', "=","Active")
        ->with('subCategories')->orderBy('order_no', 'asc')->get();
        $TimeData = array ();
        $storeCategory = array ();
        if ($store)
        {
            $TimeData = DB::table('store_working_time')->where('store_id', '=', $store->id)->get();
            $storeCategory = StoreCategory::where('store_id', '=', $store->id)->pluck('vendor_category_id')->toArray();
        }
        $TimeDetail = array ();
        if (count($TimeData))
        {
            foreach ($TimeData as $val)
            {
                $TimeDetail[$val->day] = [
                    "open_time" => $val->open_time,
                    "closing_time" => $val->closing_time,
                    "is_fullday_open" => $val->is_fullday_open
                ];
            }
        }
        $data['storeCategory'] = $storeCategory;
        $data['working_time'] = $TimeDetail;
        $data['store'] = $store;
        //dd($store);die;
        $data['category'] = VendorCategory::all()->where('status', 'Active');
        return view('admin.stores.store_create', $data);
    }

    /**
     * Update the vendor.
     *
     * @param Request $request
     * @param int $vendorId
     * @return json
     */
    public function update(Request $request, Store $store)
    {

        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();


        $this->validationRules['store_name'] = 'required|unique:stores,store_name,' . $store->id;
        $this->validate($request, $this->validationRules);

        $storeImage = $request->file('store_image');
        $destinationPath = public_path('doc/store_image');
        $store_image = $request->store_image_1;
        $data = ImageHelper::imageSave($storeImage, $destinationPath, $store_image);
        if ( ! empty($data) && $data != 'false')
        {
            $store->store_image = $data;
        }
        $bannerImage = $request->file('banner_image');
        $destinationPath = public_path('doc/store_banner_image');
        $banner_image = $request->banner_image_1;
        $destinationPath1 = public_path('doc/store_banner_images_front');
        $data = ImageHelper::imageSave($bannerImage, $destinationPath, $banner_image ,$destinationPath1); //image save
        if ( ! empty($data) && $data != 'false')
        {
            $store->banner_image = $data;
            $imgUrl = public_path('doc/store_banner_image/').'/'.$data;
            $Path = public_path('doc/store_banner_images_front');
            $thumb_img = Image::make($imgUrl)->fit(360, 145, function ($constraint) { $constraint->aspectRatio(); } );
            /*$thumb_img = Image::make($imgUrl)->heighten(145, function ($constraint) {
                $constraint->upsize();
            });*/
            // $thumb_img->save($Path, $data);
            $thumb_img->save($Path.'/'.$data,80);
        }

        $store->fill($request->all());
        $store->store_status = $request->store_status;
        $store->status = $request->status;
        if ($store->save())
        {
            $storeSlug = str_slug($request->store_name, "-");
            DB::table('stores')
                    ->where('id', $store->id)
                    ->update(['store_slug' => $storeSlug]);
            $storeCategory = new StoreCategory();
            $storeCategoryData = array ();
            DB::table('store_category')->where('store_id', '=', $store->id)->delete();
            foreach ($request->category_id as $category_id)
            {
                $storeCategoryData[] = [
                    "vendor_category_id" => $category_id,
                    "store_id" => $store->id,
                    "added_by_user_id" => $loginUser->id
                ];
            }
            $storeCategory->insert($storeCategoryData);
            $TimeData = array ();
            $TimeDetail = $request->time;
            if (count($TimeDetail))
            {
                DB::table('store_working_time')->where('store_id', '=', $store->id)->delete();
                foreach ($TimeDetail as $key => $val)
                {
                    if (isset($val['is_fullday_open']))
                    {
                        $TimeData[] = [
                            "store_id" => $store->id,
                            "day" => $key,
                            "is_fullday_open" => "Yes",
                            "open_time" => null,
                            "closing_time" => null,
                        ];
                    }
                    else
                    {
                        $TimeData[] = [
                            "store_id" => $store->id,
                            "day" => $key,
                            "is_fullday_open" => "No",
                            "open_time" => date("H:i:s", strtotime($val['open_time'])),
                            "closing_time" => date("H:i:s", strtotime($val['closing_time'])),
                        ];
                    }
                }
            }
            DB::table('store_working_time')->insert($TimeData);
            return redirect(route('stores.index'))->with('success', trans('messages.store.updated'));
        }
        return redirect(route('stores.index'))->with('error', trans('messages.error'));
    }





    // Admin add store categories



    /**
     * Vendor store categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function vendorStoreCategoryList(Request $request)
    {
        if($request->ajax())
        {

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $query = VendorProductCategory::where('vendor_id', $request->vendorId)
                ->select('id', 'category_name', 'featured', 'status');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterStoreCategory($request->search['value'], $query);

            $product = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($product));
            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $category)
            {
                $category->action = '<a href="'.url(route('editStoreVendorCategory', ['vendor_product_category' => $category->id, 'store_id' => $request->storeId ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                    '<a class="delete-data" data-name="product category" href="' . url(route('removeStoreVendorCategory', ['vendor_product_category' => $category->id, 'store_id' => $request->storeId ])) . '" title="Delete"><i class="la la-trash"></i></a>';

                $category->featured = ($category->featured === 'Yes') ? '<a class="m-badge m-badge--success m-badge--wide" title="Active">Yes</a>' : '<a class="m-badge m-badge--danger m-badge--wide" title="Inactive">No</a>';

                $category->status = ($category->status === 'Active') ? '<a class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>' : '<a class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }


    /**
     * Vendor Store category create
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  vendorStoreCategoryCreate(Request $request)
    {
        $data['vendor_id'] = $request->vendor_id;
        $data['store_id'] = $request->store_id;

        return view('admin.stores.categories.categories_add', $data);
    }


    /**
     * Vendor Store category store
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function  vendorStoreCategoryStore(Request $request)
    {
        $this->validate($request, [
            'category_name' => 'required',
            'category_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
        //dd($request->all());
        $vendorproductCategory = new VendorProductCategory();

        $vendorproductCategory->fill($request->all());

        if($request->file('category_image'))
        {
            $image = $request->file('category_image');

            $destinationPath = public_path('doc/category_image');

            $user_img = $request->category_image_1;

            $data = ImageHelper::imageSave($image,$destinationPath,$user_img);
        }


        //echo "<pre>";print_r($data);
        if (!empty($data) && $data != 'false')
        {
            $vendorproductCategory->category_image = $data;
        }

        $vendorproductCategory->vendor_id = $request->vendor_id;
        $vendorproductCategory->featured = $request->featured;
        $vendorproductCategory->status = $request->status;

        if ($vendorproductCategory->save())
        {
            $categorySlug = str_slug($request->category_name.'-'.$vendorproductCategory->id, "-");

            DB::table('vendor_product_categories')
                ->where('id', $vendorproductCategory->id)
                ->update(['vendor_category_slug' => $categorySlug]);

            return redirect(route('adminviewStore', ['stored' => $request->store_id]))->with('success', trans('messages.vendor_product_category.added'));
        }

        return redirect(route('adminviewStore', ['stored' => $request->store_id]))->with('error', trans('messages.error'));
    }


    /**
     * Edit store vendor category
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editStoreVendorCategory(Request $request)
    {
        //dd($vendor_product_category);
        $vendorProductCategory = VendorProductCategory::find($request->vendor_product_category);

        $data['vendor_id'] = $vendorProductCategory->vendor_id;
        $data['category_data'] = $vendorProductCategory;
        $data['store_id'] = $request->store_id;
        //$data['categories'] = $categories;

        return view('admin.stores.categories.categories_add', $data);
    }

    /**
     * Update Store Vendor Category.
     *
     * @param Request $request
     * @param int $vendorId
     * @return json
     */
    public function updateStoreVendorCategory(Request $request)
    {

        $this->validate($request, [
            'category_name' => 'required',
            'category_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $category = VendorProductCategory::find($request->category);

        if($request->file('category_image'))
        {
            $image = $request->file('category_image');
            $destinationPath = public_path('doc/category_image');
            $user_img = $request->category_image_1;
            $data = ImageHelper::imageSave($image,$destinationPath,$user_img);
        }


        if(!empty($data) && $data != 'false')
        {
            $category->category_image = $data;
        }
        $category->fill($request->all());
        $category->vendor_id = $request->vendor_id;

        if ($category->save())
        {
            $categorySlug = str_slug($request->category_name.'-'.$category->id, "-");

            DB::table('vendor_product_categories')
                ->where('id', $category->id)
                ->update(['vendor_category_slug' => $categorySlug]);

            return redirect(route('adminviewStore', ['stored' => $request->store_id]))->with('success', trans('messages.vendor_product_category.updated'));
        }

        return redirect(route('adminviewStore', ['stored' => $request->store_id]))->with('error', trans('messages.error'));
    }


    /**
     * Remove Store Vendor Category
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeStoreVendorCategory(Request $request)
    {
        $vendor_product_category = VendorProductCategory::find($request->vendor_product_category);

        if ($vendor_product_category->delete())
        {
            return redirect(route('adminviewStore', ['stored' => $request->store_id]))->with('success', trans('messages.collection_products.removed'));
        }
        return redirect(route('adminviewStore', ['stored' => $request->store_id]))->with('error', trans('messages.error'));
    }
}
