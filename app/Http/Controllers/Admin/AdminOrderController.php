<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
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

class AdminOrderController extends Controller
{

    public function index()
    {
        $loginUser = Auth::guard('admin')->user();
        $data = [];
        $data['loginUser'] = $loginUser;
        return view('admin.order.index', $data);
    }

    public function show(Order $order)
    {
        $loginUser = Auth::guard('admin')->user();
        $order_products = OrderProduct::select("order_products.*", "products.product_title", "users.first_name", "users.last_name", "product_attr_combination.combination_title")
                ->join("products", "products.id", "order_products.product_id")
                ->join("product_attr_combination", "product_attr_combination.id", "order_products.product_combination_id")
                ->join("users", "users.id", "order_products.product_vendor_id")
                ->where("order_id", $order->id)
                ->get();
        $customer = User::find($order->customer_id);
        $shipping_address = OrderAddress::select("order_addresses.*")
                        ->where("order_id", "=", $order->id)->where("address_type", "Shipping")->first();
        
        $data = [];
        $data['customer'] = $customer;
        $data['order'] = $order;
        $data['order_products'] = $order_products;
        $data['shipping_address'] = $shipping_address;
        
        return view('admin.order.order_detail', $data);
    }

    /**
     * Search Order.
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

            $query = Order::where('orders.payment_status','!=', 'Pending')->select('orders.id', 'orders.customer_name', 'orders.order_no', 'orders.is_mail_send','orders.sub_total as subtotal', 'orders.shipping_total as shipping_total', 'orders.tax as tax', 'orders.grand_total as grand_total', 'orders.payment_type', 'orders.payment_status', 'orders.created_at', 'orders.is_mail_send');

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
                $order->action = '<a href="' . url(route('orders.show', ['order' => $order->id])) . '" title="View"><i class="la la-eye"></i></a>';
                

                if($order->payment_status == "Completed"){
                    if($order->is_mail_send == 1){
                        $mailStatus = '<i class="la la-fw" aria-hidden="true" title="Send">&#xf1c6;</i>';
                        $labelClass = "m-badge--success";
                        $pointer = "disabled";
                    }else{
                        $mailStatus = '<i class="la la-fw" aria-hidden="true" title="Sent Mail">&#xf2a8;</i>';
                        $labelClass = "m-badge--danger";
                        $pointer = "style='cursor:pointer'";
                    }

                    $order->is_mail_send = "<label data-val='$order->is_mail_send' data-orderid='".base64_encode($order->id)."' class='m-badge reSendMail $labelClass m-badge--wide' $pointer>".$mailStatus."</label>";

                }else{
                    $order->is_mail_send = '';
                }
                
                
                $order->sub_total = $order->subtotal.' KD';
                $order->shipping_total = ($order->shipping_total == 0) ? 'FREE' : $order->shipping_total.' KD';
                $order->tax = $order->tax.' KD';
                $order->grand_total = $order->grand_total.' KD';
            }

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
        $query->where(function ($subQuery) use($search){
            $subQuery->OrWhere('customer_name', 'like', '%' . $search . '%')
                ->orWhere('sub_total', 'like', '%' . $search . '%')
                ->orWhere('shipping_total', 'like', '%' . $search . '%')
                ->orWhere('grand_total', 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%')
                ->orWhere('payment_type', 'like', '%' . $search . '%')
                ->orWhere('payment_status', 'like', '%' . $search . '%');
        });
    }

}
