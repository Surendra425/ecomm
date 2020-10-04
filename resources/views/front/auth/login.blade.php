@extends('front.layout.index')
@section('title') Login @endsection

@section('meta')
    <meta charset="utf-8">
<meta name="description" content="" />
<meta property="og:url" content="https://shopzz.com/" />
<meta property="og:type" content="E-Commerce" />
<meta property="og:title" content="Shopzz" />
<meta property="og:description" content="" />
<meta property="og:image" content="" />
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="">
<meta name="twitter:description" content="">
<meta name="twitter:image" content="">
@endsection

@section('content')
    <div class="container-fluid" id="Banner-MyAccount">
        <div class="container">
            <div class="col-sm-3 col-lg-3 col-xs-6 col-md-3">
                MY ACCOUNT
            </div>
        </div>
    </div>
    <div class="container-fluid" id="HomeBreadCumb">
        <div class="container" id="home-myAccount">
            <div class="col-sm-3 col-lg-3 col-xs-6 col-md-3">
                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span><a href="#" class="home-myAccount-1">My Account</a></span>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <!-- LOG IN -->

            <div class="col-sm-6 sign" >
                <div id="legend">
                    <h3 class="legend">LOGIN</h3>
                </div>
                <div class="sns">
                    <div class="default">
                        <button class="btn-fb btn-wide facebook" type="button">Facebook</button>
                        <button class="btn-gg btn-left google" type="button" >Google</button>
                        {{-- <button class="btn-tw btn-right twitter" type="" >Twitter</button> --}}
                    </div>
                </div>
                <form id="login-form" method="POST" action="{{ url('/login')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email">Email <span class="asteric">*</span></label>
                        <input id="email" name="email" class="form-control clsLoginDdl" type="email" data-validation="required" placeholder="Username or email">
                        <span id="error_name" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password <span class="asteric">*</span></label>
                        <input id="password" name="password" class="form-control clsLoginDdl" type="password"  placeholder="Password">
                        <span id="error_password" class="text-danger"></span>
                    </div>
                    <div>
                        <div class="col-sm-6 col-xs-12">
                        <span class="checkbox">
                            <input type="checkbox" id="checkbox" name="remember" >
                            <span id="remember"> Remember Me</span>
                        </span>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a href="{{ url('password/reset') }}"  class="forgot-password">Lost your password?</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-button-1">
                        <button type="submit" class="btn btn-primary btn-block">SIGNIN</button>
                    </div>
                    <div class="hr"><hr></div>
                </form>
            </div>
            <div class="col-sm-1">
                <div class="vertical"></div>
            </div>
            <div class="col-sm-5 Register">
                <h5>REGISTER</h5>
                <p>Registering for this site allows you to access your order status
                    and history. Just fill in the fields on left, and we'll get a new
                    account set up for you in no time. We will only ask you for
                    information necessary to make the purchase process faster
                    and easier.</p>
                <span>Need an account?</span>
                <a href="{{ url(route('customerRegisterProcess')) }}" class="btn btn-primary btn-block" id="signup-button">SIGNUP</a>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            $("button.facebook").click(function()
            {
                window.location = "{{url(route('socialLogin',['provider'=>'facebook']))}}";
            });
            $("button.google").click(function()
            {
                window.location = "{{url(route('socialLogin',['provider'=>'google']))}}";
            });

            $("#login-form").validate({
                rules: {
                    email: {
                        required: true,
                        email:true,
                        regex:emailpattern,
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    email: {
                        required: "Email address is required",
                        email:"Please enter valid email address",
                        regex:"Please enter valid email address"
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
@endsection