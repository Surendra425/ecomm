<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreRating extends Model
{
    protected $table = 'store_rating';

    protected $fillable = ['store_id', 'user_id', 'rating'];
}
