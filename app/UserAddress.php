<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';
    protected $fillable = [
        'full_name',
        'block',
        'street',
        'avenue',
        'building',
        'floor',
        'apartment',
        'additional_directions',
        'city',
        'state',
        'city_id',
        'country_id',
        'pin_code',
        'landline',
        'mobile',
        'country'
    ];
    
    /**
     * Country has many cities.
     *
     */
    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'id');
    }

    /**
     * Country has many cities duplicate but required.
     *
     */
    public function countryr()
    {
        return $this->belongsTo('App\Country', 'country_id', 'id');
    }
    
    /**
     * Country has many cities.
     *
     */
    public function city()
    {
        return $this->belongsTo('App\City',  'city_id', 'id');
    }

    /**
     * Country has many cities duplicate but required.
     *
     */
    public function cityr()
    {
        return $this->belongsTo('App\City',  'city_id', 'id');
    }
    
    /**
     * Country has many cities.
     *
     */
    public function area()
    {
        return $this->belongsTo('App\City',  'area_id', 'id');
    }
}
