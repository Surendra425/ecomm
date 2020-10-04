<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 5:24 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class PlanOptions extends Model
{
    protected $table = 'plans_options';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'price',
        'description',
        'status',
    ];
}