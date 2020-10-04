<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 12/1/18
 * Time: 6:29 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_name',
        'country_name_ar',
        'short_name',
        'status',
    ];

    public function scopeActive($query){
        return $query->where('status','Active');
    }
    
    /**
     * Product has many combination .
     */
    public function cities()
    {
        return $this->hasMany('App\City', 'country_id', 'id')->where(['status' => 'Active'])->orderBy('city_name');
    }
}