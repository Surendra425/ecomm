<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorSocialMedia extends Model
{
    protected $table = 'vendor_social_media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facebook',
        'google_plus',
        'instagram',
        'twitter',
    ];
}
