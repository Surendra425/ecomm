<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'address',
        'latitude',
        'longitude',
        'contact_number',
        'contact_number',
        'event_type',
        'status',
        'description'
    ];
    
    /**
     * Event has many medias.
     */
    public function media()
    {
        return $this->hasMany('App\EventMedia', 'event_id', 'id');
    }
    
    /**
     * Event has many medias.
     */
    public function images()
    {
        return $this->hasMany('App\EventMedia', 'event_id', 'id');
    }
}
