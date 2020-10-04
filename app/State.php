<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 11:42 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'state';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state_name',
        'country_id',
        'status',
    ];
}