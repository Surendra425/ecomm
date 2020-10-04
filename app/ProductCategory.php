<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
     protected $table = 'product_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name',
        'category_name_ar',
        'featured',
        'status',
        'description',
        'category_icon',
    ];
    /**
     * Category has many subcategory.
     */
    public function category()
    {
        return $this->belongsTo('App\ProductCategory', 'id', 'parent_category_id');
    }
    
    /**
     * Category has many subcategory.
     */
    public function subCategories()
    {
        return $this->hasMany('App\ProductCategory', 'parent_category_id')
                    ->where('status', "=","Active")
                    ->where('parent_category_id', '!=', null)
                    ->orderBy('product_category.order_no','ASC');
    }
    
    
    /**
     * Api category has many subcategory.
     */
    public function sub_categories()
    {
        return $this->hasMany('App\ProductCategory', 'parent_category_id')
                    ->selectRaw('product_category.id, category_name,category_image,parent_category_id')->where('status', "=","Active")
                    ->where('parent_category_id', '!=', null)
                    ->orderBy('product_category.order_no','ASC');
    }
}
