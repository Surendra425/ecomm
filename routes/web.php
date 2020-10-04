<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
Auth::routes();

Route::get('order-invoice', function(){

    $order = \App\Order::find(526);

   // $data = \App\Helpers\ApiHelper::afterSuccessOrderMail($order);
    $order->load('user', 'orderProducts.option', 'orderProducts.product');
    $shipping_address = \App\OrderAddress::select("order_addresses.*", "city", "state", "country","users.first_name", "users.last_name")
        ->leftjoin("users", "users.id", "order_addresses.customer_id")
        ->where("order_id", "=", $order->id)->where("address_type", "Shipping")->first();
    $orderProducts = $order->orderProducts->load('option');

    // create invoice for vendor pdf
    $vendorIds = $orderProducts->pluck('product_vendor_id')->unique()->toArray();

    $vendors = \App\Vendor::selectRaw('users.id, CONCAT(first_name," ", last_name) as vendorName, s.store_name')
        ->whereIn('users.id', $vendorIds)->leftJoin('stores as s', 's.vendor_id', 'users.id')->get();

    $vendorStores = [];

    foreach ($vendors as $key => $vendor)
    {
        $vendorStores[$key]['stores'] = $vendor->store_name;
        $vendorStores[$key]['vendor_name'] = $vendor->vendorName;
        $vendorProducts = $orderProducts->where('product_vendor_id', $vendor->id);
        $vendorStores[$key]['products'] = $vendorProducts;
    }


    $data['order'] = $order;
    $data['shipping_address'] = $shipping_address;
    $data['vendorStores'] = $vendorStores;

    //dd($data);
    return view('app.order_invoice', $data);

    $html = view('app.order_invoice', $data);
    $filePath = public_path('doc/invoice/'.$order->order_no.'.pdf');

    \App\Helpers\PDFHelper::generatePdfFile($filePath, $html);

});


Route::middleware(['browserCookie'])->group( function (){



Route::get('/', 'Customer\HomeController@index')->name('home');
Route::get('home', 'Customer\HomeController@index');

Route::get('/app','Customer\CommonController@showAppPage')->name('app');

Route::get('/getCartCount','Controller@getCartCount')->name('getCartCount');

/* Customer auth */
Route::get('/login', 'Auth\UserLoginController@showLoginForm')->name('login')->middleware('previousUrlCookie');
Route::post('/login', 'Auth\UserLoginController@login')->name('customerloginProcess');

Route::get('/register', 'Auth\UserRegisterController@showRegisterForm')->name('customerRegister')->middleware('previousUrlCookie');

Route::get('/customer-register', 'Auth\UserRegisterController@showRegisterForm')->name('customerRegister')->middleware('previousUrlCookie');
Route::post('/customer-register', 'Auth\UserRegisterController@register')->name('customerRegisterProcess');


Route::get('customer/password/reset/{token}/{email}','Auth\ResetPasswordController@showResetForm')->name('showResetForm');

// check unique value route
Route::post('check/unique/{tableName}/{columnName}/{idColumnName?}/{id?}', 'Customer\CommonController@checkUnique')->name('UniqueCheck');

Route::post('check/uniqueNotGuest/{tableName}/{columnName}', 'CommonController@uniqueNotGuest')->name('uniqueNotGuest');

Route::post('home/Products', 'Customer\HomeController@showHomeProducts')->name('homeProducts');

Route::get('collection', 'Customer\CollectionController@index')->name('collection'); 
Route::get('collection/{collectionSlug}', 'Customer\CollectionController@collectionDetails')->name('collectionDetail');
Route::post('colllection/Products', 'Customer\CollectionController@showCollectionProducts')->name('collectionProducts');

//category route
Route::post('category/Products', 'Customer\CategoryController@showCategoryProducts')->name('showCategoryProducts');
Route::get('category/{categorySlug?}', 'Customer\CategoryController@categoryDetails')->name('showCategory');


// Store Products
Route::post('store/Products', 'Customer\StoreController@showStoreProducts')->name('showStoreProducts');
Route::get('best-sellers', 'Customer\StoreController@getBestStore')->name('getBestStore');
Route::post('best-sellers/stores', 'Customer\StoreController@getStoresList')->name('getStoresList');

Route::get('product-detail/{productSlug}', 'Customer\ProductController@showProductDetails')->name('showProductDetails');
Route::post('related-products', 'Customer\ProductController@showRelatedProducts')->name('getRelatedProducts');

// best products
Route::get('best-products', 'Customer\ProductController@bestProducts')->name('bestProducts');

// Page Contents
Route::get('coming-soon', 'Customer\PageContentController@getContent')->name('comingSoon');
Route::get('help', 'Customer\PageContentController@getContent')->name('Help');
Route::get('returns', 'Customer\PageContentController@getContent')->name('returns');
Route::get('about-us', 'Customer\PageContentController@getContent')->name('aboutUs');
Route::get('site-map', 'Customer\PageContentController@getContent')->name('siteMap');
Route::get('user-agreement', 'Customer\PageContentController@getContent')->name('userAgrement');
Route::get('terms-conditions', 'Customer\PageContentController@getContent')->name('termCondtions');
Route::get('privacy-policy', 'Customer\PageContentController@getContent')->name('privacy');

// Search contents
Route::post('search-products', 'Customer\SearchController@showSearchProducts')->name('ajaxSearchProducts');

Route::get('search/{store?}', 'Customer\SearchController@searchDetails')->name('searchProduct');

Route::get('add-to-cart', 'Customer\CartController@addToCart')->name('addProductsToCart')->middleware('browserCookie');
Route::get('cart', 'Customer\CartController@showCarts')->name('cart.index');
Route::post('remove-from-cart', 'Customer\CartController@removeProductFromCart')->name('cart.remove');

// Checkout
Route::get('checkouts', 'Customer\CheckoutController@checkouts')->name('checkouts');
Route::post('storeOrder', 'Customer\CheckoutController@store')->name('storeOrder');
Route::post('apply-shipping', 'Customer\CheckoutController@applyShipping')->name('applyShipping');
Route::post('apply-promo-code', 'Customer\CheckoutController@applyPromoCode')->name('applyPromoCode');
Route::get('order-success/{orderId}','Customer\MyOrderController@orderSuccess');
Route::get('sendordersuccessmail/{orderId}','Customer\MyOrderController@sendOrderSuccessMailWithPdf');

// Get country & city
Route::post('get-cityby-country','Customer\CommonController@getCityByCountry')->name('getCityByCountry');

// Knet payment
Route::any('kresponse', 'Customer\CheckoutController@newKnetResponse')->name('newKnetResponse');
//Route::any('response', 'Customer\CheckoutController@knetResponse')->name('knetResponse');

//Contact us pages
Route::get('call-us', 'Customer\ContactUsController@call_us')->name('contactUs');
Route::get('contact-us','Customer\ContactUsController@index')->name('contactUsIndex');
Route::post('contact-us','Customer\ContactUsController@store')->name('storeContactUs');

// customer social authentication
Route::get('social/redirect/{provider}', 'Auth\SocialController@index')->name('socialLogin');
Route::get('social/handle/{provider}', 'Auth\SocialController@store');

// Vendor register
Route::get('sell-with-us', 'Customer\SellerController@index')->name('sellWithUs');
Route::post('/register', 'Customer\SellerController@store')->name('vendorRegisterProcess');

Route::post('subscribe', 'Customer\CommonController@subscribe')->name('subscribe');

Route::any('event-details/{eventSlug}', 'Customer\EventController@eventDetails')->name('EventDetails');

Route::any('video-detail/{productSlug}', 'SellerController@videoDetails')->name('sellerVideoDetail');

//Route::any('knetPay/{orders}', 'Customer\CheckoutController@knetPay')->name('knetPay');
Route::any('kNetPayment/{orders}', 'Customer\CheckoutController@kNetPayment')->name('knetPay');
Route::any('error/{orderNo}', 'Customer\CheckoutController@knetError')->name('knetError');

Route::get('/send-bulk-mail', 'Admin\AdminMailContoller@sendEmailTemplate')->name('sendBulkMail');
Route::get('my-orders/{orderNo}', 'Customer\MyOrderController@myOrderDetail')->name('myOrderDetail');

});

