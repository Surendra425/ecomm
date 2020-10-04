<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>I Can Save the world</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    {{--<meta name="viewport" content="width=device-width, initial-scale=1" />--}}
    <link rel="stylesheet" href="{{url('assets/frontend/css/app-style.css')}}">


</head>
<body>
<div class="container">
    <div class="landding_inner">
        <div class="cart_sl">
            <a href="{{url('/')}}"><img src="{{url('assets/frontend/images/cart-icon.png')}}"></a>
        </div>
        <h1>I Can Save the world</h1>
        <h2>شــوبــزّ</h2>
        <h3>Now Available!</h3>
        <h4>!مـتـوفـر الآن</h4>
        <div class="apps_icon">
            <a href="{{env('IPHONE_APPSTORE_URL')}}"><img src="{{url('assets/frontend/images/apps_store_img.png')}}" alt=""></a>
            <a href="{{env('GOOGLE_PLAYSTORE_URL')}}"><img src="{{url('assets/frontend/images/google_pay_img.png')}}" alt=""></a>
        </div>
    </div>
</div>
</body>
</html>