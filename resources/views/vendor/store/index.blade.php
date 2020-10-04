@extends('layouts.vendor')
@section('title') Store Detail @endsection

@section('css')
<style type="text/css">
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
    .m-form .form-control-feedback {
        margin-top: 0.2rem;
        color: #FF0000;
    }
</style>
@endsection
@section('content')
@php
$pageTitle ="Manage Store";
@endphp
<!--begin::Portlet-->

<div class="m-portlet">
    @include('vendor.common.flash')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-truck"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    @yield('title')
                </h3>
            </div>
        </div>
    </div>
    <form id="store_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="{{ url(route('updateVendorStore',["store"=>$store->id])) }}" novalidate>
        {{ csrf_field() }}
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="store_name">Store Name : <span class="danger">*</span></label>
                        <input type="text" class="form-control" id="store_name" name="store_name" value="<?php echo ( isset($store->store_name)) ? $store->store_name : NULL; ?>"  placeholder="Store Name">
                    </div>
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label for="category_id">Category : <span class="danger">*</span></label>
                        <select  class="custom-select form-control m-input m-input--air required" name="category_id[]" id="category_id" multiple="multiple">
                            @foreach($vendorCategory as $category)
                                  <optgroup label="{{ $category->category_name}}">
                                    @foreach ($category->subCategories as $list)             
                                    <option value="<?php echo $list->id; ?>" <?php echo (in_array($list->id, $storeCategory)) ? 'selected' : ''; ?>>{{$list->category_name}}</option>
                                    @endforeach
                                 </optgroup>
                            @endforeach
                        </select>
                        <label id="category_id-error" class="error" for="category_id" style="display: none">Store category is required</label>
                    </div>
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="description">Description : </label>
                        <textarea class="form-control" id="description" name="description" placeholder="Store Description"><?php echo (isset($store->description)) ? $store->description : NULL; ?></textarea>
                    </div>
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="banner_image">About Us : </label>
                        <textarea class="form-control summernote" name="about_us" id="about_us">{{ (isset($store->about_us)) ? $store->about_us : NULL }}</textarea>
                        <label id="about_us-error" class="error" for="about_us" style="display: none">Store about us is required</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="address">Address : <span class="danger">*</span></label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo (isset($store->address)) ? $store->address : NULL; ?>" placeholder="Address" >
                    </div>
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="city">City : <span class="danger">*</span></label>
                        <input type="text" class="form-control" id="city" name="city" value="<?php echo (isset($store->city)) ? $store->city : NULL; ?>" placeholder="City" >
                    </div>
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="state">State : <span class="danger">*</span></label>
                        <input type="text" class="form-control" id="state" name="state" value="<?php echo (isset($store->state)) ? $store->state : NULL; ?>" placeholder="Your State" >
                    </div>
                    <div class="form-group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="country">Country : <span class="danger">*</span></label>
                        <input type="text" class="form-control" id="country" name="country" value="{{ (isset($store->country)) ? $store->country : NULL }}" placeholder="Your Country" >
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="store_image">Store Image : </label>
                        <input type="file" size="20" class="form-control-file" name="store_image" id="store_image" onchange="readURL(this);">
                        <img id="blah" src="{{ url('doc/store_image/'.((isset($store->store_image) && $store->store_image != "")?$store->store_image:"store.png")) }}" width="50" height="50">
                        <span id="store_images_msg" class="danger"></span>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="banner_image">Store Banner Image : </label>
                        <input type="file" class="form-control-file" name="banner_image" id="banner_image" onchange="readURL1(this);">
                        <img id="blah1" src="{{ url('doc/store_banner_image/'.((isset($store->banner_image) && $store->banner_image != "")?$store->banner_image:"store_banner.png")) }}" width="50" height="50">
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
           
            <br/>
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
        </div>
        <div class="m-portlet__foot m-portlet__foot--fit">
            <div class="m-form__actions m-form__actions">
                <div class="row">
                    <div class="col-lg-12 ml-lg-auto text-center">
                        <button type="submit" id="submit" class="btn btn-success">Update</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!--end::Portlet-->
@endsection
@section('js')
<script src="{{ url('assets/demo/default/custom/components/forms/widgets/summernote.js') }}" type="text/javascript"></script>
<script type="text/javascript">
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
                $("#height").text(imgheight);
                if(imgheight < 100 && imgwidth < 100){
                    $("#store_images_msg").text("Image height and width must be grater then 100");
                    $("#submit").attr('disabled','disabled');
                }else if(imgheight != imgwidth)
                {
                    $("#store_images_msg").text("Image height and width must be same.");
                    $("#submit").attr('disabled','disabled');
                }
                else
                {
                    $("#store_images_msg").text("");
                    $("#submit").removeAttr('disabled');
                }
            };
            img.onerror = function() {

                $("#store_images_msg").text("not a valid file: " + file.type);
            }
        });
    });
   /* $(document).ready(function(){

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

        });
    });*/
                            $(document).ready(function ()
                            {
                                $("select").select2();
                                $("#store_create").validate({
                                    rules: {
                                        store_name: {
                                            required: true,
                                            minlength: 2
                                        },
                                        category_id: {
                                            required: true,
                                        },
                                        "about_us": {
                                            required: true
                                        },
                                        address: {
                                            required: true
                                        },
                                        city: {
                                            required: true
                                        },
                                        state: {
                                            required: true
                                        },
                                        country: {
                                            required: true
                                        }
                                    },
                                    messages: {
                                        store_name: {
                                            required: "Store Name is required"
                                        },
                                        address: {
                                            required: "Address is required",
                                            minlength: "Last Name have atleast 2 character"
                                        },
                                        city: {
                                            required: "City is required"
                                        },
                                        'category_id[]': {
                                            required: "Store category is required"
                                        },
                                        state: {
                                            required: "State is required"
                                        },
                                        country: {
                                            required: "Country is required"
                                        },
                                        about_us: {
                                            required: "Store about us is required"
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
                                    },
                                    submitHandler: function (form)
                                    {
                                        $("#about_us-error").hide();
                                        var IsValid = true;
                                        var input = $("about_us");                                        
                                        var val = $("#about_us").val().replace(/<\/p>/gi, "\n").replace(/<br\/?>/gi, "\n").replace(/<\/?[^>]+(>|$)/g, "");
                                        if ($.trim(val) == "")
                                        {
                                            $("#about_us-error").text("Store about us is required").show();
                                            IsValid = false;
                                            return false;
                                        }
                                        form.submit();
                                    }
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
                            });
</script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyBqp0to5BkIgE7_sJQkQl25M09mMvHAQqE"></script>
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
