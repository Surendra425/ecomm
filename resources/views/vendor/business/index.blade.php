@extends('layouts.vendor')
@section('title') Add Business Detail @endsection
@section('css')
<link rel="stylesheet" href="{{ url('assets/vendors/wizard/css1/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/vendors/wizard/css1/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/vendors/wizard/css1/style.css') }}">
<style type="text/css">
    .form-body-classic .form-control {
        height: 44px;
        margin: 0;
        padding: 0 20px;
        border: 1px solid #eee;
        border-radius: 0px;
        font-family: 'Roboto', FontAwesome;
        font-size: 16px;
        font-weight: 300;
        line-height: 44px;
        color: #333;
        -moz-border-radius: none;
        -webkit-border-radius: none;
        border-radius: none;
        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    .m-subheader__title
    {
        display: none;
    }
    select.form-control:not([size]):not([multiple]) {
        height: 44px;
    }
    .error
    {
        font-weight: normal;
        color: #FF1100;
    }
    .form-control-feedback {
        position: relative;
        top: auto;
        right: auto;
        z-index: 2;
        display: block;
        width: 100%;
        height: 34px;
        line-height: 34px;
        text-align: left;
        pointer-events: none;
    }
    .clsTimeRow
    {
        margin: 5px;
        /*border: 1px groove #EFEFEF;*/
        vertical-align: middle;
    }
    .
    .clsTimeRow div
    {
        vertical-align: middle;
    }
    .note-editor .note-dropzone {
        position: absolute;
        z-index: 100;
        display: none;
        color: #87cefa;
        background-color: white;
        opacity: .95;
    }
    button.note-btn.btn.btn-default.btn {
        padding: 0.35rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
        display: inline-block;
        font-weight: normal;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.65rem 1.25rem;
        font-size: 1rem;
        line-height: 1.25;
        border-radius: 0.25rem;
        transition: all 0.15s ease-in-out;
        height: 26px;
        min-width: 44px;
        color: #000000;
    }
    .select2.select2-container.select2-container--default
    {
        width: 100% !important;
    }
</style>
@endsection
@section('content')
<!--begin::Portlet-->
<div class="m-portlet">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-wizard form-header-classic form-body-classic">
                <form role="form" action="" method="post" id="FormBusinessDetail" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <h3>@yield('title')</h3>
                    <p>Fill all form field to go next step</p>
                    @include('vendor.common.flash')
                    <!-- Form progress -->
                    <div class="form-wizard-steps form-wizard-tolal-steps-5">
                        <div class="form-wizard-progress">
                            <div class="form-wizard-progress-line" data-now-value="12.25" data-number-of-steps="4" style="width: 12.25%;"></div>
                        </div>
                        <!-- Step 1 -->
                        <div class="form-wizard-step active">
                            <div class="form-wizard-step-icon">
                                <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                            </div>
                            <p>Basic Detail</p>
                        </div>
                        <!-- Step 1 -->

                        <!-- Step 2 -->
                        <div class="form-wizard-step">
                            <div class="form-wizard-step-icon">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </div>
                            <p>Business Detail</p>
                        </div>
                        <!-- Step 2 -->

                        <!-- Step 3 -->
                        <div class="form-wizard-step">
                            <div class="form-wizard-step-icon">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </div>
                            <p>Shipping Detail</p>
                        </div>
                        <!-- Step 3 -->

                        <!-- Step 4 -->
                        <div class="form-wizard-step">
                            <div class="form-wizard-step-icon">
                                <i class="fa fa-university" aria-hidden="true"></i>
                            </div>
                            <p>Billing / Deposit Detail</p>
                        </div>
                        <!-- Step 4 -->

                        <!-- Step 5 -->
                        <div class="form-wizard-step">
                            <div class="form-wizard-step-icon">
                                <i class="fa fa-file-text" aria-hidden="true"></i>
                            </div>
                            <p>Plan Detail</p>
                        </div>
                        <!-- Step 5 -->
                    </div>
                    <!-- Form progress -->

                    <!-- Form Step 1 -->
                    <fieldset style="display: block;">
                        <!-- Progress Bar -->
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                            </div>
                        </div>
                        <!-- Progress Bar -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    <label for="first_name">First Name : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square  required" id="first_name" name="first_name" value="{{$Vendor->first_name or ''}}" placeholder="First Name" >
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="last_name">Last Name : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square  required" id="last_name" name="last_name" value="{{$Vendor->last_name or ''}}" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    <label for="email">Email : <span class="danger">*</span></label>
                                    <input type="email" class="form-control required" id="email" name="email" value="{{$Vendor->email or ""}}" placeholder="Email">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="mobile_no">Mobile No : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square  required" id="mobile_no" name="mobile_no" value="{{$Vendor->mobile_no or ""}}" placeholder="Mobile No">
                                </div>
                            </div>
                        </div>
                        <div class="form-wizard-buttons">
                            <button type="button" class="btn btn-next">Next</button>
                        </div>
                    </fieldset>
                    <!-- Form Step 1 -->

                    <!-- Form Step 2 -->
                    <fieldset class="m-portlet__body">
                        <!-- Progress Bar -->
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                        </div>
                        <!-- Progress Bar -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    <label for="store_name">Store Name : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square  required" id="store_name" name="store_name" value="{{ $store->store_name or ""}}" placeholder="Store Name">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="category_id">Category : <span class="danger">*</span></label>
                                    <select  class="custom-select col-md-12 select2-hidden-accessible required" name="category_id[]" id="category_id" multiple="multiple">
                                        
                                        @foreach($VendorCategory as $category)
                                              <optgroup label="{{ $category->category_name}}">
                                                @foreach ($category->subCategories as $list)             
                                                <option value="<?php echo $list->id; ?>" <?php echo (in_array($list->id, $storeCategory)) ? 'selected' : ''; ?>>{{$list->category_name}}</option>
                                                @endforeach
                                             </optgroup>
                                        @endforeach
                                    </select>
                                    <label id="category_id-error" class="error" for="category_id">Store category is required</label>
                                </div>
                                <div class="form-group m-form__group">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="description">Description : </label>
                                    <textarea class="form-control" id="description" rows="3" name="description" placeholder="Description">{{ (!empty($store)?$store->description:"")}}</textarea>
                                </div>
                                <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                                    <label class="control-label" for="about_us">About Us : </label>
                                    <textarea class="form-control summernote required" name="about_us" id="about_us">{{ (isset($store->about_us)) ? $store->about_us : NULL }}</textarea>
                                    <label id="about_us-error" class="error" for="about_us">About us is required</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    <label for="address">Address : <span class="danger">*</span></label>
                                    <input type="text" class="form-control required m-input m-input--square" autocomplete="off" id="address" value="{{ (!empty($store)?$store->address:"")}}" name="address" placeholder="Address">
                                 {{--   <textarea class="form-control required" autocomplete="off" id="address" rows="3" name="address" placeholder="Address">{{ (!empty($store)?$store->address:"")}}</textarea>--}}
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="city">City : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square   required" id="city" name="city" value="{{ (!empty($store)?$store->city:"")}}" placeholder="Your City"  autocomplete="off">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="state">State : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square  required" id="state" name="state" value="{{ (!empty($store)?$store->state:"")}}" placeholder="Your State">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="country">Country : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square  required" id="country" name="country" value="{{ (!empty($store)?$store->country:"")}}" placeholder="Your Country">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="store_image">Store Image</label><br>
                                    <label class="custom-file">
                                        <input type="file" size="20" class="form-control-file" name="store_image" id="store_image" onchange="readURL(this);">
                                        <img id="blah" src="{{ url('doc/store_image/'.((isset($store->store_image) && $store->store_image != "")?$store->store_image:"store.png")) }}" width="50" height="50">

                                    </label>
                                    <br>
                                    <br>
                                    <br>
                                    <span id="store_images_msg" class="danger"></span>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="banner_image">Store Banner Image</label><br>
                                    <label class="custom-file">
                                        <input type="file" class="form-control-file" name="banner_image" id="banner_image" onchange="readURL1(this);">
                                        <img id="blah1" src="{{ url('doc/store_banner_image/'.((isset($store->banner_image) && $store->banner_image != "")?$store->banner_image:"store_banner.png")) }}" width="50" height="50">
                                    </label>
                                    <span id="banner_images_msg" class="danger"></span>
                                </div>
                                <div class="form-group m-form__group" >
                                    <label  for="store_status">Store Status : </label>
                                    @php
                                    $store_status = (isset($store->store_status)) ? $store->store_status :"Open";
                                    @endphp
                                    <div class="m-radio-inline">
                                        <label class="m-radio">
                                            <input type="radio" name="store_status" value="Open" {{ ($store_status=="Open")?"checked":"" }}> Open
                                            <span></span>
                                        </label>
                                        <label class="m-radio">
                                            <input type="radio" name="store_status" value="Close" {{ ($store_status=="Close")?"checked":""}} > Close
                                            <span></span>
                                        </label>
                                        <label class="m-radio">
                                            <input type="radio" name="store_status" value="Busy" {{ ($store_status=="Busy")?"checked":""}}> Busy
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h3>Store Timing</h3>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row clsTimeRow">
                                    <?php
                                    $Checked = (isset($working_time['monday']['is_fullday_open']) && $working_time['monday']['is_fullday_open'] == "Yes") ? "checked" : "";
                                    $Disabled = (isset($working_time['monday']['is_fullday_open']) && $working_time['monday']['is_fullday_open'] == "Yes") ? "disabled" : "";
                                    $OpenTime = (isset($working_time['monday']['open_time'])) ? date("h:i a", strtotime($working_time['monday']['open_time'])) : NULL;
                                    $CloseTime = (isset($working_time['monday']['closing_time'])) ? date("h:i a", strtotime($working_time['monday']['closing_time'])) : NULL;
                                    ?>
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-2">
                                        <h5>Monday</h5>
                                    </div>

                                    <div class="m-checkbox-list">
                                        <label class="m-checkbox m-checkbox--solid" >
                                            <input type="checkbox" name="time[monday][is_fullday_open]" class='clsFullday' {{$Checked}}> Full&nbsp;Day&nbsp;Open
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[monday][open_time]" class="form-control required clsOpenTime" placeholder="Open Time" value="{{$OpenTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[monday][closing_time]" class="form-control required clsCloseTime" placeholder="Close Time" value="{{$CloseTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                </div>
                                <div class="row clsTimeRow">
                                    <?php
                                    $Checked = (isset($working_time['tuesday']['is_fullday_open']) && $working_time['tuesday']['is_fullday_open'] == "Yes") ? "checked" : "";
                                    $Disabled = (isset($working_time['tuesday']['is_fullday_open']) && $working_time['tuesday']['is_fullday_open'] == "Yes") ? "disabled" : "";
                                    $OpenTime = (isset($working_time['tuesday']['open_time'])) ? date("h:i a", strtotime($working_time['tuesday']['open_time'])) : NULL;
                                    $CloseTime = (isset($working_time['tuesday']['closing_time'])) ? date("h:i a", strtotime($working_time['tuesday']['closing_time'])) : NULL;
                                    ?>
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-2">
                                        <h5>Tuesday</h5>
                                    </div>
                                    <div class="m-checkbox-list">
                                        <label class="m-checkbox m-checkbox--solid" >
                                            <input type="checkbox" name="time[tuesday][is_fullday_open]" class='clsFullday' {{$Checked}}> Full&nbsp;Day&nbsp;Open
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[tuesday][open_time]" class="form-control required clsOpenTime" placeholder="Open Time" value="{{$OpenTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[tuesday][closing_time]" class="form-control required clsCloseTime" placeholder="Close Time" value="{{$CloseTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                </div>
                                <div class="row clsTimeRow">
                                    <?php
                                    $Checked = (isset($working_time['wednesday']['is_fullday_open']) && $working_time['wednesday']['is_fullday_open'] == "Yes") ? "checked" : "";
                                    $Disabled = (isset($working_time['wednesday']['is_fullday_open']) && $working_time['wednesday']['is_fullday_open'] == "Yes") ? "disabled" : "";
                                    $OpenTime = (isset($working_time['wednesday']['open_time'])) ? date("h:i a", strtotime($working_time['wednesday']['open_time'])) : NULL;
                                    $CloseTime = (isset($working_time['wednesday']['closing_time'])) ? date("h:i a", strtotime($working_time['wednesday']['closing_time'])) : NULL;
                                    ?>
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-2">
                                        <h5>Wednesday</h5>
                                    </div>
                                    <div class="m-checkbox-list">
                                        <label class="m-checkbox m-checkbox--solid" >
                                            <input type="checkbox" name="time[wednesday][is_fullday_open]" class='clsFullday' {{$Checked}}> Full&nbsp;Day&nbsp;Open
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[wednesday][open_time]" class="form-control required clsOpenTime" placeholder="Open Time" value="{{$OpenTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[wednesday][closing_time]" class="form-control required clsCloseTime" placeholder="Close Time" value="{{$CloseTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                </div>
                                <div class="row clsTimeRow">
                                    <?php
                                    $Checked = (isset($working_time['thursday']['is_fullday_open']) && $working_time['thursday']['is_fullday_open'] == "Yes") ? "checked" : "";
                                    $Disabled = (isset($working_time['thursday']['is_fullday_open']) && $working_time['thursday']['is_fullday_open'] == "Yes") ? "disabled" : "";
                                    $OpenTime = (isset($working_time['thursday']['open_time'])) ? date("h:i a", strtotime($working_time['thursday']['open_time'])) : NULL;
                                    $CloseTime = (isset($working_time['thursday']['closing_time'])) ? date("h:i a", strtotime($working_time['thursday']['closing_time'])) : NULL;
                                    ?>
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-2">
                                        <h5>Thursday</h5>
                                    </div>
                                    <div class="m-checkbox-list">
                                        <label class="m-checkbox m-checkbox--solid" >
                                            <input type="checkbox" name="time[thursday][is_fullday_open]" class='clsFullday' {{$Checked}}> Full&nbsp;Day&nbsp;Open
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[thursday][open_time]" class="form-control required clsOpenTime" placeholder="Open Time" value="{{$OpenTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[thursday][closing_time]" class="form-control required clsCloseTime" placeholder="Close Time" value="{{$CloseTime}}" {{$Disabled}}>
                                        <br>
                                    </div>

                                </div>
                                <div class="row clsTimeRow">
                                    <?php
                                    $Checked = (isset($working_time['friday']['is_fullday_open']) && $working_time['friday']['is_fullday_open'] == "Yes") ? "checked" : "";
                                    $Disabled = (isset($working_time['friday']['is_fullday_open']) && $working_time['friday']['is_fullday_open'] == "Yes") ? "disabled" : "";
                                    $OpenTime = (isset($working_time['friday']['open_time'])) ? date("h:i a", strtotime($working_time['friday']['open_time'])) : NULL;
                                    $CloseTime = (isset($working_time['friday']['closing_time'])) ? date("h:i a", strtotime($working_time['friday']['closing_time'])) : NULL;
                                    ?>
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-2">
                                        <h5>Friday</h5>
                                    </div>
                                    <div class="m-checkbox-list">
                                        <label class="m-checkbox m-checkbox--solid" >
                                            <input type="checkbox" name="time[friday][is_fullday_open]" class='clsFullday' {{$Checked}}> Full&nbsp;Day&nbsp;Open
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[friday][open_time]" class="form-control required clsOpenTime" placeholder="Open Time" value="{{$OpenTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[friday][closing_time]" class="form-control required clsCloseTime" placeholder="Close Time" value="{{$CloseTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                </div>
                                <div class="row clsTimeRow">
                                    <?php
                                    $Checked = (isset($working_time['saturday']['is_fullday_open']) && $working_time['saturday']['is_fullday_open'] == "Yes") ? "checked" : "";
                                    $Disabled = (isset($working_time['saturday']['is_fullday_open']) && $working_time['saturday']['is_fullday_open'] == "Yes") ? "disabled" : "";
                                    $OpenTime = (isset($working_time['saturday']['open_time'])) ? date("h:i a", strtotime($working_time['saturday']['open_time'])) : NULL;
                                    $CloseTime = (isset($working_time['saturday']['closing_time'])) ? date("h:i a", strtotime($working_time['saturday']['closing_time'])) : NULL;
                                    ?>
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-2">
                                        <h5>Saturday</h5>
                                    </div>
                                    <div class="m-checkbox-list">
                                        <label class="m-checkbox m-checkbox--solid" >
                                            <input type="checkbox" name="time[saturday][is_fullday_open]" class='clsFullday' {{$Checked}}> Full&nbsp;Day&nbsp;Open
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[saturday][open_time]" class="form-control required clsOpenTime" placeholder="Open Time" value="{{$OpenTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[saturday][closing_time]" class="form-control required clsCloseTime" placeholder="Close Time" value="{{$CloseTime}}" {{$Disabled}}>
                                        <br>
                                    </div>
                                </div>
                                <div class="row clsTimeRow">
                                    <?php
                                    $Checked = (isset($working_time['sunday']['is_fullday_open']) && $working_time['sunday']['is_fullday_open'] == "Yes") ? "checked" : "";
                                    $Disabled = (isset($working_time['sunday']['is_fullday_open']) && $working_time['sunday']['is_fullday_open'] == "Yes") ? "disabled" : "";
                                    $OpenTime = (isset($working_time['sunday']['open_time'])) ? date("h:i a", strtotime($working_time['sunday']['open_time'])) : NULL;
                                    $CloseTime = (isset($working_time['sunday']['closing_time'])) ? date("h:i a", strtotime($working_time['sunday']['closing_time'])) : NULL;
                                    ?>
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-2">
                                        <h5>Sunday</h5>
                                    </div>
                                    <div class="m-checkbox-list">
                                        <label class="m-checkbox m-checkbox--solid" >
                                            <input type="checkbox" name="time[sunday][is_fullday_open]" class='clsFullday' {{$Checked}}> Full&nbsp;Day&nbsp;Open
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[sunday][open_time]" class="form-control required clsOpenTime" placeholder="Open Time" value="{{$OpenTime}}" {{ $Disabled }}>
                                        <br>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="time[sunday][closing_time]" class="form-control required clsCloseTime" placeholder="Close Time" value="{{$CloseTime}}" {{ $Disabled }}>
                                        <br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-wizard-buttons">
                            <button type="button" class="btn btn-previous">Previous</button>
                            <button type="button" id="storeNext" class="btn btn-next">Next</button>
                        </div>
                    </fieldset>
                    <!-- Form Step 2 -->

                    <!-- Form Step 3 -->
                    <fieldset>
                        <!-- Progress Bar -->
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                            </div>
                        </div>
                        <!-- Progress Bar -->
                        <div class="row" id="shipping_div">

                        @foreach($country as $counties)
                                <!--start heading-->
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group ">
                                            <label for="country_name"><b>Country</b></label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group ">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group ">
                                            <label for="charge"><b>Charge</b></label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group ">
                                            <label for="from"><b>From</b></label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group ">
                                            <label for="to"><b>To</b></label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group ">
                                            <label for="toTime"><b>Time</b></label>
                                        </div>
                                    </div>
                                    <!--end heading-->
                                    <input type="hidden" name="country_name[{{$counties->id}}][]"
                                           value="{{$counties->country_name}}">
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group ">
                                            <label for="country_name"><b>{{$counties->country_name}}</b></label>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group">
                                            <input type="checkbox" class="shipping-group country-group"
                                                   onchange="myCountry(this,'{{$counties->id}}')" name="checkCountry[]"
                                                   value="{{$counties->id}}">
                                        </div>
                                        <span id="checkCountryMsg" class="danger"></span>
                                        <br>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group ">
                                            <input type="text" class="form-control form-control-danger priceValidation charge_{{$counties->id}}"
                                                   onkeyup="addCharge('{{$counties->id}}')" name="charge[{{$counties->id}}][]"
                                                   placeholder="Charge" readonly>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group ">
                                            <input type="text" class="form-control form-control-danger priceValidation from_{{$counties->id}}"
                                                   onkeyup="addFrom('{{$counties->id}}')" name="from[{{$counties->id}}][]"
                                                   placeholder="Form" readonly>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group ">
                                            <input type="text" class="form-control form-control-danger priceValidation to_{{$counties->id}}"
                                                   onkeyup="addTo('{{$counties->id}}')" name="to[{{$counties->id}}][]"
                                                   placeholder="To" readonly>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-form__group">
                                            <select name="to_time[{{$counties->id}}][]"
                                                    onchange="dayTime(this,'{{$counties->id}}')" readonly
                                                    class="custom-select col-md-6 to_time_{{$counties->id}}">
                                                <option value="days">Day</option>
                                                <option value="hours">Hour</option>
                                            </select>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false" style="width: 100% !important;margin-left: 1%;margin-right: 1%;">
                                        <div class="m-demo__preview">
                                            <div class="row m--hide" style="margin-left: 5px !important;margin-right: 5px !important;"
                                                 id="countryDiv_{{$counties->id}}">
                                                <div class="col-md-2">
                                                    <div class="form-group m-form__group ">
                                                        <label for="country_name">Area</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group m-form__group ">

                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group m-form__group ">
                                                        <label for="charge">Charge</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group m-form__group ">
                                                        <label for="from">From</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group m-form__group ">
                                                        <label for="to">To</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group m-form__group ">
                                                        <label for="toTime">Time</label>
                                                    </div>
                                                </div>

                                                @foreach($area as $cities)
                                                    @if($cities->country_id == $counties->id)
                                                        <input type="hidden" name="city_name[{{$counties->id}}][{{$cities->id}}][]"
                                                               value="{{$cities->city_name}}">

                                                        <div class="col-md-2">
                                                            <div class="form-group m-form__group ">
                                                                <label for="country_name">{{$cities->city_name}}</label>
                                                            </div>
                                                            <br>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-form__group ">
                                                                <input type="checkbox"
                                                                       class="shipping-group checkCity_{{$counties->id}} checkCity_{{$counties->id}}_{{$cities->id}}"
                                                                       onchange="myCity(this,'{{$counties->id}}','{{$cities->id}}')"
                                                                       name="checkCity[{{$counties->id}}][]" value="{{$cities->id}}">
                                                            </div>
                                                            <br>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-form__group ">
                                                                <input type="text"
                                                                       class="form-control priceValidation form-control-danger city_charge_{{$counties->id}} city_charge_{{$counties->id}}_{{$cities->id}}"
                                                                       name="chargeCity[{{$counties->id}}][{{$cities->id}}][]" placeholder="Charge"
                                                                       readonly>
                                                            </div>
                                                            <br>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-form__group ">
                                                                <input type="text"
                                                                       class="form-control priceValidation form-control-danger city_from_{{$counties->id}} city_from_{{$counties->id}}_{{$cities->id}}"
                                                                       name="fromCity[{{$counties->id}}][{{$cities->id}}][]" placeholder="Form" readonly>
                                                            </div>
                                                            <br>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-form__group ">
                                                                <input type="text"
                                                                       class="form-control priceValidation form-control-danger city_to_{{$counties->id}} city_to_{{$counties->id}}_{{$cities->id}}"
                                                                       name="toCity[{{$counties->id}}][{{$cities->id}}][]" placeholder="To" readonly>
                                                            </div>
                                                            <br>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-form__group ">
                                                                <select name="city_to_time[{{$counties->id}}][{{$cities->id}}][]"
                                                                        class="form-control col-md-12 city_to_time_{{$counties->id}} city_to_time_{{$counties->id}}_{{$cities->id}}">
                                                                    <option value="days">Day</option>
                                                                    <option value="hours">Hour</option>
                                                                </select>
                                                            </div>
                                                            <br>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                        @endforeach

                        </div>
                        <br>
                        <div class="form-wizard-buttons">
                            <button type="button" class="btn btn-previous">Previous</button>
                            <button type="button" class="btn btn-next">Next</button>
                        </div>
                    </fieldset>
                    <!-- Form Step 3 -->

                    <!-- Form Step 4 -->
                    <fieldset>
                        <!-- Progress Bar -->
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                            </div>
                        </div>
                        <!-- Progress Bar -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    <label for="benificiary_name">Beneficiary name : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square  m-input m-input--square required" id="benificiary_name" name="benificiary_name" value="{{ (!empty($bankdetail)?$bankdetail->benificiary_name:"")}}" placeholder="Beneficiary Name">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="account_number">Account number (Max 12 Digits) : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square  m-input m-input--square required" id="account_number" name="account_number" value="{{ (!empty($bankdetail)?$bankdetail->account_number:"")}}" placeholder="Account number" minlength="12">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    <label for="bank_name">Bank name : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square  m-input m-input--square required" id="bank_name" name="bank_name" value="{{ (!empty($bankdetail)?$bankdetail->bank_name:"")}}" placeholder="Bank name">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="swift_code">Swift Code : <span class="danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square  m-input m-input--square required" id="swift_code" name="swift_code" value="{{ (!empty($bankdetail)?$bankdetail->swift_code:"")}}" placeholder="Swift Code">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-wizard-buttons">
                            <button type="button" class="btn btn-previous">Previous</button>
                            <button type="button" class="btn btn-next">Next</button>
                        </div>
                    </fieldset>
                    <!-- Form Step 4 -->

                    <!-- Form Step 5 -->
                    <fieldset>
                        <!-- Progress Bar -->
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                            </div>
                        </div>
                        <!-- Progress Bar -->
                        <div class="m-pricing-table-3 m-pricing-table-3--fixed">
                            <div class="m-pricing-table-3__items">
                                <div class="row m-row--no-padding">
                                    <?php foreach ($Plans as $key => $val) : ?>
                                        <div class="m-pricing-table-3__item col-lg-1"></div>
                                        <div class="m-pricing-table-3__item col-lg-4" style="background: aliceblue">
                                            <div class="m-pricing-table-3__wrapper">
                                                <h3 class="m-pricing-table-3__title"><?php echo $val['plan_name']; ?><br>
                                                    <span class="m-pricing-table-3__description" style="margin-top: 0.5rem;"><?php echo $val['sales_percentage']; ?> from the sales</span>
                                                </h3>
                                                <br>
                                                <span class="m-pricing-table-3__description">
                                                    <div class="m-radio-inline">
                                                        <?php if (count($val['Options'])) : ?>
                                                            <?php foreach ($val['Options'] as $v) : ?>
                                                                <label class="m-radio">
                                                                    <input type="radio" class="required" name="plan_option" value="<?php echo $v['id']; ?>" {{ (!empty($Vendor->selected_plan_option_id) && $Vendor->selected_plan_option_id==$v['id'])?"checked":"" }}> <?php echo (number_format($v['price'], 2)) . " KD for " . $v['duration']; ?>
                                                                    <span></span>
                                                                </label><br/>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="m-pricing-table-3__item col-lg-1"></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-wizard-buttons">
                            <button type="button" class="btn btn-previous">Previous</button>
                            <button type="submit" class="btn btn-submit">Submit</button>
                        </div>
                    </fieldset>
                    <!-- Form Step 5 -->
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::Portlet-->
@endsection
@section('js')
<script type="text/javascript" src="{{ url('assets/vendors/wizard/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/vendors/wizard/js/form-wizard.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/demo/default/custom/components/forms/widgets/summernote.js') }}"></script>
<script type="text/javascript" src="{{ url ('assets/demo/default/custom/components/forms/widgets/form-repeater.js')}}"></script>
<script src="{{ url ('assets/demo/default/custom/components/forms/widgets/select2.js')}}"
        type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script type="text/javascript">
    function myCity(checkbox, country_id, id) {
        var charge = $(".charge_" + country_id).val();
        var from = $(".from_" + country_id).val();
        var to = $(".to_" + country_id).val();
        var to_time = $(".to_time_" + country_id).val();
        if (checkbox.checked) {
            $(".city_charge_" + country_id + "_" + id).removeAttr('readonly');
            $(".city_from_" + country_id + "_" + id).removeAttr('readonly');
            $(".city_to_" + country_id + "_" + id).removeAttr('readonly');
            //$(".city_to_time_" + country_id + "_" + id).prop("readonly", false);

            $(".city_charge_" + country_id + "_" + id).addClass('re-charge required');
            $(".city_from_" + country_id + "_" + id).addClass('re-from required');
            $(".city_to_" + country_id + "_" + id).addClass('re-to required');
            $(".city_to_time_" + country_id + "_" + id).addClass('re-time required');


            $(".city_charge_" + country_id + "_" + id).attr('required');
            $(".city_from_" + country_id + "_" + id).attr('required');
            $(".city_to_" + country_id + "_" + id).attr('required');

            var numberOfChecked = $('.checkCity_' + country_id + ':checked').length;
            var totalCheckboxes = $('.checkCity_' + country_id).length;
            var numberNotChecked = totalCheckboxes - numberOfChecked;
            if (numberNotChecked == 0) {
                $(".charge_" + country_id).prop("readonly", false);
                $(".from_" + country_id).prop("readonly", false);
                $(".to_" + country_id).prop("readonly", false);
                $(".to_time_" + country_id).prop("readonly", false);

                $(".charge_" + country_id).addClass('re-charge required');
                $(".from_" + country_id).addClass('re-from required');
                $(".to_" + country_id).addClass('re-to required');
                $(".to_time_" + country_id).addClass('re-time required');

                $(".charge_" + country_id).attr('required');
                $(".from_" + country_id).attr('required');
                $(".to_" + country_id).attr('required');
            }

        } else {

            $(".checkCity_" + country_id + "_" + id).removeAttr('checked');

            var numberOfChecked = $('.checkCity_' + country_id + ':checked').length;
            var totalCheckboxes = $('.checkCity_' + country_id).length;
            var numberNotChecked = totalCheckboxes - numberOfChecked;
            if (numberNotChecked == totalCheckboxes) {
                $(".charge_" + country_id).prop("readonly", false);
                $(".from_" + country_id).prop("readonly", false);
                $(".to_" + country_id).prop("readonly", false);
                $(".to_time_" + country_id).prop("readonly", false);


                $(".charge_" + country_id).attr('required');
                $(".from_" + country_id).attr('required');
                $(".to_" + country_id).attr('required');

                $(".charge_" + country_id).addClass('re-charge required');
                $(".from_" + country_id).addClass('re-from required');
                $(".to_" + country_id).addClass('re-to required');
                $(".to_time_" + country_id).addClass('re-time required');
            } else {
                $(".charge_" + country_id).attr('readonly','readonly');
                $(".from_" + country_id).attr('readonly','readonly');
                $(".to_" + country_id).attr('readonly','readonly');
                $(".to_time_" + country_id).attr("readonly", "readonly");

                $(".charge_" + country_id).removeClass('re-charge required');
                $(".from_" + country_id).removeClass('re-from required');
                $(".to_" + country_id).removeClass('re-to required');
                $(".to_time_" + country_id).removeClass('re-time required');
                $(".charge_" + country_id).val('');
                $(".from_" + country_id).val('');
                $(".to_" + country_id).val('');

                $(".charge_" + country_id).removeAttr('required');
                $(".from_" + country_id).removeAttr('required');
                $(".to_" + country_id).removeAttr('required');
            }

            $(".city_charge_" + country_id + "_" + id).removeAttr('required');
            $(".city_from_" + country_id + "_" + id).removeAttr('required');
            $(".city_to_" + country_id + "_" + id).removeAttr('required');

            $(".city_charge_" + country_id + "_" + id).attr('readonly', 'readonly');
            $(".city_from_" + country_id + "_" + id).attr('readonly', 'readonly');
            $(".city_to_" + country_id + "_" + id).attr('readonly', 'readonly');
           // $(".city_to_time_" + country_id + "_" + id).prop("readonly", true);

            $(".city_charge_" + country_id + "_" + id).removeClass('re-charge required');
            $(".city_from_" + country_id + "_" + id).removeClass('re-from required');
            $(".city_to_" + country_id + "_" + id).removeClass('re-to required');
            $(".city_to_time_" + country_id + "_" + id).removeClass('re-time required');

            $(".city_charge_" + country_id + "_" + id).val('');
            $(".city_from_" + country_id + "_" + id).val('');
            $(".city_to_" + country_id + "_" + id).val('');

        }
        $(".charge_" + country_id).val(charge);
        $(".from_" + country_id).val(from);
        $(".to_" + country_id).val(to);
        $(".to_time_" + country_id).val(to_time);

    }
    function myCountry(checkbox, id) {
        if (checkbox.checked) {
            $(".charge_" + id).removeAttr('readonly');
            $(".from_" + id).removeAttr('readonly');
            $(".to_" + id).removeAttr('readonly');
            $(".to_time_" + id).prop("readonly", false);

            $("#countryDiv_" + id).removeClass('m--hide');

            $(".charge_" + id).addClass('re-charge required');
            $(".from_" + id).addClass('re-from required');
            $(".to_" + id).addClass('re-to required');
            $(".to_time_" + id).addClass('re-time required');

            $(".charge_" + id).attr('required');
            $(".from_" + id).attr('required');
            $(".to_" + id).attr('required');

        } else {


            $(".charge_" + id).attr('readonly', 'readonly');
            $(".from_" + id).attr('readonly', 'readonly');
            $(".to_" + id).attr('readonly', 'readonly');
            $(".to_time_" + id).prop("readonly", true);

            $(".charge_" + id).removeAttr('required');
            $(".from_" + id).removeAttr('required');
            $(".to_" + id).removeAttr('required');

            $("#countryDiv_" + id).addClass('m--hide');

            $(".charge_" + id).removeClass('re-charge required');
            $(".from_" + id).removeClass('re-from required');
            $(".to_" + id).removeClass('re-to required');
            $(".to_time_" + id).removeClass('re-time required');
            $(".charge_" + id).val('');
            $(".from_" + id).val('');
            $(".to_" + id).val('');
        }

    }

    function dayTime(timeday, id) {
        $(".city_to_time_" + id).val(timeday.value);
        $(".checkCity_" + id).attr("checked", "checked");
    }
    function addCharge(id) {
        $(".city_charge_" + id).val($(".charge_" + id).val());
        $(".checkCity_" + id).attr("checked", "checked");


    }
    function addFrom(id) {
        $(".city_from_" + id).val($(".from_" + id).val());
        $(".checkCity_" + id).attr("checked", "checked");

    }
    function addTo(id) {
        $(".city_to_" + id).val($(".to_" + id).val());
        $(".checkCity_" + id).attr("checked", "checked");

    }


    /*$("#shipping_create").submit(function (){
     $(".country-group").find("checkbox").each(function(){
     if ($(this).prop('checked')==true){
     alert("hi");
     }else{
     alert("hello");
     }
     });

     });*/

    $( "#shipping_create" ).submit(function( event ) {
        var numberOfChecked = $('.country-group:checked').length;
        var totalCheckboxes = $('.country-group').length;
        var numberNotChecked = totalCheckboxes - numberOfChecked;
        if (numberOfChecked == 0) {
            $("#checkCountryMsg").text("Please select at least one country.");
            return false;
        }
        //return false;

    });
    $(document).ready(function(){
        var _URL = window.URL || window.webkitURL;
        $('#store_image').change(function () {
            var file = $(this)[0].files[0];
            img = new Image();
            var imgheight = 100;
            var imgwidth = 100;

            img.src = _URL.createObjectURL(file);
            img.onload = function() {
                imgheight = this.height;
                imgwidth = this.width;
                console.log(imgheight);
                console.log(imgwidth);
                $("#height").text(imgheight);
                if(imgheight < 100 && imgwidth < 100){
                    $("#store_images_msg").text("Image height and width must be grater then 100");
                    $("#storeNext").attr('disabled','disabled');
                }else if(imgheight != imgwidth)
                {
                    $("#store_images_msg").text("Image height and width must be same.");
                    $("#storeNext").attr('disabled','disabled');
                }
                else
                {
                    $("#store_images_msg").text("");
                    $("#storeNext").removeAttr('disabled');
                }
            };
            img.onerror = function() {

                $("#store_images_msg").text("not a valid file: " + file.type);
            }
        });
    });
    /*$(document).ready(function(){

        var _URL = window.URL || window.webkitURL;

        $('#banner_image').change(function () {
            var file = $(this)[0].files[0];
            img = new Image();
            var imgheight = 0;

            img.src = _URL.createObjectURL(file);
            img.onload = function() {
                imgheight = this.height;
                $("#height").text(imgheight);
                if(imgheight != 220){
                    $("#banner_images_msg").text("Image height must be 220px");
                    $("#submit").attr('disabled','disabled');
                }else{
                    $("#submit").removeAttr('disabled');
                }
            };
            img.onerror = function() {

                $("#banner_images_msg").text("not a valid file: " + file.type);
            }

        });
    });*/
    $(document).ready(function ()
    {

        $(".custom-select").select2();
        $("#FormBusinessDetail").validate({
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                mobile_no: {
                    required: true,
                    number: true
                },
                store_name: {
                    required: true,
                },
                category_id: {
                    required: true,
                },
                address: {
                    required: true,
                },
                'category_id[]': {
                    required: true,
                },
                city: {
                    required: true,
                },
                state: {
                    required: true,
                },
                country: {
                    required: true,
                },
                benificiary_name: {
                    required: true,
                },
                account_number: {
                    required: true,
                    number: true,
                    maxlength: 12,
                    minlength: 8
                },
                bank_name: {
                    required: true,
                },
                "about_us": {
                    required: true
                },
                "checkCountry[]": {
                    require_from_group: [1, ".shipping-group"]
                },
                swift_code: {
                    required: true,
                }
            },
            messages: {
                first_name: {
                    required: "Firstname is required"
                },
                last_name: {
                    required: "Lastname is required"
                },
                email: {
                    required: "Email is required",
                    email: "Please enter valid email"
                },
                mobile_no: {
                    required: "Mobile no is required",
                    number: "Please enter valid mobile no"
                },
                store_name: {
                    required: "Store name is required"
                },
                'category_id[]': {
                    required: "Store category is required"
                },
                about_us: {
                    required: "Store about us is required"
                },
                address: {
                    required: "Address is required"
                },
                category_id: {
                    required: "Category is required"
                },
                city: {
                    required: "City is required",
                },
                state: {
                    required: "State is required",
                },
                country: {
                    required: "Country is required",
                },
                benificiary_name: {
                    required: "Beneficiary name is required",
                },
                account_number: {
                    required: "Account number is required",
                },
                bank_name: {
                    required: "Bank name is required",
                },
                "checkCountry[]": "Please select at least one country.",
                swift_code: {
                    required: "Swift code is required",
                },
                "time[monday][open_time]": {
                    required: "Open time is required",
                },
                "time[tuesday][open_time]": {
                    required: "Open time is required",
                },
                "time[wednesday][open_time]": {
                    required: "Open time is required",
                },
                "time[thursday][open_time]": {
                    required: "Open time is required",
                },
                "time[friday][open_time]": {
                    required: "Open time is required",
                },
                "time[saturday][open_time]": {
                    required: "Open time is required",
                },
                "time[sunday][open_time]": {
                    required: "Open time is required",
                },
                "time[monday][closing_time]": {
                    required: "Close time is required",
                },
                "time[tuesday][closing_time]": {
                    required: "Close time is required",
                },
                "time[wednesday][closing_time]": {
                    required: "Close time is required",
                },
                "time[thursday][closing_time]": {
                    required: "Close time is required",
                },
                "time[friday][closing_time]": {
                    required: "Close time is required",
                },
                "time[saturday][closing_time]": {
                    required: "Close time is required",
                },
                "time[sunday][closing_time]": {
                    required: "Close time is required",
                },
            }
        });

        $.validator.addMethod("chargeRequired", $.validator.methods.required,
                "Charge is required");

        $.validator.addMethod("fromRequired", $.validator.methods.required,
                "From is required");

        $.validator.addMethod("toRequired", $.validator.methods.required,
                "To is required");

        $.validator.addMethod("timeRequired", $.validator.methods.required,
                "Please select time");


        $.validator.addMethod("chargeNumber", $.validator.methods.number,
                "Charge must be number");

        $.validator.addMethod("fromNumber", $.validator.methods.required,
                "From must be number");
        $.validator.addMethod("toNumber", $.validator.methods.required,
                "To must be number");
$.validator.addMethod("fromNotZero", $.validator.methods.min,
            "From must be grater then 0");
            $.validator.addMethod("toNotZero", $.validator.methods.min,
            "To must be grater then 0");
        jQuery.validator.addClassRules("re-charge", {
            chargeRequired: true,
            chargeNumber: true,
        });
        jQuery.validator.addClassRules("re-time", {
            timeRequired: true,
        });

        jQuery.validator.addClassRules("re-from", {
            fromRequired: true,
            fromNumber: true,
             fromNotZero:true,
        });
        jQuery.validator.addClassRules("re-to", {
            toRequired: true,
            toNumber: true,
             toNotZero:true,
        });
    });
    $(document).on("change", ".clsFullday", function ()
    {
        if ($(this).prop("checked"))
        {
            $(this).closest(".clsTimeRow").find(".clsOpenTime,.clsCloseTime").attr("disabled", "disabled");
            $(this).closest(".clsTimeRow").find(".clsOpenTime,.clsCloseTime").removeClass("required");
        }
        else
        {
            $(this).closest(".clsTimeRow").find(".clsOpenTime,.clsCloseTime").removeAttr("disabled");
            $(this).closest(".clsTimeRow").find(".clsOpenTime,.clsCloseTime").addClass("required");
        }
        $("form").valid();

    });
    $(document).find(".clsOpenTime,.clsCloseTime").each(function ()
    {
        $(this).timepicker('setTime', $(this).val());
    });
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyBqp0to5BkIgE7_sJQkQl25M09mMvHAQqE"></script>
<script type="text/javascript">
                                                        google.maps.event.addDomListener(window, 'load', function ()
                                                        {
                                                            var places = new google.maps.places.Autocomplete(document.getElementById('address'));

                                                            google.maps.event.addListener(places, 'place_changed', function ()
                                                            {
                                                                var place = places.getPlace();

                                                                var address = place.address_components;
                                                                var totalcomp = address.length;
                                                                console.log(address);
                                                                console.log(totalcomp);
                                                                if ((totalcomp - 1) > 0)
                                                                {
                                                                    document.getElementById('country').value = address[totalcomp - 2]['long_name'];
                                                                }
                                                                if ((totalcomp - 2) > 0)
                                                                {
                                                                    document.getElementById('state').value = address[totalcomp - 3]['long_name'];
                                                                }
                                                                if ((totalcomp - 3) > 0)
                                                                {
                                                                    document.getElementById('city').value = address[totalcomp - 4]['long_name'];
                                                                }
                                                            });
                                                        });
</script>
@endsection