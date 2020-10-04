<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Default Api auth is guest user
Route::prefix('v1')->middleware('ApiAuth')->group(function () {

    // User login, register, social login and forgot password feature
    Route::post('login', 'Api\AuthController@login')->name('Login');
    Route::post('register', 'Api\AuthController@register')->name('Register');
    Route::post('socialLogin', 'Api\AuthController@socialLogin')->name('SocialLogin');
    Route::post('forgotPassword', 'Api\AuthController@sendResetLinkEmail')->name('ForgotPassword');

    // Check version api
    Route::post('checkAppVersion', 'Api\AppVersionController@checkAppVersion')->name('AppVersionList');
    
    // Get Country api
    Route::any('getCountryList', 'Api\CountryController@index')->name('CountryList');
    
    // Get Area api
    Route::any('getAreaList', 'Api\CountryController@getAreas')->name('AreaList');
    
    // Get Category api
    Route::any('getCategoryList', 'Api\CategoryController@index')->name('CategoryList');
    Route::post('getCategoryDetails', 'Api\CategoryController@getCategoryDetails')->name('GetCategoryDetails');
    Route::post('getCategoryProducts', 'Api\CategoryController@getCategoryProducts')->name('GetCategoryProducts');
    Route::post('getCategoryStores', 'Api\CategoryController@getCategoryStores')->name('GetCategoryStores');
    
    // Get Collection api
    Route::post('getCollectionProducts', 'Api\CollectionController@getCollectionProducts')->name('GetCollectionProducts');
    
    
    // Get Home Details api
    Route::post('getHomeDetails', 'Api\HomeController@index')->name('HomeDetails');
    
    // Search api
    Route::post('searchPage', 'Api\SearchController@index')->name('SearchPage');
    Route::post('getKeywords', 'Api\SearchController@getKeywords')->name('GetKeywords');
    Route::post('searchViewMore', 'Api\SearchController@viewMore')->name('ViewMore');
    Route::post('searchDetails', 'Api\SearchController@searchDetails')->name('ProductDetails');
    
    // terms and condition api
    Route::get('getTermConditions', 'Api\AppPageController@getTermConditions')->name('GetTermConditions');
    
    // Privacy policy api
    Route::get('getPrivacyPolicy', 'Api\AppPageController@getPrivacyPolicy')->name('GetPrivacyPolicy');

    // Change User Lang api
    Route::post('changeLanguage', 'Api\CustomerController@changeLanguage')->name('changeLanguage');
    
    Route::middleware('ApiAuth:customer')->group(function () {

        // Update profile
        Route::post('updateProfile', 'Api\CustomerController@updateProfile')->name('UpdateProfile');
        Route::post('changePassword', 'Api\CustomerController@changePassword')->name('ChangePassword');
        
        // Like and unlike api
        Route::post('likeOrUnlikeProduct', 'Api\LikeController@likeOrUnlikeProduct')->name('ProductLikeUnlike');
        Route::post('myOrders', 'Api\MyOrderController@myOrders')->name('MyOrders');
        Route::post('orderDetails', 'Api\MyOrderController@orderDetails')->name('OrderDetails');
        
        // Gets Liked products
        Route::post('getMyLiked', 'Api\LikeController@getMyLiked')->name('GetLikedProducts');

        // Gets Addresses
        Route::post('getUserAddresses', 'Api\AddressController@index')->name('GetAddresses');
        Route::post('addUserAddress', 'Api\AddressController@store')->name('AddUserAddress');
        Route::post('updateUserAddress', 'Api\AddressController@update')->name('UpdateUserAddress');
        Route::post('deleteUserAddress', 'Api\AddressController@delete')->name('deleteAddress');
        Route::post('setDefaultUserAddress', 'Api\AddressController@setDefault')->name('SetDefaultAddress');        
        
        // Follow and unfollow api
        Route::post('followOrUnfollowStore', 'Api\StoreController@followOrUnfollowStore')->name('FollowOrUnfollowStore');
        Route::post('myShopzz', 'Api\StoreController@myShopzz')->name('MyShopzz');
        
        // Product and store Rating api
        Route::post('productRating', 'Api\ProductController@productRating')->name('ProductRating');
        Route::post('storeRating', 'Api\StoreController@storeRating')->name('StoreRating');
        
        // Like and unlike event api
        Route::post('likeOrUnlikeEvent', 'Api\LikeController@likeOrUnlikeEvent')->name('EventLikeUnlike');
        
        // Contact us
        Route::post('contactUs', 'Api\ContactUsController@contactUs')->name('ContactUs');

        // Logout Api
        Route::post('logout', 'Api\CustomerController@logout')->name('logout');
        
    });

    // Carts Api
    Route::post('getCartProducts', 'Api\CartController@index')->name('GetCartProducts');
    Route::post('addToCart', 'Api\CartController@store')->name('AddToCart');
    Route::post('updateCart', 'Api\CartController@update')->name('UpdateCart');
    Route::post('deleteCartProducts', 'Api\CartController@delete')->name('DeleteCart');

   //Product details
   Route::post('productDetails', 'Api\ProductController@productDetails')->name('ProductDetails');

   //Event Details
   Route::post('eventDetails', 'Api\EventController@eventDetails')->name('EventDetails');
   
   // Apply Promocode
   Route::post('applyPromocode', 'Api\OrderController@applyPromocode')->name('ApplyPromocode');

   // Get Shipping details
   Route::post('getShippingDetails', 'Api\OrderController@getShippingDetails')->name('GetShippingDetails');

   // Get Shipping details
   Route::post('placeOrder', 'Api\OrderController@placeOrder')->name('PlaceOrder');

   // Store details
   Route::post('storeDetails', 'Api\StoreController@storeDetails')->name('StoreDetails');
   Route::post('storeProducts', 'Api\StoreController@storeProducts')->name('StoreProducts');

   // Request For Vendor
   Route::post('requestForVendor', 'Api\SellerController@requestForVendor')->name('RequestForVendor');
});
