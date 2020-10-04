<!--vendor edit-->
@extends('layouts.vendor')
@section('title') Edit Profile @endsection
@section('content')
@php 
$pageTitle ="dashboard";
@endphp
<!--begin::Portlet-->
<div class="m-portlet">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    @yield('title')
                </h3>
            </div>
        </div>
    </div>
    @include('vendor.common.flash')
    <!--begin::Form-->
    <form class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="{{ route('VendorProfileUpdate') }}" id="Form-EditProfile">
        {{ csrf_field() }}        
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">  
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="first_name">First Name : <span class="danger">*</span></label>
                        <input type="text" class="form-control" value="{{ $vendor->first_name }}" id="first_name" name="first_name" placeholder="First Name">                            
                    </div>      
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="last_name">Last Name : <span class="danger">*</span></label>
                        <input type="text" class="form-control" value="{{ $vendor->last_name }}" id="last_name" name="last_name" placeholder="Last Name">
                    </div>
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="email">Email : <span class="danger">*</span></label>
                        <input type="text" class="form-control" value="{{ $vendor->email }}" id="email" name="email" placeholder="Email Address" readonly>                            
                    </div>
                </div>
                <div class="col-md-6"> 
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="mobile_no">Mobile : <span class="danger">*</span></label>
                        <input type="text" class="form-control phonNumberOnly" id="mobile_no" name="mobile_no" value="{{ $vendor->mobile_no }}" placeholder="Your Phone Number" readonly>
                    </div>   
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="profile_image">Profile Image : <span class="danger">*</span></label>
                        <input type="file" class="form-control" size="20" name="profile_image" id="profile_image" onchange="readURL(this);">
                        <div class="col-md-3">
                            <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                                <input type="hidden" value="{{ $vendor->profile_image }}" name="profile_image_1" id="profile_image_1">
                                @if( ! empty($vendor->profile_image)) 
                                    <img id="blah" src="<?php echo url('/doc/vendor_logo') . '/' . $vendor->profile_image; ?>" width="50" height="50">
                                @endif                      
                            </div>
                        </div> 
                    </div>                 
                </div>  
            </div>
        </div>
        <div class="m-portlet__foot m-portlet__foot--fit">
            <div class="m-form__actions m-form__actions">
                <div class="row">
                    <div class="col-lg-12 ml-lg-auto text-center">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Portlet-->
@endsection
@section('js')
        <script src="{{ url('assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/vendors/base/jquery.validate.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
                            $(document).ready(function ()
                            {
                                $("#Form-EditProfile").validate({
                                    rules: {
                                        first_name: {
                                            required: true,
                                        },
                                        last_name: {
                                            required: true,
                                        },
                                        email: {
                                            required: true,
                                            email: true,
                                        },
                                    },
                                    messages: {
                                        first_name: {
                                            required: "First name is required",
                                        },
                                        last_name: {
                                            required: "Last name is required",
                                        },
                                        email: {
                                            required: "Email address is required",
                                            email: "Email address is invalid"
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