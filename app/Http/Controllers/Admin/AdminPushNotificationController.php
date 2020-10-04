<?php

namespace App\Http\Controllers\Admin;

use App\PushNotification;
use App\UserTokens;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminPushNotificationController extends Controller
{

    /**
     * Load message page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.pushNotification.index');
    }

    /**
     * Send push notifications
     *
     * @param Request $request
     */
    public function sendPushNotification(Request $request)
    {
        $this->validate($request, [
           'title' => 'required',
           'message' => 'required'
        ]);
        
        $notification = new PushNotification();
        $notification->fill($request->all());
        $notification->save();

        $cmd = 'cd '. base_path().' && php artisan send:SendPushNotificationsCommand '.$notification->id;
        exec($cmd. ' > /dev/null &');

        return redirect(route('push-notification.index'))->with('success',trans('messages.push-notifications.send'));
    }
}
