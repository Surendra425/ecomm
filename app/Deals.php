<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deals extends Model
{
    protected $table = 'deals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deal_name',
        'discount_type',
        'discount_amount',
        'min_total_amount',
        'max_discount_amount',
        'start_date',
        'end_date',
        'status',
    ];
}
