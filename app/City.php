<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 12:58 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_name',
        'city_name_ar',
        'country_id',
        'status',
    ];
}