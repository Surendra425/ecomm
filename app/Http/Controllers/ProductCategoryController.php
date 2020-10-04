<?php

/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 12/1/18
 * Time: 11:10 AM
 */

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\ProductCategory;
use App\ShopzzCategory;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class ProductCategoryController extends Controller
{

    private $validationRules = [
        'category_name' => 'required',
        'category_image' => 'image|mimes:jpeg,png,jpg,svg',
        'background_image' => 'image|mimes:jpeg,png,jpg,svg',
        'category_icon' => 'required',
    ];

    /**
     * Display Product Category details.
     *
     * @return json
     */
    public function index()
    {
        $loginUser = Auth::guard('admin')->user();
        return view('productCategory.category_list', ['loginUser' => $loginUser->type]);
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
        $loginUser = Auth::guard('admin')->user();
        //$loginUser->type;

        if ($loginUser->type == 'vendor')
        {
            $productCategory = ProductCategory::all()->where('status', 'Active')->where('added_by_user_id', $loginUser->id);
        }
        else
        {
            $productCategory = ProductCategory::where([
                'status' => 'Active',
                'parent_category_id' => null,
            ])->get();
        }
        return view('productCategory.category_create', [
            'category' => $productCategory,
            'loginUser' => $loginUser->type
        ]);
    }

    /**
     * Save the Product category.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $productCategory = new ProductCategory();

        $loginUser = Auth::guard('admin')->user();

        $productCategory->fill($request->all());
        $image = $request->file('category_image');
        $backgroundImage = $request->file('background_image');
        $destinationPath = public_path('doc/category_image');
        $bgDestinationPath = public_path('doc/category_image/mobile');
        $user_img = $request->category_image_1;
        
        
        if(!empty($image))
        {
            $data = ImageHelper::imageSave($image, $destinationPath, $user_img);

            if ( ! empty($data) && $data != 'false')
            {
                $productCategory->category_image = $data;
            }
        }
        
        if(!empty($backgroundImage))
        {
            $backGroundImageData = ImageHelper::imageSave($backgroundImage, $bgDestinationPath);
            
            if ( ! empty($backGroundImageData) && $backGroundImageData != 'false')
            {
                $productCategory->background_image = $backGroundImageData;
            }
        }
        

        $productCategory->featured = $request->featured;
        $productCategory->status = $request->status;
        if ( ! empty($request->parent_category_id) && $request->parent_category_id != 'Select Parent Category')
        {
            $productCategory->parent_category_id = $request->parent_category_id;
        }
        else
        {
            $productCategory->parent_category_id = Null;
        }
        $productCategory->added_by_user_id = $loginUser->id;


        if ($productCategory->save())
        {
            $categorySlug = str_slug($request->category_name . '-' . $productCategory->id, "-");
            DB::table('product_category')
                    ->where('id', $productCategory->id)
                    ->update(['category_slug' => $categorySlug]);
            return redirect(route('products-category.index'))->with('success', trans('messages.productCategory.added'));
        }

        return redirect(route('products-category.index'))->with('error', trans('messages.error'));
    }

    /**
     * Search product category.
     *
     * @return json
     */
    public function search(Request $request)
    {
         //dd($request->all());
        if ($request->ajax())
        {
            $loginUser = Auth::guard('admin')->user();

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $query = ProductCategory::select('product_category.id', 'product_category.category_name', 'product_category.parent_category_id', 'product_category.description', 'product_category.featured', 'product_category.status', 'category.category_name as parent_category_name'
                    )->leftjoin('product_category as category', 'category.id', '=', 'product_category.parent_category_id');
            if ($loginUser->type == 'vendor')
            {
                $query->where('product_category.added_by_user_id', $loginUser->id);
            }
            else
            {
                $query->where('product_category.added_by_user_id', $loginUser->id);
            }
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterStore($request->search['value'], $query);

            $productCategory = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);

            $data = json_decode(json_encode($productCategory));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $category)
            {
                $category->action = '<a href="' . url(route('editProductCategory', ['productCategory' => $category->id])) . '" title="Edit"><i class="la la-edit"></i></a>' .
                        '<a href="' . url(route('adminviewproductCategory', ['productCategory' => $category->id])) . '" title="View"><i class="la la-eye"></i></a>' .
                        '<a class="delete-data" data-name="product category" href="' . url(route('deleteProductCategory', ['category' => $category->id])) . '" title="Delete"><i class="la la-trash"></i></a>';

                $category->featured = ($category->featured === 'Yes') ? '<a href="' . url(route('changeProductCategoryFeature', ['productCategory' => $category->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Yes</a>' : '<a href="' . url(route('changeProductCategoryFeature', ['productCategory' => $category->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">No</a>';

                $category->status = ($category->status === 'Active') ? '<a href="' . url(route('changeProductCategoryStatus', ['productCategory' => $category->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>' : '<a href="' . url(route('changeProductCategoryStatus', ['productCategory' => $category->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
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
        $query->where('product_category.category_name', 'like', '%' . $search . '%')
                ->orWhere('category.category_name', 'like', '%' . $search . '%')
                ->orWhere('product_category.status', 'like', '%' . $search . '%')
                ->orWhere('product_category.description', 'like', '%' . $search . '%')
                ->orWhere('product_category.featured', 'like', '%' . $search . '%');
    }

    /**
     * Delete product category by unique identifier.
     *
     * @return json
     */
    public function destroy(ProductCategory $productCategory)
    {
        $cat = ShopzzCategory::where('shopzz_category_id' , $productCategory->id)->count();
        if($cat == 0){
            if ($productCategory->delete())
            {
                return redirect(route('products-category.index'))->with('success', trans('messages.productCategory.deleted'));
            }
        }else{
            return redirect(route('products-category.index'))->with('error', trans('messages.productCategory.error'));
        }


        return redirect(route('products-category.index'))->with('error', trans('messages.error'));
    }


    /**
     * Change status of the store.
     *
     * @param Store $vendor
     * @return json
     */
    public function changeStatus(ProductCategory $productCategory)
    {
        if ($productCategory->status == 'Active')
        {
            $productCategory->status = 'Inactive';
        }
        else
        {
            $productCategory->status = 'Active';
        }
        if ($productCategory->save())
        {

            return redirect(route('products-category.index'))->with('success', trans('messages.productCategory.change_status'));
        }

        return redirect(route('products-category.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change featured status of the store.
     *
     * @param Store $vendor
     * @return json
     */
    public function changeFeaturedStatus(ProductCategory $productCategory)
    {
        if ($productCategory->featured == 'No')
        {
            $productCategory->featured = 'Yes';
        }
        else
        {
            $productCategory->featured = 'No';
        }
        if ($productCategory->save())
        {

            return redirect(route('products-category.index'))->with('success', trans('messages.productCategory.change_featured'));
        }

        return redirect(route('products-category.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show category view page.
     *
     * @param ProductCategory $productCategory
     * @return json
     */
    public function profile(ProductCategory $productCategory)
    {
        $loginUser = Auth::guard('admin')->user();
        $parent_category_name = "";
        if (isset($productCategory->parent_category_id) && ! empty($productCategory->parent_category_id))
        {
            $parent_category = ProductCategory::select('category_name as parent_category_name')
                    ->where('id',"=", $productCategory->parent_category_id)
                    ->first();
            $parent_category_name = $parent_category->parent_category_name;
        }
        $productCategory['parent_category_name'] = $parent_category_name;

        //echo "<pre>";print_r($productCategory);die;
        return view('productCategory.profile', [
            'category' => $productCategory,
            'loginUser' => $loginUser->type
        ]);
    }

    /**
     * Show category edit page.
     *
     * @param ProductCategory $productCategory
     * @return json
     */
    public function edit(ProductCategory $productCategory)
    {
        //echo "<pre>";print_r($productCategory);die;
        $loginUser = Auth::guard('admin')->user();
        if ($loginUser->type == 'vendor')
        {
            $category_data = ProductCategory::all()->where('status', 'Active')->where('added_by_user_id', $loginUser->id)->whereNotIn('id', $productCategory->id);
        }
        else
        {
            $category_data = ProductCategory::all()->where('status', 'Active')->whereNotIn('id', $productCategory->id);
        }
        return view('productCategory.category_create', [
            'productCategory' => $productCategory,
            'category' => $category_data,
            'loginUser' => $loginUser->type
        ]);
    }

    /**
     * Update the category.
     *
     * @param Request $request
     * @param int $productCategory
     * @return json
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $this->validate($request, $this->validationRules);
        //$this->validate($request, $this->validationRules);
        $loginUser = Auth::guard('admin')->user();

        $image = $request->file('category_image');
        $backgroundImage = $request->file('background_image');
        
        $destinationPath = public_path('doc/category_image');
        $bgDestinationPath = public_path('doc/category_image/mobile');
        $user_img = $request->category_image_1;

        if(!empty($image))
        {
            $data = ImageHelper::imageSave($image, $destinationPath);
            
            if ( ! empty($data) && $data != 'false')
            {
                $productCategory->category_image = $data;
            }
        }
        
        if(!empty($backgroundImage))
        {
            $backGroundImageData = ImageHelper::imageSave($backgroundImage, $bgDestinationPath);
            
            if ( ! empty($backGroundImageData) && $backGroundImageData != 'false')
            {
                $productCategory->background_image = $backGroundImageData;
            }
        }
        
        $productCategory->fill($request->all());
        if ( ! empty($request->parent_category_id))
        {
            $productCategory->parent_category_id = $request->parent_category_id;
        }
        $productCategory->added_by_user_id = $loginUser->id;
        if ($productCategory->save())
        {
            $categorySlug = str_slug($request->category_name . '-' . $productCategory->id, "-");
            DB::table('product_category')
                    ->where('id', $productCategory->id)
                    ->update(['category_slug' => $categorySlug]);
            return redirect(route('products-category.index'))->with('success', trans('messages.productCategory.updated'));
        }

        return redirect(route('products-category.index'))->with('error', trans('messages.error'));
    }

}
