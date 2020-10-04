<?php

namespace App\Http\Controllers\Vendor;

use App\ProductVisitor;
use App\Store;
use App\StoreVisitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Product;
use App\Order;
use App\OrderProduct;

class VendorDashboardController extends Controller
{

    public function dashboard()
    {
        
        $date =date('Y-m-d').' 00:00:00';
        $vendor = Auth::guard('vendor')->user();
       // dd($vendor);die;
        $data['totalProducts'] = Product::where("vendor_id", "=", $vendor->id)->count();
        $data['totalOrders'] = Order::selectRaw('orders.id, orders.customer_name, SUM(order_products.sub_total) AS sub_total, SUM(order_products.grand_total) AS grand_total, orders.tax, orders.payment_type, orders.payment_status, orders.created_at')
                        ->join("order_products", "order_products.order_id", "orders.id")
                        ->where("order_products.product_vendor_id", "=", $vendor->id)
                        ->groupBy('orders.id')->get()->count();
        $data['totalSales'] = Order::selectRaw('SUM(order_products.sub_total) AS sub_total,  SUM(order_products.grand_total) AS grand_total,COUNT(DISTINCT(orders.customer_id)) as total_customer')
                ->join("order_products", "order_products.order_id", "orders.id")
                ->where("order_products.product_vendor_id", "=", $vendor->id)
                ->groupBy('order_products.product_vendor_id')
                ->first();
        $data['totalDaliyOrders'] = Order::selectRaw('orders.id, orders.customer_name, SUM(order_products.sub_total) AS sub_total,  SUM(order_products.grand_total) AS grand_total, orders.tax, orders.payment_type, orders.payment_status, orders.created_at')
            ->join("order_products", "order_products.order_id", "orders.id")
            ->where("order_products.product_vendor_id", "=", $vendor->id)
            ->where("orders.created_at", ">=", $date)
            ->groupBy('orders.id')->get()->count();
        $data['totalCustomers'] = Order::selectRaw('DISTINCT(orders.customer_id) as total_customer')
                        ->join("order_products", "order_products.order_id", "orders.id")
                        ->where("order_products.product_vendor_id", "=", $vendor->id)->get()->count();
        $data['totalStoreVisitor'] = StoreVisitor::select('store_visitor.store_id')->leftjoin('stores','stores.id','store_visitor.store_id')
            ->where('vendor_id',$vendor->id)->count();
        //echo "<pre>";print_r($data['totalStoreVisitor']);die;
        $data['totalProductVisitor'] = ProductVisitor::select('product_visitor.product_id')->leftjoin('products','products.id','product_visitor.product_id')
            ->where('vendor_id',$vendor->id)->count();
        //echo "<pre>";print_r($data['totalDaliyOrders']);die;
        return view('vendor.dashboard', $data);
    }

    public function Profile()
    {
        return view('vendor.users.profile');
    }
    public function vendorInactivePage(){
        return view('vendor.inactive');
    }
}
