<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductLike;
use App\ProductImage;
use App\ProductCart;
use App\ProductShipping;
use App\ProductCategory;
use App\ProductAttrCombination;
use App\User;
use App\Order;
use App\OrderProduct;
use App\OrderAddress;
use App\OrderTraking;

class VendorOrderController extends Controller
{

    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $data = [];
        $data['vendor'] = $vendor;
        return view('vendor.order.index', $data);
    }

    public function show(Order $my_order)
    {
        $order = $my_order;

        $vendor = Auth::guard('vendor')->user();
        $order_products = OrderProduct::selectRaw("order_products.*, products.product_title, users.first_name, users.last_name,product_attr_combination.combination_title")
                ->join("products", "products.id", "order_products.product_id")
                ->join("product_attr_combination", "product_attr_combination.id", "order_products.product_combination_id")
                ->join("users", "users.id", "order_products.product_vendor_id")
                ->where("order_products.product_vendor_id", "=", $vendor->id)
                ->where("order_id", $order->id)
                ->get();

        $orderProduct = Order::selectRaw('orders.id, orders.customer_name, SUM(order_products.sub_total) AS sub_total,order_products.shipping_charges, SUM(order_products.grand_total) AS grand_total, orders.tax, orders.payment_type, orders.payment_status, orders.created_at')
            ->join("order_products", "order_products.order_id", "orders.id")
            ->where("order_products.product_vendor_id", "=", $vendor->id)
            ->where("order_id", $order->id)
            ->groupBy('orders.id')->first();
            $orderProduct->grand_total = number_format((float)($orderProduct->sub_total + $orderProduct->shipping_charges),2);
        $orderProduct->sub_total = number_format((float)($orderProduct->sub_total),2);
        $orderProduct->shipping_charges = number_format((float)($orderProduct->shipping_charges),2);
       // dd($orderProduct);die;
        //echo "<pre>";print_r($orderProduct);die;
        /*$order->sub_total = array_sum(array_column($order_products->toarray(), 'sub_total'));
        $order->shipping_total = array_sum(array_column($order_products->toarray(), 'shipping_total'));
        $order->grand_total = array_sum(array_column($order_products->toarray(), 'grand_total'));*/
        $customer = User::find($order->customer_id);
        $shipping_address = OrderAddress::select("order_addresses.*")
                        ->where("order_id", "=", $order->id)->where("address_type", "Shipping")->first();
        $data = [];
        $data['orderProduct'] = $orderProduct;
        $data['vendor'] = $vendor;
        $data['customer'] = $customer;
        $data['order'] = $order;
        $data['order_products'] = $order_products;
        $data['shipping_address'] = $shipping_address;
        
        return view('vendor.order.order_detail', $data);
    }

    /**
     * Search Order.
     *
     * @return json
     */
    public function search(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        if ($request->ajax())
        {
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $query = Order::selectRaw('orders.id, orders.customer_name,orders.order_no, SUM(order_products.sub_total) AS sub_total,
            SUM(order_products.grand_total) AS grand_total,order_products.shipping_charges, orders.tax, orders.payment_type, orders.payment_status, orders.created_at')
                    ->join("order_products", "order_products.order_id", "orders.id")
                    ->where("order_products.product_vendor_id", "=", $vendor->id)
                    ->groupBy('orders.id');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterOrder($request->search['value'], $query);

            $orders = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);

            $data = json_decode(json_encode($orders));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $order)
            {
                $order->grand_total = number_format((float)($order->sub_total + $order->shipping_charges),2);
                $order->sub_total = number_format((float)($order->sub_total),2);
                $order->action = '<a href="' . url(route('my_order.show', ['my_order' => $order->id])) . '" title="View"><i class="la la-eye"></i></a>';
            }
//dd($data);die;
            return response()->json($data);
        }
    }

    /**
     * Filter Order listing.
     *
     * @param $search
     * @return $query
     */
    private function filterOrder($search, $query)
    {
        $query->where(function ($query) use ($search)
        {
            $query->where('customer_name', 'like', '%' . $search . '%')
//                    ->orWhereRaw('SUM(order_products.sub_total)', 'like', '%' . $search . '%')
//                    ->orWhereRaw('SUM(order_products.shipping_total)', 'like', '%' . $search . '%')
//                    ->orWhereRaw('SUM(order_products.grand_total)', 'like', '%' . $search . '%')
//                    ->orWhereRaw('sub_total', 'like', '%' . $search . '%')
//                    ->orWhereRaw('SUM(order_products.shipping_total)', 'like', '%' . $search . '%')
//                    ->orWhereRaw('SUM(order_products.grand_total)', 'like', '%' . $search . '%')
                    ->orWhere('orders.created_at', 'like', '%' . $search . '%')
                    ->orWhere('orders.payment_type', 'like', '%' . $search . '%')
                    ->orWhere('orders.payment_status', 'like', '%' . $search . '%');
        });
//        $query->HavingRaw("orders.customer_name like '%$search%' OR orders.created_at like '%$search%'");
    }

    public function updateOrderProductStatus(Request $request)
    {
        $orderProduct = OrderProduct::find($request->order_product_id);
        $orderProduct->delivery_status = $request->delivery_status;
        if ($orderProduct->save())
        {
            return redirect(route('my_order.show', ['my_order' => $orderProduct->order_id]))->with('success', trans('messages.order_product.status_update'));
        }
        return redirect(route('my_order.show', ['my_order' => $orderProduct->order_id]))->with('error', trans('messages.error'));
    }

}
