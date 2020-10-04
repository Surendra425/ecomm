<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 16/1/18
 * Time: 4:11 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    protected $table = 'coupons';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'coupon_code',
    'discount_type',
    'discount_amount',
    'min_total_amount',
    'max_discount_amount',
    'start_date',
    'end_date',
    'status',
];

}