@extends('layouts.'.$loginUser->type)
@section('content')
    @php
        $pageTitle ="dashboard";
    @endphp
    <div class="col-md-12">
    @include('admin.common.flash')
    <!--begin::Portlet-->
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                        <h3 class="m-portlet__head-text">
                            Change Password
                        </h3>
                    </div>
                </div>
            </div>
            <!--begin::Form-->
            <form id="password_change" class="m-form m-form--fit m-form--label-align-right form"  method="post" action="{{url(route('change-password.store'))}}" novalidate>
                {{ csrf_field() }}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group" id="old_password_div">
                        <label for="old_password">Old Password <span class="text-danger">*</span></label>
                        <input class="form-control m-input m-input--square" type="password" id="old_password" name="old_password" placeholder="Enter Old Password">
                        <span id="old_password_message" style="color: #f4516c"></span>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="password">New Password <span class="text-danger">*</span></label>
                        <input class="form-control m-input m-input--square" type="password" id="password" name="password" placeholder="Enter New Password">
                    </div>
                    <div class="form-group m-form__group">
                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                        <input class="form-control m-input m-input--square" type="password" id="password_confirmation" name="password_confirmation" placeholder="Enter Confirm Password">
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
        <!--end::Portlet-->
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        $("#old_password").change(function(){
            var password = $("#old_password").val();
            $.ajax({
                url: " {{ url(route("changePassword"))  }}",
                method: "POST",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data:{
                    'old_password' : password
                },
                success: function(result){
                   if(result == 0){
                    $("#old_password_message").text("Current Password not match.");
                    $("#old_password_div").addClass('has-text-danger');
                   }else{
                       $("#old_password_div").removeClass('has-text-danger');
                       $("#old_password_message").text("");
                   }
                }});
        });
        $(document).ready(function ()
        {
            $("#password_change").validate({
                rules: {
                    old_password: {
                        required: true
                    },
                    password:{
                        required: true,
                        minlength: 6
                    },
                    password_confirmation:{
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    old_password: {
                        required: "Old Password is required"
                    },
                    password: {
                        required: "New Password is required.",
                        minlength: "Password have atleast 6 character"
                    },
                    password_confirmation: {
                        required: "Confirm Password is required.",
                        equalTo: "New password and confirm password not match"
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
