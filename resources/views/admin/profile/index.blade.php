@extends('layouts.admin')
@section('content')
    @php
        $pageTitle ="dashboard";
    $contentTitle ='Edit Profile';
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
                            Edit Profile
                        </h3>
                    </div>
                </div>
            </div>
            <!--begin::Form-->
            <form id="admin_form" class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data"
                  method="post"
                  action="{{ route('AdminProfileUpdate') }}" novalidate>
                {{ csrf_field() }}
                <div class="m-portlet__body">
                    <div class="row">
                    <div class="col-md-6">
                        
                        <div class="form-group m-form__group">
                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square"
                                   value="{{ $admin->first_name }}" id="first_name"
                                   name="first_name" placeholder="First Name">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="lname">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control  m-input m-input--square"
                                   value="{{ $admin->last_name }}" id="last_name"
                                   name="last_name" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group m-form__group">
                            <input type="hidden" value="{{ $admin->profile_image }}" name="profile_image_1"
                                   id="profile_image_1">
                            <?php
                            if(!empty($admin->profile_image)) { ?>
                            <img src="<?php echo url('/doc/profile_image') . '/' . $admin->profile_image; ?>" id="blah" width="70"
                                 height="70">
                            <?php } ?>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="profile_image">Profile Image</label><br>
                            <label class="custom-file">
                                <input type="file" name="profile_image"
                                       id="profile_image" class="custom-file-input" onchange="readURL(this);">
                                <span class="custom-file-control"></span>
                            </label>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="custom-file">
                                <img id="blah" src="#" alt=""/>
                            </label>
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


        $(document).ready(function () {
            $("#admin_form").validate({
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
                        number: true
                    },
                    email: {
                        required: true
                    }
                    /*profile_image: {
                        extension: "jpg,jpeg,png,svg",
                        filesize: 2048
                    }*/
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
                        number: "Invalid phone number"
                    },
                    email: {
                        required: "Email is required"
                    }
                    /* profile_image: {
                         extension: "Please upload .jpg or .png or .jpeg or .svg file of notice.",
                         filesize: "file size must be less than 2 MB."
                     }*/
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection



