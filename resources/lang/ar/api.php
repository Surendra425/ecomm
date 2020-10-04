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
        'invalid_key' => 'الرمز غير صالح',
        'invalid_user' => 'يرجى تسجيل الدخول لهذه الميزة',
        'inactive_user' => 'حسابك غير نشط ، يرجى الاتصال بالمشرف',
        'logout' => 'تم الخروج بنجاح'
    ],
    'user' => [
        'not_available' => 'المستخدم غير متوفر',
        'language_success' => 'تم تغيير اللغة بنجاح'
    ],

    'app_version' => [
        'not_available' => 'إصدار التطبيق غير متاح',
    ],

    'country' => [
        'not_available' => 'البلد غير متاح',
    ],

    'category' => [
        'not_available' => 'القسم غير متوفر',
    ],

    'collection' => [
        'not_available' => 'التشكيلة غير متوفرة',
    ],

    'area' => [
        'not_available' => 'المنطقة غير متوفرة',
    ],

    'products' => [
        'not_available' => 'المنتجات غير متوفرة',
    ],

    'stores' => [
        'not_available' => 'المتاجر غير متوفرة',
    ],

    'product' => [
        'not_available' => 'المنتج غير متوفر',
    ],

    'like_product' => [
        'error' => 'يوجد مشكلة في تفضيل المنتج',
        'not_available' => 'لا يوجد منتجات مفضلة',
    ],

    'event' => [
        'not_available' => 'الحدث غير متاح',
    ],

    'like_event' => [
        'error' => 'يوجد مشكلة في تفضيل الحدث',
    ],

    'login' => [
        'success' => 'تم تسجيل الدخول بنجاح',
        'inactive' => 'المستخدم غير نشط',
        'invalid' => 'عنوان البريد الإلكتروني أو كلمة المرور غير صالحة',
    ],

    'register' => [
        'already_exist' => 'هذا البريد مسجل من قبل',
        'error' => 'هناك بعض المشاكل في التسجيل',
        'already_vendor' => 'يتم تسجيل عنوان البريد الإلكتروني كبائع',
    ],

    'page' => [
        'not_available' => 'محتوى الصفحة غير متاح',
    ],
    'addresses' => [
        'not_available' => 'العناوين غير متوفرة',
        'add' => [
            'success' => 'تمت إضافة العنوان بنجاح',
            'error' => 'هناك بعض المشاكل في اضافة العنوان',
        ],
        'update' => [
            'success' => 'تم تحديث العنوان بنجاح',
            'error' => 'هناك بعض المشاكل في تحديث العنوان',
        ],
        'delete' => [
            'success' => 'تم حذف العنوان بنجاح',
            'error' => 'هناك بعض المشاكل في حذف العنوان',
            'default' => 'لا يمكنك حذف العنوان الأساسي',
        ],
        'set_default' => [
            'success' => 'تم تعيين العنوان الأساسي بنجاح',
            'error' => 'هناك بعض المشاكل في تعيين العنوان الأساسي',
        ],

    ],
    'address' => [
        'not_available' => 'العنوان غير متوفر',
        'delete' => [
            'success' => 'تم حذف العنوان بنجاح',
            'error' => 'هناك بعض المشاكل في حذف العنوان',
            'default' => 'لا يمكنك حذف العنوان الافتراضي',
        ],
    ],

    'promo_code' => [
        'expired' => 'انتهت صلاحية كود الترويج الخاص بك',
        'minimum_order_error' => 'يجب أن يكون الحد الأدنى لمبلغ الطلب amount: لرمز القسيمة الترويجية',
        'success' => 'تم تطبيق رمز الترويج بنجاح',
        'error' => 'الكود غير صالح'
    ],

    'carts' => [
        'empty' => 'السلة فارغة',
        'qty_not_available' => 'كمية المنتج غير متوفر',
        'store_status_error' => 'الآن حتى لا يمكنك إضافة هذا المنتج إلى عربة التسوق.'.':store is :status',
        'delivery_not_available' => 'لا يمكن توصيل المنتج لمنطقتك',
        'add' => [
            'success' => 'تم اضافة المنتج بنجاح',
            'error' => 'هناك بعض المشاكل في إضافة إلى عربة التسوق',
        ],
        'update' => [
            'success' => 'تم تحديث السلة بنجاح',
            'error' => 'هناك بعض المشاكل في تحديث السلة',
        ],
        'note' => [
            'success' => 'تم حفظ الملاحظة بنجاح',
            'error' => 'هناك مشكلة في ملاحظة التحديث',
        ],
        
        'delete' => [
            'success' => 'تمت إزالة المنتج من السلة بنجاح',
            'error' => 'هناك بعض المشاكل في إزالة المنتج من العربة',
        ],

        'product' => [
            'not_available' => 'منتج العربة غير متوفر',
        ],
        'option_not_available' => 'لم يتم العثور على مجموعة المنتجات الخاصة بك'
    ],

    'product_option' => [
        'not_available' => 'خيار المنتج غير متوفر',
    ],

    'update_profile' => [
        'success' => 'تم تحديث ملفك الشخصي بنجاح',
        'error' => 'هناك بعض المشاكل في تحديث الملف الشخصي',
    ],

    'change_password' => [
        'success' => 'تم تغيير كلمة مرورك بنجاح',
        'error' => 'الرجاء إدخال كلمة مرور صالحة الحالية',
    ],

    'order' => [
        'success' => 'تم تقديم طلبك بنجاح.',
        'not_available' => 'الطلب غير متوفر'
    ],

    'orders' => [
        'not_available' => 'الطلبات غير متوفرة'
    ],

    'store' => [
        'not_available' => 'المتجر غير متوفر'
    ],

    'product_rating' => [
        'success' => 'شكرا لتقييم المنتج',
        'already_available' => 'لقد اضفت تقييم سابق'
    ],

    'store_rating' => [
        'success' => 'شكرا لتصنيف المتجر',
        'already_available' => 'لقد أضفت بالفعل تصنيفًا لهذا المتجر'
    ],

    'request_vendor' => [
        'success' => 'لقد تلقينا طلبك للبائع',
        'error' => 'هناك بعض المشاكل. حاول مرة اخرى.',
    ],

    'my_likes' => [
        'not_available' => 'المفضلة غير متوفرة',
    ],

    'contact_us' => [
        'success' => 'تم إرسال طلبك ، سيتصل بك المسؤول'
    ],

    'static_content' => [
        'popular' => 'الأكثر طلباً',
        'featured_stores' => 'متاجر مميزة',
        'related_products' => 'منتجات ذات صله',
        'just_added' => 'تم الإضافة',
        'All' => 'الكل',
        'project_delivery' => 'شركة بروجكت للتوصيل',
        'days' => 'يوم',
        'hours' => 'ساعات',
        'Open' => 'متاح',
        'Close' => 'مغلق',
        'Busy' => 'مشغول',
        'free' => 'مجاناً',
        'KD' => 'د.ك'
        
    ]
];
