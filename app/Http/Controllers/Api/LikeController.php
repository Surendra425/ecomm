<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use App\Helpers\ApiHelper;
use App\ProductLike;
use App\EventLike;
use App\Product;
use App\Event;
use Carbon\Carbon;

class LikeController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Like Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles like, unlike, gets unlikes apis.
     */

    /**
     * Update like or unlike product for user.
     *
     * @return json
     */
    public function likeOrUnlikeProduct(Request $request)
    {
        $user = session()->get('authUser');
        
        $this->validate($request, [
            'product_id' => 'required|numeric',
        ]);
        
        $product = Product::where([
                      'id' => $request->product_id,
                      'status' => 'Active',
                   ])->first();

        if(!empty($product))
        {
             $productLike = ProductLike::where([
                 'product_id' => $request->product_id,
                 'user_id' => $user->id,
             ])->first();

             // Check product is like or not
             if(empty($productLike))
             {
                 $productLike = new ProductLike();
                 $productLike->user_id = $user->id;
                 $productLike->product_id = $request->product_id;

                 // save as like product or not
                 if($productLike->save())
                 {
                     return $this->toJson([
                         'is_liked' => 1
                     ]);
                 }
                 
                 return $this->toJson([], trans('api.like_product.error'), 0);
             }

             // Unlike product
             if($productLike->delete())
             {
                 return $this->toJson([
                     'is_liked' => 0
                 ]);
             }

             return $this->toJson([], trans('api.like_product.error'), 0);
        }

        return $this->toJson([], trans('api.product.not_available'), 0);
    }
    
    /**
     * Update like or unlike event for user.
     *
     * @return json
     */
    public function likeOrUnlikeEvent(Request $request)
    {
        $user = session()->get('authUser');
        
        $this->validate($request, [
            'event_id' => 'required|numeric',
        ]);
        
        $event = Event::where([
            'id' => $request->event_id,
            'status' => 1,
        ])->first();
        
        if(!empty($event))
        {
            $eventLike = EventLike::where([
                'event_id' => $request->event_id,
                'user_id' => $user->id,
            ])->first();
            
            // Check event is like or not
            if(empty($eventLike))
            {
                $eventLike = new EventLike();
                $eventLike->user_id = $user->id;
                $eventLike->event_id = $request->event_id;
                
                // save as like event or not
                if($eventLike->save())
                {
                    return $this->toJson([
                        'is_liked' => 1
                    ]);
                }
                
                return $this->toJson([], trans('api.like_event.error'), 0);
            }
            
            // Unlike event
            if($eventLike->delete())
            {
                return $this->toJson([
                    'is_liked' => 0
                ]);
            }
            
            return $this->toJson([], trans('api.like_event.error'), 0);
        }
        
        return $this->toJson([], trans('api.event.not_available'), 0);
    }

    /**
     * Gets Likes products.
     *
     * @return json
     */
    public function getMyLiked(Request $request)
    {
        $user = session()->get('authUser');
        
        $eventsQuery = ApiHelper::getEventQuery($user);
        $productQuery = ApiHelper::getProductsQuery($request, $user);
        
        $products = $productQuery->where('product_likes.id', '!=', null)
                                 ->orderBy('product_likes.id', 'desc')
                                 ->get()->toArray();
        
        $events = $eventsQuery->where('event_likes.id', '!=', null)
                              ->orderBy('event_likes.id', 'desc')
                              ->get()->toArray();
        
                              
        $result = [];
        
        if(!empty($products))
        {
            foreach($products as $product)
            {
                $product['type'] = 'product';
                $product['created_at']= Carbon::parse($product['product_liked_date'])->getTimestamp();
                
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
        }
        
        if(!empty($events))
        {
            foreach($events as $event)
            {
                
                $event['type'] = 'event';
                $event['created_at'] = Carbon::parse($event['event_liked_date'])->getTimestamp();
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
      
      
        $resultData = collect($result)->sortByDesc('created_at')->values();
        
        // Check products is available or not
        if(!$resultData->isEmpty())
        {
             $result = [
                 'myLikes' => [
                     'data' => $resultData,
                 ]
             ];
             
             return $this->toJson($result);
        }
        
        
        

        return $this->toJson([], trans('api.my_likes.not_available'), 0);
    }
}