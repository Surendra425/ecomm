<?php

namespace App;

use App\Scopes\CustomerScope;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Customer extends User
{
    protected $table = 'users';
   
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'first_name',
       'last_name',
       'email',
       'mobile_no',
       'password',
   ];

   /**
    * The "booting" method of the model.
    *
    * @return void
    */
   protected static function boot()
   {
       parent::boot();
       
       static::addGlobalScope(new CustomerScope);
   }
}
