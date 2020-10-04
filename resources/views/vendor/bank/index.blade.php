@extends('layouts.vendor')
@section('title') Bank Detail @endsection
@php
$pageTitle ="Manage Bank Detail";
@endphp
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
<!--begin::Portlet-->

<div class="m-portlet">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    @yield('title')
                </h3>
            </div>
        </div>
    </div>
    @include('vendor.common.flash')
    <form id="bankDetail" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="{{ url(route('updateVendorBankDetail')) }}" novalidate>
        {{ csrf_field() }}
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        <label for="benificiary_name">Beneficiary name : <span class="danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="benificiary_name" name="benificiary_name" value="{{ $bank_detail->benificiary_name }}" placeholder="Beneficiary Name">
                    </div>  
                    <div class="form-group m-form__group">
                        <label for="account_number">Account number (Max 12 Digits) : <span class="danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="account_number" name="account_number" value="{{ $bank_detail->account_number }}" placeholder="Account number">
                    </div>                                 
                </div>
                <div class="col-md-6"> 
                    <div class="form-group m-form__group">
                        <label for="bank_name">Bank name : <span class="danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="bank_name" name="bank_name" value="{{ $bank_detail->bank_name }}" placeholder="Bank name">
                    </div>  
                    <div class="form-group m-form__group">
                        <label for="swift_code">Swift Code : <span class="danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="swift_code" name="swift_code" value="{{ $bank_detail->swift_code }}" placeholder="Swift Code">
                    </div>                                   
                </div>
            </div>
        </div>
        <div class="m-portlet__foot m-portlet__foot--fit">
            <div class="m-form__actions m-form__actions">
                <div class="row">
                    <div class="col-lg-12 ml-lg-auto text-center">
                        <button type="submit" class="btn btn-success">Update</button>
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
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#bankDetail").validate({
            rules: {
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
                swift_code: {
                    required: true,
                }
            },
            messages: {
                benificiary_name: {
                    required: "Beneficiary name is required",
                },
                account_number: {
                    required: "Account number is required",
                },
                bank_name: {
                    required: "Bank name is required",
                },
                swift_code: {
                    required: "Swift code is required",
                },
            },
            submitHandler: function (form)
            {
                var IsValid = true;
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
        var places = new google.maps.places.Autocomplete(document.getElementById('city'));

        google.maps.event.addListener(places, 'place_changed', function ()
        {
            var place = places.getPlace();
            var address = place.formatted_address;
            var res = address.split(", ");
            if (res[0])
            {
                document.getElementById('city').value = res[0];
            }
            if (res[1])
            {
                document.getElementById('state').value = res[1];
            }
            if (res[2])
            {
                document.getElementById('country').value = res[2];
            }
        });
    });
</script>
@endsection
