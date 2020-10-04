<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    /*
     * Show admin booking listing.
     */
    public function profile()
    {
        $data['admin'] = Auth::user();
        //echo "<pre>";print_r($data['admin']);die;
        return view('admin.profile.index', $data);
    }

    /**
     * To change password of admin user.
     *
     * @return json
     */
    public function store(Request $request)
    {
        //echo "hi";die;
        //print_r($request);die;
        $admin = Auth::user();

        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'profile_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',

        ]);

        $image = $request->file('profile_image');
        $destinationPath = public_path('doc/profile_image');
        $user_img = $request->profile_image_1;
        $data = ImageHelper::imageSave($image,$destinationPath,$user_img); //image save
        if (!empty($data) && $data != 'false') {
            $admin->profile_image = $data;
        }
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        if($admin->save()) {

            return redirect(route('AdminProfile'))->with('success', trans('messages.profile.success'));
        }

        return redirect(route('AdminProfile'))->with('error', trans('messages.error'));
    }
}
