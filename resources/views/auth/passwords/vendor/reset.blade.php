<html lang="en" >
    <head>
        <meta charset="utf-8" />
        <title>I Can Save the world | Forgot Password</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
        <link href="{{ url('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="{{ url('assets/demo/default/media/img/logo/favicon.png') }}" /> 
    </head>
    <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--singin" id="m_login">
                <div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
                    <div class="m-stack m-stack--hor m-stack--desktop">
                        <div class="m-stack__item m-stack__item--fluid">
                            <div class="m-login__wrapper">
                                <div class="m-login__logo">
                                    <a href="#">
                                        <img src="{{ url('assets/logo.png') }}">  	
                                    </a>
                                </div>
                                <div class="m-login__signin">
                                    <div class="m-login__head">
                                        <h3 class="m-login__title">Enter Email to Forgot Password</h3>
                                    </div>
                                    <div class="form-group">
                                        @include('vendor.common.flash')
                                    </div> 
                                    <form class="m-login__form m-form" action="{{ route('sendVendorResetPassword') }}" method="POST" id="formLogin">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="email" placeholder="Email" name="email" autocomplete="off">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="password" id="password" placeholder="Password" name="password" autocomplete="off">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="password" placeholder="Confirm Password" name="password_confirmation" autocomplete="off">
                                        </div>
                                        <div class="row m-login__form-sub">
                                            <div class="col m--align-left">
                                            </div>
                                            <div class="col m--align-right">
                                                <a href="{{ url('/vendor') }}" id="m_login_forget_password" class="m-link">Login ?</a>
                                            </div>
                                        </div>
                                        <div class="m-login__form-action">
                                            <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">Reset Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="m-stack__item m-stack__item--center">  
                            <div class="m-login__account">
                                <span class="m-login__account-msg">
                                    Don't have an account yet ?
                                </span>&nbsp;&nbsp;
                                <a href="{{ url('vendor/register') }}" id="m_login_signup" class="m-link m-link--focus m-login__account-link">Sign Up</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1	m-login__content" style="background-image: url({{ url('assets/app/media/img/bg/bg-4.jpg') }})">
                    <div class="m-grid__item m-grid__item--middle">
                        <h3 class="m-login__welcome">Join Our Community</h3>
                        <p class="m-login__msg">
                            <!--Lorem ipsum dolor sit amet, coectetuer adipiscing<br>elit sed diam nonummy et nibh euismod-->
                        </p>
                    </div>
                </div>
            </div>	
        </div>    
        <script src="{{ url('assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/vendors/base/jquery.validate.min.js') }}" type="text/javascript"></script>
        <script type="text/javascript">
$(document).ready(function ()
{
    $("#formLogin").validate({
        rules: {
            email: {
                required: true
            },
            password: {
                required: true,
                minlength: 6
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            },
        },
        messages: {
            email: {
                required: "Email is required"
            },
            password: {
                required: "Password is required",
                minlength: "Password have atleast 6 character"
            },
            password_confirmation: {
                required: "Confirm password is required",
                equalTo: "Password and confirm password not match"
            },
        },
        submitHandler: function (form)
        {
            form.submit();
        }
    });
});
        </script>
    </body>
</html>