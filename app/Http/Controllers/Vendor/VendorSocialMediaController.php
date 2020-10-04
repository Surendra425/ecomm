<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\VendorSocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorSocialMediaController extends Controller
{

    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $social_media_detail = $vendor->social_media_detail()->first();
        $data['social_media_detail'] = $social_media_detail;
        return view('vendor.social_media.index', $data);
    }

    public function store(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        $social_media_detail = $vendor->social_media_detail()->first();
        if (empty($social_media_detail))
        {
            $social_media_detail = new VendorSocialMedia();
            $social_media_detail->vendor_id = $vendor->id;
        }
        $social_media_detail->fill($request->all());
        if ($social_media_detail->save())
        {
            return redirect(url("vendor/social-media"))->with('success', trans('messages.social_media.updated'));
        }
        return redirect(url("vendor/social-media"))->with('error', trans('messages.error'));
    }

}
