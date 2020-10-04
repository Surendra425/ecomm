<?php

namespace App;

use App\Scopes\VendorScope;
use Hamcrest\Core\IsNull;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\VendorResetPasswordNotification;

class Vendor extends User
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
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new VendorScope);
    }

    /**
     * Vendor has one store.
     */
    public function store()
    {
        return $this->hasOne('App\Store', 'vendor_id', 'id');
    }

    /**
     * Vendor has many proucts.
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }
    
    
    /**
     * Vendor has bank detail.
     */
    public function bank_detail()
    {
        return $this->hasOne('App\VendorDepositInfo', 'vendor_id', 'id');
    }
    /**
     * Vendor has social media detail.
     */
    public function social_media_detail()
    {
        
        return $this->hasOne('App\VendorSocialMedia', 'vendor_id', 'id');
    }

    /*
     * to get vendor name who dont have any store
     */

    public function getVendor()
    {
        return $collection = DB::table($this->table)
                ->select('users.id', 'users.first_name', 'users.last_name')
                ->leftJoin('stores', 'stores.vendor_id', '=', 'users.id')
                ->whereNull('stores.vendor_id')
                ->where('type', 'vendor')
                ->get();
    }

//Send password reset notification

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new VendorResetPasswordNotification($token));
    }

}
