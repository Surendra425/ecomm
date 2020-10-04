<?php

return [
    
    /*
     |--------------------------------------------------------------------------
     | Flash Messages Language Lines
     |--------------------------------------------------------------------------
     */
    'register' => [
        'success' => 'Vendor has been registred successfully',
        'error' => 'There is some problem please try again!',
        'already_exists' => 'Email address is already exists'
    ],
    
    'login' => [
        'error' => [
            'status' =>  'Vendor is inactive',
            'credential' => 'Email address or password is invalid',
        ],
    ],
    
    'store' => [
        'error' => 'There is some problem please try again',
    ],
    
    'bank_details' => [
        'error' => 'There is some problem please try again',
    ],
    
    'changePassword' => [
        'success' => 'password has been changed successfully.',
        'error' => 'There is some problem please try again',
        'current_password_error' => 'Current password which you enter is invalid',
    ],
    
    'popupads' => [
        'error' => 'There is some problem please try again',
        'delete' => [
            'success' => 'Popup add has been deleted successfully.',
            'error' => 'There is some problem please try again',
        ]
    ],

    'products' => [
        'add' => [
            'success' => 'product has been added successfully.',
            'error' => 'There is some problem please try again',
        ],
        'out_of_stock' => [
            'success' => 'product has been out of stock successfully',
            'error' => 'There is some problem please try again',
        ],
        'delete'  => [
            'success' => 'product has been delete successfully',
            'error' => 'There is some problem please try again',
        ],
        'not_authorize' => 'Unthorize to access this product.',
    ],
    
    'productimage' => [
       'success' => 'product image has been added successfully',
       'error' => 'There is some problem please try again',
        'cover' => [
            'success' => 'Image is set as cover successfully',
            'error' => 'There is some problem please try again',
            'notfound' => 'Product image is not available', 
        ],
        'delete' => [
            'success' => 'Product image deleted successfully',
            'error' => 'There is some problem to delete product image',
            'cover_not_allow' => 'Cover image not allow to delete',
        ],
    ],
    
    'plans' => [
        'not_found' => 'plans for suubscription has been not available.',
    ],
    
    'logout' => [
        'success' => 'Vendor has been logout successfully',
        'error' => 'There is some problem please try again',
    ],
    
    'order' => [
       'return_request' => [
           'accept' => 'Your return order is accepted successfully.',
           'reject' => 'Your return order is rejected.',
           'error' => 'There is some problem in return order request. please try again',
           'unauthorize' => 'Your return order request is unauthorize',
       ],
       'special_request' => [
           'accept' => 'Your special request accepted successfully.',
           'reject' => 'Your special request order is rejected.',
           'error' => 'There is some problem in special request order request. please try again',
           'unauthorize' => 'Your special request order request is unauthorize',
       ], 
    ],
];
