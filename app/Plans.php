<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 5:22 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_name',
        'sales_percentage',
        'status',
    ];

}