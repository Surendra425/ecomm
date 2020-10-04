<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use App\Customer;
use App\Mail\WelcomeMail;
use App\Http\Controllers\Controller;
use App\User;
use App\UserTokens;
use App\Helpers\ApiHelper;
use App\ProductCart;

class AuthController extends Controller
{
    
    use SendsPasswordResetEmails;
    
    /*
      |--------------------------------------------------------------------------
      | Auth Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles login,social login, registration and forgot password features.
     */

    /**
     * Login user in our system.
     *
     * @return json
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'device_id' => 'required',
            'fcm_token' => 'required'
        ]);

        $user = $this->getUserByEmail($request->email);

        if(!empty($user) && $user->type == 'customer') {
            
            if($user->status != 1) {

                return $this->toJson([], trans('api.login.inactive'), 0);
            }

            if (Hash::check($request->password, $user->password)) {
               
                $user->device_id = $request->device_id;
                $user->save();

                $this->storeUserToken($user->id, $request);

                ApiHelper::moveCartProducts($request->device_id, $user);
                
                $user->cart_count = $this->getUserCartCount($user->id);
                return $this->toJson($user, trans('api.login.success'));
            }
        }

        return $this->toJson([], trans('api.login.invalid'), 0);
    }
    
    /**
     * Register user in our system.
     * 
     * @param Request $request
     * @return json
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_no' => 'nullable',
            'gender' => 'nullable|in:Male,Female',
            'email' => 'required|email',
            'password' => 'required_if:is_create_account,1|min:6',
            'device_id' => 'required',
            'fcm_token' => 'required'
        ]);

        $user = new Customer();
        $userData = User::where('email', $request->email)->first();

        // check user is already registered or not
        if(!empty($userData) && $userData->type != 'guest')
        {
            return $this->toJson([], trans('api.register.already_exist'), 0);
        }
        
        elseif (!empty($userData) && $userData->type == 'guest')
        {
            $user = $userData;
        }

        $user->fill($request->all());
        $user->password = ($request->is_create_account) ? bcrypt($request->password) : bcrypt(str_random(10));
        $user->gender = !empty($request->gender) ? $request->gender : '';
        $user->type = ($request->is_create_account) ? 'customer' : 'guest';
        $user->status = 1;
        $user->device_id = $request->device_id;
        if($user->save())
        {
            $this->storeUserToken($user->id, $request);
            
            $userData = $this->getUserByEmail($request->email);

            if($userData->type == 'customer')
            {
                Mail::to($userData->email)->send(new WelcomeMail($userData));
                ApiHelper::moveCartProducts($userData->device_id, $userData);
            }
            $userData->cart_count = $this->getUserCartCount($userData->id);
            return $this->toJson($userData); 
        }

        return $this->toJson([], trans('api.register.error'), 0);
    }

    /**
     * Store User Token
     * @param Request $userId, $request
     * @return Response null
     */
    public function storeUserToken($userId, $request)
    {
        $userToken = UserTokens::where('fcm_token', $request->fcm_token)->first();

        $userToken = !empty($userToken) ? $userToken : new UserTokens();

            $userToken->user_id = $userId;
            $userToken->fill($request->all());
            $userToken->save();
    }

    /**
     * Social Login User.
     *
     * @return json
     */
    public function socialLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'social_type' => 'required|in:facebook,google',
            'social_id' => 'required|string',
            'first_name' => 'required',
            'last_name' => 'required',
            'device_id' => 'required',
            'fcm_token' => 'required'
        ]);
        
        $socialkey = $request->social_type.'_id';
        
        $userData = User::where('email', $request->email)
                        ->first();
     
        
        // Check user is already exist or not.
        if(!empty($userData)) {
            
            if($userData->type == 'vendor') {
                
                return $this->toJson([], trans('api.register.already_vendor'), 0);
            }
            if($userData->status != 1) {
                
                return $this->toJson([], trans('api.login.inactive'), 0);
            }

            $userData->$socialkey = $request->social_id;
            $userData->device_id = $request->device_id;
            $userData->save();
            $userData = $this->getUserByEmail($request->email);
            ApiHelper::moveCartProducts($request->device_id, $userData);
            $userData->cart_count = $this->getUserCartCount($userData->id);

            $this->storeUserToken($userData->id, $request);

            return $this->toJson($userData); 
        }
        
        $user = new Customer();
        $user->fill($request->all());
        $user->password = bcrypt(str_random(10));
        $user->mobile_no = !empty($request->mobile_no) ? $request->mobile_no : '';
        $user->gender = !empty($request->gender) ? $request->gender : 'Male';
        $user->type = 'customer';
        $user->status = 1;
        $user->device_id = $request->device_id;
        $user->$socialkey = $request->social_id;

        if($user->save())
        {
            $this->storeUserToken($user->id, $request);

            $userData = $this->getUserByEmail($request->email);
            
            Mail::to($userData->email)->send(new WelcomeMail($userData));
            
            ApiHelper::moveCartProducts($request->device_id, $userData);
          
            $userData->cart_count = $this->getUserCartCount($userData->id);

            return $this->toJson($userData);
        }
    }

    
    
    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return $this->toJson([], trans($response));
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
        return $this->toJson([], trans($response), 0);
    }
    
    
    /**
     * Gets user by email address.
     * @param $email
     * @return Customer $customer
     */
    private function getUserByEmail($email)
    {
        return User::select('id', 'first_name', 'last_name', 'email', 'password', 'mobile_no', 'gender', 'type',
                                'facebook_id', 'google_id', 'profile_image', 'device_id','landline_no', 'status')
                       ->where(['email' => $email])
                       ->first();
    }
    
    
    /**
     * Gets user cart total.
     * @return int $userId
     */
    private function getUserCartCount($userId)
    {
        $where = ['user_id' => $userId];
        
        return ProductCart::where($where)->count();
    }
}
