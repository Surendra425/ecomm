<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserChangePasswordController extends Controller
{

    private $validationRules = [
        'password' => 'required|confirmed',
    ];

    public function index()
    {
        $loginUser = Auth::guard('customer')->user();
        return view('auth.change_password', ['loginUser' => $loginUser]);
    }

    public function checkOldPassword(Request $request)
    {
        $loginUser = Auth::user();
        $user = User::where('id', $loginUser->id)->first();

        if (Hash::check($request->old_password, $user->password))
        {
            $responce = 1;
        }
        else
        {
            $responce = 0;
        }
        echo $responce;
    }

    public function store(Request $request)
    {
        $this->validationRules['old_password'] = 'required';
        $this->validate($request, $this->validationRules);
        $loginUser = Auth::user();
        $user = User::where('id', $loginUser->id)->first();
        if (Hash::check($request->old_password, $user->password))
        {
            $user->password = bcrypt($request->password);
            if ($user->save())
            {

                return redirect('change-password')->with('success', trans('messages.password.updated'));
            }
            return redirect('change-password')->with('error', trans('messages.error'));
        }
        return Redirect::back()->with('error', trans('messages.password.error'));
    }

}
