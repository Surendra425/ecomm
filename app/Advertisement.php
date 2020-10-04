<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $table = 'advertisements';

    protected $fillable = [
        'advertisement_name',
        'advertisement_tagline',
        'status',
        'display_status',
    ];
}
