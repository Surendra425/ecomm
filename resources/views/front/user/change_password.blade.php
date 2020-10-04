@extends('front.layout.index')
@section('title') Change Password @endsection
@section('content')
    <div class="container-fluid" id="Banner-MyAccount">
        <div class="container">
            <div class="col-sm-3 col-lg-3 col-xs-6 col-md-3">
                Change Password
            </div>
        </div>
    </div>
    <div class="container-fluid" id="HomeBreadCumb">
        <div class="container" id="home-myAccount">
            <div class="col-sm-3 col-lg-3 col-xs-6 col-md-3">
                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span><a class="home-myAccount-1"> @yield('title') </a></span>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="container">
            <!-- REGISTER -->
            <div class="col-sm-6" id="login-page" >
                <div id="legend">
                    <legend class="">CHANGE PASSWORD</legend>
                </div>
                <form name="myform" method="post" action="{{ url('change-password')}}" id="password_change">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="old_password">Old Password <span class="asteric">*</span></label>
                        <input id="old_password" name="old_password" class="form-control clsLoginField" type="password" data-validation="required" placeholder="Old Password">
                    </div>
                    <div class="form-group">
                        <label for="password">New Password <span class="asteric">*</span></label>
                        <input id="password" name="password" class="form-control clsLoginField" type="password" data-validation="required" placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password <span class="asteric">*</span></label>
                        <input id="password_confirmation" name="password_confirmation" class="form-control clsLoginField" type="password" data-validation="required" placeholder="Confirm Password">
                    </div>
                    <div class="form-group">
                        <button type="submit" value="submit" class="btn btn-primary btn-block">Change Password</button>
                    </div>
                </form>
                <div class="hr"><hr></div>
            </div>
            <div class="col-sm-1">
                <div class="vertical" style="height: 360px;margin-top: 30px;"></div>
            </div>
            <div class="col-sm-5" id="Log-In">
                <h5>LOGIN</h5>
                <p>Registering for this site allows you to access your order status
                    and history. Just fill in the fields on left, and we&apos;ll get a new
                    account set up for you in no time. We will only ask you for
                    information necessary to make the purchase process faster
                    and easier.</p>
                <span>Already have an account?</span>
                <a id="Login-button" href="{{ url('profile') }}" class="btn btn-primary btn-block">My Profile</a>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">

        $(document).ready(function () {
            $("#password_change").validate({
                rules: {
                    old_password :{
                        required:true,
                    },
                    password:{
                        required: true,
                        minlength:6
                    },
                    password_confirmation:{
                        required: true,
                        equalTo : "#password"
                    }
                },
                messages:{
                    old_password:{
                        required : "Please enter old password"
                    },
                    password:{
                        required: "Please enter password",
                        minlength:"Please enter password must be greater than {0} characters"
                    },
                    password_confirmation:{
                        required: "Please enter confirm password",
                        equalTo:  "Password and confirm password does not match"
                    }
                },
            });
        });

    </script>
@endsection