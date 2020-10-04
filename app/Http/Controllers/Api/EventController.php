<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use App\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Event Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles event related  apis.
     */
    
    /**
     * Gets event details api.
     *
     * @return json
     */
    public function eventDetails(Request $request)
    {
        $this->validate($request, [
            'event_slug' => 'required',
        ]);
        
        $user = session()->get('authUser');

        $eventQuery = ApiHelper::getEventQuery($user);
        
        $event = $eventQuery->where([
            'slug' => $request->event_slug
        ])->first();
        
        if(!empty($event))
        {
            $event = $event->toArray();
            $event['type'] = 'event';
            $event['created_at'] = Carbon::parse($event['created_at'])->getTimestamp();
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
            
            return $this->toJson([
                'event' => $event,
            ]);
        }

        return $this->toJson(null, trans('api.event.not_available'), 0);
    }
}