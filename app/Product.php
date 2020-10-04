<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_title',
        'vendor_id',
        'long_description'
    ];
    
    /**
     * Product has many combination .
     */
    public function options()
    {
        return $this->hasMany('App\ProductAttrCombination', 'product_id', 'id')->where('is_delete', 0);
    }
    
    /**
     * Product has many images.
     */
    public function images()
    {
        return $this->hasMany('App\ProductImage', 'product_id', 'id');
    }
    
    /**
     * Product has many images.
     */
    public function image()
    {
        return $this->hasOne('App\ProductImage', 'product_id', 'id');
    }
    
    /**
     * Product has many videos .
     */
    public function videos()
    {
        return $this->hasMany('App\ProductVideo', 'product_id', 'id');
    }
 
    /**
     * Product has many product shipping.
     */
    public function productShipping()
    {
        return $this->hasMany('App\ProductShipping', 'product_id', 'id');
    }

    /**
     * Get vendor detail
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vendorDetail(){
        return $this->belongsTo('App\Vendor', 'vendor_id', 'id');
    }

    /**
     * Get Shopzz Categories
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopzzCategory(){
        return $this->hasMany('App\ShopzzCategory', 'product_id', 'id');
    }

    /**
     * Get product store Categories
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function storeProductCategory(){
        return $this->hasMany('App\StoreProductCategory', 'product_id', 'id');
    }
}