Route::middleware(['auth:customer'])->group(function ()
{
    Route::get('/logout', 'Auth\UserLoginController@logout')->name('customerLogout');

    // My Orders
    Route::get('my-orders', 'Customer\MyOrderController@myOrders')->name('myOrders');
    Route::post('show-more-orders','Customer\MyOrderController@showMoreOrders')->name('showMoreOrders');


    Route::get('my-likes', 'Customer\LikeController@myLikes')->name('myLikes');
    Route::post('my-likes-products', 'Customer\LikeController@showMyLikesProducts')->name('myLikesProducts');

    Route::get('my-shopzz', 'Customer\LikeController@myShopzz')->name('myShopzz');
    Route::post('like-product', 'Customer\LikeController@likeOrUnlikeProduct')->name('productLikeAndUnlike');

    Route::post('store/rate', 'Customer\StoreController@storeRating')->name('rateStore');
    Route::post('store/follow', 'Customer\StoreController@followOrUnfollowStore')->name('followUnFollowStore');

    // Customer profile
    Route::resource('profile', 'Customer\UserProfileController', ['only' => [
        'index', 'store'
    ]]);

    Route::get('change-password','Customer\UserProfileController@getChangePassword')->name('getChangePassword');
    Route::post('validate-password','Customer\UserProfileController@validateOldPassword')->name('validateOldPassword');
    Route::post('change-password','Customer\UserProfileController@postChangePassword')->name('postChangePassword');

    /* address route */
    Route::resource('address', 'Customer\UserAddressController', ['only' => [
        'store', 'create'
    ]]);

    Route::post('my-address/change-address', 'Customer\UserAddressController@userAddressSelect')->name('userAddressSelect');
    Route::post('my-address/{address}/update', 'Customer\UserAddressController@update')->name('userAddressUpdate');
    Route::post('my-address/delete', 'Customer\UserAddressController@delete')->name('deleteUserAddress');


    /*Route::resource('checkout', 'CheckoutController', ['only' => [
        'index', 'store'
    ]]);*/
    Route::post('get/product-review/{tableName}/{column}', 'Customer\CommonController@getProductReview')->name('getProductReview');
    Route::post('rateProduct', 'Customer\ProductController@rateProduct')->name('rateProduct');

});


