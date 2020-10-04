@extends('layouts.admin')
@section('content')
    @php
        $pageTitle ="admin";
    $contentTitle ='Admin';
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
                            Admin
                        </h3>
                    </div>
                </div>
            </div>
            <!--begin::Form-->
            <form class="m-form m-form--fit">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>First Name:</label>
                                <p class="form-control-static">{{ $admin->first_name }}</p>
                            </div>
                            <div class="form-group m-form__group">
                                <label>Last Name:</label>
                                <p class="form-control-static">{{ $admin->last_name }}</p>
                            </div>
                            <div class="form-group m-form__group">
                                <label>Email:</label>
                                <p class="form-control-static">{{ $admin->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Mobile:</label>
                                <p class="form-control-static">{{ $admin->mobile_no }}</p>
                            </div>
                            <div class="form-group m-form__group">
                                <label>Profile Image:</label>
                                <p class="form-control-static">
                                    <img src="<?php echo (!empty($admin->profile_image)) ? url('doc/profile_image').'/'.$admin->profile_image : url('assets/demo/default/media/img/logo/user.png'); ?>"
                                         width="50" height="50">
                                </p>
                            </div>
                        </div>
                    </div>


                </div>
            </form>

            <!--end::Form-->
        </div>
        <!--end::Portlet-->
    </div>


@endsection