<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use App\Helpers\ApiHelper;
use App\User;
use App\Customer;
use Carbon\Carbon;

/*
 |--------------------------------------------------------------------------
 | Home Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles home screen apis.
 */

class HomeController extends Controller
{
    protected $perPage = 20;

    /**
     * Gets listing products and events.
     *
     * @return json
     */
    public function index(Request $request)
    {
       $user = session()->get('authUser');
       $genderType = [];
       if(!empty($user->gender))
       {
           $genderType[] = 'Both'; 
           $genderType[] = $user->gender;
       }
       
       $productQuery = ApiHelper::getProductsQuery($request, $user, 'home_date_time,');
       $currentTime = Carbon::now();
       $todayStartDate = Carbon::now()->startOfDay();
       $todayEndDate = Carbon::now()->endOfDay();

       /* $productQuery = $productQuery->whereBetween('home_date_time',[
           $todayStartDate,$todayEndDate
       ]); */
       
       $productQuery = $productQuery->where('home_date_time','<=',$currentTime);

       $productsData = $productQuery->when(!empty($genderType),function($query) use($genderType) {
           $query->whereIn('gender_type', $genderType);
       })
       ->orderBy('products.home_date_time', 'desc')
       ->orderBy('products.created_at', 'desc')
       ->paginate($this->perPage)
       ->toArray();
       
       $hasMoreProducts = !empty($productsData['next_page_url']) ? 1 : 0;
       $products = collect($productsData['data']);
       
       $result = [];

       // Checks product is available or not.
       if(!$products->isEmpty())
       {
           //Gets last products created date
           $startDate = $products->last()['home_date_time'];
           
           $endDate = $products->first()['home_date_time'];

           $latestEvents = [];
           
           if($productsData['current_page'] == 1)
           {
               $endDate  = Carbon::now()->endOfDay();
               //$latestEvents = ApiHelper::getlatestEventAfterProducts($user, $startDate)->toArray();
           }
           
           if($productsData['current_page'] == $productsData['last_page'])
           {
               $startDate = Carbon::now()->startOfDay();
           }
           

           $events = ApiHelper::getEvents($user, $startDate, $endDate)->toArray();

           foreach($products as $product)
           {
               $product['type'] = 'product';
               $product['home_date_time']= Carbon::parse($product['home_date_time'])->getTimestamp();
               
               $product['images'] = collect(array_merge($product['images'], $product['videos']));
               
               $productImages = $product['images']->toArray();
               
               if(!empty($productImages))
               {
                   foreach($productImages as $key => $image)
                   {
                       unset($productImages[$key]['product_id']);
                   }
               }
               
               $product['files'] = $productImages;
               $product['images'] = $product['images']->pluck('file_name');
               
               unset($product['videos']);
               $result[] = $product;
           }
           
           if(!empty($events))
           {
               foreach($events as $event)
               {
                 
                   $event['type'] = 'event';
                   $event['home_date_time'] = Carbon::parse($event['start_date_time'])->getTimestamp();
                   unset($event['updated_at']);
                  
                   if(!empty($event['images']))
                   {
                       foreach($event['images'] as $key => $image)
                      {
                          unset($event['images'][$key]['event_id']);
                      }
                   }

                   $event['files'] = $event['images'];
                   $event['images'] = collect($event['files'])->pluck('file_name');
                   $result[] = $event;
               }
           }

           
           $resultData = collect($result)->sortByDesc('home_date_time')->values();

           return $this->toJson([
               'products' => $resultData,
               'hasMore' => $hasMoreProducts
           ]);
       }

       return $this->toJson($result, trans('api.products.not_available'), 0);
    }
}
