<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorPlanDetail extends Model
{
    protected $table = 'vendor_plan_info';
    protected $fillable = [
        'plan_option_id',
        'plan_name',
        'plan_periods',
        'sales_percentage',
        'price',
        'start_at',
        'end_at',
        'description',
    ];
}
