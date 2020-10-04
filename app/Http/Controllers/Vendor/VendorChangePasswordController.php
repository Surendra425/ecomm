<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorChangePasswordController extends Controller
{
    /*
     * Show admin booking listing.
     */

    public function index()
    {
        return view('admin.change_password.index');
    }

    /**
     * To change password of admin user.
     *
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        $currentUser = Auth::guard('vendor')->user();
        $currentPassword = $currentUser->password;

        // Check old password is current or not
        if (Hash::check($request->current_password, $currentPassword))
        {

            $currentUser->password = bcrypt($request->password);
            $currentUser->save();

            return redirect(route('password.index'))->with('success', trans('messages.changePassword.success'));
        }

        return redirect(route('password.index'))->with('error', trans('messages.changePassword.error'));
    }

}
