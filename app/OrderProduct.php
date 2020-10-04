<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{

    protected $table = 'order_products';

    /**
     * Order product belongs to product attr combination .
     */
    public function option()
    {
        return $this->belongsTo('App\ProductAttrCombination', 'product_combination_id');
    }
    
    /**
     * Order product belongs to product .
     */
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }


}
