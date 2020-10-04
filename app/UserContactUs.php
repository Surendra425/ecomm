<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserContactUs extends Model
{
    protected $table = 'user_contact_us';
    protected $fillable = [
        'email',
        'subject',
        'description'
    ];
}
