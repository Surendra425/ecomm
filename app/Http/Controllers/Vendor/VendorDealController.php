<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Deals;
use App\DealProducts;
use App\Product;
use Illuminate\Support\Facades\DB;

class VendorDealController extends Controller
{

    private $validationRules = [
        'deal_name' => 'required|unique:deals',
        'discount_type' => 'required',
        'discount_amount' => 'required|numeric',
        'max_discount_amount' => 'numeric|nullable',
        'min_total_amount' => 'numeric|nullable',
        'start_date' => 'date|nullable',
        'end_date' => 'date|nullable',
    ];

    public function index()
    {
        $loginUser = Auth::guard('vendor')->user();
        $data['loginUser'] = $loginUser;
        return view('vendor.deals.index', $data);
    }

    /**
     * Save the customer.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $loginUser = Auth::guard('vendor')->user();
        // Validate fields
        $this->validate($request, $this->validationRules);
        //dd($request);die;
        $deal = new Deals();
        $deal->fill($request->all());
        $dateRange = $request->daterange;

        //echo "<pre>"; print_r($dateRange);die;
        if (strpos($dateRange, ' / ') !== false)
        {
            $dateRange = explode(' / ', $dateRange);
        }
        else
        {
            $dateRange = explode(' - ', $dateRange);
        }

        $deal->start_date = date('Y-m-d', strtotime($dateRange[0]));
        $deal->end_date = date('Y-m-d', strtotime($dateRange[1]));
        $deal->vendor_id = $loginUser->id;
        if ($deal->save())
        {
            $dealSlug = str_slug($request->deal_name.'-'.$deal->id, "-");
            DB::table('deals')
                ->where('id', $deal->id)
                ->update(['deal_slug' => $dealSlug]);
            return redirect(route('deals.index'))->with('success', trans('messages.deals.added'));
        }

        return redirect(route('deals.index'))->with('error', trans('messages.error'));
    }

    public function create(Request $request)
    {
        $loginUser = Auth::guard('vendor')->user();
        $data['loginUser'] = $loginUser;
        return view('vendor.deals.create', $data);
    }

    public function edit(Deals $deal)
    {
        $loginUser = Auth::guard('vendor')->user();
        $data['loginUser'] = $loginUser;
        $data['deal'] = $deal;
        return view('vendor.deals.create', $data);
    }

    /**
     * Save the deal.
     *
     * @param Request $request
     * @return json
     */
    public function update(Request $request, Deals $deal)
    {
        $loginUser = Auth::guard('vendor')->user();
        $this->validationRules['deal_name'] = 'required|unique:deals,deal_name,' . $deal->id;

        // Validate fields
        $this->validate($request, $this->validationRules);

        $deal->fill($request->all());
        $dateRange = $request->daterange;
        if (strpos($dateRange, ' / ') !== false)
        {
            $dateRange = explode(' / ', $dateRange);
        }
        else
        {
            $dateRange = explode(' - ', $dateRange);
        }
        $deal->start_date = date('Y-m-d', strtotime($dateRange[0]));
        $deal->end_date = date('Y-m-d', strtotime($dateRange[1]));
        $deal->vendor_id = $loginUser->id;
        if ($deal->save())
        {
            $dealSlug = str_slug($request->deal_name.'-'.$deal->id, "-");
            DB::table('deals')
                ->where('id', $deal->id)
                ->update(['deal_slug' => $dealSlug]);
            return redirect(route('deals.index'))->with('success', trans('messages.deals.updated'));
        }
        return redirect(route('deals.index'))->with('error', trans('messages.error'));
    }

