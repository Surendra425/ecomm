<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 29/3/18
 * Time: 10:53 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class StaticPages extends Model
{
    protected $table = 'static_pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_name',
        'description',
        'description_ar',
        'headline',
        'headline_ar',
        'status'
    ];
}