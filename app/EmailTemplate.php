<?php
/**
 * Created by PhpStorm.
 * User: Angat
 * Date: 2018-02-23
 * Time: 6:53 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templets';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_content',
        'name',
        'subject',
    ];
}