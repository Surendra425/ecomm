<?php

namespace App\Http\Controllers\VendorAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
//Auth Facade
use Illuminate\Support\Facades\Auth;
//Password Broker Facade
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{

    //trait for handling reset Password
    use ResetsPasswords;

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        $data = [
            'token' => $token,
            'email' => $request->email,
            'resetPage' => true
        ];
        return view('auth.passwords.vendor.reset', $data);
    }

    public function broker()
    {
        return Password::broker('vendors');
    }

    //returns authentication guard of seller
    protected function guard()
    {
        return Auth::guard('vendor');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
                $this->credentials($request), function ($user, $password)
        {
            $this->resetPassword($user, $password);
        }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET ? $this->sendResetResponse($response) : $this->sendResetFailedResponse($request, $response);
    }
    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse($response)
    {
        return redirect(route('vendorDashboard'))->with('success', trans($response));
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect(route('vendorLogin'))->with('error', trans($response));
    }

}
