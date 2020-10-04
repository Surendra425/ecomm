<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTokens extends Model
{
    protected $fillable = [
        'language',
        'device_id',
        'fcm_token'
    ];
}
