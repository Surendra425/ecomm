@extends('layouts.admin')

@section('content')
    @php
        $pageTitle ="storeAdd";
    $contentTitle =empty($store) ? 'Create Store' : 'Edit Store';
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
                        <?php echo (empty($store)) ? 'Create Store' : 'Edit Store'; ?>
                    </h3>
                </div>
            </div>
        </div>
        <form id="store_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="<?php echo (!empty($store)) ? (url(route('AdminStoreUpdate',['store'=>$store->id]))) :(url(route('stores.store'))); ?>" novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{(!empty($store)) ? $store->id : ''}}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group" >
                            <label  for="first_name">Vendor : <span class="text-danger">*</span></label>
                            <?php if(!empty($store)) { ?>
                            <input type="hidden" id="vendor_id" name="vendor_id" value="{{ !empty($store) ? $store->vendor_id : ''}}">
                            <input type="text" class="form-control m-input m-input--square" id="vendor_name" name="vendor_name" value="<?php echo $store->first_name . ' ' . $store->last_name;?>" readonly>
                            <?php } else{ ?>
                            <select class="custom-select col-md-6" name="vendor_id" id="vendor_id">
                                <option value="" selected="">Select Vendor</option>
                                <?php foreach($vendor as $list) { ?>
                                <option value="{{$list->id}}" ><?php echo $list->first_name .' '.$list->last_name; ?></option>
                                <?php } ?>
                            </select>
                            <?php } ?>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="store_name">Store Name : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="store_name" name="store_name" value="{{$store->store_name or ''}}"  placeholder="Store Name">
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label for="category_id">Category : <span class="text-danger">*</span></label>
                            <select class="form-control m-select2" name="category_id[]" id="m_select2_11" multiple>
                                @foreach($vendorCategory as $category)
                                              <optgroup label="{{ $category->category_name}}">
                                                @foreach ($category->subCategories as $list)             
                                                <option value="<?php echo $list->id; ?>" <?php echo (!empty($storeCategory) && in_array($list->id, $storeCategory)) ? 'selected' : ''; ?>>{{$list->category_name}}</option>
                                                @endforeach
                                             </optgroup>
                                        @endforeach
                            </select>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="description">Description : </label>
                            <textarea class="form-control" id="description" name="description" placeholder="Store Description">{{$store->description or ''}}</textarea>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="store_image">Store Image : </label>
                            <label class="custom-file">
                                <input type="file" size="20" class="custom-file-input" name="store_image" id="store_image" onchange="readURL(this);">
                                <span class="custom-file-control"></span>
                            </label>

                            <img id="blah" src="<?php echo (!empty($store->store_image)) ? url('/doc/store_image').'/'.$store->store_image : url('assets/app/media/img/no-images.jpeg'); ?>" width="50" height="50">
                            <span id="store_images_msg" class="danger"></span>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="banner_image">Store Banner Image : </label>
                            <label class="custom-file">
                                <input type="file" size="20" class="custom-file-input" name="banner_image" id="banner_image" onchange="readURL1(this);">
                                <span class="custom-file-control"></span>
                            </label>

                            <img id="blah1" src="<?php echo (!empty($store->banner_image)) ? url('/doc/store_banner_image').'/'.$store->banner_image	 : url('assets/app/media/img/no-images.jpeg'); ?>" width="50" height="50">
                            <span id="banner_images_msg" class="danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="address">Address : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" value="{{$store->address or ''}}" placeholder="Address" >
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="city">City : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="city" name="city" value="{{$store->city or ''}}" placeholder="City" >
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="state">State : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="state" name="state" value="{{$store->state or ''}}" placeholder="Your State" >
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="country">Country : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="country" name="country" value="{{$store->country or ''}}" placeholder="Your Country" >
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
                        <div class="form-group m-form__group" >
                            <label  for="store_status">Featured Store : </label>
                            @php
                                $store_featured = (isset($store->featured)) ? $store->featured :"Yes";
                            @endphp
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="featured" value="Yes" {{ ($store_featured=="Yes")?"checked":"" }}> Yes
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="featured" value="No" {{ ($store_featured=="No")?"checked":""}} > No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group m-form__group" >
                            <label  for="store_status">Status : </label>
                            @php
                                $status = (isset($store->status)) ? $store->status :"Yes";
                            @endphp
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Active" {{ ($status=="Active")?"checked":"" }}> Yes
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Inactive" {{ ($status=="Inactive")?"checked":""}} > No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div><br/>
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
                        <div class="col-lg-9 ml-lg-auto">
                            <button type="submit" id="submit" class="btn btn-success">Submit</button>
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
    <script src="{{ url ('assets/demo/default/custom/components/forms/widgets/select2.js')}}"  type="text/javascript"></script>
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

            $('#banner_image').change(function () {
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
                        $("#banner_images_msg").text("Image height and width must be grater then 100");
                        $("#submit").attr('disabled','disabled');
                    }else if(imgheight > imgwidth)
                    {
                        $("#banner_images_msg").text("Image height must be smaller then image width.");
                        $("#submit").attr('disabled','disabled');
                    }
                    else
                    {
                        $("#banner_images_msg").text("");
                        $("#submit").removeAttr('disabled');
                    }
                };
                img.onerror = function() {

                    $("#banner_images_msg").text("not a valid file: " + file.type);
                }
            });
        });
        $(document).ready(function ()
        {
            $("#store_create").validate({
                rules: {
                    store_name: {
                        required: true,
                        minlength: 2,
                        remote: {
                            url: baseUrl+'/check/unique/stores/store_name',
                            type: "post",
                            data: {
                                value: function() {
                                    return $( "#store_name" ).val();
                                },
                                id: function() {
                                    return $( "#id" ).val();
                                },
                            },
                        }
                    },
                    vendor_id: {
                        required: true
                    },
                    category_id: {
                        required: true
                    },
                    address: {
                        required: true,
                        minlength: 2
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
                    vendor_id: {
                        required: "Please select Vendor Name."
                    },
                    category_id: {
                        required: "Please select Category."
                    },
                    store_name: {
                        required: "Store Name is required",
                        minlength: "Store Name have atleast 2 character",
                        remote: "Store Name is already exists."
                    },
                    address: {
                        required: "Address is required",
                        minlength: "Address have atleast 2 character"
                    },
                    city: {
                        required: "City is required"
                    },
                    state: {
                        required: "State is required"
                    },
                    country: {
                        required: "Country is required"
                    }
                }

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
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyBqp0to5BkIgE7_sJQkQl25M09mMvHAQqE"></script>
    <script type="text/javascript">
        google.maps.event.addDomListener(window, 'load', initialize)
        function initialize() {
            var input = document.getElementById('city');
            var options = {
                types: ['(cities)']
            }

            var places = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();
                var address = place.formatted_address;
                var res = address.split(", ");
                if(res[2] == undefined){
                    document.getElementById('city').value = res[0];
                    document.getElementById('state').value = res[0];
                    document.getElementById('country').value = res[1];
                }else{
                    document.getElementById('city').value = res[0];
                    document.getElementById('state').value = res[1];
                    document.getElementById('country').value = res[2];
                }


            });
        }
        /*google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('city'));

            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();
                var address = place.formatted_address;
                var res = address.split(", ");
                document.getElementById('city').value = res[0];
                document.getElementById('state').value = res[1];
                document.getElementById('country').value = res[2];

            });


        });*/
    </script>
@endsection
