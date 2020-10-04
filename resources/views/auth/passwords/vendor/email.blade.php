<html lang="en" >
    <head>
        <meta charset="utf-8" />
        <title>I Can Save the world | Login</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
        <link href="{{ url('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="{{ url('assets/demo/default/media/img/logo/favicon.png') }}" /> 
        {{--<style type="text/css">
            .m-stack {
                display: block;
                width: 100%;
                height: auto; 
            }
        </style>--}}
    </head>
    <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-1" id="m_login" style="background-color:lightgray;">
            <div class="m-grid__item m-grid__item--fluid    m-login__wrapper">
                <div class="m-login__container">
                    <div class="m-login__logo">
                        <a>
                            <img  style="height:80px;" src="{{ url('assets/demo/default/media/img/logo/logo_default_dark.png') }}">
                        </a>
                    </div>
                    <div class="m-login__signin">
                        <div class="m-login__head">
                            <h3 class="m-login__title">Enter email address to get " Reset Password " Instructions</h3>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    @include('vendor.common.flash')
                                </div>
                            </div>
                        </div>
                        <form class="m-login__form m-form" action="{{ url('vendor/password/email') }}" method="POST" id="formLogin">
                            {{ csrf_field() }}
                            <div class="form-group m-form__group">
                                <input class="form-control m-input forgotPassword" type="email" placeholder="Email" name="email" autocomplete="off">
                            </div>
                            <div class="row m-login__form-sub">
                                <div class="col m--align-left">
                                </div>
                                <div class="col m--align-right">
                                    <a href="{{ url('/vendor') }}" id="m_login_forget_password" class="m-link forgotPassword">Login</a>
                                </div>
                            </div>
                            <div class="m-login__form-action">
                                <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air forgotPassword">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

                {{--<div class="m-stack m-stack--hor m-stack--desktop">
                    <div class="m-stack__item m-stack__item--fluid">
                        <div class="m-login__wrapper forgotPassword">

                            <div class="m-login__signin">
                                <div class="m-login__head">
                                    <h4 class="m-login__title">Enter email address to get " Reset Password " Instructions</h4>
                                </div>
                                <div class="form-group">
                                    @include('vendor.common.flash')
                                </div>
                                <form class="m-login__form m-form" action="{{ url('vendor/password/email') }}" method="POST" id="formLogin">
                                    {{ csrf_field() }}
                                    <div class="form-group m-form__group">
                                        <input class="form-control m-input forgotPassword" type="email" placeholder="Email" name="email" autocomplete="off">
                                    </div>
                                    <div class="row m-login__form-sub">
                                        <div class="col m--align-left">
                                        </div>
                                        <div class="col m--align-right">
                                            <a href="{{ url('/vendor') }}" id="m_login_forget_password" class="m-link forgotPassword">Login</a>
                                        </div>
                                    </div>
                                    <div class="m-login__form-action">
                                        <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air forgotPassword">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="m-stack__item m-stack__item--center">
                        <div class="m-login__account">
                            <span class="m-login__account-msg forgotPassword">
                                Don't have an account yet ?
                            </span>&nbsp;&nbsp;
                            <a href="{{ route('sellWithUs') }}" id="m_login_signup" class="m-link m-link--focus m-login__account-link forgotPassword">Request For Vendor Account</a>
                        </div>
                    </div>
                </div>--}}


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
                        }
                    },
                    messages: {
                        email: {
                            required: "Email address is required"
                        },
                        password: {
                            required: "Password is required",
                            minlength: "Password have atleast 6 character"
                        }
                    },
                    submitHandler: function (form)
                    {
                        form.submit();
                    }
                });
            });
        </script>
    <!--<script src="{{ url('assets/snippets/pages/user/login.js') }}" type="text/javascript"></script>-->
    </body>
</html>