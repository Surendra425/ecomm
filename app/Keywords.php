<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Keywords extends Model
{
    protected $table = 'keywords';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'keyword'
    ];
}

