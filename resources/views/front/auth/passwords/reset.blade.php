@extends('front.layout.index')
@section('title') Reset Password @endsection
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
                <span><a href="{{route('home')}}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span><a href="#" class="home-myAccount-1">My Account</a></span>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="container">
            <!-- LOG IN -->

            <div class="forgotPage">
                @if(isset($token))
                    <div id="legend">
                        <legend class="">Reset Password</legend>
                    </div>
                    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}" id="formLogin">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                            <label for="email">Email <span class="asteric">*</span></label>
                            <input id="email" name="email" class="form-control clsLoginDdl" type="email"  placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password <span class="asteric">*</span></label>
                            <input id="password" name="password" class="form-control clsLoginDdl" type="password"  placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password <span class="asteric">*</span></label>
                            <input id="password_confirmation" name="password_confirmation" class="form-control clsLoginDdl" type="password"  placeholder="Confirm Password">
                        </div>
                        <div class="form-group" style="width: 100;text-align: center">
                            <button type="submit" class="btn btn-success">Reset Password</button>
                        </div>
                    </form>
            @else
                <div id="legend">
                    <legend class="">Already Reset Password</legend>
                </div>
            @endif
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function ()
        {
            $("#formLogin").validate({
                rules: {
                    email: {
                        required: true,
                        email:true,
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
                        required: "Email is required",
                        email:"Please enter valid email"
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
@endsection