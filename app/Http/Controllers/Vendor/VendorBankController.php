<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use DB;
use App\Store;
use App\VendorDepositInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorBankController extends Controller
{

    private $validationRules = [
        'benificiary_name' => 'required',
        'account_number' => 'required',
        'bank_name' => 'required',
        'swift_code' => 'required'
    ];

    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $bank_detail = $vendor->bank_detail()->first();
        $data['bank_detail'] = $bank_detail;
        return view('vendor.bank.index', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $vendor = Auth::guard('vendor')->user();
        $bank_detail = $vendor->bank_detail()->first();
        $bank_detail->fill($request->all());

        if ($bank_detail->save())
        {
            return redirect(url("vendor/bank_detail"))->with('success', trans('messages.bank_detail.updated'));
        }
        return redirect(url("vendor/bank_detail"))->with('error', trans('messages.error'));
    }

}
