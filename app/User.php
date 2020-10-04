<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MyResetPassword;
class User extends Authenticatable
{

    use Billable;
    use Notifiable;

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
        'landline_no',
        'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * customer has one billing addresses.
     */
    public function defaultAddress()
    {
        return $this->hasOne('App\UserAddress', 'user_id', 'id')->where('is_selected', 'Yes');
    }
    
    /**
     * customer has one shipping addresses.
     */
    public function shippingAddress()
    {
        return $this->hasOne('App\UserAddress', 'user_id', 'id')->where('type', 'shipping');
    }
    
    /**
     * customer has one billing addresses.
     */
    public function billingAddress()
    {
        return $this->hasOne('App\UserAddress', 'user_id', 'id')->where('type', 'billing');
    }
    
    /**
     * customer has many subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany('App\UserSubscription', 'customer_id', 'id');
    }
    
    /**
     * customer has many cart products.
     */
    public function cartProducts()
    {
        return $this->hasMany('App\Cart', 'user_id', 'id');
    }
    
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $data['token'] = $token;
        $data['broker'] = 'customer';
        $this->notify(new MyResetPassword($data));
    }
}
