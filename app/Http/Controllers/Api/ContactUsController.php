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
use App\UserContactUs;

class ContactUsController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Contact us Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles contact to admin.
     */
    
    /**
     * Accept contact us request for admin.
     *
     * @return json
     */
    public function contactUs(Request $request)
    {
        $user = session()->get('authUser');

        $this->validate($request, [
            'subject' => 'required',
            'description' => 'required'
        ]);
        

        $contactUs = new UserContactUs();
        $contactUs->fill($request->all());
        $contactUs->email = $user->email;
        
        if($contactUs->save())
        {
            return $this->toJson(null, trans('api.contact_us.success'));
        }

        return $this->toJson(null, trans('api.contact_us.error'));
    }
}