<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 17/1/18
 * Time: 3:31 PM
 */

namespace App\Http\Controllers;


use App\Country;
use App\OrderProduct;
use App\Product;
use App\ProductAttributeList;
use App\ProductCategory;
use App\ProductVideo;

use App\ShopzzCategory;
use App\StoreProductCategory;
use App\User;
use App\VendorProductCategory;
use App\VendorShippingDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Vendor;

use App\ProductAttrCombination;
use App\ProductImage;
use App\ProductShipping;

use App\ProductKeyword;
use App\Keywords;
use App\Helpers\NameHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;


class ProductController extends Controller
{

    private $validationRules = [

        'product_title' => 'required',
        'shopzz_category_id' => 'required',
        'store_category_id' => 'required',
 
    ];

    /**
     * Display Product details.
     *
     * @return json
     */
    public function index()
    {
         $url = request()->segment(1);
         $loginUser = Auth::guard($url)->user();
         $currentYear = Carbon::now()->year;
         $currentDay = Carbon::now()->day;
         $currentMinute = Carbon::now()->minute;
         $currentHour = Carbon::now()->hour;
         $currentMonth = Carbon::now()->month - 1;

         //dd($loginUser);die;
         return view('product.product_list', ['loginUser' => $loginUser->type, 
             'currentYear' => $currentYear,
             'currentDay' => $currentDay,
             'currentMinute' => $currentMinute,
             'currentHour' => $currentHour,
             'currentMonth' => $currentMonth,
         ]);
    }

    public function viewProductVendor($vendor)
    {

        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();

         $currentYear = Carbon::now()->year;
         $currentDay = Carbon::now()->day;
         $currentMinute = Carbon::now()->minute;
         $currentHour = Carbon::now()->hour;
         $currentMonth = Carbon::now()->month - 1;

        //dd($loginUser);die;
        $data['vendorId'] = $vendor;
        $data['loginUser'] = $loginUser->type;
        $data['currentYear'] = $currentYear;
        $data['currentDay'] = $currentDay;
        $data['currentMinute'] = $currentMinute;
        $data['currentHour'] = $currentHour;
        $data['currentMonth'] = $currentMonth;

        
        return view('product.product_list', $data);
    }

    /**
     * Display create Product page.
     *
     * @return json
     */
    public function create()
    {
        $parentCategories = ProductCategory::where('status', 'Active')
            ->where('parent_category_id', null)
            ->select('id','category_name')->get()->keyBy('id')->toArray();

        $mainCategory = ProductCategory::where('status', 'Active')->where('parent_category_id','!=', null)->select('id','category_name','parent_category_id')->get();

        $mainCategory = $mainCategory->groupBy('parent_category_id');

        /*$attribute = ProductAttributeList::where('status', 'Active')->get();*/

        $country = Country::where('status', 'Active')->select('id','country_name','short_name')->get();
        dd($country);
        //$country = VendorShippingDetail::get();
        $vendor = Vendor::where('status', '1')->get();
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        return view('product.product_create', [
            'loginUser' => $loginUser->type,
            'loginUserId' => $loginUser->id,
            'vendor' => $vendor,
            'mainCategory' => $mainCategory,
            'parentCategories' => $parentCategories,
        //    'attribute' => $attribute,
            'country' => $country,
        ]);
    }

