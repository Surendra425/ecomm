<html lang="en" >
    <head>
        <meta charset="utf-8" />
        <title>I Can Save the world | Admin Login</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="{{ url('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="{{ url('assets/demo/default/media/img/logo/favicon.png') }}" /> 
    </head>
    <!-- end::Head -->
    <!-- end::Body -->
    <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-1" id="m_login" style="background-color:lightgray;">
                <div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
                    <div class="m-login__container">
                        <div class="m-login__logo">
                            <a>
                                <img src="{{ url('assets/demo/default/media/img/logo/logo_default_dark.png') }}"  style="height:80px;">  	
                            </a>
                        </div>
                        <div class="m-login__signin">
                            <div class="m-login__head">
                                <h3 class="m-login__title">Sign In To Admin</h3>
                            </div>
                            @if ($errors->has('email'))
                            <div class="alert alert-danger">
                                <strong>Error!</strong> {{ $errors->first('email') }}
                            </div>
                            @endif
                            <form class="m-login__form m-form" action="{{ url('/admin')}}" method="POST" id="form_login">
                                {{ csrf_field() }}
                                
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input"   type="text" placeholder="Email" name="email" autocomplete="off">
                                </div>
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password">
                                </div>
                                <!-- <div class="row m-login__form-sub">

                                    <div class="col m--align-right m-login__form-right">
                                        <a href="javascript:;" id="m_login_forget_password" class="m-link">Forget Password ?</a>
                                    </div>
                                </div> -->
                                <div class="m-login__form-action">
                                    <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primary">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>	
        </div>
        
        <script src="{{ url('assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/vendors/base/jquery.validate.min.js') }}" type="text/javascript"></script>
        {{--<script src="{{ url('assets/snippets/pages/user/login.js') }}" type="text/javascript"></script>--}}
        <script type="text/javascript">
            $(document).ready(function ()
            {
                $("#form_login").validate({
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true
                        }
                    },
                    messages: {
                        email: {
                            required: "Email is required",
                            email: "Please enter valid email address"
                        },
                        password: {
                            required: "Password is required"
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