<html lang="en" >
    <head>
        <meta charset="utf-8" />
        <title>I Can Save the world | Register Vendor</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
        <link href="{{ url('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="{{ url('assets/demo/default/media/img/logo/favicon.png') }}" /> 
        <style type="text/css">
            .m-login.m-login--1 .m-login__wrapper {
                overflow: hidden;
                padding: 0% 2rem 2rem 0rem;
            }
            .m-login.m-login--1 .m-login__wrapper .m-login__logo {
                text-align: center;
                margin: 0 auto 1rem auto;
            }
            .m-login.m-login--1 .m-login__aside {
                width: 700px;
                padding: 1rem 11rem;
            }
            .m-login.m-login--1 .m-login__wrapper {
                overflow: hidden;
                padding: 0% 2rem 0rem 0rem;
            }
        </style>
    </head>
    <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--singin" id="m_login">
                <div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
                    <div class="m-stack m-stack--hor m-stack--desktop">
                        <div class="m-stack__item m-stack__item--fluid">
                            <div class="m-login__wrapper">
                                <div class="m-login__logo">
                                    <a href="{{ url(route('vendorDashboard')) }}">
                                        <img src="{{ url('assets/logo.png') }}">  	
                                    </a>
                                </div>
                                <div class="m-login__signin">
                                    <div class="m-login__head">
                                        <h3 class="m-login__title">Sign Up</h3>
                                        <div class="m-login__desc">Enter your details to create your account:</div>
                                    </div>
                                    @include('vendor.common.flash')
                                    <form class="m-login__form m-form" action="{{ route('vendorRegisterProcess') }}" method="POST" id="vendor_register">
                                        {{ csrf_field() }}
                                        @if(isset($plan_option) && !empty($plan_option))
                                        <input type="hidden" name="plan_option" value="{{ $plan_option->id }}">                                        
                                        @endif
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="text" placeholder="Firstname" name="first_name">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="text" placeholder="Lastname" name="last_name">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="email" placeholder="Email" name="email" autocomplete="off">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="text" placeholder="Mobile No" name="mobile_no" autocomplete="off">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="password" placeholder="Password" name="password" id="password">
                                        </div>
                                        <div class="form-group m-form__group">
                                            <input class="form-control m-input" type="password" placeholder="Confirm Password" name="password_confirmation">
                                        </div>
                                        <div class="row form-group m-form__group m-login__form-sub">
                                            <div class="col m--align-left">
                                                <label class="m-checkbox m-checkbox--focus">
                                                    <input type="checkbox" name="agree"> I Agree the <a href="#" class="m-link m-link--focus">terms and conditions</a>.
                                                    <span></span>
                                                </label>
                                                <span class="m-form__help"></span>
                                            </div>
                                        </div>
                                        <div class="m-login__form-action">
                                            <button id="m_login_signup_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">Sign Up</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="m-stack__item m-stack__item--center">  
                                    <div class="m-login__account">
                                        <span class="m-login__account-msg">
                                            Already have an account ?
                                        </span>&nbsp;&nbsp;
                                        <a href="{{ route('vendorLogin') }}" id="m_login_signup" class="m-link m-link--focus m-login__account-link">Sign In</a>
                                    </div>
                                </div>
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
    $("#vendor_register").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2
            },
            last_name: {
                required: true,
                minlength: 2
            },
            mobile_no: {
                required: true,
                minlength: 8,
                number: true,
            },
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
            agree: {
                required: true
            },
        },
        messages: {
            first_name: {
                required: "First Name is required",
                minlength: "First Name have atleast 2 character"
            },
            last_name: {
                required: "Last Name is required",
                minlength: "Last Name have atleast 2 character"
            },
            mobile_no: {
                required: "Mobile Number is required",
                minlength: "Mobile Number have atleast 8 character",
                number: "Invalid phone number",
            },
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
            email: {
                required: "Please agree the terms and conditions."
            }
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