Route::middleware(['auth:admin'])->group(function ()
{
    Route::get('admin/{admin}/profile', 'Admin\AdminController@profile')->name('adminProfile');
    Route::get('admin/{admin}/change-status', 'Admin\AdminController@changeStatus')->name('changeAdminStatus');
    Route::get('admin/{admin}/delete', 'Admin\AdminController@destroy')->name('deleteAdmin');
    Route::get('admin/{admin}/edit', 'Admin\AdminController@edit')->name('adminEdit');
    Route::post('{admin}/update', 'Admin\AdminController@update')->name('AdminUpdate');
    Route::post('admin/store', 'Admin\AdminController@store')->name('AdminStore');
    Route::get('admin/list', 'Admin\AdminController@index')->name('adminList');
    Route::get('admin/create', 'Admin\AdminController@create')->name('adminCreate');
    Route::post('admin/search', 'Admin\AdminController@search')->name('adminSearch');
});


//common route for admin and vendor
$lang = Request::segment(1);

Route::prefix($lang)->group(function ()
{
    Route::post('check/unique/{tableName}/{columnName}', 'CommonController@checkUnique')->name('UniqueCheck');
    Route::post('get/unique/{tableName}/{columnName}', 'CommonController@getDataById')->name('getDataById');

    Route::middleware(['auth:admin,vendor'])->group(function ()
    {

        Route::post('upload', 'CommonController@uploadImage')->name('uploadImage');
        Route::post('uploadVideo', 'CommonController@uploadVideos')->name('uploadVideo');
        
        Route::post('event/image/upload', 'Admin\AdminEventController@uploadEventImage')->name('uploadEventImage');
        Route::post('event/video/upload', 'Admin\AdminEventController@uploadEventVideo')->name('uploadEventVideo');
        Route::post('event-media/delete', 'Admin\AdminEventController@eventMediaDelete')->name('eventMediaDelete');
        
        
        Route::post('delete/{tableName}/{columnName}', 'CommonController@deleteData')->name('deleteData');

        Route::post('get/shippingClass/{tableName}/{columnName}', 'CommonController@getShippingClass')->name('getShippingClass');
        Route::post('get/shippingData/{tableName}/{columnName}', 'CommonController@getShippingData')->name('getShippingData');
        Route::post('get/product/{tableName}/{columnName}', 'CommonController@getproductDataById')->name('getproductDataById');
        Route::post('get/shipping/{tableName}/{columnName}', 'CommonController@getVendorShippingDetails')->name('getVendorShippingDetails');
        Route::post('get/city/{tableName}/{columnName}', 'CommonController@getCityById')->name('getCityById');
        Route::post('get/keyword/{tableName}/', 'CommonController@getkeyword')->name('getkeyword');

        Route::middleware(['vendorBusiness'])->group(function ()
        {
            //product collection route
             //product collection route
            Route::resource('products-collections', 'ProductCollection');
            Route::post('products-collections/search', 'ProductCollection@search')->name('searchProductCollection');
            Route::get('products-collections/{collection}/{vendor}/add', 'ProductCollection@add')->name('addpc');
            //Route::get('productsCollections/{collection}/{vendor}/add-product', 'ProductCollection@addProduct')->name('collectionAddProduct');
            Route::post('products-collections/{collection}/add-product', 'ProductCollection@storeCollectionProduct')->name('storeCollectionProduct');
            Route::get('products-collections/{collection}/{vendor}/view-product', 'ProductCollection@view')->name('collectionViewProduct');
            Route::post('products-collections/{collectionProducts}/searchProducts', 'ProductCollection@searchProducts')->name('collectionProductsSearch');
            Route::get('products-collections/{collection_product}/{vendor}/delete-product', 'ProductCollection@remove')->name('collectionProductRemove');
            Route::post('product/productSearch', 'ProductCollection@productCollectionSearch')->name('productCollectionSearch');

            /*
              //Product Collection routes
              Route::resource('products-collection', 'VendorProductCollectionController');

              Route::post('products-collection/search', 'VendorProductCollectionController@search')->name('vendorCollectionSearch');
              Route::post('products-collection/{collectionProducts}/searchProducts', 'VendorProductCollectionController@searchProducts')->name('vendorCollectionProductsSearch');
              Route::get('products-collection/{collection}/add-product', 'VendorProductCollectionController@addProduct')->name('vendorCollectionAddProduct');
              Route::post('products-collection/{collection}/add-product', 'VendorProductCollectionController@storeCollectionProduct')->name('vendorStoreCollectionProduct');
              Route::get('products-collection/{collectionProducts}/view-product', 'VendorProductCollectionController@view')->name('vendorCollectionViewProduct');
              Route::get('products-collection/{collection_product}/delete-product', 'VendorProductCollectionController@remove')->name('vendorCollectionProductRemove');
             */


            // Recent Products Routes
            Route::get('recent-product/', 'ProductController@recentProduct')->name('recentProduct');
            Route::post('recent-product/search', 'ProductController@recentproductSearch')->name('recentproductSearch');
            Route::get('recent-product/{product}/profile', 'ProductController@viewRecentProduct')->name('viewRecentProduct');
            Route::get('recent-product/{product}/change-status', 'ProductController@recentChangeStatus')->name('recentChangeStatus');
            Route::get('recent-product/{product}/change-featured', 'ProductController@recentChangeFeaturedStatus')->name('recentChangeFeaturedStatus');

            // Products Routes
            Route::resource('products', 'Admin\AdminProductsController');
            Route::get('product/{product}/change-status', 'ProductController@changeStatus')->name('changeProductStatus');
            Route::get('product/{product}/change-featured', 'ProductController@changeFeaturedStatus')->name('changeFeaturedStatus');
            Route::post('product/search', 'Admin\AdminProductsController@search')->name('productSearch');
            Route::post('product/{product}/update', 'Admin\AdminProductsController@update')->name('productUpdate');
            Route::get('product/{product}/delete', 'ProductController@destroy')->name('deleteProduct');
            Route::get('product/{product}/profile', 'ProductController@profile')->name('viewProduct');
            Route::post('delete-product-images','Admin\AdminProductsController@deleteProductImages')->name('deleteProductImages');
            Route::post('delete-product_video','Admin\AdminProductsController@deleteProductVideos')->name('deleteProductVideos');


            Route::resource('change-passwords', 'Auth\ChangePasswordController', ['only' => [
                'index', 'store'
            ]]);
            Route::post('Password', 'Auth\ChangePasswordController@checkOldPassword')->name('changePassword');

            // Products Category Routes
            Route::resource('products-category', 'ProductCategoryController');
            Route::get('product-category/{productCategory}/change-status', 'ProductCategoryController@changeStatus')->name('changeProductCategoryStatus');
            Route::get('product-category/{productCategory}/change-featured', 'ProductCategoryController@changeFeaturedStatus')->name('changeProductCategoryFeature');
            Route::post('product-category/search', 'ProductCategoryController@search')->name('productCategorySearch');
            Route::post('product-category/{productCategory}', 'ProductCategoryController@update')->name('productCategoryUpdate');
            Route::get('product-category/{productCategory}/delete', 'ProductCategoryController@destroy')->name('deleteProductCategory');
            Route::get('product-category/{productCategory}/profile', 'ProductCategoryController@profile')->name('adminviewproductCategory');
            Route::get('product-category/{productCategory}/edit', 'ProductCategoryController@edit')->name('editProductCategory');

            // Coupons Routes
            Route::resource('coupons', 'CouponController');
            Route::post('coupon/search', 'CouponController@search')->name('couponSearch');
            Route::post('coupons/{coupon}/update', 'CouponController@update')->name('couponUpdate');
            Route::get('coupon/{coupon}/delete', 'CouponController@destroy')->name('couponDelete');
            Route::get('coupon/{coupon}/profile', 'CouponController@profile')->name('couponProfile');
            Route::get('coupon/{coupon}/change-status', 'CouponController@changeStatus')->name('changeCouponStatus');

            // Shipping class Routes
            Route::resource('shipping-class', 'ShippingController');
            Route::post('shipping/search', 'ShippingController@search')->name('shippingClassSearch');
            Route::get('shipping/{shipping}/edit', 'ShippingController@edit')->name('shippingClassEdit');
            Route::post('shipping-class/{shipping}/update', 'ShippingController@update')->name('shippingClassUpdate');
            Route::get('shipping/{shipping}/delete', 'ShippingController@destroy')->name('shippingClassDelete');
            Route::get('shipping/{shipping}/profile', 'ShippingController@profile')->name('shippingClassProfile');
            Route::get('shipping/{shipping}/change-status', 'ShippingController@changeStatus')->name('changeShippingClass');
        });
    });
    Route::post('get/unique/{tableName}/{columnName}', 'CommonController@getDataById')->name('getDataById');
});

