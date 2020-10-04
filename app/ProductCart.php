<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    protected $table = 'product_cart';
    
    /**
     * Product has many combination .
     */
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
