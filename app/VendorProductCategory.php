<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorProductCategory extends Model
{
    protected $table = 'vendor_product_categories';

    protected $fillable = [
        'category_name',
        'featured',
        'status',
        'vendor_id'
    ];
}
