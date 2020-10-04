<?php

namespace App\Http\Controllers\VendorAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
//Trait
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{

    //Sends Password Reset emails
    use SendsPasswordResetEmails;

    //Shows form to request password reset
    public function showLinkRequestForm()
    {
        return view('auth.passwords.vendor.email');
    }

    //Password Broker for Seller Model
    public function broker()
    {
        return Password::broker('vendors');
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return redirect(route('vendorLogin'))->with('success', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return redirect(route('vendorLogin'))->with('error', trans($response));        
    }
}
