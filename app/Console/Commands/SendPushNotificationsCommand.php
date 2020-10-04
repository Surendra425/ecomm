<?php

namespace App\Console\Commands;

use App\Helpers\PushNotificationHelper;
use App\PushNotification;
use App\UserTokens;
use Illuminate\Console\Command;

class SendPushNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:SendPushNotificationsCommand {notificationId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notificationId = $this->argument('notificationId');

        $notification = PushNotification::find($notificationId);

        if(!empty($notification))
        {
            $enUsers = UserTokens::selectRaw('notification_tag,fcm_token')
                ->where('language','en')->whereNotNull('fcm_token')->get();

            if(!empty($enUsers))
            {
                foreach ($enUsers as $key => $userToken)
                {
                    $notificationDetails = [
                        'registration_ids' => [$userToken->fcm_token],
                        $userToken->notification_tag => [
                            'title' => $notification->title,
                            'body' => $notification->message,
                            'click_action' => 'Splash',
                            'icon' => '',
                            'sound' => 'default'
                        ]
                    ];

                    PushNotificationHelper::sendPushNotification($notificationDetails);
                }


            }

            $arUsers = UserTokens::selectRaw('notification_tag,fcm_token')
                ->where('language','ar')->whereNotNull('fcm_token')->get();


            if(!empty($arUsers))
            {
                foreach ($arUsers as $key => $userToken)
                {
                    $notificationDetails = [
                        'registration_ids' => [$userToken->fcm_token],
                        $userToken->notification_tag => [
                            'title' => $notification->title_ar,
                            'body' => $notification->message_ar,
                            'click_action' => 'Splash',
                            'icon' => '',
                            'sound' => 'default'
                        ]
                    ];

                    PushNotificationHelper::sendPushNotification($notificationDetails);
                }

            }

        }
    }
}
