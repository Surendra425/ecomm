<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorDepositInfo extends Model
{
    protected $table = 'vendor_deposit_info';
    protected $fillable = [
        'benificiary_name',
        'account_number',
        'bank_name',
        'swift_code'
    ];
    
}
