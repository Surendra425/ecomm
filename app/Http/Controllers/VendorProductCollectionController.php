<?php

namespace App\Http\Controllers;

use App\Collections;
use App\CollectionProducts;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Vendor;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Product;
use App\ProductImage;
use Illuminate\Support\Facades\DB;

class VendorProductCollectionController extends Controller
{

    private $validationRules = [
        'vendor_id' => 'required'
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
        return view('productsCollections.collection_list',['loginUser'=>$loginUser->type]);
    }

    public function show()
    {
        
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
            $query = CollectionProducts::select('vendor_id','collection_name', 'collection_tagline', 'collections.status', 'display_status','collection_products.vendor_id','collections.id as collectionIds')
            ->leftjoin('collections','collections.id','collection_products.collection_id');

            if($loginUser->type == 'vendor'){
                $query->where('collection_products.vendor_id',$loginUser->id);
            }

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterCollection($request->search['value'], $query);

            $collection = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);

            $data = json_decode(json_encode($collection));

            $data->recordsFiltered = $data->recordsTotal = $data->total;
            //dd($data);die;
            foreach ($data->data as $collections)
            {
            //dd(route(route('vendorCollectionAddProduct', ['collection' => $collections->id])));
                $collections->action = '<a href="' . url(route('vendorCollectionAddProduct', ['collection' => $collections->collectionIds])) . '" title="Add Product"><i class="la la-plus-square"></i></a>' .
                        '<a href="' . url(route('vendorCollectionViewProduct', ['collectionProducts' => $collections->collectionIds,'vendor'=>$collections->vendor_id])) . '" title="View Products"><i class="la la-eye"></i></a>';
            }

            return response()->json($data);
        }
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
    /**
     * Filter Collection listing.
     *
     * @param $search
     * @return $query
     */
    private function filterCollection($search, $query)
    {
        $query->where('collection_name', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->orWhere('collection_tagline', 'like', '%' . $search . '%')
                ->orWhere('display_status', 'like', '%' . $search . '%');
    }

    /**
     * Display create Collection page.
     *
     * @return json
     */
    public function addProduct(Collections $collection)
    {
        //dd($collection);
        //$vendor = Auth::guard('vendor')->user();
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();

        $collectionProducts = CollectionProducts::where('id', '=', $collection->id)->get();
        $products = Product::all()->where('status', 'Active');
        if($loginUser->type == 'vendor'){
            $collectionProducts = CollectionProducts::where('vendor_id', '=', $loginUser->id)->where('id', '=', $collection->id)->get();
            $products = Product::all()->where('vendor_id', '=', $loginUser->id)->where('status', 'Active');
        }
        $vendor = Vendor::all()->where('status','1');
        $data = [];
        $data['collectionProducts'] = $collectionProducts;
        $data['collection'] = $collection;
        $data['products'] = $products;
        $data['loginUser'] = $loginUser;
        $data['vendor'] = $vendor;
        return view('productsCollections.collection_add_products', $data);
    }

    public function store(Request $request){
        $this->validate($request, $this->validationRules);
        $ProductIds = implode(',',$request->ddlProducts);
        $collectionProducts = new CollectionProducts();

        $collectionProducts->vendor_id  = $request->vendor_id;
        $collectionProducts->collection_id  = $request->collection_id;
        $collectionProducts->product_id  = $ProductIds;
        //CollectionProducts::where('vendor_id', '=', $request->vendor_id)->where('collection_id', '=', $request->collection_id)->whereIn("product_id", $ProductIds)->delete();
        if ($collectionProducts->save())
        {
            return redirect(route('products-collection.index'))->with('success',trans('messages.collection_products.added'));
        }

        return redirect(route('products-collection.index'))->with('error', trans('messages.error'));
    }
    

    public function view(Request $request, CollectionProducts $collectionProducts, $vendor)
    {
        //dd($collectionProducts);die;
        //$vendor = Auth::guard('vendor')->user();
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        $collection = Collections::where('id', '=', $collectionProducts->collection_id)->first();

        $productId = [];
        $productId = explode(',',$collectionProducts->product_id);
        //$products = Product::all()->where('vendor_id', '=', $collectionProducts->vendor_id)->whereIn('id',$productId)->where('status', 'Active');

        //dd($products);die;
        $data = [];
        $data['collection'] = $collection;
        //$data['products'] = $products;
        $data['collectionProducts'] = $collectionProducts;

        return view('productsCollections.collection_view_products', $data,['loginUser'=>$loginUser->type]);
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

            $query = CollectionProducts::select('collection_products.id', 'collection_products.product_id', 'products.product_title','products.brand_name')
                ->leftjoin('products', 'products.id', '=', 'collection_products.product_id')
                ->where('products.vendor_id', '=', $collectionProducts);
               // ->where('collection_products.collection_id', '=', $collectionProducts->collection_id);


            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterCollectionProduct($request->search['value'], $query);
            $collection = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);
            $data = json_decode(json_encode($collection));

            $data->recordsFiltered = $data->recordsTotal = $data->total;
//dd($data);
            foreach ($data->data as $collectionproducts)
            {
                $ProductImage = ProductImage::where("product_id", $collectionproducts->product_id)->first();
                $collectionproducts->action = '<a class="delete-data" data-name="product" href="' . url(route('vendorCollectionProductRemove', ['collection_product' => $collectionproducts->id])) . '" title="Remove Product"><i class="la la-trash"></i></a>';
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

    public function productCollectionSearch(Request $request){


        $productId=[];
        $product = CollectionProducts::select('collection_products.product_id')
            ->where('collection_products.vendor_id', '=', $request->vendor_id)
            ->where('collection_products.collection_id', '=', $request->collection_id)->get();
        foreach($product as $item){
            $productId[] = $item->product_id;
        }
         $data = Product::select('products.id', 'products.product_title','products.brand_name')
            ->whereNotIn('products.id', $productId)->get();
        //dd($product);die;

        $data = json_decode(json_encode($data));
        echo json_encode($data);
        exit;
    }
    /**
     * Delete collections by unique identifier.
     *
     * @return json
     */
    public function remove(CollectionProducts $collection_product)
    {
        if ($collection_product->delete())
        {
            return redirect(url(route('vendorCollectionViewProduct', ['collection' => $collection_product->collection_id])))->with('success', trans('messages.collection_products.removed'));
        }
        return redirect(url(route('vendorCollectionViewProduct', ['collection' => $collection_product->collection_id])))->with('error', trans('messages.error'));
    }

}
