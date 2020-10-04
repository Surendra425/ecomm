@extends('layouts.admin')
@section('content')
    @php
        $pageTitle ="vendor";
    $contentTitle ='Vendor';
    @endphp
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-truck"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        Vendor
                    </h3>
                </div>
            </div>
        </div>
        <form class="m-form m-form--fit">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="first_name">First Name</label>
                        <p class="form-control-static">{{ $vendor->first_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="email">Email</label>
                        <p class="form-control-static">{{ $vendor->email }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="profile_image">Profile Image</label>
                        <img src="<?php echo (!empty($vendor->profile_image)) ? url('/doc/vendor_logo').'/'.$vendor->profile_image : url('assets/demo/default/media/img/logo/user.png'); ?>" width="50" height="50">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="lname">Last Name</label>
                        <p class="form-control-static">{{ $vendor->last_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="mobile_no">Mobile</label>
                        <p class="form-control-static">{{ $vendor->mobile_no }}</p>
                    </div>
                </div>

            </div>
        </div>
    	</form>
    </div>
@endsection