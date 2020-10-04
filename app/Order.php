<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = 'orders';
    protected $fillable = [
        'sub_total',
        'shipping_total',
        'tax',
        'coupon_code',
        'order_total',
        'discount_amount',
        'grand_total',
        'payment_type'
    ];

    /**
     * Order has many order products.
     */
    public function orderProducts()
    {
        return $this->hasMany('App\OrderProduct', 'order_id', 'id');
    }
    
    /**
     * Order belongs to user.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'customer_id','id');
    }
    
    /**
     * Order has one address.
     */
    public function address()
    {
        return $this->hasOne('App\OrderAddress', 'order_id', 'id');
    }
}
