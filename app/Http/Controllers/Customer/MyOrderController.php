<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\PDFHelper;
use App\Mail\OrderConfirmationMail;
use App\Mail\VendorOrderMail;
use App\Order;
use App\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/*
 |--------------------------------------------------------------------------
 | MyOrder Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles order pages.
 */

class MyOrderController extends Controller
{

    /**
     * set order success page
     * @param $orderId
     * @return view
     */
    public function orderSuccess($orderId){

        $orderId = base64_decode($orderId);

        $orderDetail = Order::find($orderId);
        if(!empty($orderDetail)) {

            $data = [
                'status' => ($orderDetail->payment_status=='Completed' || $orderDetail->payment_status=='Pending')?'Placed ':$orderDetail->payment_status,
                'orderDetail' => $orderDetail,
                'order_content' => trans('messages.orders.status.approved'),
            ];

            if($orderDetail->payment_status == 'Cancelled' || $orderDetail->payment_status == 'Failed'){
                $data['order_content'] = trans('messages.orders.status.failed');
            }
        }
        else{
            return abort(404);
        }

        return view('front.orders.order_placed',$data);

    }

    /**
     * generate pdf and send order success mail
     * @Param $orderId
     * @Response json
     */

    public function sendOrderSuccessMailWithPdf($orderId){
        
        $orderId = base64_decode($orderId);
        
        // Send vendor email with invoice attachement

        $order = Order::find($orderId);

        if($order->is_mail_send == 1){
            //echo "Mail already send.";
            return;
        }

        $order->is_mail_send = 1;
        $order->save();
         
        $cmd = 'cd '.base_path().' && php artisan sendOrderSuccessMail:send '.$orderId;
        exec($cmd. '> /dev/null &');
        
        /*$orderProducts = $order->orderProducts;

        $vendorIds = $orderProducts->pluck('product_vendor_id')->unique()->toArray();
        $vendors = Vendor::whereIn('id', $vendorIds)
            ->with(['store' => function($query){
                return $query->select('vendor_id','store_name');
            }])
            ->get();

        foreach ($vendors as $vendor)
        {
            $vendorProducts = $orderProducts->where('product_vendor_id', $vendor->id);
            $data['order'] = $order;
            $data['vendorOrderProducts'] = $vendorProducts;
            $data['address'] = $order->address;
            $data['vendor'] = $vendor;
            //dd($vendor->store->store_name);
            $html = view('app.order_vendor_invoice', $data);

            $filePath = public_path('doc/invoice/'.$order->order_no . '_' . $vendor->id . '.pdf');
            PDFHelper::generatePdfFile($filePath, $html);
            chmod($filePath, 0777);
        }

        try{
            //Send notification mail to user
            Mail::to($order->user->email)->send(new OrderConfirmationMail($order->id));
            $order->is_mail_send = 1;
            $order->save();
            //Send notification mail to vendor
            foreach($vendors as $vendor)
            {
                Mail::to($vendor->email)->send(new VendorOrderMail($order,$vendor));
            }

        }
        catch(\Exception $e)
        {

        }*/
        
    }

    /**
     * My orders listing.
     *
     * @param Request $request
     * @return json
     */
    public function myOrders(Request $request)
    {
        $user = \Auth::guard('customer')->user();

        $data['orders'] = $this->getMyOrders($user);

        return view('front.user.my_orders',$data);

    }

    /**
     * Ajax call for more orders
     * @param Request $request
     * @return view
     */
    public function showMoreOrders(Request $request){

        $user = \Auth::guard('customer')->user();

        $data['orders'] = $this->getMyOrders($user);

        return view('front.user.order_box',$data);
    }

    /**
     * Query of fetch customer orders
     * @param $user
     * @return mixed
     */
    public function getMyOrders($user){

        $orders = Order::selectRaw('id, order_no, payment_status, created_at,grand_total, IF(payment_type != "KNet", (IF(payment_type != "CreditCard", "Cash", "Credit Card")),  "Knet") AS payment_type')
            ->where('customer_id', $user->id)
            ->where('payment_status', '!=','Pending')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return $orders;
    }

    /**
     * get single order detail
     * @param $orderNo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myOrderDetail($orderNo){


        $user = \Auth::guard('customer')->user();

        $userId = '';

        $order = Order::selectRaw('id, order_no,sub_total,discount_amount,shipping_total,payment_status,
        is_mail_send,created_at,grand_total, IF(payment_type != "KNet", IF(payment_type != "CreditCard", "Cash",  "Credit Card"),  "Knet") AS payment_type,
        knet_payment_id, kent_track_id, knet_tran_id')
            ->where([
                'order_no' => $orderNo,
                //'customer_id' => $user->id
            ])->first();

        if(!empty($order))
        {
            $order->load([
                'orderProducts' => function ($query) use ($userId) {
                    $query->selectRaw('products.id AS product_id,order_products.id,order_products.product_combination_id,product_images.image_url AS image,
                                       order_products.order_id,order_products.note,products.product_title, order_products.quantity, order_products.rate AS price,
                                       order_products.sub_total,order_products.product_slug,product_attr_combination.combination_title, 
                                       stores.id AS store_id,stores.store_name AS store_name,stores.store_slug AS store_slug, CAST(IF(product_review.id != "NULL", product_review.rating, 0) AS UNSIGNED) AS user_rating')
                        ->leftjoin('products', 'products.id', '=', 'order_products.product_id')
                        ->leftjoin('product_images', 'products.id', '=', 'product_images.product_id')
                        ->leftjoin('stores', 'stores.vendor_id', '=', 'products.vendor_id')
                        ->leftJoin('product_review', function ($join) use ($userId) {
                            $join->on('product_review.product_id', '=', 'products.id')
                                ->where('product_review.user_id', '=', $userId);
                        })
                        ->leftjoin('product_attr_combination', 'order_products.product_combination_id', '=', 'product_attr_combination.id')
                        ->groupBy('order_products.id');
                },
                'address' => function ($query) {
                    $query->selectRaw('order_addresses.id, order_id,CONCAT(u.first_name, " ", u.last_name) as full_name,
                    block,street,avenue,building,additional_directions,floor,apartment,order_addresses.mobile_no AS mobile,
                     order_addresses.landline_no AS landline,city,state,country')
                        ->leftJoin('users as u', 'u.id', 'order_addresses.customer_id');
                }
            ]);
           // dd($order);
            $order['order'] = $order;
           
            return view('front.user.order_detail',$order);
        }
    }
}
