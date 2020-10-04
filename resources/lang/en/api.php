<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Messages files
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'auth' => [
        'invalid_key' => 'Authentication key is invalid',
        'invalid_user' => 'Please login for this feature',
        'inactive_user' => 'Your account is inactive, please contact to admin',
        'language_not_found' => 'Language not found',
        'logout' => 'You are logout successfully'
    ],
    'user' => [
        'not_available' => 'User is not available',
        'language_success' => 'User Language changed successfully'
    ],
    
    'app_version' => [
        'not_available' => 'App version is not available',
    ],
    
    'country' => [
        'not_available' => 'Country is not available',
    ],
    
    'category' => [
        'not_available' => 'Category is not available',
    ],

    'collection' => [
        'not_available' => 'Collection is not available',
    ],
    
    'area' => [
        'not_available' => 'Area is not available',
    ],
    
    'products' => [
        'not_available' => 'Products are not available',
    ],
    
    'stores' => [
        'not_available' => 'Stores are not available',
    ],
    
    'product' => [
        'not_available' => 'Product is not available',
    ],
    
    'like_product' => [
        'error' => 'There is some problem in like or unlike product',
        'not_available' => 'Liked products not available',
    ],
   
    'event' => [
        'not_available' => 'Event is not available',
    ],
    
    'like_event' => [
        'error' => 'There is some problem in like or unlike event',
    ],

    'login' => [
        'success' => 'You are logged in successfully',
        'inactive' => 'User is inactive',
        'invalid' => 'Email address or password is invalid',
    ], 

    'register' => [
        'already_exist' => 'Email address is already registered',
        'error' => 'There is some problem in registration',
        'already_vendor' => 'Email address is registered as vendor',
    ],
    
    'page' => [
        'not_available' => 'Page content not available',
    ],
    'addresses' => [
        'not_available' => 'Addresses are not available',
        'add' => [
            'success' => 'Address has been added successfully',
            'error' => 'There is some problem in add address',
        ],
        'update' => [
            'success' => 'Address has been updated successfully',
            'error' => 'There is some problem in update address',
        ],
        'delete' => [
            'success' => 'Address has been deleted successfully',
            'error' => 'There is some problem in delete address',
            'default' => 'You can not delete default address',
        ],
        'set_default' => [
            'success' => 'Address has been set default successfully',
            'error' => 'There is some problem in set default address',
        ],
        
    ],
    'address' => [
        'not_available' => 'Address is not available',
        'delete' => [
            'success' => 'Address has been deleted successfully',
            'error' => 'There is some problem in delete address',
            'default' => 'You can not delete default address',
        ],
    ],
    
    'promo_code' => [
        'expired' => 'your promotion code is expired',
        'minimum_order_error' => 'Minimum order amount must be :amount for promotion coupon code',
        'success' => 'promotion code has been applied successfully',
        'error' => 'promotion code is invalid'
    ],
    
    'carts' => [
        'empty' => 'Your cart is empty',
        'qty_not_available' => 'Product qty is not available',
        'store_status_error' => ':store is :status now so you can not add this product to cart.',
        'delivery_not_available' => 'Product is not delivered in your area',
        'add' => [
            'success' => 'Product has been added in cart successfully',
            'error' => 'There is some problem in add to cart',
        ],
        'update' => [
            'success' => 'Cart has been updated successfully',
            'error' => 'There is some problem in update cart',
        ],
        'note' => [
            'success' => 'Note saved successfully',
            'error' => 'There is some problem in update note',
        ],
        'delete' => [
            'success' => 'Product has been removed from cart successfully',
            'error' => 'There is some problem in remove product from cart',
        ],

        'product' => [
            'not_available' => 'cart product is not available',
        ],
        'option_not_available' => 'Your product combination not found'
    ],
    
    'product_option' => [
        'not_available' => 'Product option not available',
    ],
    
    'update_profile' => [
        'success' => 'Your profile has been updated successfully',
        'error' => 'There is some problem in update profile',
    ],

    'change_password' => [
        'success' => 'Your password has been changed successfully',
        'error' => 'Please enter valid current password',
    ],

    'order' => [
        'success' => 'Your order has been placed successfully.',
        'not_available' => 'Order is not available'
    ],
    
    'orders' => [
        'not_available' => 'Orders are not available'
    ],
    
    'store' => [
        'not_available' => 'Store is not available'
    ],

    'product_rating' => [
        'success' => 'Thank you for rating a product',
        'already_available' => 'You already added rating for this product'
    ],

    'store_rating' => [
        'success' => 'Thank you for rating a store',
        'already_available' => 'You already added rating for this store'
    ],

    'request_vendor' => [
        'success' => 'We received your request for vendor',
        'error' => 'There is some problem. please try again.',
    ],
    
    'my_likes' => [
        'not_available' => 'My likes are not available',
    ],
    
    'contact_us' => [
        'success' => 'Your request has been submitted, admin will contact you'
    ],

    'static_content' => [
        'popular' => 'Popular',
        'related_products' => 'Related Products',
        'featured_stores' => 'Featured Stores',
        'just_added' => 'Just Added',
        'All' => 'All',
        'project_delivery' => 'Project Delivery',
        'days' => 'days',
        'hours' => 'hours',
        'Open' => 'Open',
        'Close' => 'Close',
        'Busy' => 'Busy',
        'free' => 'Free',
        'KD' => 'KD'
    ]
];
