<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 18/1/18
 * Time: 11:22 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'shipping';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipping_class',
        'vendor_id',
        'status'
    ];
}