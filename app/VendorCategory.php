<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 9/1/18
 * Time: 5:36 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    protected $table = 'vendor_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_category_name',
         'status',
        'featured'
    ];

}