// Admin Routes
Route::prefix('admin')->group(function ()
{
    // Admin Auth Routes
    Route::get('/', 'Auth\AdminLoginController@showLoginForm')->name('adminLogin');
    Route::post('/', 'Auth\AdminLoginController@login')->name('adminloginProcess');
    
    // Admin Authenticated Routes
    Route::middleware(['auth:admin'])->group(function ()
    {

        Route::post('products/{product}/home-date', 'ProductController@updateProductHomeDate')->name('ajaxProductHomeDateUpdate');
        Route::post('products/{product}/gender-type', 'ProductController@updateProductGender')->name('ajaxProductGenderTyeUpdate');
        
        Route::resource('events', 'Admin\AdminEventController');
        Route::post('events/{event}/update', 'Admin\AdminEventController@update')->name('eventUpdate');
        Route::get('events/{event}/change-status', 'Admin\AdminEventController@changeStatus')->name('changeEventStatus');
        Route::post('events/search', 'Admin\AdminEventController@search')->name('eventSearch');
        Route::get('events/{event}/delete', 'Admin\AdminEventController@destroy')->name('deleteEvent');
        
        //vendor sales % update
        Route::get('vendor-sales-update', 'Admin\AdminVendorController@vendorSalesEdit')->name('vendorSalesEdit');
        Route::post('vendor-sales-updates', 'Admin\AdminVendorController@vendorSaleUpdate')->name('vendorSaleUpdate');
        
        
        Route::resource('versions', 'Admin\AppVersionController');
        Route::post('versions/search', 'Admin\AppVersionController@search')->name('appVersionSearch');
         Route::get('version/{version}/{type}', 'Admin\AppVersionController@edit')->name('versionsEdit');
        Route::post('version/{version}', 'Admin\AppVersionController@update')->name('versionUpdate');

        Route::resource('sales', 'SaleReport');
        Route::post('sales/search', 'SaleReport@search')->name('saleReportSearch');
        
        Route::get('sales/{vendorId}/{month}/{paid}/status', 'SaleReport@changeStatus')->name('changeSaleStatus');
        Route::get('sale/save', 'SaleReport@salesStore')->name('salesStore');
    
        Route::resource('vendorSales', 'Admin\VendorSalesReportController');
        Route::post('vendorSales/search', 'Admin\VendorSalesReportController@search')->name('vendorSaleReportSearch');
        
        //static pages routes
        Route::resource('pages', 'Admin\AdminStaticPagesController');
        Route::post('pages/{page}/update', 'Admin\AdminStaticPagesController@update')->name('pageUpdate');
        Route::post('pages/search', 'Admin\AdminStaticPagesController@search')->name('pageSearch');

        
        Route::post('sales_records', 'SaleReport@vendorSaleRecordForAdmin')->name('vendorSaleRecordForAdmin');
        // Admin Dashboard
        Route::get('/dashboard', 'Admin\AdminDashboardController@dashboard')->name('adminDashboard');
        Route::get('/logout', 'Auth\AdminLoginController@logout')->name('adminLogout');


        // subscribe user Routes
        Route::resource('subscribe', 'Admin\AdminSubscriptionController', ['only' => [
            'index'
        ]]);
        Route::post('subscribe/search', 'Admin\AdminSubscriptionController@search')->name('subscribeSearch');


        // inquiry user Routes
        Route::resource('inquiry-lists', 'Admin\AdminInquiryListController', ['only' => [
            'index'
        ]]);
        Route::post('inquiry-lists/search', 'Admin\AdminInquiryListController@search')->name('inquiryListSearch');
        Route::get('inquiry-lists/{inquiry}', 'Admin\AdminInquiryListController@show')->name('inquiry.view');
        
        // Admin Change Password Routes
        /*Route::resource('change/password', 'Admin\AdminChangePasswordController', ['only' => [
            'index', 'store'
        ]]);*/

        // Admin side push notifications
        Route::resource('push-notification', 'Admin\AdminPushNotificationController');
        Route::post('sendPushNotification', 'Admin\AdminPushNotificationController@sendPushNotification')->name('sendPushNotification');

        // Users Routes
        Route::resource('users', 'Admin\AdminUserController');
        Route::post('users/{user}', 'Admin\AdminUserController@update')->name('adminUserUpdate');
        Route::get('user/{user}/change-status', 'Admin\AdminUserController@changeStatus')->name('changeUserStatus');
        Route::post('user/search', 'Admin\AdminUserController@search')->name('userSearch');
        Route::get('user/{user}/delete', 'Admin\AdminUserController@destroy')->name('deleteUser');
        Route::get('user/{user}/profile', 'Admin\AdminUserController@profile')->name('vieUser');

        //vendor routes
        Route::resource('vendors', 'Admin\AdminVendorController');
        Route::post('vendors/{vendor}', 'Admin\AdminVendorController@update')->name('AdminVendorUpdate');
        Route::get('vendor/{vendor}/change-status', 'Admin\AdminVendorController@changeStatus')->name('changeVendorStatus');
        Route::post('vendor/search', 'Admin\AdminVendorController@search')->name('vendorSearch');
        Route::get('vendor/{vendor}/delete', 'Admin\AdminVendorController@destroy')->name('deleteVendor');
        Route::get('vendors/{vendor}/profile', 'Admin\AdminVendorController@profile')->name('viewVendor');
        Route::get('vendors/{vendor}/list', 'ProductController@viewProductVendor')->name('viewProductVendor');

        //vendor store routes
        Route::resource('stores', 'Admin\AdminVendorStoreController');
        Route::post('vendor-store/{store}/update', 'Admin\AdminVendorStoreController@update')->name('AdminStoreUpdate');
        Route::get('vendor-store/{stored}/change-status', 'Admin\AdminVendorStoreController@changeStatus')->name('adminchangeStoreStatus');
        Route::get('vendor-store/{stored}/change-featured', 'Admin\AdminVendorStoreController@changeFeaturedStatus')->name('adminchangeStorefeatured');
        Route::post('store/search', 'Admin\AdminVendorStoreController@search')->name('adminstoreSearch');
        Route::get('vendor-store/{stored}/delete', 'Admin\AdminVendorStoreController@destroy')->name('admindeleteStore');
        Route::get('vendor-store/{stored}/profile', 'Admin\AdminVendorStoreController@profile')->name('adminviewStore');
        Route::post('store-categories', 'Admin\AdminVendorStoreController@vendorStoreCategoryList')->name('vendorStoreCategoryList');
        Route::get('store-categories-create', 'Admin\AdminVendorStoreController@vendorStoreCategoryCreate')->name('vendorStoreCategoryCreate');
        Route::post('store-categories-store', 'Admin\AdminVendorStoreController@vendorStoreCategoryStore')->name('vendorStoreCategoryStore');
        Route::get('store-categories-edit', 'Admin\AdminVendorStoreController@editStoreVendorCategory')->name('editStoreVendorCategory');
        Route::post('store-categories-update', 'Admin\AdminVendorStoreController@updateStoreVendorCategory')->name('updateStoreVendorCategory');
        Route::any('store-categories-remove', 'Admin\AdminVendorStoreController@removeStoreVendorCategory')->name('removeStoreVendorCategory');

        //vendor store category routes
        Route::resource('stores-category', 'Admin\AdminStoreCategoryController');
        Route::post('stores-category/{category}', 'Admin\AdminStoreCategoryController@update')->name('AdminStoreCategoryUpdate');
        Route::get('store-category/{category}/edit', 'Admin\AdminStoreCategoryController@edit')->name('adminStoreEdit');
        Route::get('store-category/{category}/change-status', 'Admin\AdminStoreCategoryController@changeStatus')->name('adminchangeStoreCategoryStatus');
        Route::get('store-category/{category}/change-featured', 'Admin\AdminStoreCategoryController@changeFeaturedStatus')->name('adminchangeStoreCategoryfeatured');
        Route::post('store-category/search', 'Admin\AdminStoreCategoryController@search')->name('adminstoreCategorySearch');
        Route::get('store-category/{category}/delete', 'Admin\AdminStoreCategoryController@destroy')->name('admindeleteStoreCategory');
        Route::get('store-category/{category}/profile', 'Admin\AdminStoreCategoryController@profile')->name('adminviewStoreCategory');

        //country routes
        Route::resource('country', 'Admin\AdminCountryController');
        Route::post('country/{country}/update', 'Admin\AdminCountryController@update')->name('countryUpdate');
        Route::get('country/{country}/change-status', 'Admin\AdminCountryController@changeStatus')->name('changeCountryStatus');
        Route::post('country/search', 'Admin\AdminCountryController@search')->name('countrySearch');
        Route::get('country/{country}/delete', 'Admin\AdminCountryController@destroy')->name('deleteCountry');
        Route::get('country/{country}/profile', 'Admin\AdminCountryController@profile')->name('profileCountry');

        //state routes
        Route::resource('state', 'Admin\AdminStateController');
        Route::post('state/{state}/update', 'Admin\AdminStateController@update')->name('stateUpdate');
        Route::get('state/{state}/change-status', 'Admin\AdminStateController@changeStatus')->name('changeStateStatus');
        Route::post('state/search', 'Admin\AdminStateController@search')->name('stateSearch');
        Route::get('state/{state}/delete', 'Admin\AdminStateController@destroy')->name('deleteState');
        Route::get('state/{state}/profile', 'Admin\AdminStateController@profile')->name('profileState');

        //city routes
        Route::resource('city', 'Admin\AdminCityController');
        Route::post('city/{city}/update', 'Admin\AdminCityController@update')->name('cityUpdate');
        Route::get('city/{city}/change-status', 'Admin\AdminCityController@changeStatus')->name('changeCityStatus');
        Route::post('city/search', 'Admin\AdminCityController@search')->name('citySearch');
        Route::get('city/{city}/delete', 'Admin\AdminCityController@destroy')->name('deleteCity');
        Route::get('city/{city}/profile', 'Admin\AdminCityController@profile')->name('profileCity');

        //collection routes
        Route::resource('collections', 'Admin\AdminCollectionsController');
        Route::post('collections/{collection}', 'Admin\AdminCollectionsController@update')->name('updateCollection');
        Route::get('collection/{collection}/change-status', 'Admin\AdminCollectionsController@changeStatus')->name('changeCollectionStatus');
        Route::get('collection/{collection}/change-display-status', 'Admin\AdminCollectionsController@changeDisplayStatus')->name('changeCollectionDisplayStatus');
        Route::post('collection/search', 'Admin\AdminCollectionsController@search')->name('collectionSearch');
        Route::get('collection/{collection}/delete', 'Admin\AdminCollectionsController@destroy')->name('deleteCollection');
        Route::get('collection/{collection}/profile', 'Admin\AdminCollectionsController@profile')->name('profileCollection');


        //plan routes
        Route::resource('plans', 'Admin\AdminPlanController');
        Route::post('plans/{plan}/update', 'Admin\AdminPlanController@update')->name('planUpdate');
        Route::get('plan/{plan}/change-status', 'Admin\AdminPlanController@changeStatus')->name('changePlanStatus');
        Route::post('plan/search', 'Admin\AdminPlanController@search')->name('planSearch');
        Route::get('plan/{plan}/delete', 'Admin\AdminPlanController@destroy')->name('deletePlan');
        Route::get('plan/{plan}/profile', 'Admin\AdminPlanController@profile')->name('profilePlan');

        //plan option routes
        Route::resource('plan-options', 'Admin\AdminPlanOptionController');
        Route::post('plan-options/{option}/update', 'Admin\AdminPlanOptionController@update')->name('planOptionUpdate');
        Route::get('plan-option/{option}/change-status', 'Admin\AdminPlanOptionController@changeStatus')->name('changePlanOptionStatus');
        Route::post('plan-option/search', 'Admin\AdminPlanOptionController@search')->name('planOptionSearch');
        Route::get('plan-option/{option}/delete', 'Admin\AdminPlanOptionController@destroy')->name('deletePlanOption');
        Route::get('plan-option/{option}/profile', 'Admin\AdminPlanOptionController@profile')->name('profilePlanOption');
        Route::get('plan-option/{option}/edit', 'Admin\AdminPlanOptionController@edit')->name('editPlanOption');

        //Attribute ist Routes
        Route::resource('attributes', 'Admin\AdminProductAttrListController');
        Route::post('attributes/{attribute}/update', 'Admin\AdminProductAttrListController@update')->name('attrUpdate');
        Route::post('attribute-check-name', 'Admin\AdminProductAttrListController@checkAttrName')->name('checkAttrName');
        Route::post('attribute/search', 'Admin\AdminProductAttrListController@search')->name('attrSearch');
        Route::get('attribute/{attribute}/change-status', 'Admin\AdminProductAttrListController@changeStatus')->name('changeStatus');
        Route::get('attribute/{attribute}/delete', 'Admin\AdminProductAttrListController@destroy')->name('deleteAttr');

        // Admin Change Password Routes
        Route::get('profile', 'Admin\AdminProfileController@profile')->name('AdminProfile');
        Route::post('profile', 'Admin\AdminProfileController@store')->name('AdminProfileUpdate');
        /* Route::resource('profile', 'Admin\AdminProfileController', ['only' => [
          'index', 'store'
          ]])->name('AdminProfile'); */

        // Admin Order Routes
        Route::resource('orders', 'Admin\AdminOrderController', ['only' => [
            'index', 'show'
        ]]);
        Route::post('order/search', 'Admin\AdminOrderController@search')->name('orderSearch');


        //email template routes
        Route::resource('email-template', 'Admin\AdminMailContoller');
        Route::get('email-templates/{email}/edit', 'Admin\AdminMailContoller@edit')->name('emailTemplateEdit');
        Route::post('email-template/{email}', 'Admin\AdminMailContoller@update')->name('emailTemplateUpdate');
        Route::post('email-templates/search', 'Admin\AdminMailContoller@search')->name('emailTemplateSearch');
        Route::get('email-templates/{email}/delete', 'Admin\AdminMailContoller@destroy')->name('deleteEmailTemplate');
        Route::get('email-templates/{email}/{type}/sendMail', 'Admin\AdminMailContoller@saveEmail')->name('emailTemplateSend');
        Route::get('email-templates', 'Admin\AdminMailContoller@sendEmailTemplate')->name('sendEmailTemplate');

        //Advertisement list Routes
        Route::resource('advertisement', 'Admin\AdminAdvertisementController');
        Route::post('advertisements/{advertisement}', 'Admin\AdminAdvertisementController@update')->name('updateAdvertisement');
        Route::get('advertisement/{advertisement}/change-status', 'Admin\AdminAdvertisementController@changeStatus')->name('changeAdvertisementStatus');
        Route::get('advertisement/{advertisement}/change-display-status', 'Admin\AdminAdvertisementController@changeDisplayStatus')->name('changeAdvertisementDisplayStatus');
        Route::post('advertisement/search', 'Admin\AdminAdvertisementController@search')->name('advertisementSearch');
        Route::get('advertisement/{advertisement}/delete', 'Admin\AdminAdvertisementController@destroy')->name('deleteAdvertisement');
        Route::get('advertisement/{advertisement}/profile', 'Admin\AdminAdvertisementController@profile')->name('profileAdvertisement');
    });
});
// Vendor Routes
Route::prefix('vendor')->group(function ()
{

    // Vendor Auth Routes
    Route::get('/', 'Auth\VendorLoginController@showLoginForm')->name('vendorLogin');
    Route::post('/', 'Auth\VendorLoginController@loginWithId')->name('vendorloginProcess');
    Route::get('/register', 'Auth\VendorRegisterController@showRegisterForm')->name('vendorRegister');
    Route::get('/register/{plan_option}', 'Auth\VendorRegisterController@showRegisterFormWithPlan')->name('vendorRegisterWithPlanOption');
    

    //Password reset routes
    Route::get('/password/reset', 'VendorAuth\ForgotPasswordController@showLinkRequestForm')->name('showVendorLinkRequestForm');
    Route::post('/password/email', 'VendorAuth\ForgotPasswordController@sendResetLinkEmail')->name('sendVendorResetLinkEmail');
    Route::get('/password/reset/{token}', 'VendorAuth\ResetPasswordController@showResetForm')->name('sendVendorResetLinkEmail');
    Route::post('/password/reset', 'VendorAuth\ResetPasswordController@reset')->name('sendVendorResetPassword');
    // Vendor Authenticated Routes
    Route::middleware(['auth:vendor'])->group(function ()
    {
        
        Route::resource('subscription', 'Vendor\VendorBusinessController');
        Route::get('/business_detail', 'Vendor\VendorBusinessController@add')->name('vendorBusinessDetail');
        Route::get('/store_inactive', 'Vendor\VendorDashboardController@vendorInactivePage')->name('vendorInactivePage');
        Route::post('/business_detail', 'Vendor\VendorBusinessController@save')->name('saveVendorBusinessDetail');
        Route::get('/unsubscribe/{vendor_subscription}', 'Vendor\VendorBusinessController@unSubscribe')->name('unSubscribeVendorPlan');
        Route::get('/update-subscription', 'Vendor\VendorBusinessController@edit')->name('updateSubscribeVendorPlan');
        Route::post('/update-subscription', 'Vendor\VendorBusinessController@update')->name('updateSubscribeVendorPlan');
        Route::get('/logout', 'Auth\VendorLoginController@logout')->name('vendorLogout');
        Route::get('/payment/success/{vendor_plan_id}', 'Vendor\VendorBusinessController@successVendorPayment')->name('VendorPlanPaymentSuccess');
        Route::get('/update-subscription/payment/success/{vendor_plan_id}', 'Vendor\VendorBusinessController@successUpdatePlanPayment')->name('VendorUpdatePlanPaymentSuccess');

        Route::middleware(['vendorBusiness','storeInactive'])->group(function ()
        {
            
            // Vendor Dashboard
            Route::get('/dashboard', 'Vendor\VendorDashboardController@dashboard')->name('vendorDashboard');

            Route::post('user/search', 'Vendor\VendorUserController@search')->name('refundSubmit');

            // Store Routes
            Route::resource('store', 'Vendor\VendorStoreController');
            Route::post('store/{store}', 'Vendor\VendorStoreController@updateStoreDetail')->name('updateVendorStore');

            // Bank Detail Routes
            Route::resource('bank_detail', 'Vendor\VendorBankController');
            Route::post('bank_detail', 'Vendor\VendorBankController@update')->name('updateVendorBankDetail');

            // Profile Routes        
            Route::get('profile', 'Vendor\VendorProfileController@profile')->name('VendorProfile');
            Route::post('profile', 'Vendor\VendorProfileController@store')->name('VendorProfileUpdate');

            Route::post('sales_record', 'SaleReport@vendorSaleRecord')->name('vendorSaleRecord');

            //Social Media routes
            Route::resource('social-media', 'Vendor\VendorSocialMediaController');

            //Product Category routes
            Route::resource('store-products-category', 'Vendor\VendorProductCategoryController');
            Route::post('store-products-category/search', 'Vendor\VendorProductCategoryController@search')->name('vendorProductCategorySearch');
            Route::post('store-products-category/update/{category}', 'Vendor\VendorProductCategoryController@update')->name('vendorProductCategoryUpdate');
            Route::get('store-products-category/{vendor_product_category}/remove', 'Vendor\VendorProductCategoryController@remove')->name('removeVendorProductCategory');

            // Vendor Order Routes
            Route::resource('my_order', 'Vendor\VendorOrderController', ['only' => [
                'index', 'show'
            ]]);
            // Vendor Shipping Detail Routes
            Route::resource('shipping', 'Vendor\VendorShippingController', ['only' => [
                'index', 'store'
            ]]);
            Route::get('shipping/{shipping}/delete', 'Vendor\VendorShippingController@delete')->name('vendorShippingDelete');
            Route::post('shipping/{shipping}/update', 'Vendor\VendorShippingController@update')->name('updateVendorShipping');
            Route::post('shipping/search', 'Vendor\VendorShippingController@search')->name('searchVendorShipping');

            Route::post('my-order/search', 'Vendor\VendorOrderController@search')->name('vendorOrderSearch');
            Route::post('my-order/product/update', 'Vendor\VendorOrderController@updateOrderProductStatus')->name('updateOrderProductStatus');
            Route::get('store-product-category/{vendor_product_category}/edit', 'Vendor\VendorProductCategoryController@edit')->name('editVendorProductCategory');
        });
    });
});