    /**
     * Search product.
     *
     * @return json
     */
    public function search(Request $request)
    {
        //dd($request->vendorId);die;
        if ($request->ajax()) {
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $url = request()->segment(1);
            $loginUser = Auth::guard($url)->user();
            $query = Product::select('products.id', 'products.status', 'products.gender_type', 'products.home_date_time',
                'product_title', DB::raw('CONCAT(first_name," ",last_name) AS vendor_name')
            )->leftjoin('users', 'users.id', '=', 'products.vendor_id');
            if ($loginUser->type == 'vendor') {
                $query->where('products.vendor_id', "$loginUser->id");
            }
            if (!empty($request->vendorId)) {
                $query->where('products.vendor_id', $request->vendorId);
            }
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterProduct($request->search['value'], $query);

            $product = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($product));
//dd($data);die;
            $data->recordsFiltered = $data->recordsTotal = $data->total;
            //dd($data);die;
            foreach ($data->data as $products) {
                //$products->vendor_name = $products->first_name .' '.$products->last_name;
                $products->action = '<a href="' . url(route('products.edit', ['product' => $products->id])) . '" title="Edit"><i class="la la-edit"></i></a>' .
                    '<a href="' . url(route('viewProduct', ['product' => $products->id])) . '" title="View"><i class="la la-eye"></i></a>';
                //'<a class="delete-data" data-name="product" href="'.url(route('deleteProduct', ['product' => $products->id ])).'" title="Delete"><i class="la la-trash"></i></a>';

                $productHomeDate = !empty($products->home_date_time) ? Carbon::parse($products->home_date_time)->format('d/m/Y H:i') : 'Select Date';
                $date =  !empty($products->home_date_time) ? Carbon::parse($products->home_date_time)->format('d/m/Y H:i') : '';
                $products->home_date_time = '<a id="home_date_link_'.$products->id.'" data-toggle="modal" data-product-id="'.$products->id.'" data-target="#UpdateDateModel" class="update_home_date" href="javascript:void(0);" data-date="'.$date.'" data-url="' . url(route('ajaxProductHomeDateUpdate', ['product' => $products->id])) . '" title="Select Date">'.$productHomeDate.'</a>';
                
                $products->gender_type = '<a id="gender_type_link_'.$products->id.'" class="update_gender_type" data-product-id="'.$products->id.'" data-toggle="modal" data-gender="'.$products->gender_type.'" data-target="#UpdateGenderModel" href="javascript:void(0);" data-url="'.url(route('ajaxProductGenderTyeUpdate', ['product' => $products->id])) . '" title="Select Prodcut Type">'.$products->gender_type.'</a>';
                    
                $products->status = ($products->status === 'Active') ? '<a href="' . url(route('changeProductStatus', ['product' => $products->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="' . url(route('changeProductStatus', ['product' => $products->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }
            // dd($data);die;
            return response()->json($data);
        }
    }

    /**
     * Filter Product listing.
     *
     * @param $search
     * @return $query
     */
    private function filterProduct($search, $query)
    {
        $query->where(function ($query) use ($search) {

            $query->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('product_title', 'like', "%$search%")
                ->orWhere('products.id', 'like', "%$search%")
                ->orWhere('products.status', 'like', "%$search%");
        });
    }

    
    /**
     * Update the product home date.
     *
     * @param Request $request
     * @param Product $product
     * @return json
     */
    public function updateProductHomeDate(Request $request, Product $product)
    {
        $this->validate($request, [
            'home_date_time' => 'required',
        ]);

        $product->home_date_time = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->home_date_time)));
        
        $product->save();
        
        return response()->json([
            'success' => 1,
        ]);
    }
    
    /**
     * Update the product gender.
     *
     * @param Request $request
     * @param Product $product
     * @return json
     */
    public function updateProductGender(Request $request, Product $product)
    {
        $this->validate($request, [
            'gender_type' => 'required|in:Both,Male,Female',
        ]);
        
        $product->gender_type = $request->gender_type;
        
        $product->save();
        
        return response()->json([
            'success' => 1,
        ]);
    }
    
    /**
     * Save the Product.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        
        //print_r($request->ColourValue);
         //dd($request);die;

        $this->validate($request, $this->validationRules);
        if (isset($request->options) && empty($request->options[0]['options']) && isset($request->qty_default) && empty($request->qty_default)) {
             if(isset($request->qty_default) && empty($request->qty_default) && $request->qty_default == 0){
                return Redirect::back()->with('error', "Qty must be grater then 0.");
            }
            return Redirect::back()->with('error', "Price and Qty is required");
        } else {
            $product = new Product();


            $commonController = New CommonController();

            $url = request()->segment(1);
            $loginUser = Auth::guard($url)->user();

            //product detils
            $product->fill($request->all());

            $product->vendor_id = $request->vendor_id;
            $product->long_description = $request->long_description;
            $product->product_unit_no = $commonController->getProductSerialNumber(4);
            /*$product->weight = $request->weight;
            $product->height = $request->height;
            $product->length = $request->length;
            $product->width = $request->width;*/

            $product->added_by_user_id = $loginUser->id;
            //$product->product_category_id = $request->store_category_id;
            $product->save();
            $productId = $product->id;
            $productSlug = str_slug($request->product_title . '-' . $product->id, "-");
            DB::table('products')
                ->where('id', $product->id)
                ->update(['product_slug' => $productSlug]);
            //image save

            $productImages = explode('"', $request->productImages);
            $productImages = array_filter($productImages);
            foreach ($productImages as $images) {
                $productImage = new ProductImage();
                $productImage->product_id = $productId;
                $productImage->image_url = $images;
                $productImage->save();
            }

            //product video save
            
