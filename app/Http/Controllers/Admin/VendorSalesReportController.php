<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 28/3/18
 * Time: 6:10 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorSalesReportController extends Controller
{
    public function index()
    {
        $url = request()->segment(1);
        $loginUser = Auth::guard($url)->user();

        return view('reports.vendor_sales_report',['loginUser'=>$loginUser->type]);
        //echo "<pre>";print_r($orders);die;
    }

    public function search(Request $request){
        //  echo $request->date;die;
        if(!empty($request->date)){
            $monthStart = date('Y-m-d',strtotime($request->date))." 00:00:00";
            $monthEnd = new Carbon('last day of '.$request->date);
        }else{

            $monthStart = new Carbon('first day of this month');
            $date = explode(' ',$monthStart);
            $monthStart = $date[0].' 00:00:00';
            $monthEnd = new Carbon('last day of this month')."00:00:00";
        }

        $date =  date('Y-m');
        $currentDate = date('Y-m-d',strtotime($date))." 00:00:00";

        if ($request->ajax())
        {
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });
            $url = request()->segment(1);
            $loginUser = Auth::guard($url)->user();

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $query ="SELECT
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
                 `order_products`.`product_vendor_id`,sales_percentage from `orders` 
                 left join `order_products` on `order_products`.`order_id` = `orders`.`id` 
                 left join `users` on `users`.`id` = `order_products`.`product_vendor_id` 
                 left join `vendor_plan_info` on `vendor_plan_info`.`vendor_id` = `users`.`id` 
                 where `orders`.`created_at` >= '{$monthStart} '
                 and `orders`.`created_at` <= '{$monthEnd} '
                 and `orders`.`payment_status` = 'Completed'
                 group by `order_products`.`product_vendor_id`,orders.payment_type order by ".$orderColumn ." " .$orderDir.") MainSub GROUP BY MainSub.product_vendor_id";

            $data['data'] =  DB::select($query);

            if($loginUser->type == 'vendor'){
                $query->where('order_products.product_vendor_id',$loginUser->id);
            }
            /*// DB::raw('sum(order_products.grand_total) as total_sale,
            //$query = Collections::select('collection_name', 'collection_tagline', 'collections.status', 'display_status','collections.id');
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterSales($request->search['value'], $query);
            $order = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);*/


            $query1 ="SELECT 
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
                 group by `order_products`.`product_vendor_id`,orders.payment_type) MainSub GROUP BY MainSub.product_vendor_id";

            $data1 =  DB::select($query1);

            $data['recordsTotal'] = count($data1);
            $data['total'] = count($data);
            $data = json_decode(json_encode($data));
            //dd($data);die;
            $data->recordsFiltered = $data->recordsTotal = $data->total;
//echo "<pre>";print_r($data->data);die;
            foreach ($data->data as $orders)
            {

                $orders->vendorName = $orders->vendorName .' ('.$orders->sales_percentage.' % )';
                $amount = $orders->COD + $orders->Knet;

                $orders->total = $amount;
            }
            //echo "<pre>";print_r($data->data);die;
            return response()->json($data);
        }
    }
    /**
     * Filter slaes report listing.
     *
     * @param $search
     * @return $query
     */
    private function filterSales($search, $query)
    {
        $query->where(function ($query) use($search) {

            $query->where('first_name', 'like', '%'.$search.'%')
                ->orWhere('last_name', 'like', "'%'.$search.'%'");
        });
    }
}