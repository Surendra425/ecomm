@extends('layouts.admin')
@section('content')
@php 
$pageTitle ="vendorAdd";
$contentTitle =empty($vendors) ? 'Create Vendor' : 'Edit Vendor';
@endphp

<!--begin::Portlet-->
    <div class="m-portlet m-portlet--tab">
        @include('admin.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        {{(empty($vendors)) ? 'Create Vendor' : 'Edit Vendor'}}
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <form id="vendor_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="<?php echo(!empty($vendors)) ?  (url(route('AdminVendorUpdate',['vendor'=>$vendors['id']]))) : (url(route('vendors.store'))); ?>" novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{$vendors->id or ''}}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="first_name">First Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="first_name"
                                   name="first_name" value="{{$vendors->first_name or ''}}"
                             placeholder="First Name">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="lname">Last Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="last_name"
                                   name="last_name" value="{{$vendors->last_name or '' }}" placeholder="Last Name">
                        </div>
                        @if(empty($vendors))
                        <div class="form-group m-form__group">
                            <label for="email">Email<span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="email" name="email"
                                   value="{{$vendors->email or '' }}" placeholder="Email Address" {{(!empty($vendors)) ? 'readonly' : '' }}>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="mobile_no">Mobile<span class="text-danger">*</span></label>
                            <input type="text" class="form-control phonNumberOnly" id="mobile_no" name="mobile_no"
                                   value="{{$vendors->mobile_no or '' }}" placeholder="Your Phone Number" {{(!empty($vendors)) ? 'readonly' : '' }}>
                        </div>
                        @endif

                    </div>
                    <div class="col-md-6">

                            @if(empty($vendors))
                                <div class="form-group m-form__group">
                                    <label for="password">Password<span class="text-danger">*</span></label>
                                    <input type="password" class="form-control m-input m-input--square" id="password"
                                           name="password" placeholder="Your Password">
                                </div>
                        <div class="form-group m-form__group">
                            <label for="password_confirmation">Confirm Password<span
                                        class="text-danger">*</span></label>
                            <input type="password" class="form-control m-input m-input--square"
                                   id="password_confirmation" name="password_confirmation"
                                   placeholder="Your Confirm Password">
                        </div>
                        @endif
                                <div class="form-group m-form__group">
                                    <label for="profile_image">Profile Image</label><br>
                                    <label class="custom-file">
                                        <input type="file" name="profile_image"
                                               id="profile_image" class="custom-file-input" onchange="readURL(this);">
                                        <span class="custom-file-control"></span>
                                    </label>
                                </div>
                                <div class="row">
                                   <div class="col-md-3">
                                        <div class="form-group m-form__group" >
                                            <input type="hidden"name="profile_image_1" id="profile_image_1" value="<?php if(!empty($vendors->profile_image)) { echo $vendors->profile_image;} ?>">
                                           <img id="blah" src="{{ !empty($vendors->profile_image) ? url('/doc/vendor_logo').'/'.$vendors->profile_image : url('assets/app/media/img/no-images.jpeg')}}" width="50" height="50">

                                        </div>
                                    </div>
                                </div>
                   </div>
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

@endsection
@section('js')
<script type="text/javascript">
            $(document).ready(function ()
            {
                $("#vendor_create").validate({
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
                            number:true,
                            remote: {
                                url: baseUrl+'/check/unique/users/mobile_no',
                                type: "post",
                                data: {
                                    value: function() {
                                        return $( "#mobile_no" ).val();
                                    },
                                    id: function() {
                                        return $( "#id" ).val();
                                    },
                                },
                            }
                        },
                        email: {
                            required: true,
                            email: true,
                            remote: {
                                url: baseUrl+'/check/unique/users/email',
                                type: "post",
                                data: {
                                    value: function() {
                                        return $( "#email" ).val();
                                    },
                                    id: function() {
                                        return $( "#id" ).val();
                                    },
                                },
                            }
                        },
                        password: {
                            required: true,
                            minlength: 6
                        },
                        password_confirmation: {
                            required: true,
                            equalTo: "#password"
                        }
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
                            required: "Phone Number is required",
                            minlength: "Phone Number have atleast 8 character",
                            number: "Invalid phone number",
                            remote: "Phone Number is already taken."
                        },
                        email: {
                            required: "Email is required",
                            email: "Please enter a valid email address.",
                            remote: "Email is already taken."
                        },
                        password: {
                            required: "Password is required",
                            minlength: "Password have atleast 6 character"
                        },
                        password_confirmation: {
                            required: "Confirm password is required",
                            equalTo: "Password and confirm password not match"
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