            if (!empty($request->productVideo)) {
                $productVideos = explode('"', $request->productVideo);
                $productVideos = array_filter($productVideos);
                foreach ($productVideos as $videos) {
                    $video = str_replace('"', "", $videos);
                    $productVideo = new ProductVideo();
                    $productVideo->product_id = $productId;
                    $productVideo->video_url =$video;
                    $productVideo->save();
                }
                
            }

            //insert options
            if (isset($request->options) && !empty($request->options)) {
                foreach ($request->options as $options) {
                    if ($options['options'] != NULL) {
                        $productAttrCombination = new ProductAttrCombination();
                        $productAttrCombination->combination_title = $options['options'];
                        $productAttrCombination->quantity = $options['qty'];
                        $productAttrCombination->rate = $options['price'];
                        $productAttrCombination->product_id = $productId;
                        $productAttrCombination->save();
                        $productCombiSlug = str_slug($options['options'] . '-' . $product->id, "-");
                        DB::table('product_attr_combination')
                            ->where('id', $productAttrCombination->id)
                            ->update(['combination_slug' => null]);
                        $productKeywords = new ProductKeyword();
                        $keywords = new Keywords();
                        $keyword = Keywords::where('keyword', $options['options'])->count();
                        if ($keyword < 1) {
                            $keywords->keyword = $options['options'];
                            $keywords->save();
                            $productKeywords->product_id = $productId;
                            $productKeywords->attr_cat_id = $productAttrCombination->id;
                            $productKeywords->keyword_id = $keywords->id;
                            $productKeywords->save();
                        }
                    }
                }
            }
            if (isset($request->qty_default) && !empty($request->qty_default)) {
                $productAttrCombination = new ProductAttrCombination();
                $productAttrCombination->quantity = $request->qty_default;
                $productAttrCombination->rate = $request->price_default;
                $productAttrCombination->product_id = $productId;
                $productAttrCombination->save();
            }
            //shopzz category insert
            if (isset($request->shopzz_category_id) && !empty($request->shopzz_category_id)) {
                foreach ($request->shopzz_category_id as $shopzzCategory) {
                    $shopzzCategories = new ShopzzCategory();
                    $shopzzCategories->shopzz_category_id = $shopzzCategory;
                    $shopzzCategories->product_id = $productId;
                    $shopzzCategories->shopzz_category_name = NameHelper::getNameBySingleId('product_category', 'category_name', 'id', $shopzzCategory);
                    $shopzzCategories->save();
                }
            }
            //store category insert
            if (isset($request->store_category_id) && !empty($request->store_category_id)) {
                foreach ($request->store_category_id as $storeCategory) {
                    $storeCategoires = new StoreProductCategory();
                    $storeCategoires->store_category_id = $storeCategory;
                    $storeCategoires->product_id = $productId;
                    $storeCategoires->store_category_name = NameHelper::getNameBySingleId('vendor_product_categories', 'category_name', 'id', $storeCategory);
                    $storeCategoires->save();
                }
            }

            //save product shipping
            $productShipping = new ProductShipping();
            $ShippingData = [];
            $ShippingDetail = $request->checkCountry;
            if ( ! empty($ShippingDetail)) {
                foreach ($request->checkCountry as $key => $shipping) {
                    $ShippingData[] = array (
                        "product_id" => $productId,
                        "country_id" => $shipping,
                        "country_name" => $request->country_name[$key],
                        'added_by_user_id' => $loginUser->id
                    );
                }
                $productShipping->insert($ShippingData);
            }
            /*$productShipping->product_id = $productId;
            $productShipping->shipping_charge = $request->charge;
            $productShipping->country_id = $request->country;
            $productShipping->country_name = NameHelper::getNameBySingleId('country', 'country_name', 'id', $request->country);
            $productShipping->delivery_day_1 = $request->dayFrom;
            $productShipping->delivery_day_2 = $request->dayTo;
            $productShipping->added_by_user_id = $loginUser->id;*/


            //save product kewyword

            $keyword = Keywords::where('keyword', $request->keyword)->count();
            if (!$keyword > 0) {
                if ($request->keyword != null) {
                    foreach ($request->keyword as $values) {
                        $keywords = new Keywords();
                        $productKeywords = new ProductKeyword();
                        if (is_numeric($values)) {
                            $keywords->keyword = NameHelper::getNameBySingleId('keywords', 'keyword', 'id', $values);
                        } else {
                            $keywords->keyword = $values;

                        }
                        $keywords->save();
                        $productKeywords->product_id = $productId;
                        $productKeywords->keyword_id = $keywords->id;
                        $productKeywords->save();

                    }
                }

            }

            if (isset($product->id) && !empty($product->id)) {
                return redirect(route('products.index'))->with('success', trans('messages.products.added'));
            }

