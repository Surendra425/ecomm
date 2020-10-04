<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VendorProfileController extends Controller
{
    /*
     * Show vendor booking listing.
     */
    public function profile()
    {
        $data['vendor'] = Auth::user();
        
        return view('vendor.profile.index', $data);
    }

    /**
     * To change password of vendor user.
     *
     * @return json
     */
    public function store(Request $request)
    {
        $vendor = Auth::user();
        $this->validate($request, [ 
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$vendor->id.'|email',
            'mobile_no' => 'required',
            'profile_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
        $image = $request->file('profile_image');
        if(!empty($image)){
            $input['imagename'] = str_replace(" ",'-', $image->getClientOriginalName());       
             $destinationPath = public_path('doc/vendor_logo');
            
            $user_img = $request->profile_image_1;
            $img= $destinationPath.'/'.$user_img;
            if(!empty($user_img)){
                if(file_exists($img)){
                    unlink($img);
                }
            }
            
            $image->move($destinationPath, $input['imagename']);
            $vendor->profile_image = $input['imagename'];
        
        }

        $vendor->first_name = $request->first_name;
        $vendor->last_name = $request->last_name;
        $vendor->email = $request->email;
        $vendor->mobile_no = $request->mobile_no;
        
        if($vendor->save()) {
            $nameSlug = str_slug($request->first_name ."-". $request->last_name."-".$vendor->id, "-");
            DB::table('users')
                ->where('id', $vendor->id)
                ->update(['name_slug' => $nameSlug]);
            return redirect(route('VendorProfile'))->with('success', trans('messages.profile.success'));
        }

        return redirect(route('VendorProfile'))->with('error', trans('messages.error'));
    }
}
