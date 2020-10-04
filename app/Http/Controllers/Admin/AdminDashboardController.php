<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 19/1/18
 * Time: 3:53 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Order;
use App\Store;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        
        $data['customer'] = User::where('type','customer')->count();
        $data['vendor'] = User::where('type','vendor')->count();
        $data['store'] = Store::count();
        $data['totalSale'] = Order::select(DB::raw('sum(grand_total) as total_sale'))->where('payment_status','Completed')->first();
        $data['totalCOD'] = Order::select(DB::raw('sum(grand_total) as total_sale'))->where('payment_status','Completed')->where('payment_type' ,'=','Cash on Delivery')->first();
        $data['totalKnet'] = Order::select(DB::raw('sum(grand_total) as total_sale'))->where('payment_status','Completed')->where('payment_type' ,'!=','Cash on Delivery')->first();

        // echo"<pre>";print_r($data);die;

        return view('admin.dashboard',$data);
    }

    public function Profile()
    {
        return view('admin.users.profile');
    }
}