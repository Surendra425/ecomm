@extends('layouts.'.$loginUser->type)
@section('content')
@php
$pageTitle ="shippingClassAdd";
$contentTitle ='Create Shipping Class';
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
                    Create Shipping Class
                </h3>
            </div>
        </div>
    </div>
    <form id="shipping_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="{{url(route('shipping-class.store'))}}" novalidate>
        {{ csrf_field() }}
        <input type="hidden" id="vendor_id" name="vendor_id" value="{{ $loginUser->id }}">

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
                     @endif
                    <div class="form-group m-form__group">
                        <label for="vendor_category_name">Shipping Class : <span class="text-danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="shipping_class" name="shipping_class" placeholder="Shipping Class" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        <label for="status">Status : </label>
                        <div class="m-radio-inline">
                            <label class="m-radio">
                                <input type="radio" name="status" value="Active" checked="checked"> Active
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="status" value="Inactive" > Inactive
                                <span></span >
                            </label>
                        </div>
                    </div>
                </div>
            </div><br/>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-form__group ">
                        <div id="m_repeater_3">
                            <div data-repeater-list="shipping" class="col-lg-15">
                                <div data-repeater-item class="row m--margin-bottom-10">
                                    <div class="col-lg-3" onchange="fnchkFlow(this);">
                                        <label for="vendor_id">Country </label>
                                        <select class="custom-select col-md-6" name="country_id" id="country_id" >
                                            <option selected="selected ">Select Country</option>
                                            @foreach ($country as $list) 
                                                <option value="{{ $list->id }}" <?php echo ( ! empty($shipping) && ($shipping->country_id == $list->id)) ? 'selected' : ''; ?>>
                                                    {{ $list->country_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4">
                                        <label>City</label>
                                        <div class="m-radio-inline" id="allCity" onchange="checkRadio(this);">
                                            <label class="m-radio">
                                                <input type="radio" name="city" id="all_city" value="All City" checked="checked"> All City
                                                <span></span>
                                            </label>
                                            <label class="m-radio">
                                                <input type="radio" name="city" id="selectedcity" value="Selected City"> Selective City
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div id="selectiveCity">
                                            <label>Select City</label><br>
                                            <select class="m-select2 col-lg-12" id="m_select2_3" name="city_id" multiple="multiple" style="width: 80% !important;" disabled>
                                                 {{--<optgroup id="cityOption" name="cityOption">

                                                </optgroup>--}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label  class="col-form-label">Charge</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-danger" id="charge" name="charge" placeholder="Charge">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label  class="col-form-label">Delivery Day-1</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-danger" id="dayFrom" name="dayFrom" placeholder="6">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label  class="col-form-label">Delivery Day-2</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-danger" id="dayTo" name="dayTo" placeholder="9">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <a href="#" data-repeater-delete="" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only close-icon">
                                            <i class="la la-remove"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col">
                                    <div data-repeater-create="" class="btn btn btn-primary m-btn m-btn--icon">
                                        <span>
                                            <i class="la la-plus"></i>
                                            <span>Add</span>
                                        </span>
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

    function checkRadio(e){
        var listbox = $(e);

        if(listbox && listbox.length)
        {
            var allCity = $(listbox).find("input:radio:checked");
            var cityName = allCity.attr('name');
            var cityValue = allCity.val();
            var optionValue = cityName.replace('[city]','[city_id][]');
            //alert(cityValue);
            if(cityValue == "All City") {
               // alert("hi");
                $("select[name='"+ optionValue +"']").prop('disabled', 'disabled');
            }else if(cityValue == "Selected City"){
                $("select[name='"+ optionValue +"']").removeAttr('disabled');
            }else{
               // alert("else");
                $("select[name='"+ optionValue +"']").prop('disabled', 'disabled');
            }
        }
    }
    function fnchkFlow(e){

        //first get the jQuery version of the listbox
        var listbox = $(e);

        //make sure we have something (basic error checking)
        if(listbox && listbox.length)
        {
            //find the items you're looking at
            //var optionFlow = $(listbox).find("option[value='1']);
            //var optionStat = $(listbox).find("option[value='2']);

            //whoops - they are not "options"

            //These selectors should work.
            var allCity = $(listbox).find("select");
            var countryName = allCity.attr('name');

            var optionValue = countryName.replace('[country_id]','[city_id][]');
            console.log(optionValue);
            $("select[name='"+ countryName +"']").on('change', function (e) {
                var countryId = this.value;
                $.ajax({
                    url: "{{ url('admin/get/city/state/country_id') }}",
                    method: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        'value': countryId
                    },
                    success: function (result)
                    {
                        var obj = jQuery.parseJSON(result);
                        var html = '';

                        if (obj.length > 0)
                        {
                            $.each(obj, function (i, item)
                            {
                                html += "<option value='" + item.id + "'>" + item.city_name + "</option>";

                            });
                            $("select[name='"+ optionValue +"']").append(html);
                        }
                        else
                        {
                            html += "<option value=''>Select City</option>";
                            $("select[name='"+ optionValue +"']").append(html);
                        }
                    }});
            });
        }
    }

    $("#country_id").change(function ()
    {
        var countryId = this.value;
        $.ajax({
            url: "{{ url('admin/get/city/state/country_id') }}",
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'value': countryId
            },
            success: function (result)
            {
                var obj = jQuery.parseJSON(result);
                var html = '';

                if (obj.length > 0)
                {
                    $.each(obj, function (i, item)
                    {
                        html += "<option value='" + item.id + "'>" + item.city_name + "</option>";

                    });
                    $('#m_select2_3').append(html);
                }
                else
                {
                    html += "<option value=''>Select City</option>";
                    $('#m_select2_3').append(html);
                }
            }});
    });
$(document).ready(function ()
{
    var allTextBoxes = $( "input[id*='all_city']" );
    //alert(allTextBoxes);

});
$(document).ready(function ()
{
    $("#shipping_create").validate({
        rules: {
            shipping_class: {
                required: true,
                minlength: 2
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
