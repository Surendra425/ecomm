<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 2:41 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Collections extends Model
{
    protected $table = 'collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'collection_name',
        'collection_name_ar',
        'collection_tagline',
        'status',
        'display_status',
    ];
}