    /**
     * Search Customers.
     *
     * @return json
     */
    public function search(Request $request)
    {
        $loginUser = Auth::guard('vendor')->user();
        if ($request->ajax())
        {
            $loginUser = Auth::user();
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $query = Deals::select('id', 'deal_name', 'discount_type', 'discount_amount', 'min_total_amount', 'max_discount_amount', 'start_date', 'end_date', 'status')->where("vendor_id", $loginUser->id);

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterDeal($request->search['value'], $query);

            $deal = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);

            $data = json_decode(json_encode($deal));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $deals)
            {
                $deals->action = '<a href="' . url(route('deals.edit', ['deal' => $deals->id])) . '" title="Edit"><i class="la la-edit"></i></a>' .
                        '<a href="' . url(route('dealProfile', ['deal' => $deals->id])) . '" title="View"><i class="la la-eye"></i></a>' .
                        '<a class="delete-data" data-name="deal" href="' . url(route('dealDelete', ['deal' => $deals->id])) . '" title="Delete"><i class="la la-trash"></i></a>' .
                        '<a data-name="deal-products" href="' . url(route('dealProducts', ['deal' => $deals->id])) . '" title="View Products"><i class="la la-align-right"></i></a>';

                $deals->status = ($deals->status === 'Active') ? '<a href="' . url(route('changeDealStatus', ['deal' => $deals->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>' : '<a href="' . url(route('changeDealStatus', ['deal' => $deals->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }

    /**
     * Filter Deal listing.
     *
     * @param $search
     * @return $query
     */
    private function filterDeal($search, $query)
    {
        $query->where('deal_name', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->orWhere('discount_type', 'like', '%' . $search . '%')
                ->orWhere('min_total_amount', 'like', '%' . $search . '%')
                ->orWhere('max_discount_amount', 'like', '%' . $search . '%')
                ->orWhere('end_date', 'like', '%' . $search . '%')
                ->orWhere('start_date', 'like', '%' . $search . '%')
                ->orWhere('discount_amount', 'like', '%' . $search . '%');
    }

    /**
     * Show Deals view page.
     *
     * @param Deals $deal
     * @return json
     */
    public function profile(Deals $deal)
    {
        $loginUser = Auth::guard('vendor')->user();
        $data['loginUser'] = $loginUser;
        $data['deal'] = $deal;
        return view('vendor.deals.profile', $data);
    }

    /**
     * Delete deal by unique idetifier.
     *
     * @return json
     */
    public function destroy(Deals $deal)
    {
        if ($deal->delete())
        {

            return redirect(route('deals.index'))->with('success', trans('messages.deals.deleted'));
        }

        return redirect(route('deals.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the deal.
     *
     * @param Employee $employee
     * @return json
     */
    public function changeStatus(Deals $deal)
    {
        if ($deal->status == 'Active')
        {
            $deal->status = 'Inactive';
        }
        else
        {
            $deal->status = 'Active';
        }
        if ($deal->save())
        {
            return redirect(route('deals.index'))->with('success', trans('messages.deals.change_status'));
        }
        return redirect(route('deals.index'))->with('error', trans('messages.error'));
    }

    public function products(Deals $deal)
    {
        $vendor = Auth::guard('vendor')->user();
        $data['vendor'] = $vendor;
        $data['deal'] = $deal;
        return view('vendor.deal_products.index', $data);
    }

    public function addProducts(Deals $deal)
    {
        $vendor = Auth::guard('vendor')->user();
        $products = Product::all()->where('vendor_id', '=', $vendor->id)->where('status', 'Active');
        $data['vendor'] = $vendor;
        $data['deal'] = $deal;
        $data['products'] = $products;
        return view('vendor.deal_products.create', $data);
    }

    public function storeProducts(Request $request, Deals $deal)
    {
        $vendor = Auth::guard('vendor')->user();
        $ProductIds = $request->ddlProducts;
        $ProductData = [];
        $dealProducts = [];
        if (count($ProductIds))
        {
            foreach ($ProductIds as $ProductId)
            {
                $ProductData[] = [
                    "deal_id" => $deal->id,
                    "product_id" => $ProductId
                ];
            }
        }
        DealProducts::where('deal_id', '=', $deal->id)->whereIn("product_id", $ProductIds)->delete();
        if (DealProducts::insert($ProductData))
        {
            return redirect(url(route('dealProducts', ['deal' => $deal->id])))->with('success', trans('messages.deal_products.added'));
        }
        return redirect(url(route('addDealProducts', ['deal' => $deal->id])))->with('error', trans('messages.error'));
    }

    public function removeProducts(DealProducts $deal_product)
    {
        if ($deal_product->delete())
        {
            return redirect(url(route('dealProducts', ['deal' => $deal_product->deal_id])))->with('success', trans('messages.deal_products.removed'));
        }
        return redirect(url(route('dealProducts', ['deal' => $deal_product->deal_id])))->with('error', trans('messages.error'));
    }

    /**
     * Search product.
     *
     * @return json
     */
    public function searchProducts(Request $request, Deals $deal)
    {
        if ($request->ajax())
        {
            $loginUser = Auth::user();

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $loginUser = Auth::user();

            $query = DealProducts::select('deal_products.id', 'product_category.category_name', 'products.featured', 'products.status', 'brand_name', 'first_name', 'last_name', 'product_title', 'product_shipping.shipping_class')
                    ->join("products", "products.id", "deal_products.product_id")
                    ->leftjoin('product_category', 'product_category.id', '=', 'products.product_category_id')
                    ->leftjoin('product_shipping', 'product_shipping.product_id', '=', 'products.id')
                    ->leftjoin('users', 'users.id', '=', 'products.vendor_id');
            $query->where('deal_products.deal_id', $deal->id);

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterProduct($request->search['value'], $query);

            $product = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);

            $data = json_decode(json_encode($product));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $products)
            {
                $products->action = "";
                $products->action = '<a class="delete-data" data-name="product" href="'.url(route('removeDealProducts', ['product' => $products->id ])).'" title="Delete"><i class="la la-trash"></i></a>';

                $products->featured = ($products->featured === 'Yes') ? '<a href="' . url(route('changeFeaturedStatus', ['product' => $products->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Yes</a>' : '<a href="' . url(route('changeFeaturedStatus', ['product' => $products->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">No</a>';

                $products->status = ($products->status === 'Active') ? '<a href="' . url(route('changeProductStatus', ['product' => $products->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>' : '<a href="' . url(route('changeProductStatus', ['product' => $products->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

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
        $query->where('product_category.category_name', 'like', '%' . $search . '%')
                ->orWhere('product_title', 'like', '%' . $search . '%')
                ->orWhere('brand_name', 'like', '%' . $search . '%')
                ->orWhere('products.id', 'like', '%' . $search . '%')
                ->orWhere('product_shipping.shipping_class', 'like', '%' . $search . '%')
                ->orWhere('products.status', 'like', '%' . $search . '%')
                ->orWhere('products.featured', 'like', '%' . $search . '%');
    }
}
