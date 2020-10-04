<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 17/1/18
 * Time: 12:17 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class ProductAttributeList extends Model
{
    protected $table = 'product_attributes_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attribute_name',
        'status',
    ];
}