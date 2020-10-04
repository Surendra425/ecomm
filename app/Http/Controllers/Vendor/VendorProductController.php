<?php

namespace App\Http\Controllers\Vendor;

use App\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorProductController extends Controller
{
    private $validationRules = [

        'product_name' => 'required|unique:product,product_name',
        'address' => 'required',
        'city' => 'required',
        'state' => 'required',
        'country' => 'required',
        'product_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
    ];

    /**
     * Display Store details.
     *
     * @return json
     */
    public function index()
    {

        return view('vendor.products.index');
    }

    /**
     * Display create product page.
     *
     * @return json
     */
    public function create()
    {
        $vendor = new Vendor();
        $data['vendor'] = $vendor->get_vendor();
         //$data['vendor'] = VendorCategory::all();
        return view('vendor.products.product_create', [
                'vendor' => $data
            ]);
    }

    /**
     * Save the Store.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
//        print_r($request);
//        $this->validate($request, $this->validationRules);
//        $product = new Store();
//
//        $product->fill($request->all());
//        $image = $request->file('product_image');
//        $destinationPath = public_path('doc/product_image');
//        $user_img = $request->product_image_1;
//        $ImageController->imageSave($image,$destinationPath,$user_img);
//        if (!empty($image)) {
//            $img = str_replace(" ",'-', $image->getClientOriginalName());
//            $img = explode('.',$img);
//            $img=$img[0].'-'.time().'.'.$img[1];
//
//            $product->product_image = $img;
//
//        }
//        $product->featured = 'No';
//        $product->status = $request->status;
//        $product->vendor_id=$request->vendor_id;
//        if ($product->save()) {
//
//            return redirect(route('products.index'))->with('success', trans('messages.products.added'));
//        }
//
//        return redirect(route('products.index'))->with('error', trans('messages.error'));
    }

    /**
     * Search products.
     *
     * @return json
     */
    public function search()
    {
        $perpage = $_POST['datatable']['pagination']['perpage'];

        $search = '';
        $status = '';
        $featured = '';
        if (isset($_POST['datatable']['query']['generalSearch'])) {
            $search = $_POST['datatable']['query']['generalSearch'];
        }
        if (isset($_POST['datatable']['query']['Status'])) {
            $status = $_POST['datatable']['query']['Status'];
        }
        if (isset($_POST['datatable']['query']['Featured'])) {
            $featured = $_POST['datatable']['query']['Featured'];
        }

        $orderDir = $_POST['datatable']['sort']['sort'];
        $orderColumn = 'id';

        $query = \App\Product::select('products.id','products.product_title','description','featured','status')
            ->leftJoin('stores', 'stores.id', '=', 'products.store_id');
        //->leftJoin('stores', 'stores.id', '=', 'products.store_id');

        if ($status != '') {
            $query->where('status', "$status");
        }
        if ($featured != '') {
            $query->where('featured', "$featured");
        }
        // $this->filterStoreStatus($status, $query);
        if ($search != '') {
            $this->filterStore($search, $query);
        }


        $vendors = $query->orderBy($orderColumn, $orderDir)
            ->paginate($perpage);

        $data = json_decode(json_encode($vendors));

        //print_r($data->data);die;
        $Result = array();
        $total = count($vendors);


        $Result['meta']['page'] = $_POST['datatable']['pagination']['page'];
        $Result['meta']['pages'] = 1;
        $Result['meta']['perpage'] = $perpage;
        $Result['meta']['sort'] = $orderDir;
        $Result['meta']['field'] = $orderColumn;
        $Result['meta']['total'] = $total;
        $Result['data'] = $data->data;
        echo json_encode($Result);

    }
    /**
     * Filter Store listing.
     *
     * @param $search
     * @return $query
     */
    private function filterStore($search, $query)
    {
        $query->where('product_name', 'like', '%'.$search.'%')
            ->orWhere('status', 'like', '%'.$search.'%')
            ->orWhere('city', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%')
            ->orWhere('first_name', 'like', '%'.$search.'%')
            ->orWhere('last_name', 'like', '%'.$search.'%')
            ->orWhere('featured', 'like', '%'.$search.'%');
    }



    /**
     * Delete product by unique identifier.
     *
     * @return json
     */
    public function destroy(Store $productd)
    {
        if($productd->delete()) {

            return redirect(route('products.index'))->with('success', trans('messages.products.deleted'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.error'));
    }



    /**
     * Change status of the products.
     *
     * @param Store $vendor
     * @return json
     */
    public function changeStatus(Store $productd)
    {
        if($productd->status == 'Active'){
            $productd->status ='Inactive';
        }else{
            $productd->status ='Active';
        }
       if($productd->save()) {

            return redirect(route('products.index'))->with('success', trans('messages.products.change_status'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.error'));
    }
    /**
     * Change featured status of the products.
     *
     * @param Store $vendor
     * @return json
     */
    public function changeFeaturedStatus(Store $productd)
    {
        if($productd->featured == 'No'){
            $productd->featured ='Yes';
        }else{
            $productd->featured ='No';
        }
       if($productd->save()) {

            return redirect(route('products.index'))->with('success', trans('messages.products.change_featured'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show Store view page.
     *
     * @param Store $vendor
     * @return json
     */
    public function profile(Store $productd)
    {
        $product_data = new Store();
        $data = $product_data->get_product_by_id($productd->id);
        return view('vendor.products.profile', [
            'product' => $data[0]
        ]);
    }

    /**
     * Show Store edit page.
     *
     * @param Store $users
     * @return json
     */
    public function edit(Store $product)
    {
        $product_data = new Store();
        $data = $product_data->get_product_by_id($product->id);
        return view('vendor.products.product_create', [
            'product' => $data[0],
            'product' => $product
        ]);
    }

    /**
     * Update the vendor.
     *
     * @param Request $request
     * @param int $vendorId
     * @return json
     */
    public function update(Request $request, Store $product)
    {
        //echo $product->id;die;
        //echo "hi";die;
        // Validate fields
        $this->validate($request, [
            'product_name' => 'required|unique:product,product_name,'.$product->id,
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'product_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
         //$this->validate($request, $this->validationRules);

         $ImageController = new ImageController();

        $image = $request->file('product_image');
        $destinationPath = public_path('doc/product_image');
        $user_img = $request->product_image_1;
        $ImageController->imageSave($image,$destinationPath,$user_img);
        if (!empty($image)) {
            $img = str_replace(" ",'-', $image->getClientOriginalName());
            $img = explode('.',$img);
            $img=$img[0].'-'.time().'.'.$img[1];

            $product->product_image = $img;

        }

        /*$response = [
            'status' => 0,
            'message' => trans('messages.failed'),
        ];*/

        $product->fill($request->all());
        $product->vendor_id=$request->vendor_id;
        if ($product->save()) {

            return redirect(route('products.index'))->with('success', trans('messages.products.updated'));
        }

        return redirect(route('products.index'))->with('error', trans('messages.error'));
    }

}
