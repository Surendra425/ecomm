<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 9/3/18
 * Time: 2:43 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_no',
    ];
}