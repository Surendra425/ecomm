<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 13/2/18
 * Time: 10:25 AM
 */

namespace App\Http\Controllers;


use App\CollectionProducts;
use App\Collections;
use App\Product;
use App\ProductImage;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductCollection extends Controller
{
    private $validationRules = [
        'vendor_id' => 'required',
        'ddlProducts' => 'required'
    ];

    /**
     * Display Collection details.
     *
     * @return json
     */
    public function index()
    {
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        $data['collection'] = Collections::all()->where('status','Active');
        $data['vendor'] = Vendor::all()->where('status','1');
        $data['loginUser'] = $loginUser;
        //dd($data);die;
        return view('collectionsProducts.collection_list',$data);
    }

    /**
     * Search Collection.
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
            $url = request()->segment(1);
            $loginUser = Auth::guard($url)->user();
            $query = CollectionProducts::select('collection_products.id','collection_products.id','vendor_id','collection_name', 'collection_tagline','users.first_name','users.last_name','collections.id as collectionId',DB::raw('count(product_id) as product_count'))
                ->join('collections','collections.id','collection_products.collection_id')
                ->leftjoin('users','users.id','collection_products.vendor_id')
            ->groupBy('collections.id','collection_products.vendor_id');
           /* $query = CollectionProducts::select(DB::raw('count(product_id) as product_count,collection_products.id,vendor_id,collection_name, collection_tagline,first_name,last_name'))
            ->leftjoin('collections','collections.id','collection_products.collection_id')
            ->leftjoin('users','users.id','collection_products.vendor_id')
            ->groupBy('collection_products.vendor_id','collections.id');*/

            if($loginUser->type == 'vendor'){
                $query->where('collection_products.vendor_id',$loginUser->id);
            }
            //$query = Collections::select('collection_name', 'collection_tagline', 'collections.status', 'display_status','collections.id');
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterCollection($request->search['value'], $query);
            $collection = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($collection));
            //dd($data);die;
            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $collections)
            {
                $collections->vendorName = $collections->first_name . ' '. $collections->last_name;
                //dd(route(route('collectionAddProduct', ['collection' => $collections->id,'vendor' => $collections->vendor_id])));
                $collections->action = '<a href="' . url(route('addpc', ['collection' => $collections->collectionId,'vendor' => $collections->vendor_id])) . '" title="Add Product"><i class="la la-plus-square"></i></a>' .
                    '<a href="' . url(route('collectionViewProduct', ['collection' => $collections->collectionId,'vendor' => $collections->vendor_id])) . '" title="View Products"><i class="la la-eye"></i></a>';
            }

            return response()->json($data);
        }
    }
   public function add(Request $request,Collections $collection,$vendor){
       $url = request()->segment(1);
       $loginUser = Auth::guard($url)->user();
      // $collections = Collections::where('id',"=",$collection)->first();
       $data = [];
       //$data['collectionProducts'] = $collectionProducts;
       $data['collection'] = $collection;
       $data['loginUser'] = $loginUser;
       $data['vendorId'] = $vendor;
       //dd($collection);die;
       return view('collectionsProducts.collection_add_products', $data);
   }
    /**
     * Filter Collection listing.
     *
     * @param $search
     * @return $query
     */
    private function filterCollection($search, $query)
    {
        $query->where(function ($query) use($search) {

            $query->where('collection_name', 'like', '%'.$search.'%');
        });

    }
    /**
     * Filter Collection listing.
     *
     * @param $search
     * @return $query
     */
    private function filterCollectionProduct($search, $query)
    {
        $query->where('product_title', 'like', '%' . $search . '%');
    }

    public function store(Request $request){
        $this->validate($request, $this->validationRules);
        $ProductIds = implode(',',$request->ddlProducts);
        $productCollectionData = array ();
        foreach($request->ddlProducts as $item){
            $productCollectionData[] = [
                    "vendor_id" => $request->vendor_id,
                    "collection_id" => $request->collection_id,
                    "product_id" => $item
                ];
        }
        //dd($productCollectionData);die;
        $collectionProducts = new CollectionProducts();

        /*$collectionProducts->vendor_id  = $request->vendor_id;
        $collectionProducts->collection_id  = $request->collection_id;
        $collectionProducts->product_id  = $ProductIds;*/
        if ($collectionProducts->insert($productCollectionData))
        {
            return redirect(route('products-collections.index'))->with('success',trans('messages.collection_products.added'));
        }

        return redirect(route('products-collections.index'))->with('error', trans('messages.error'));
    }

    public function view(Request $request, Collections $collection, $vendor)
    {
        //dd($collectionProducts);die;
        //$vendor = Auth::guard('vendor')->user();
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        $data = [];
        $data['collection'] = $collection;
        //$data['products'] = $products;
        $data['vendor'] = $vendor;

        return view('collectionsProducts.collection_view_products', $data,['loginUser'=>$loginUser->type]);
    }

    public function searchProducts(Request $request,  $collectionProducts)
    {
        //dd($collectionProducts);
        if ($request->ajax())
        {
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $query = CollectionProducts::select('collection_products.id', 'collection_products.product_id', 'collection_products.vendor_id','products.product_title')
                ->leftjoin('products', 'products.id', '=', 'collection_products.product_id')
                ->where("products.status", "=", 'Active')
                ->where('products.vendor_id', '=', $collectionProducts);
            // ->where('collection_products.collection_id', '=', $collectionProducts->collection_id);


            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterCollectionProduct($request->search['value'], $query);
            $collection = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);
            $data = json_decode(json_encode($collection));
           // dd($data);
            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $collectionproducts)
            {
                $ProductImage = ProductImage::where("product_id", $collectionproducts->product_id)->first();
                $collectionproducts->action = '<a class="delete-data" data-name="product" href="' . url(route('collectionProductRemove', ['collection_product' => $collectionproducts->id,'vendor' => $collectionproducts->vendor_id])) . '" title="Remove Product"><i class="la la-trash"></i></a>';
                $collectionproducts->image = "";
                if(!empty($ProductImage))
                {
                    $collectionproducts->image = "<img src='".url('doc/product_image').'/'.$ProductImage->image_url."' style='height:80px;' >";
                }else{
                    $collectionproducts->image = "<img src='".url('assets/app/media/img/no-images.jpeg')."' style='height:80px;' >";

                }
            }

            return response()->json($data);
        }
    }
    /*search vendor product*/
    public function productCollectionSearch(Request $request){


        $productId=[];
        $product = CollectionProducts::select('collection_products.product_id')
            ->where('collection_products.vendor_id', '=', $request->vendor_id)
            ->where('collection_products.collection_id', '=', $request->collection_id)->get();
        foreach($product as $item){
            $productId[] = $item->product_id;
        }
        $data = Product::select('products.id', 'products.product_title')
            ->where('products.vendor_id', '=', $request->vendor_id)
         ->whereNotIn('products.id', $productId)
            ->where("products.status", "=", 'Active')->get();
       // dd($data);die;

        $data = json_decode(json_encode($data));
        echo json_encode($data);
        exit;
    }
    /**
     * Delete collections by unique identifier.
     *
     * @return json
     */
    public function remove(CollectionProducts $collection_product,$vendor)
    {
        if ($collection_product->delete())
        {
            return redirect(url(route('collectionViewProduct', ['collection' => $collection_product->collection_id ,'vendor'=>$vendor])))->with('success', trans('messages.collection_products.removed'));
        }
        return redirect(url(route('collectionViewProduct', ['collection' => $collection_product->collection_id ,'vendor'=>$vendor])))->with('error', trans('messages.error'));
    }

}