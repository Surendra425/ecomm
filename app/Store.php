<?php

/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 9/1/18
 * Time: 11:52 AM
 */

namespace App;

use App\Scopes\VendorScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Store extends Model
{

    protected $table = 'stores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_name',
        'description',
        'address',
        'city',
        'state',
        'country',
        'featured',
        'about_us',
        'store_status',
        'status'
    ];

    /**
     * Scope method for return active records
     */

    public function ScoopActive($query){
        return $query->where('status','Active');
    }

    /**
     * store belongs to vendor.
     */
    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id', 'id');
    }

    /*
     * to get vendor name who dont have any store
     */

    public function getStoreById($id)
    {
        return $collection = DB::table($this->table)
                ->select('stores.*', 'users.first_name', 'users.last_name', 'store_category.*', 'vendor_category.vendor_category_name')
                ->leftJoin('users', 'users.id', '=', 'stores.vendor_id')
                ->leftJoin('store_category', 'store_category.store_id', '=', 'stores.id')
                ->leftJoin('vendor_category', 'vendor_category.id', '=', 'store_category.vendor_category_id')
                ->where('stores.id', $id)
                ->get();
    }

    /**
     * store belongs to vendor.
     */
    public function shippingCities()
    {
        return $this->belongsTo('App\VendorShippingDetail', 'vendor_id', 'vendor_id');
    }
}
