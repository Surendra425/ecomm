@extends('layouts.admin')
@section('content')
@php
$pageTitle ="appVersionAdd";
$contentTitle ='Create App Version';
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

                 Create App Version
             </h3>
         </div>
     </div>
 </div>

 <!--begin::Form-->
 <form id="version_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data"
 method="post"
 action="{{ url(route('versions.store'))}}"
 novalidate>
 {{ csrf_field() }}
 
<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <span class="m-portlet__head-icon m--hide">
                <i class="la la-gear"></i>
            </span>
            <h3 class="m-portlet__head-text">

             ANDROID
         </h3>
     </div>
 </div>
</div>
<div class="m-portlet__body">
    <input type="hidden" name="app_type_android" id="app_type_android">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group m-form__group">
                <label for="android_version">App Version</label>
                <input type="text" class="form-control m-input m-input--square" id="android_version"
                name="android_version" placeholder="App Version">
            </div>
            <div class="form-group m-form__group">
                <label for="android_update_msg">App Update Message</label>
                <input type="text" class="form-control m-input m-input--square" id="android_update_msg"
                name="android_update_msg" placeholder="App Update Message">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group m-form__group">
                <label for="android_update_type">Is Update Type</label>
                 <select class="custom-select col-md-6" name="android_update_type" id="android_update_type">
                    <option value="0">0-Under Construction</option>
                    <option value="1">1-Live & no update available</option>
                    <option value="2">2-Optional update available</option>
                    <option value="3">3-Compulsory update available</option>
                </select>
            </div>
            <div class="form-group m-form__group">
                <label for="android_maintenanace_msg">App Maintenance Message </label>
               <input type="text" class="form-control m-input m-input--square" id="android_maintenanace_msg"
                name="android_maintenanace_msg" placeholder="App Maintenance Message">
            </div>
        </div>
        <div class="col-md-12">
            <br>
            <div class="form-group m-form__group">
                <label for="android_app_url">App Url</label>
                <input type="text" class="form-control m-input m-input--square" id="android_app_url"
                name="android_app_url" placeholder="App Url">
            </div>
        </div>
    </div>
</div>
<div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">

                 IPHONE
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
    <input type="hidden" name="app_type_ios" id="app_type_ios">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group m-form__group">
                <label for="ios_version">App Version</label>
                <input type="text" class="form-control m-input m-input--square" id="ios_version"
                name="ios_version" placeholder="App Version">
            </div>
            <div class="form-group m-form__group">
                <label for="ios_update_msg">App Update Message</label>
                <input type="text" class="form-control m-input m-input--square" id="ios_update_msg"
                name="ios_update_msg" placeholder="App Update Message">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group m-form__group">
                <label for="ios_update_type">Is Update Type</label>
                 <select class="custom-select col-md-6" name="ios_update_type" id="ios_update_type">
                    <option value="0">0-Under Construction</option>
                    <option value="1">1-Live & no update available</option>
                    <option value="2">2-Optional update available</option>
                    <option value="3">3-Compulsory update available</option>
                </select>
            </div>
            <div class="form-group m-form__group">
                <label for="ios_maintenanace_msg">App Maintenance Message </label>
               <input type="text" class="form-control m-input m-input--square" id="ios_maintenanace_msg"
                name="ios_maintenanace_msg" placeholder="App Maintenance Message">
            </div>
        </div>
        <div class="col-md-12">
            <br>
            <div class="form-group m-form__group">
                <label for="android_app_url">App Url</label>
                <input type="text" class="form-control m-input m-input--square" id="android_app_url"
                name="ios_app_url" placeholder="App Url">
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
    $("#ios_version").change(function(){
        $("#app_type_ios").val(1);
});
     $("#android_version").change(function(){
        $("#app_type_android").val(2);
});
</script>
@endsection
