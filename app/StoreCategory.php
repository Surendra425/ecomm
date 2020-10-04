<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 11/1/18
 * Time: 10:38 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class StoreCategory extends Model
{
    protected $table = 'store_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_category_id',
        'store_id',
    ];
}