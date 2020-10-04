<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\Deals;
use App\DealProducts;

class VendorDealProductsController extends Controller
{

    public function store(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
//        $social_media_detail = $vendor->social_media_detail()->first();
//        if (empty($social_media_detail))
//        {
//            $social_media_detail = new VendorSocialMedia();
//            $social_media_detail->vendor_id = $vendor->id;
//        }
//        $social_media_detail->fill($request->all());
//        if ($social_media_detail->save())
//        {
//            return redirect(url("vendor/social-media"))->with('success', trans('messages.social_media.updated'));
//        }
//        return redirect(url("vendor/social-media"))->with('error', trans('messages.error'));
    }


    public function create(Deals $deal)
    {
        
    }

}
