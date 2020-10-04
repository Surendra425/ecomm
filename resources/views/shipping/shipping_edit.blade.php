@extends('layouts.'.$loginUser->type)
@section('content')
@php
$pageTitle ="shippingClassEdit";
$contentTitle ='Edit Shipping Class';
@endphp
<!--begin::Portlet-->
<div class="m-portlet m-portlet--tab">
    @include($loginUser->type.'.common.flash')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Edit Shipping Class
                </h3>
            </div>
        </div>
    </div>
<?php //echo "<pre>";print_r($shipping->city);die; ?>
    <form id="shipping_edit" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="{{url(route('shippingClassUpdate' ,['shipping' => $shipping->id]))}}" novalidate>
        {{ csrf_field() }}
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    @if ( ! empty($loginUser) && $loginUser->type == 'admin')
                    <div class="form-group m-form__group">
                        <label for="vendor_id">Vendor : </label>
                        <select class="custom-select col-md-6" name="vendor_id" id="vendor_id">
                            <option selected="">Select Vendor</option>
                            @foreach ($vendor as $list) 
                            <option value="{{ $list->id }}" <?php echo ( ! empty($shipping) && ($shipping->vendor_id == $list->id)) ? 'selected' : ''; ?>>
                                {{ $list->first_name . ' ' . $list->last_name }}</option>
                            @endforeach;
                        </select>
                    </div>
                    @else
                    <input type="hidden" id="vendor_id" name="vendor_id" value="{{ $loginUser->id }}">
                    @endif
                    <div class="form-group m-form__group">
                        <label for="vendor_category_name">Shipping Class : <span class="text-danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="shipping_class" name="shipping_class" placeholder="Shipping Class" value="{{ $shipping->shipping_class }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <?php
                    $status = '';
                    $no_status = '';
                    if ($shipping->status === 'Active')
                    {
                        $status = 'checked="checked"';
                    }
                    else if ($shipping->status === 'Inactive')
                    {
                        $no_status = 'checked="checked"';
                    }
                    ?>
                    <div class="form-group m-form__group">
                        <label for="status">Status : </label>
                        <div class="m-radio-inline">
                            <label class="m-radio">
                                <input type="radio" name="status" value="Active" <?php echo $status; ?>> Active
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="status" value="Inactive" <?php echo $no_status; ?>> Inactive
                                <span></span >
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="col-md-12">
                <div class="form-group m-form__group ">
                    <div id="m_repeater_3">
                        <div class="col-lg-15">
                            <div class="row m--margin-bottom-10">
                                <div class="col-lg-3">
                                    <label for="vendor_id">Country </label>
                                    <input type="hidden" name="countryIds" id="countryIds" value="{{$shipping->country_id}}">
                                    <select class="custom-select col-md-6" name="country_id" id="country_id">
                                        <option value="{{$shipping->country_id}}" selected>
                                            {{$shipping->country_name }}</option>
                                    </select>
                                </div>

                                <?php

                                $city = '';
                                $allCity = '';
                                if ($shipping->city_name === 'All City')
                                {
                                    $allCity = 'checked="checked"';
                                }
                                else
                                {
                                    $city = 'checked="checked"';
                                }
                                ?>
                                <div class="col-lg-4">
                                    <label>City</label>
                                    <div class="m-radio-inline" id="allCity">
                                        <label class="m-radio">
                                            <input type="radio" name="city" id="all_city" value="All City" {{$allCity}}> All City
                                            <span></span>
                                        </label>
                                        <label class="m-radio">
                                            <input type="radio" name="city" id="selectedcity" value="Selected City" {{$city}}> Selective City
                                            <span></span>
                                        </label>
                                    </div>

                                </div>
                                <div class="col-lg-4">
                                    <div id="selectiveCity">
                                        <input type="hidden" id="cityIds" value="{{$shipping->city_id}}">
                                        <label>Select City</label><br>
                                        <select class="m-select2" id="m_select2_3_validate" name="city_id[]" multiple="multiple" style="width: 80%">
                                            <optgroup id="cityOption">
                                                @foreach ($cities as $list)
                                                    <option value="{{ $list->id }}"  {{ (in_array($list->id,$shipping->city)) ? 'selected' : '' }}>{{$list->city_name}}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label  class="col-form-label">Charge</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-danger" id="charge" name="charge" value="{{$shipping->shipping_charge}}" placeholder="Charge">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label  class="col-form-label">Delivery Day-1</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-danger" id="dayFrom" name="dayFrom" value="{{$shipping->delivery_day_1}}" placeholder="6">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label  class="col-form-label">Delivery Day-2</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-danger" id="dayTo" name="dayTo" value="{{$shipping->delivery_day_2}}" placeholder="9">
                                    </div>
                                </div>
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
</div>
<!--end::Portlet-->
@endsection
@section('js')
<script src="{{ url ('assets/demo/default/custom/components/forms/widgets/form-repeater.js')}}" type="text/javascript"></script>
<script src="{{ url ('assets/demo/default/custom/components/forms/widgets/select2.js')}}"  type="text/javascript"></script>
<script type="text/javascript">

$('input[type=radio]').on('change', function ()
{
    if (this.value == 'All City')
    {
        $("#selectiveCity").addClass('m--hide');
        $("option:selected").removeAttr("selected");
    }
    else if (this.value == 'Selected City')
    {
        $("#selectiveCity").removeClass('m--hide');
    }
});

$(document).ready(function ()
{

    var cityReadio = $("input[type=radio][name='city']:checked").val();
    if (cityReadio == 'All City')
    {
        $("#selectiveCity").addClass('m--hide');
        $("option:selected").removeAttr("selected");
    }
    else if (cityReadio == 'Selected City')
    {
        $("#selectiveCity").removeClass('m--hide');
    }

});
$(document).ready(function ()
{
    $("#shipping_edit").validate({
        rules: {
            shipping_class: {
                required: true,
            }
        },
        messages: {
            shipping_class: {
                required: "Shipping Class is required"
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
