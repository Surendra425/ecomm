<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $fillable = [
        'title',
        'title_ar',
        'message',
        'message_ar'
    ];
}