            return redirect(route('products.index'))->with('error', trans('messages.error'));
        }
    }

    /**
     * Delete Product by unique identifier.
     * developed by Nikita Patel
     * @return json
     */
    public function destroy(Product $product)
    {
        /* $productCate = ProductCategory::where("parent_category_id", $productCategory->id)->get();

         foreach($productCate as $list){
             DB::table('product_category')
                 ->where('parent_category_id', $productCategory->id)
                 ->update(['parent_category_id' => NULL ]);
         }*/

        if ($product->delete()) {

            return redirect(route('products.index'))->with('success', trans('messages.products.deleted'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the product.
     *
     * @param Product $product
     * @return json
     * developed by Nikita Patel
     */
    public function changeStatus(Product $product)
    {
        if ($product->status == 'Active') {
            $product->status = 'Inactive';
        } else {
            $product->status = 'Active';
        }
        if ($product->save()) {

            return redirect(route('products.index'))->with('success', trans('messages.products.change_status'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change featured status of the product.
     *
     * @param Product $product
     * developed by Nikita Patel
     * @return json
     */
    public function changeFeaturedStatus(Product $product)
    {
        if ($product->featured == 'No') {
            $product->featured = 'Yes';
        } else {
            $product->featured = 'No';
        }
        if ($product->save()) {

            return redirect(route('products.index'))->with('success', trans('messages.products.change_featured'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show Product view page.
     *
     * @param Product $product
     * developed by Nikita Patel
     * @return json
     */
    public function profile(Product $product)
    {

        $productShopzzCategory = ShopzzCategory::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name'))->where('product_id', $product->id)->groupBy('product_id')->first();
        $productStoreCategory = StoreProductCategory::select(DB::raw('group_concat(store_category_name) as store_category_name'))->where('product_id', $product->id)->groupBy('product_id')->first();
        $productAttrCombination = ProductAttrCombination::where('product_id', $product->id)->where('is_delete','0')->get();
        $productImage = ProductImage::where('product_id', $product->id)->get();
        $productShipping = VendorShippingDetail::select()
            ->leftjoin('product_shipping','product_shipping.country_id','vendor_shipping_detail.country_id')
            ->where('product_shipping.product_id', $product->id)->where('vendor_shipping_detail.vendor_id', $product->vendor_id)
            ->groupBY('vendor_shipping_detail.country_id')->get();
        $productVideo = ProductVideo::where('product_id', $product->id)->first();
        $vendorName = Vendor::select('first_name', 'last_name')->where('id', $product->vendor_id)->first();
        $product->vendor_name = $vendorName->first_name . ' ' . $vendorName->last_name;

         //echo "<pre>";print_r($productShipping);die;
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        return view('product.profile', [
            'loginUser' => $loginUser->type,
            'product' => $product,
            'productShopzzCategory' => $productShopzzCategory,
            'productStoreCategory' => $productStoreCategory,
            'productAttrCombination' => $productAttrCombination,
            'productVideo' => $productVideo,
            'productImage' => $productImage,
            'productShipping' => $productShipping,
            //'productAttr' =>$productAttr,
        ]);
    }

    /**
     * Show Product edit page.
     *
     * @param Product $product
     * developed by Nikita Patel
     * @return json
     */
    public function edit(Product $product)
    {
         echo 'hello';exit;
        //echo "<pre>";
        
        $parentCategories = ProductCategory::where('status', 'Active')->where('parent_category_id', null)->get()->keyBy('id')->toArray();
        
        $shopzzCategory = ProductCategory::where('status', 'Active')->where('parent_category_id','!=', null)->get();
        
        $shopzzCategory = $shopzzCategory->groupBy('parent_category_id');
        
        //$shopzzCategory = ProductCategory::where('status', 'Active')->get();
        
        $storeCategory = VendorProductCategory::where('status', 'Active')->where('vendor_id', $product->vendor_id)->get();
        $vendor = Vendor::where('status', '1')->get();
        $keyword = ProductKeyword::select('products_keywords.id', 'keyword_id', 'keywords.keyword')
            ->leftjoin('keywords', 'keywords.id', '=', 'products_keywords.keyword_id')
            ->where('products_keywords.product_id', $product->id)->get();
        $productShopzzCategory = ShopzzCategory::where('product_id', $product->id)->get();
        $productStoreCategory = StoreProductCategory::where('product_id', $product->id)->get();
        $productAttrCombination = ProductAttrCombination::where('product_id', $product->id)->where('is_delete', '0')->get();
        
        $productImage = ProductImage::where('product_id', $product->id)->get();
        $productShipping = VendorShippingDetail::select('product_shipping.country_id')
            ->leftjoin('product_shipping','product_shipping.country_id','vendor_shipping_detail.country_id')
            ->where('product_shipping.product_id', $product->id)
            ->where('vendor_shipping_detail.vendor_id', $product->vendor_id)->groupby('product_shipping.country_id')->get();
        $vendorShipping = VendorShippingDetail::where('vendor_shipping_detail.vendor_id', $product->vendor_id)->groupby('vendor_shipping_detail.country_id')->get();
        $productVideo = ProductVideo::where('product_id', $product->id)->get();
        $country = Country::where('status', 'Active')->get();
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        $shipping= [];
foreach($productShipping as $item){
    $shipping[] = $item->country_id;
}
        //echo "<pre>";print_r($shipping);die;
        return view('product.product_edit', [
            'loginUser' => $loginUser->type,
            'loginUserId' => $loginUser->id,
            'vendor' => $vendor,
            'shopzzCategory' => $shopzzCategory,
            'storeCategory' => $storeCategory,
            'productShopzzCategory' => $productShopzzCategory,
            'productStoreCategory' => $productStoreCategory,
            'product' => $product,
            'country' => $country,
            'keyword' => $keyword,
            'productAttrCombination' => $productAttrCombination,
            'productImage' => $productImage,
            'productShipping' => $shipping,
            'vendorShipping' => $vendorShipping,
            'parentCategories' => $parentCategories,
            'productVideo' => $productVideo
        ]);
    }

    /**
     * Update the product.
     *
     * @param Request $request
     * @param int $product
     * developed by Nikita Patel
     * @return json
     */
    public function update(Request $request, Product $product)
    {
     
       
        $this->validate($request, $this->validationRules);
        
        if (isset($request->options) && empty($request->options[0]['options']) && isset($request->qty_default) && empty($request->qty_default)) {
            if(isset($request->qty_default) && empty($request->qty_default) && $request->qty_default == 0){
                return Redirect::back()->with('error', "Qty must be grater then 0.");
            }
            return Redirect::back()->with('error', "Price and Qty is required");
        } else {
            $commonController = New CommonController();

            if($request->combination != $product->combination)
            {
                DB::table('product_attr_combination')
                ->where('product_id', $product->id)
                ->update(['is_delete' => '1']); 
            }

            $url = request()->segment(1);
            $loginUser = Auth::guard($url)->user();
            $productId = $product->id;
            //product detils
            $product->fill($request->all());
            $product->long_description = $request->long_description;
            $product->vendor_id = $request->vendor_id;
            $product->added_by_user_id = $loginUser->id;
            $product->save();
            $productSlug = str_slug($request->product_title . '-' . $product->id, "-");
            DB::table('products')
                ->where('id', $product->id)
                ->update(['product_slug' => $productSlug]);

            //image save

            $productImages = explode('"', $request->productImages);
            $productImages = array_filter($productImages);
            foreach ($productImages as $images) {
                $productImage = new ProductImage();
                $productImage->product_id = $productId;
                $productImage->image_url = $images;
                $productImage->save();
            }

            //product video save
            if (!empty($request->productVideo)) {
                $productVideos = explode('"', $request->productVideo);
                $productVideos = array_filter($productVideos);
                foreach ($productVideos as $videos) {
                    $video = str_replace('"', "", $videos);
                    $productVideo = new ProductVideo();
                    $productVideo->product_id = $productId;
                    $productVideo->video_url =$video;
                    $productVideo->save();
                }
                
            }
            
            
            
            //insert options
            $combination = ProductAttrCombination::select('product_attr_combination.id')
                ->leftjoin('order_products', 'order_products.product_combination_id', 'product_attr_combination.id')
                ->where('product_attr_combination.product_id', '=', $productId)
                ->get();
                $cartCmbination = ProductAttrCombination::select('product_attr_combination.id')
                ->leftjoin('product_cart', 'product_cart.product_combination_id', 'product_attr_combination.id')
                ->where('product_cart.product_id', '=', $productId)
                ->get();
            
            $combinationId = [];
            
            foreach ($combination as $list) {
                $combinationId[] = $list->id;
            }
            foreach ($cartCmbination as $list) {
                $combinationId[] = $list->id;
            }
            
            //dd($combinationId);die;
            //echo "<pre>";print_r($combination);print_r($combinationId);die;
            //$order = OrderProduct::whereIn('product_combination_id',$combinationId)->get();
            DB::table('product_attr_combination')
                ->whereIn('id', $combinationId)
                ->update(['is_delete' => '1']);
            DB::table('product_attr_combination')->where('product_id', '=', $productId)->whereNotIn('id', $combinationId)->delete();
           // dd($request->options);die;
           /* if($request->combination == 'No'){
                //echo "hi";die;
              
                $productAttrCombination = new ProductAttrCombination();
                $productAttrCombination->quantity = $request->qty_default;
                $productAttrCombination->rate = $request->price_default;
                $productAttrCombination->product_id = $productId;
                $productAttrCombination->save();
            }else{
                //echo "hello";die;
                foreach ($request->option as $options) {
                    if ($options['options'] != null) {

                        $productAttrCombination = new ProductAttrCombination();
                        $productAttrCombination->combination_title = $options['options'];
                        $productAttrCombination->quantity = $options['qty'];
                        $productAttrCombination->rate = $options['price'];
                        $productAttrCombination->product_id = $product->id;
                        $productAttrCombination->save();
                        $productCombiSlug = str_slug($options['options'] . '-' . $product->id, "-");
                        DB::table('product_attr_combination')
                            ->where('id', $productAttrCombination->id)
                            ->update(['combination_slug' => null]);
                        $productKeywords = new ProductKeyword();
                        $keywords = new Keywords();
                        $keyword = Keywords::where('keyword', $options['options'])->count();
                        if ($keyword < 1) {
                            $keywords->keyword = $options['options'];
                            $keywords->save();
                            $productKeywords->product_id = $product->id;
                            $productKeywords->attr_cat_id = $productAttrCombination->id;
                            $productKeywords->keyword_id = $keywords->id;
                            $productKeywords->save();
                        }
                    }
                }
            }*/
            if ($request->combination == 'Yes' && isset($request->options) && count($request->options) > 0) 
            {
  
                foreach ($request->options as $options) {
                    if ($options['options'] != null) {

                        $productAttrCombination = new ProductAttrCombination();
                        $productAttrCombination->combination_title = $options['options'];
                        $productAttrCombination->quantity = $options['qty'];
                        $productAttrCombination->rate = $options['price'];
                        $productAttrCombination->product_id = $product->id;
                        $productAttrCombination->save();
                        $productCombiSlug = str_slug($options['options'] . '-' . $product->id, "-");
                        DB::table('product_attr_combination')
                            ->where('id', $productAttrCombination->id)
                            ->update(['combination_slug' => null]);
                        $productKeywords = new ProductKeyword();
                        $keywords = new Keywords();
                        $keyword = Keywords::where('keyword', $options['options'])->count();
                        if ($keyword < 1) {
                            $keywords->keyword = $options['options'];
                            $keywords->save();
                            $productKeywords->product_id = $product->id;
                            $productKeywords->attr_cat_id = $productAttrCombination->id;
                            $productKeywords->keyword_id = $keywords->id;
                            $productKeywords->save();
                        }
                    }
                }
            }
            elseif (isset($request->price_default) && !empty($request->price_default)) {
               /* ProductAttrCombination::where('product_id',$productId)
                ->update(['quantity'=>$request->qty_default,'rate'=>$request->price_default]);*/
                $productAttrCombination = new ProductAttrCombination();
                $productAttrCombination->quantity = $request->qty_default;
                $productAttrCombination->rate = $request->price_default;
                $productAttrCombination->product_id = $productId;
                $productAttrCombination->save();
            }
            /* if (isset($request->option) && !empty($request->option)) {
                foreach ($request->option as $key => $value) {
                    if ($value != null) {
                        $productAttrCombination = new ProductAttrCombination();
                        $productAttrCombination->combination_title = $value;
                        $productAttrCombination->quantity = $request->qty[$key];
                        $productAttrCombination->rate = $request->price[$key];
                        $productAttrCombination->product_id = $product->id;
                        $productAttrCombination->save();
                        $productKeywords = new ProductKeyword();
                        $productCombiSlug = str_slug($value . '-' . $product->id, "-");
                        DB::table('product_attr_combination')
                            ->where('id', $productAttrCombination->id)
                            ->update(['combination_slug' => null]);
                        $keywords = new Keywords();
                        $keyword = Keywords::where('keyword', $value)->count();
                        if ($keyword < 1) {
                            $keywords->keyword = $options['options'];
                            $keywords->save();
                            $productKeywords->product_id = $product->id;
                            $productKeywords->attr_cat_id = $productAttrCombination->id;
                            $productKeywords->keyword_id = $keywords->id;
                            $productKeywords->save();
                        }
                    }
                }
            } */
            DB::table('shopzz_product_categories')->where('product_id', '=', $productId)->delete();
            
            //shopzz category insert
            if (isset($request->shopzz_category_id) && !empty($request->shopzz_category_id)) {
                foreach ($request->shopzz_category_id as $shopzzCategory) {
                    $shopzzCategories = new ShopzzCategory();
                    $shopzzCategories->shopzz_category_id = $shopzzCategory;
                    $shopzzCategories->product_id = $product->id;
                    $shopzzCategories->shopzz_category_name = NameHelper::getNameBySingleId('product_category', 'category_name', 'id', $shopzzCategory);
                    $shopzzCategories->save();
                }
            }
            DB::table('store_product_categories')->where('product_id', '=', $productId)->delete();
            //store category insert
            if (isset($request->store_category_id) && !empty($request->store_category_id)) {
                foreach ($request->store_category_id as $storeCategory) {
                    $storeCategoires = new StoreProductCategory();
                    $storeCategoires->store_category_id = $storeCategory;
                    $storeCategoires->product_id = $product->id;
                    $storeCategoires->store_category_name = NameHelper::getNameBySingleId('vendor_product_categories', 'category_name', 'id', $storeCategory);
                    $storeCategoires->save();
                }
            }

            //save product shipping
            $countrySelected = ProductShipping::where("product_id", $product->id)->pluck("country_id");

            $requestCountries = collect($request->checkCountry);
            $newCountries = $requestCountries->diff($countrySelected);
            $deletedCountries = $countrySelected->diff($requestCountries);
            
            if(!$newCountries->isEmpty())
            {
                $countries = Country::whereIn('id', $newCountries->toArray())->get()->keyBy('id')->toArray();
                $ShippingData = [];
                foreach($newCountries as $newCountry)
                {

                    $ShippingData[] = array(
                        "product_id" => $product->id,
                        "country_id" => $newCountry,
                        "country_name" => $countries[$newCountry]['country_name'],
                        'added_by_user_id' => $loginUser->id
                    );
                    
                   
                }

                if(!empty($ShippingData))
                {
                    $productShipping = new ProductShipping();
                    $productShipping->insert($ShippingData);
                }
                
            }
            
            if(!$deletedCountries->isEmpty())
            {
                DB::table('product_shipping')->whereIn('country_id', $deletedCountries->toArray())->where('product_id', $product->id)->delete();
            }
            
            /* $countrySelected = [];
            $ShippingData = [];
            $ShippingDetail = $request->checkCountry;
            $productShipping = new ProductShipping();
            if ( ! empty($ShippingDetail)) {
                //dd($shipping);die;
                foreach ($request->checkCountry as $key => $shipping) {

                    if (in_array($shipping, $countrySelected)) {
                       
                        
                        unset($countrySelected[$key]);
                        
                    } else {
                        
                        DB::table('product_shipping')->whereIn('country_id', $countrySelected)->where('product_id', $product->id)->delete();
                        
                    }
                    
                    
                }
                
            } */
            /*$productShipping = array(
                'country_name' => $country_name,
                'shipping_charge' => $request->charge,
                'country_id' => $request->country,
                'delivery_day_1' => $request->dayFrom,
                'delivery_day_2' => $request->dayTo,
                'added_by_user_id' => $loginUser->id
            );
            DB::table('product_shipping')
                ->where('product_id', $product->id)
                ->update($productShipping);*/
            /* $productShipping = new ProductShipping();
             $productShipping->product_id = $productId;
             $productShipping->shipping_charge = $request->charge;
             $productShipping->country_id = $request->country;

             $productShipping->delivery_day_1 = $request->dayFrom;
             $productShipping->delivery_day_2 = $request->dayTo;
             $productShipping->added_by_user_id = $loginUser->id;
             $productShipping->save();*/


            //$productShipping->save();
            //DB::table('product_shipping')->where('product_id', $productId)->update($productShipping);
            //save product kewyword

            if ($request->keyword != null) {
                foreach ($request->keyword as $values) {
                    $keywords = new Keywords();
                    $productKeywords = new ProductKeyword();
                    if (is_numeric($values)) {
                        $keywordName = NameHelper::getNameBySingleId('keywords', 'keyword', 'id', $values);
                        $keyword = Keywords::where('keyword', $keywordName)->count();
                        $keywords->keyword = $keywordName;
                        $productKeyword = ProductKeyword::where('keyword_id', $values)->where('product_id', $product->id)->count();
                        if (!($productKeyword >= 1)) {
                            $productKeywords->product_id = $product->id;
                            $productKeywords->keyword_id = $values;
                            $productKeywords->save();
                        }
                    } else {
                        $keyword = Keywords::where('keyword', $values)->count();
                        $keywords->keyword = $values;
                    }
                    //dd($keyword);die;
                    if (!($keyword >= 1)) {
                        $keywords->save();
                        $productKeywords->product_id = $product->id;
                        $productKeywords->keyword_id = $keywords->id;
                        $productKeywords->save();
                    }

                }
            }

            if (isset($product->id) && !empty($product->id)) {
                return redirect(route('products.index'))->with('success', trans('messages.products.added'));
            }

            return redirect(route('products.index'))->with('error', trans('messages.error'));
        }
        
        
    }

    /**
     * Display Recent Product details.
     *
     * @return json
     * developed by Nikita Patel
     */
    public function recentProduct()
    {
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        //dd($loginUser);die;
        return view('product.recent_product_list', ['loginUser' => $loginUser->type]);
    }

    /**
     * Search recent product.
     * developed by Nikita Patel
     * @return json
     */
    public function recentproductSearch(Request $request)
    {
        if ($request->ajax()) {
            $today = Carbon::today();
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $url = request()->segment(1);
            $loginUser = Auth::guard($url)->user();
            $query = Product::select('products.id', 'products.status',
                'first_name', 'last_name', 'product_title'
            )->leftjoin('users', 'users.id', '=', 'products.vendor_id')
                ->where('products.created_at', '>=', $today)
                ->groupBy('products.id');
            if ($loginUser->type == 'vendor') {
                $query->where('products.vendor_id', "$loginUser->id");
            }
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterProduct($request->search['value'], $query);

            $product = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($product));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $products) {
                $products->vendor_name = $products->first_name . ' ' . $products->last_name;
                $products->action = '<a href="' . url(route('viewRecentProduct', ['product' => $products->id])) . '" title="View"><i class="la la-eye"></i></a>';

                $products->status = ($products->status === 'Active') ? '<a href="' . url(route('recentChangeStatus', ['product' => $products->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="' . url(route('recentChangeStatus', ['product' => $products->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }
            return response()->json($data);
        }
    }

    /**
     * Show Recent Product view page.
     *
     * @param Product $product
     * @return json
     *  developed by Nikita Patel
     */
    public function viewRecentProduct(Product $product)
    {

        $productShopzzCategory = ShopzzCategory::select(DB::raw('group_concat(shopzz_category_name) as shopzz_category_name'))->where('product_id', $product->id)->groupBy('product_id')->first();
        $productStoreCategory = StoreProductCategory::select(DB::raw('group_concat(store_category_name) as store_category_name'))->where('product_id', $product->id)->groupBy('product_id')->first();
        $productAttrCombination = ProductAttrCombination::where('product_id', $product->id)->where('is_delete', '0')->get();
        $productImage = ProductImage::where('product_id', $product->id)->get();
        $productShipping = VendorShippingDetail::select()
            ->leftjoin('product_shipping','product_shipping.country_id','vendor_shipping_detail.country_id')
        ->where('product_shipping.product_id', $product->id)->where('vendor_shipping_detail.vendor_id', $product->vendor_id)->first();
        $productVideo = ProductVideo::where('product_id', $product->id)->first();
        $vendorName = Vendor::select('first_name', 'last_name')->where('id', $product->vendor_id)->first();
        $product->vendor_name = $vendorName->first_name . ' ' . $vendorName->last_name;

         //echo "<pre>";print_r($productShipping);die;
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        return view('product.recentProduct', [
            'loginUser' => $loginUser->type,
            'product' => $product,
            'productShopzzCategory' => $productShopzzCategory,
            'productStoreCategory' => $productStoreCategory,
            'productAttrCombination' => $productAttrCombination,
            'productVideo' => $productVideo,
            'productImage' => $productImage,
            'productShipping' => $productShipping
        ]);
    }

    /**
     * Change status of the product.
     *
     * @param Product $product
     * @return json
     * developed by Nikita Patel
     */
    public function recentChangeStatus(Product $product)
    {
        if ($product->status == 'Active') {
            $product->status = 'Inactive';
        } else {
            $product->status = 'Active';
        }
        if ($product->save()) {

            return redirect(route('products.index'))->with('success', trans('messages.products.change_status'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change featured status of the product.
     *
     * @param Product $product
     * developed by Nikita Patel
     * @return json
     */
    public function recentChangeFeaturedStatus(Product $product)
    {
        if ($product->featured == 'No') {
            $product->featured = 'Yes';
        } else {
            $product->featured = 'No';
        }
        if ($product->save()) {

            return redirect(route('products.index'))->with('success', trans('messages.products.change_featured'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.error'));
    }
}