Route::post('ajaxStoreCategoryProducts', 'SellerController@ajaxStoreCategoryProducts')->name('ajaxStoreCategoryProducts');

Route::get('apple-app-site-association', 'DeepLinkingController@deeplinking')->name('DeepLinking');

Route::get('master-card/test', 'MasterCardController@index')->name('matercardtest');
Route::any('master-card/notification', 'MasterCardController@notification')->name('matercardtestNotification');
Route::any('{storeSlug}', 'Customer\StoreController@storeDetails')->name('sellerDetail');
Route::any('{storeSlug}/about-us', 'Customer\StoreController@storeAboutUs')->name('sellerAboutUs');


Route::get('card/payment/{orderNumber}/{token?}', 'MasterCardController@cardPaymentPage')->name('CardPayment');
Route::get('card/payment/notification/{orderNumber}', 'MasterCardController@cardPaymentNotification')->name('CardPaymentNotification');

Route::get('order/status/{orderNumber}', 'MasterCardController@orderStatus')->name('OrderStatus');

Route::any('knet/handle', 'CheckoutController@handleKnetResponse')->name('KnetResponse');


Route::get('{storeSlug}', 'Customer\StoreController@storeDetails')->name('sellerDetail');
Route::get('{storeSlug}/about-us', 'Customer\StoreController@storeAboutUs')->name('sellerAboutUs');
