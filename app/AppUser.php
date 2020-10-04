<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 12:58 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'device_id',
        'device_name',
        'device_model',
        'app_version',
        'app_type',
        'is_show_update'
    ];
}