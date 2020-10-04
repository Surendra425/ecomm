<?php

return [
    
    /*
     |--------------------------------------------------------------------------
     | Flash Messages Language Lines
     |--------------------------------------------------------------------------
     */
    'register' => [
        'success' => 'Customer has been registred successfully',
        'error' => 'There is some problem please try again',
        'already_exists' => 'Email address is already exists'
    ],
    
    'login' => [
        'error' => [
            'status' =>  'Customer is inactive',
            'credential' => 'Email address or password is invalid',
        ],
    ],
    
    'logout' => [
        'success' => 'Customer has been logout successfully',
        'error' => 'There is some problem please try again',
    ],
    
    'app_content' => [
        'error' => 'App content not found',
    ],
    
    'product' => [
        'success' => null,
        'error' => [
            'not_found' => 'product not available.',
        ],
    ],
    
    'favrouite' => [
        'success' => 'Favrouite Product has been saved',
        'error' => [
            'default' => 'There is some problem please try again',
            'already' => 'Product is already saved as favroite',
        ],
        'delete' => [
            'success' => 'Product has been removed from favrouite product',
            'error' => [
                'default' => 'There is some problem please try again',
                'customer_not' => 'Customer not access to remove this product ',
            ],
        ]
    ],

    'review' => [
        'add' => [
            'success' => 'product review has been added successfully.',
            'error' => [
                'default' => 'There is some problem please try again',
                'already' => 'Product review is already added',
                'product_not_exist' => 'Product is not available',
            ],
        ],
    ],
    
    'support' => [
        'success' => 'Support ticket has been generated successfully',
        'error' => 'There is some problem please try again',
    ],
    
    'change_lang' => [
        'error' => 'There is some problem please try again',
    ],
    
    'update_city' => [
        'success' => 'City has been updated successfully',
        'error' => 'There is some problem please try again',
        'not_found' => 'City not available',
    ],
    'popup_ads' => [
        'not_available' => 'Popup ads is not available',
    ],
    
    'add_to_cart' => [
        'success' => 'Product has been added to cart successfully',
        'error' => 'There is some problem to add to cart product',
        'not_found' => 'Your cart is empty.',
        'out_of_stock' =>'Product is out of stock',
        'delete' => [
            'success' => 'Product has been deleted from cart successfully',
            'error' => 'There is some problem delete from cart product',
        ],
    ],
    'city' => [
        'not_found' => 'City is not available.'
    ],
    'city_delivery' => [
        'error' => 'product is not avaialable for your city'
    ],

    'promo_code' => [
        'expired' => 'your promontional code is expired',
        'minimum_order_error' => 'Minimum order amount must be :amount for promontional coupon code',
        'success' => 'Promo code has been applied successfully',
        'error' => 'Promo code is invalid'
    ],
    
    'cart' => [
        'success' => 'Cart has been moved to successfully',
        'error' => 'Please enter valid  token',
        'not_found' => 'Cart item is not available',
        'update' => [
             'success'=> 'Cart has been updated successfully.',
        ],
        'exchange_price_error' => 'You can not exchange less than price item',
    ],
    
    'checkout' => [
        'not_available' => ':name not available for selected city.',
    ],
    
    'address' => [
        'not_found' => 'Address is not available',
    ],
    'order' => [
        'success' => 'Your order is successfully placed.',
        'cart_empty' => 'Cart is empty',
        'error' => 'There is some problem please try again',
        'current_order' => 'No Orders Available',
        'cancel' => [
            'success' => 'Your product is cancelled successfully.',
            'error' => 'You can\'t cancel this product.',
        ],
        'return' => [
            'success' => 'Your reuest for return product is successfully sent.',
            'error' => 'You can\'t return this product.',
        ],
        'exchange' => [
            'success' => 'Your request for exchange product is successfully sent.',
            'error' => 'You can\'t exchange this product.',
        ],
        'out_for_delivery' => [
            'success' => 'Your product status is updated successfully.',
            'error' => 'You can\'t out for delivery this product',
            'unauthorize' => 'You ar-e unauthorize to update this order product',
        ],
        'delivered' => [
            'success' => 'Your product status is delivered successfully.',
            'error' => 'You can\'t delivered this product',
            'unauthorize' => 'You are unauthorize to update this order product',
        ],
    ],
    
    'exchange' => [
        'success' => 'Your exchange request is generated successfully.',
        'error' => 'Theere is some problem in exchange order.',
        'status_not_delivered' => 'you can not exchange order until not delivered',
    ],
    
    'order_product' => [
        'error' => [
            'not_found' => 'Order product is not available.'
        ],
    ],
    
];
