<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 20/2/18
 * Time: 10:34 AM
 */
namespace App\Http\Controllers;

use App\MonthSales;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TranscationMail;
use App\Vendor;
use App\VendorPlanDetail;

class SaleReport extends Controller
{

    public function index(Request $request)
    {
        $date = !empty($request->date) ? $request->date : date('Y-M');
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        
        return view('reports.sales_report', [
            'loginUser' => $loginUser->type,
            'date' => $date,
        ]);
        // echo "<pre>";print_r($orders);die;
    }

    public function search(Request $request)
    {
        //start: 10
        //length: 10
        //echo $request->length;die;
       
        if (! empty($request->date)) {
            $monthStart = date('Y-m-d', strtotime($request->date)) . " 00:00:00";
            $monthEnd = new Carbon('last day of ' . $request->date);
        } else {
            
            $thisMonth = new Carbon('first day of this month');
            $date = explode(' ', $thisMonth);
            $monthStart = $date[0] . ' 00:00:00';
            $monthEnd = new Carbon('last day of this month') . "00:00:00";
            $request->date= $thisMonth;
        }
        //echo $thisMonth . '      '.$request->date;  die;
        $date = date('Y-m');
        $currentDate = date('Y-m-d', strtotime($date)) . " 00:00:00";
        
        if ($request->ajax()) {
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);
            
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $url = request()->segment(1);
            $loginUser = Auth::guard($url)->user();
            
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $query = "SELECT
                MainSub.product_vendor_id as vendor_id,
                MainSub.vendorName,
                MainSub.checkId,
                MainSub.sales_percentage,
                MAX(MainSub.Knet) as Knet,
                MAX(MainSub.COD) as COD
                FROM
                (select  (CASE WHEN orders.payment_type != 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS Knet,
                 (CASE WHEN orders.payment_type = 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS COD,
                 GROUP_CONCAT(orders.id) as checkId,
                 orders.id,`orders`.`payment_type`,CONCAT(first_name, ' ', last_name) as vendorName,
                 `order_products`.`product_vendor_id`,`sales_percentage` from `orders` 
                  left join `order_products` on `order_products`.`order_id` = `orders`.`id` 
                  join `users` on `users`.`id` = `order_products`.`product_vendor_id` 
                  left join `vendor_plan_info` on `vendor_plan_info`.`vendor_id` = `users`.`id`                 
                  where `orders`.`created_at` >= '{$monthStart} '
                  and `orders`.`created_at` <= '{$monthEnd} '
                  and `vendor_plan_info`.`status` = 'Active'
                  group by `order_products`.`product_vendor_id`,orders.payment_type order by " . $orderColumn . " " . $orderDir . ") 
                  MainSub GROUP BY MainSub.product_vendor_id ";
            
            
            $data['data'] = $record = DB::select($query);
            
            if ($loginUser->type == 'vendor') {
                $query->where('order_products.product_vendor_id', $loginUser->id);
            }
            /*
             * // DB::raw('sum(order_products.grand_total) as total_sale,
             * //$query = Collections::select('collection_name', 'collection_tagline', 'collections.status', 'display_status','collections.id');
             * $orderDir = $request->order[0]['dir'];
             * $orderColumnId = $request->order[0]['column'];
             * $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']); ds             * $this->filterSales($request->search['value'], $query);
             * $order = $query->orderBy($orderColumn, $orderDir)
             * ->paginate($request->length);
             */
            
            $query1 = "SELECT 
                MainSub.product_vendor_id as vendor_id,
                MainSub.vendorName,
                MainSub.sales_percentage,
                MAX(MainSub.Knet) as Knet,
                MAX(MainSub.COD) as COD
                FROM
                (select  (CASE WHEN orders.payment_type != 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS Knet,(CASE WHEN orders.payment_type = 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS COD,
                 orders.id,`orders`.`payment_type`,CONCAT(first_name, ' ', last_name) as vendorName,
                 `order_products`.`product_vendor_id`,`sales_percentage` from `orders` 
                 left join `order_products` on `order_products`.`order_id` = `orders`.`id` 
                  join `users` on `users`.`id` = `order_products`.`product_vendor_id` 
                 left join `vendor_plan_info` on `vendor_plan_info`.`vendor_id` = `users`.`id` 
                 where `orders`.`created_at` >= '{$monthStart} ' 
                 and `orders`.`created_at` <= '{$monthEnd} ' 
                 and `orders`.`payment_status` = 'Completed'
                    and `vendor_plan_info`.`status` = 'Active'
                 group by `order_products`.`product_vendor_id`,orders.payment_type) 
            MainSub GROUP BY MainSub.product_vendor_id
                ";
            
            $data1 = DB::select($query1);
            
            $data['recordsTotal'] = count($record);
            $data['total'] = count($data1);
            $data = json_decode(json_encode($data));
            $data->recordsFiltered = $data->recordsTotal = $data->total;
            
            foreach ($data->data as $orders) {
                
                // if($currentDate != $monthStart){
                // $monthSale = MonthSales::where('vendor_id',$orders->vendor_id)->where('month',$monthStart)->get();
                $totalPaid = DB::table('month_sales_reports')->where('vendor_id', $orders->vendor_id)
                    ->where('month', $monthStart)
                    ->sum('paid_amount');
                    //dd($totalPaid);die;
                if ($totalPaid != 0) {
                    $orders->COD = '0';
                    $orders->Knet = '0';
                    $orders->CODAmount = '0';
                    $orders->KnetAmount = '0';
                    $orders->paid = $totalPaid;
                    $orders->totalComissions = '0';
                    $amount = '0';
                } else {
                    
                    $orders->COD = floatval($orders->COD);
                    $orders->Knet = floatval($orders->Knet);
                    $orders->CODAmount = '0';
                    $orders->KnetAmount = '0';
                    $orders->CODAmount = floatval((($orders->COD * $orders->sales_percentage) / 100));
                    $orders->KnetAmount = floatval((($orders->Knet * $orders->sales_percentage) / 100));
                    $pending = ($orders->Knet - $orders->KnetAmount);
                    $amounts = $pending - $orders->CODAmount;
                    
                    $orders->totalComissions = $orders->KnetAmount + $orders->CODAmount;
                    
                    $orders->paid = 0;
                    $amount = $amounts;
                }
                $orders->remaning = $amount;
                $actionName = 'Pay';
                $btnClass= 'primary';
                if($amount == 0){
                    $actionName = 'Paid';
                    $btnClass= 'success';
                }
                $orders->action = '<a class="m-badge m-badge--'.$btnClass.' m-badge--wide" data-value="' . $amount . ' KD" data-name="' . $orders->vendorName . '"  title="'.$actionName.'">'.$actionName.'</a>';
                if($currentDate != $monthStart && $amount != 0){
                   $orders->action = '<a class="m-badge m-badge--primary m-badge--wide pay-data" data-value="' . $amount . ' KD" data-name="' . $orders->vendorName . '" href="' . url(route('changeSaleStatus', [
                       'vendorId' => $orders->vendor_id,
                       'month' => $monthStart,
                       'paid' => $amount
                   ])) . '"  title="Pay">Pay</a>';
                }
               
                
                $orders->vendorName = $orders->vendorName . ' (' . $orders->sales_percentage . ' % )';
                /*
                 * }else{
                 * $orders->action = '<a class="btn btn-primary" title="Pay"><i class="fa fa-paypal" disabled></i>&nbsp;Pay</a>';
                 * }
                 */
            }
            // echo "<pre>";print_r($data->data);die;
            return response()->json($data);
        }
    }

    /**
     * Filter slaes report listing.
     *
     * @param
     *            $search
     * @return $query
     */
    private function filterSales($search, $query)
    {
        $query->where(function ($query) use ($search) {
            
            $query->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', "'%'.$search.'%'");
        });
    }

    public function changeStatus($vendorId, $month, $paid)
    {
        $monthEnd = new Carbon('last day of ' . $month);
        
        $query = "SELECT
                MainSub.product_vendor_id as vendor_id,
                MainSub.vendorName,
                MainSub.sales_percentage,
                MAX(MainSub.Knet) as Knet,
                MAX(MainSub.COD) as COD
                FROM
                (select  (CASE WHEN orders.payment_type != 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS Knet,(CASE WHEN orders.payment_type = 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS COD,
                 orders.id,`orders`.`payment_type`,CONCAT(first_name, ' ', last_name) as vendorName,
                 `order_products`.`product_vendor_id`,sales_percentage from `orders`
                 left join `order_products` on `order_products`.`order_id` = `orders`.`id`
                 left join `users` on `users`.`id` = `order_products`.`product_vendor_id`
                 left join `vendor_plan_info` on `vendor_plan_info`.`vendor_id` = `users`.`id`
                 where `orders`.`created_at` >= '{$month} '
                 and `orders`.`created_at` <= '{$monthEnd} '
                 and `orders`.`payment_status` = 'Completed'
                    and `order_products`.`product_vendor_id` = '{$vendorId}'
and `vendor_plan_info`.`status` = 'Active'
                 group by `order_products`.`product_vendor_id`,orders.payment_type) MainSub GROUP BY MainSub.product_vendor_id";
        
        $sales =  DB::select($query);
        //dd($sales);die;
        $CODAmount = '00';
        $KnetAmount = '00';
        $CODAmount = round((($sales[0]->COD * $sales[0]->sales_percentage) / 100));
        $KnetAmount = round((($sales[0]->Knet * $sales[0]->sales_percentage) / 100));
        $pending= round($sales[0]->Knet - $KnetAmount);
        $amounts = $pending - $CODAmount;
        if ($amounts > 0) {
            $paymentType = 'Cr';
        } elseif ($amounts < 0) {
            $paymentType = 'Dr';
        }
        $totalComissions = $KnetAmount + $CODAmount;
        $monthSales = new MonthSales();
        $monthSales->vendor_id = $vendorId;
        $monthSales->month = $month;
        $monthSales->vendor_id = $sales[0]->vendor_id;
           $monthSales->knet_payment = $sales[0]->Knet;
           $monthSales->cod_comission_payment = $sales[0]->COD;
           $monthSales->kent_comission_payment = $KnetAmount;
           $monthSales->cod_payment = $CODAmount;
           $monthSales->total_comission_payment = $totalComissions;
           $monthSales->payment_type = $paymentType;
           $monthSales->payment_Satus = 'Paid';
        $monthSales->paid_amount = $paid;
        $vendor = Vendor::where('id', $vendorId)->first();
        $vendor->amount = $paid;
        if ($monthSales->save()) {
            Mail::to($vendor->email)->send(new TranscationMail($vendor, $monthSales));
            return redirect(route('sales.index').'?date='.$monthEnd->format('Y-m'))->with('success', trans('messages.sales.change_status'));
        } else {
            return redirect(route('sales.index').'?date='.$monthEnd->format('Y-m'))->with('error', trans('messages.error'));
        }
        // }
        // $this->salesStore();
        // echo $month;die;
        /*
         * $sale = MonthSales::where('vendor_id', $vendorId)->where('month', $month)->first();
         *
         *
         * $data = DB::table('month_sales_reports')
         * ->where('vendor_id', $vendorId)
         * ->where('month', $month)
         * ->update(['payment_Satus' => 'Paid','paid_amount'=>'0']);
         * if($data === 1) {
         *
         * return redirect(route('sales.index'))->with('success', trans('messages.sales.change_status'));
         * }
         */
        
        return redirect(route('sales.index'))->with('error', trans('messages.error'));
    }

    public function salesStore(Request $request)
    {
        $monthStart = new Carbon('first day of this month');
        $date = explode(' ', $monthStart);
        $monthStart = $date[0] . ' 00:00:00';
        $monthEnd = new Carbon('last day of this month');
        $query = "SELECT
                MainSub.product_vendor_id as vendor_id,
                MainSub.vendorName,
                MainSub.sales_percentage,
                MAX(MainSub.Knet) as Knet,
                MAX(MainSub.COD) as COD
                FROM
                (select  (CASE WHEN orders.payment_type != 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS Knet,(CASE WHEN orders.payment_type = 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS COD,
                 orders.id,`orders`.`payment_type`,CONCAT(first_name, ' ', last_name) as vendorName,
                 `order_products`.`product_vendor_id`,sales_percentage from `orders` 
                 left join `order_products` on `order_products`.`order_id` = `orders`.`id` 
                 left join `users` on `users`.`id` = `order_products`.`product_vendor_id` 
                 left join `vendor_plan_info` on `vendor_plan_info`.`vendor_id` = `users`.`id` 
                 where `orders`.`created_at` >= '{$monthStart} '
                 and `orders`.`created_at` <= '{$monthEnd} '
                 and `orders`.`payment_status` = 'Completed'
                 and `vendor_plan_info`.`status` = 'Active'
                 group by `order_products`.`product_vendor_id`,orders.payment_type) MainSub GROUP BY MainSub.product_vendor_id";
        
        $data['data'] = DB::select($query);
        $data = json_decode(json_encode($data));
        // echo $monthStart;die;
        foreach ($data->data as $orders) {
            
            $CODAmount = round((($orders->COD * $orders->sales_percentage) / 100));
            
            $KnetAmount = round((($orders->Knet * $orders->sales_percentage) / 100));
            $pending = round($orders->Knet - (($orders->Knet * $orders->sales_percentage) / 100));
            $amount = $pending - $CODAmount;
            if ($amount > 0) {
                $remaning = $amount;
                $status = 'Cr';
            } elseif ($amount < 0) {
                $remaning = str_replace('-', '', $amount);
                $status = 'Dr';
            }
            $totalComissions = $KnetAmount + $CODAmount;
            
            $monthSales = new MonthSales();
            $monthSales->vendor_id = $orders->vendor_id;
            $monthSales->month = $monthStart;
            $monthSales->knet_payment = $orders->Knet;
            $monthSales->cod_payment = $orders->COD;
            $monthSales->kent_comission_payment = $KnetAmount;
            $monthSales->cod_comission_payment = $CODAmount;
            $monthSales->total_comission_payment = $totalComissions;
            $monthSales->paid_amount = $remaning;
            $monthSales->payment_type = $status;
            $monthSales->payment_Satus = 'Remaining';
            $monthSales->save();
        }
    }

    public function vendorSaleRecord(Request $request)
    {
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();
        $query = "SELECT
                MainSub.y,
                MAX(MainSub.Knet) as a ,
                MAX(MainSub.COD) as b
                FROM
                (select  (CASE WHEN orders.payment_type != 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS Knet,(CASE WHEN orders.payment_type = 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS COD,
                 orders.id,`orders`.`payment_type`,orders.created_at,DATE_FORMAT(orders.created_at, '%Y-%m') as y,CONCAT(first_name, ' ', last_name) as vendorName,
                 `order_products`.`product_vendor_id`,sales_percentage from `orders` 
                 left join `order_products` on `order_products`.`order_id` = `orders`.`id` 
                 left join `users` on `users`.`id` = `order_products`.`product_vendor_id` 
                 left join `vendor_plan_info` on `vendor_plan_info`.`vendor_id` = `users`.`id` 
                 where `order_products`.`product_vendor_id` = '{$loginUser->id} '
                 and `vendor_plan_info`.`status` = 'Active'
                 group by DATE_FORMAT(orders.created_at, '%Y%m'),orders.payment_type) MainSub GROUP BY MainSub.y ";
        
        $data = DB::select($query);
        // $data = json_encode($data);
        
        return $data;
    }

    public function vendorSaleRecordForAdmin(Request $request)
    {
        $query = "SELECT
                MainSub.y,
                MAX(MainSub.Knet) as a ,
                MAX(MainSub.COD) as b
                FROM
                (select  (CASE WHEN orders.payment_type != 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS Knet,(CASE WHEN orders.payment_type = 'Cash on Delivery' THEN sum(order_products.grand_total)
                 ELSE 0 END) AS COD,
                 orders.id,`orders`.`payment_type`,orders.created_at,DATE_FORMAT(orders.created_at, '%Y-%m') as y,CONCAT(first_name, ' ', last_name) as vendorName,
                 `order_products`.`product_vendor_id`,sales_percentage from `orders` 
                 left join `order_products` on `order_products`.`order_id` = `orders`.`id` 
                 left join `users` on `users`.`id` = `order_products`.`product_vendor_id` 
                 left join `vendor_plan_info` on `vendor_plan_info`.`vendor_id` = `users`.`id` 
                 and `vendor_plan_info`.`status` = 'Active'
                 group by `order_products`.`product_vendor_id`, DATE_FORMAT(orders.created_at, '%Y%m'),orders.payment_type) MainSub GROUP BY MainSub.y ";

        $data =  DB::select($query);
       // echo "<pre>";print_r($data);die;
        //$data = json_encode($data);
        return $data;
    }
}