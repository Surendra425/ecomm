<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Helpers\NameHelper;
use App\Keywords;
use App\Product;
use App\ProductAttrCombination;
use App\ProductCart;
use App\ProductCategory;
use App\ProductImage;
use App\ProductKeyword;
use App\ProductShipping;
use App\ProductVideo;
use App\ShopzzCategory;
use App\StoreProductCategory;
use App\Vendor;
use App\VendorProductCategory;
use App\VendorShippingDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AdminProductsController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Products Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles Admin side & vendor side products
     */


    /**
     * Product Validation rules array
     * @var array
     */
    private $validationRules = [

        'vendor_id' => 'required',
        'shopzz_category_id' => 'required|array',
        'shopzz_category_id.*'  => 'required|string|min:1',
        'store_category_id' => 'required|array',
        'store_category_id.*'  => 'required|string|min:1',
        'checkCountry' => 'required|array',
        'checkCountry.*'  => 'required|string|min:1',
        'combination' => 'required|in:Yes,No',
        'product_title' => 'required|string|max:100',
    ];

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
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

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentCategories = ProductCategory::where('status', 'Active')
            ->where('parent_category_id', null)
            ->select('id','category_name')
            ->get()
            ->keyBy('id')
            ->toArray();

        $mainCategory = ProductCategory::where('product_category.status', 'Active')
            ->where('parent.status','Active')
            ->leftJoin('product_category as parent','parent.id','=','product_category.parent_category_id')
            ->where('product_category.parent_category_id','!=', null)
            ->select('product_category.id','product_category.category_name','parent.id as parent_id','parent.category_name as parent_name',
                'product_category.parent_category_id')
            ->get();

        $mainCategory = $mainCategory->groupBy('parent_category_id');
        //dd($mainCategory);
        $vendor = Vendor::where('status', '1')
            ->select('id','first_name','last_name')
            ->get();

        $url = request()->segment(1);

        $loginUser = Auth::guard($url)->user();

        return view('product.product_create', [
            'loginUser' => $loginUser,
            'vendor' => $vendor,
            'mainCategory' => $mainCategory,
            'parentCategories' => $parentCategories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        //dd($request->all());
        
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();

        $product = new Product();
        $product->fill($request->all());
        $product->added_by_user_id = $loginUser->id;
        $product->save();

        $product->product_unit_no = "PRODUCT_" . str_pad($product->id, 6, mt_rand(100000, 999999), STR_PAD_RIGHT);
        $product->product_slug = str_slug($request->product_title . '-' . $product->id, "-");

        if($product->save())
        {
            // Insert product images
            $this->insertProductImage($request, $product);

            // Insert product Video
            $this->insertProductVideo($request, $product);

            // Insert product category
            $this->insertShopzzCategories($product, $request->shopzz_category_id);

            // Insert product store category
            $this->insertStoreProductCategories($product, $request->store_category_id);

            //Save product shipping
            $this->insertShippingDetail($product, $request->checkCountry, $loginUser);

            // Insert product keywords
            $this->insertProductKeywords($product, $request->keyword);

            // Product combinations
            if($request->combination == 'No'){

                if (isset($request->qty_default) && !empty($request->qty_default)) {
                    $this->insertSingleProductAttribute($product,$request->qty_default,$request->price_default);
                }

            }
            else{

                if(isset($request->options) && !empty($request->options)){
                    $this->insertMultipleProductAttribute($product,$request->options);
                }
            }

            return redirect(route('products.index'))->with('success', trans('messages.products.added'));

        }

        return redirect(route('products.index'))->with('error', trans('messages.products.error'));
    }


    /**
     * Load products on listing page.
     *
     * @param  Request  $request
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
                    '<a href="' . url(route('products.show', ['product' => $products->id])) . '" title="View"><i class="la la-eye"></i></a>';
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
     * Show Product view page.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        $productShopzzCategory = ShopzzCategory::selectRaw('group_concat(shopzz_category_name) as shopzz_category_name')
            ->where('product_id', $product->id)
            ->groupBy('product_id')
            ->first();

        $productStoreCategory = StoreProductCategory::selectRaw('group_concat(store_category_name) as store_category_name')
            ->where('product_id', $product->id)
            ->groupBy('product_id')
            ->first();

        $product->load(['vendorDetail'=>function($query){
            return $query->select('id','first_name','last_name');
        }]);

        $product->productShipping;
        $product->options;
        $product->images;
        $product->videos;

        $data['product'] = $product;
        $data['productShopzzCategory'] = $productShopzzCategory;
        $data['productStoreCategory'] = $productStoreCategory;
        $data['loginUser'] = $loginUser->type;

       // dd($data);
        return view('product.profile',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
       
        $url = request()->segment(1);

        $data['loginUser'] = Auth::guard($url)->user();

        $data['parentCategories'] = ProductCategory::where('status', 'Active')
            ->where('parent_category_id', null)
            ->get()->keyBy('id')->toArray();

        $shopzzCategory = ProductCategory::where('status', 'Active')->where('parent_category_id','!=', null)->get();

        $data['shopzzCategory'] = $shopzzCategory->groupBy('parent_category_id');

        $data['storeCategory'] = VendorProductCategory::where('status', 'Active')
            ->where('vendor_id', $product->vendor_id)->get();

        $data['vendors'] = Vendor::where('status', '1')->get();

        $data['vendorShipping'] = VendorShippingDetail::where('vendor_shipping_detail.vendor_id', $product->vendor_id)->groupby('vendor_shipping_detail.country_id')->get();

        $data['keyword'] = ProductKeyword::select('products_keywords.id', 'keyword_id', 'keywords.keyword')
            ->leftjoin('keywords', 'keywords.id', '=', 'products_keywords.keyword_id')
            ->where('products_keywords.product_id', $product->id)->get();

        $product->load(['vendorDetail'=>function($query){
            return $query->select('id','first_name','last_name');
        }]);

        $product->shopzzCategory;
        $product->storeProductCategory;
        $product->productShipping;
        $product->images;
        $product->videos;

        $product->options;
        $shipping = [];

        foreach($product->productShipping as $item){
            $shipping[] = $item->country_id;
        }

        $data['product'] = $product;
        $data['productShipping'] = $shipping;
        //dd($data['product']);
        return view('product.product_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        $this->validationRules['product_title'] = 'required|string|max:100|unique:products,id,'.$product->id;

        $this->validate($request, $this->validationRules);
        
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
       // dd($request->all());

        // Update Product
        $product->fill($request->all());
        $product->product_slug = str_slug($request->product_title . '-' . $product->id, "-");

        if($product->save())
        {
            if(!empty($request->productImages)){
                // Insert product images
                $this->insertProductImage($request, $product);
            }

            if(!empty($request->productVideo)){
                ProductVideo::where('product_id',$product->id)->delete();

                // Insert product Video
                $this->insertProductVideo($request, $product);
            }

            // Product combinations
            if($request->combination == 'No'){

                // Remove from Cart if combination available
                if(!empty($request->options)){
                    $combinationIds = array_keys($request->options);
                    ProductCart::where('product_id',$product->id)->whereIn('product_combination_id',$combinationIds)->delete();
                }

                // Update product attributes if available
                 ProductAttrCombination::where('id',$request->option_id)->update(['quantity'=>$request->qty_default,'rate'=>$request->price_default]);

                if ($request->old_combination=='Yes') {
                    ProductAttrCombination::where('product_id',$product->id)->update(['is_delete'=>1]);
                    $this->insertSingleProductAttribute($product,$request->qty_default,$request->price_default);
                }
            }
            else{

                if ($request->old_combination=='No') {
                    ProductAttrCombination::where('product_id',$product->id)->update(['is_delete'=>1]);
                }
                if(!empty($request->options)){
                    $this->updateMultipleProductAttribute($product,$request->options);
                }

                if(empty($request->optionsDem[0]['options'])){
                    $request->optionsDem = array_slice($request->optionsDem,1);
                }

                // Add new cmbinations
                if(!empty($request->optionsDem)){
                    $this->insertMultipleProductAttribute($product,$request->optionsDem);
                }
            }

            // Remove old keywords
            ProductKeyword::where('product_id',$product->id)->delete();

            // Insert product keywords
            $this->insertProductKeywords($product, $request->keyword);

            // Update product shopzz category
            $this->updateShopzzCategories($product, $request->shopzz_category_id);

            // Update product store category
            $this->updateStoreProductCategories($product, $request->store_category_id);

            //Save product shipping
            $this->updateShippingDetail($product, $request->checkCountry, $loginUser);

            return redirect(route('products.index'))->with('success', trans('messages.products.updated'));
        }

        return redirect(route('products.index'))->with('success', trans('messages.products.update_error'));
    }

    /**
     * Insert product image.
     *
     * @param $request
     * @param $product
     *
     * @return null
     */
    private function insertProductImage($request, $product)
    {
        //Insert product images
        $productImagesNames = explode(',', $request->productImages);
        $productImagesNames = array_filter($productImagesNames);

        if(!empty($productImagesNames))
        {
            foreach ($productImagesNames as $images) {

                $productImagesinfo[] =[

                    'product_id' => $product->id,
                    'image_url' => trim($images,'"'),
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now(),
                ];
            }

            if(!empty($productImagesinfo))
            {
                ProductImage::insert($productImagesinfo);
            }
        }

    }

    /**
     * Insert product video
     *
     * @param $request
     * @param $product
     */
    private function insertProductVideo($request, $product){

        if (!empty($request->productVideo)) {

                $productVideo = new ProductVideo();
                $productVideo->product_id = $product->id;
                $productVideo->video_url =trim($request->productVideo,'"');
                $productVideo->save();

        }
    }

    /**
     * Insert product categories.
     *
     * @param $product
     * @param $categoriesIds
     *
     * @return null
     */
    private function insertShopzzCategories($product, $categoriesIds)
    {
        $categoriesInfo = [];
        if(!empty($categoriesIds))
        {
            //insert product categories
            foreach($categoriesIds as $categoryId)
            {
                $categoriesInfo[] =[
                    'product_id' => $product->id,
                    'shopzz_category_id' =>$categoryId,
                    'shopzz_category_name' => NameHelper::getNameBySingleId('product_category', 'category_name', 'id', $categoryId),
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now(),
                ];
            }

            ShopzzCategory::insert($categoriesInfo);
        }
    }

    /**
     * Update product Shopzz category and insert newly added
     *
     * @param $product
     * @param $categoriesIds
     */
    private function updateShopzzCategories($product, $categoriesIds){

        if(!empty($categoriesIds))
        {
            // Remove if deselected from edit page
            ShopzzCategory::select('shopzz_category_id')->where('product_id',$product->id)
            ->whereNotIn('shopzz_category_id',$categoriesIds)->delete();


            $shopzzCategory = ShopzzCategory::select('shopzz_category_id')->where('product_id',$product->id);

            $existId = $shopzzCategory->whereIn('shopzz_category_id',$categoriesIds)->get()->toArray();
            $existId = array_column($existId,'shopzz_category_id');

            $newCategory = array_diff($categoriesIds,$existId);

            if(count($newCategory)>0){
                $this->insertShopzzCategories($product,$newCategory);
            }

        }
    }

    /**
     * Insert product categories.
     *
     * @param $product
     * @param $categoriesIds
     *
     * @return null
     */
    private function insertStoreProductCategories($product, $categoriesIds)
    {
        $categoriesInfo = [];
        if(!empty($categoriesIds))
        {
            //insert product categories
            foreach($categoriesIds as $categoryId)
            {
                $categoriesInfo[] =[
                    'product_id' => $product->id,
                    'store_category_id' =>$categoryId,
                    'store_category_name'=>NameHelper::getNameBySingleId('vendor_product_categories', 'category_name', 'id', $categoryId),
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now(),
                ];
            }

            StoreProductCategory::insert($categoriesInfo);
        }
    }

    /**
     * Update product category and insert newly added
     *
     * @param $product
     * @param $categoriesIds
     */
    private function updateStoreProductCategories($product, $categoriesIds){

        if(!empty($categoriesIds))
        {
            StoreProductCategory::where('product_id',$product->id)
                ->whereNotIn('store_category_id',$categoriesIds)->delete();

            $existId = StoreProductCategory::select('store_category_id')->where('product_id',$product->id)
                ->whereIn('store_category_id',$categoriesIds)->get()->toArray();

            $existId = array_column($existId,'store_category_id');

            $newCategory = array_diff($categoriesIds,$existId);

            if(count($newCategory)>0){
                $this->insertStoreProductCategories($product,$newCategory);
            }

        }
    }

    /**
     * Insert product shipping detail
     *
     * @param $product
     * @param $shipping
     */
    private function insertShippingDetail($product, $shipping, $loginUser){

        $ShippingData = [];
        if(!empty($shipping)) {

            foreach ($shipping as $key => $shippingCountry) {
                $ShippingData[] = [
                    'product_id' => $product->id,
                    'country_id' => $shippingCountry,
                    'added_by_user_id' => $loginUser->id,
                    'country_name' => NameHelper::getNameBySingleId('country', 'country_name', 'id', $shippingCountry),
                ];
            }

            ProductShipping::insert($ShippingData);
        }
    }

    /**
     * Update product shipping detail
     *
     * @param $product
     * @param $shipping
     * @param $loginUser
     */
    private function updateShippingDetail($product, $shipping, $loginUser){

        if(!empty($shipping)) {

            ProductShipping::where('product_id',$product->id)
                ->whereNotIn('country_id',$shipping)->delete();

            $existId = ProductShipping::select('country_id')->where('product_id',$product->id)
                ->whereIn('country_id',$shipping)->get()->toArray();

            $existId = array_column($existId,'country_id');

            $newCategory = array_diff($shipping,$existId);

            if(count($newCategory)>0){
                $this->insertShippingDetail($product,$newCategory,$loginUser);
            }
        }
    }

    /**
     * Insert product keywords
     *
     * @param $product
     * @param $keywords
     */
    private function insertProductKeywords($product, $keywords){

        $keywordsData = [];

        if(!empty($keywords)){

            foreach ($keywords as $key=> $keyword){

                if(!is_numeric($keyword)){

                    $newKeyword = new Keywords();
                    $newKeyword->keyword = $keyword;
                    $newKeyword->save();
                    $keyword = $newKeyword->id;
                }

                $keywordsData[] = [

                    'product_id'=>$product->id,
                    'keyword_id'=>$keyword,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),

                ];
            }

            ProductKeyword::insert($keywordsData);
        }
    }

    /**
     * Insert product attribute when combinations not available
     *
     * @param $product
     * @param $quantity
     * @param $price
     */
    private function insertSingleProductAttribute($product, $quantity, $price){

        $productAttrCombination = new ProductAttrCombination();

        $productAttrCombination->quantity = $quantity;
        $productAttrCombination->rate = $price;
        $productAttrCombination->product_id = $product->id;
        $productAttrCombination->created_at = Carbon::now();
        $productAttrCombination->updated_at = Carbon::now();

        $productAttrCombination->save();

    }

    /**
     * Insert multiple product combinations
     *
     * @param $product
     * @param $options
     */
    private function insertMultipleProductAttribute($product, $options){

        $optionsData = [];
        foreach ($options as $key=> $option) {
            if ($option['options'] != NULL) {

                $optionsData[] = [
                    'combination_title' => $option['options'],
                    'quantity' => $option['qty'],
                    'rate' => $option['price'],
                    'product_id' => $product->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

            ProductAttrCombination::insert($optionsData);

    }

    /**
     * Update product options
     *
     * @param $product
     * @param $options
     */
    private function updateMultipleProductAttribute($product, $options){

        $optionsKey = array_keys($options);

        $data = ProductAttrCombination::where('product_id',$product->id)
            ->whereNotIn('id',$optionsKey)->where('is_delete',0);

        $removeCart = $data->select('id')->get()->toArray();

        $removeOptionsKey = array_column($removeCart,'id');

        ProductCart::where('product_id',$product->id)->whereIn('product_combination_id',$removeOptionsKey)->delete();

        $data->update(['is_delete'=>1]);

        $optionsData = [];

        foreach ($options as $key=> $option) {

            if ($option['options'] != NULL) {

                $optionsData = [
                    'combination_title' => $option['options'],
                    'quantity' => $option['qty'],
                    'rate' => $option['price'],
                    'product_id' => $product->id,
                    'updated_at' => Carbon::now(),
                ];
                ProductAttrCombination::where('id',$key)->update($optionsData);
            }
        }

    }

    /**
     * Remove product image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProductImages(Request $request){

        if(!empty($request->imageId)){

            $productImageCount = ProductImage::where('product_id',$request->productId)->count();

            if($productImageCount<=1){

                return response()->json(['status'=>0, 'msg' => trans('messages.products.image_delete.at_least_one')]);
            }

            $productImage = ProductImage::find($request->imageId);

            if(!empty($productImage)) {

                $imgMain = public_path('doc/product_image/' . $productImage->image_url);
                $imgtemp = public_path('doc/product_image_temp/' . $productImage->image_url);
                $isDelete = 0;

                if (file_exists($imgMain)) {
                    unlink($imgMain);
                    $isDelete++;
                }

                if (file_exists($imgtemp)) {
                    unlink($imgtemp);
                    $isDelete++;
                }

                if ($productImage->delete()) {

                    return response()->json(['status' => 1, 'msg' => trans('messages.products.image_delete.success')]);
                }
            }
        }
        return response()->json(['status'=>0, 'msg' => trans('messages.products.image_delete.error')]);
    }

    /**
     * Remove product video
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProductVideos(Request $request){

        if(!empty($request->videoId)){

            $productvideo = ProductVideo::find($request->videoId);
            $videoFile =  public_path('doc/video/'.$productvideo->video_url);

            $isDelete = 0;

            if(file_exists($videoFile)){
                unlink($videoFile);
                $isDelete++;
            }

           // if($isDelete>0){

                if($productvideo->delete()){

                    return response()->json(['status'=>1,trans('messages.products.video_delete.success')]);
                }

           // }
        }
        return response()->json(['status'=>0,trans('messages.products.video_delete.error')]);
    }

}
