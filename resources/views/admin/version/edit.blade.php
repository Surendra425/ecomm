@extends('layouts.admin')
@section('content')
@php
$pageTitle ="appVersionList";
$contentTitle ='Edit App Version';
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

                 Edit {{ ucfirst($type) }} App Version
             </h3>
         </div>
     </div>
 </div>

 <!--begin::Form-->
 
 
<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <span class="m-portlet__head-icon m--hide">
                <i class="la la-gear"></i>
            </span>
            <h3 class="m-portlet__head-text">

             {{ strtoupper($type) }}
         </h3>
     </div>
 </div>
</div>
 <form id="version_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data"
 method="post"
 action="{{ url(route('versionUpdate', ['version' => $version->id])) }}"
 novalidate>
 {{ csrf_field() }}
<div class="m-portlet__body">
   
    <input type="hidden" name="app_type" id="app_type" value="{{ ($type == 'iphone') ? 1 : 2 }}">
    <input type="hidden" name="id" id="id" value="{{ $version->id }}">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group m-form__group">
                <label for="app_version">App Version</label>
                <input type="text" class="form-control m-input m-input--square" id="app_version"
                name="app_version" value="{{ $version->app_version or '' }}" placeholder="App Version">
            </div>
            <div class="form-group m-form__group">
                <label for="app_update_msg">App Update Message</label>
                <input type="text" class="form-control m-input m-input--square" id="app_update_msg"
                name="app_update_msg" placeholder="App Update Message" value="{{ $version->app_update_msg }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group m-form__group">
                <label for="app_is_update">Is Update Type</label>

                 <select class="custom-select col-md-6" name="app_is_update" id="app_is_update">
                    <option value="0" {{ $version->app_is_update == 0 ? 'selected' : '' }}>0-Under Construction</option>
                    <option value="1" {{ $version->app_is_update == 1 ? 'selected' : '' }}>1-Live & no update available</option>
                    <option value="2" {{ $version->app_is_update == 2 ? 'selected' : '' }}>2-Optional update available</option>
                    <option value="3" {{ $version->app_is_update == 3 ? 'selected' : '' }}>3-Compulsory update available</option>
                </select>
            </div>
            <div class="form-group m-form__group">
                <label for="app_maintenance_msg">App Maintenance Message </label>
               <input type="text" class="form-control m-input m-input--square" id="app_maintenance_msg"
                name="app_maintenance_msg" value="{{ $version->app_maintenance_msg }}" placeholder="App Maintenance Message">
            </div>
        </div>
        <div class="col-md-12">
            <br>
            <div class="form-group m-form__group">
                <label for="app_url">App Url</label>
                <input type="text" class="form-control m-input m-input--square" id="app_url"
                name="app_url" value="{{ $version->app_url }}" placeholder="App Url">
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
