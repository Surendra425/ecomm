<?php

namespace App\Http\Controllers\Customer;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Event Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles home screen apis.
     */

    /**
     * Gets product details api.
     *
     * @return json
     */
    public function eventDetails($eventSlug)
    {
        $data['event'] = Event::where([
            'slug' => $eventSlug,
            'status' => 1,
        ])->with('media')->first();


        if (!empty($data['event']))
        {
            return view('app.event_details', $data);
        }

        return abort(404);
    }
}
