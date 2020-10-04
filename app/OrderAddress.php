<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{

    protected $table = 'order_addresses';
    protected $fillable = [
        'full_name',
        'block',
        'street',
        'avenue',
        'building',
        'floor',
        'apartment',
        'additional_directions',
        'city',
        'state',
        'pin_code',
        'country',
        'mobile_no',
        'landline_no'
    ];

}
