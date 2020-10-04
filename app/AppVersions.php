<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 12:58 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class AppVersions extends Model
{
    protected $table = 'app_versions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_version',
        'app_url',
        'app_is_update',
        'app_update_msg',
        'app_maintenance_msg',
        'app_type',
    ];
}