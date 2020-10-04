<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttrCombination extends Model
{
    protected $table = 'product_attr_combination';
    
    /**
     * Product has many combination .
     */
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}

