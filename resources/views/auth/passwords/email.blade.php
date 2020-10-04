@extends('layouts.app')
@section('title') Forgot Password @endsection
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
            <span><a href="#" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
            <span><a href="#" class="home-myAccount-1">My Account</a></span>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="container">
        <!-- LOG IN -->
        <div class="forgotPage">
            <div id="legend">
                <legend class="">Forgot Password</legend>
            </div>
            <form class="form-horizontal" method="POST" action="{{ route('password.email') }}" id="formLogin">
                {{ csrf_field() }}
                <div class="form-group">
                    @include('app.common.flash')
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="asteric">*</span></label>
                    <input id="email" name="email" class="form-control clsLoginDdl" type="email"  placeholder="Email">
                </div>  
                <div>
                    <div class="col-sm-6 col-xs-6">
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group">  
                            <a href="{{ url('login') }}"  class="forgot-password">Login</a>  
                        </div>
                    </div>
                </div>
                <div class="form-group" style="width: 100;text-align: center">
                    <button type="submit" class="btn btn-success">Submit</button>  
                </div>
            </form>
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
                    required: true
                }
            },
            messages: {
                email: {
                    required: "Email address is required"
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
