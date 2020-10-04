@extends('layouts.vendor')
@section('title') <?php echo (empty($deal)) ? 'Create Deal' : 'Edit Deal'; ?> @endsection
@section('content')
@php
$pageTitle ="Manage Deals";
@endphp
<!--begin::Portlet-->
<?php //$todayDate = date('Y/m/d'); ?>
<div class="m-portlet m-portlet--tab">
    @include('vendor.common.flash')
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
    <form id="deal_create" method="post" action=" <?php echo ( ! empty($deal)) ? (url(route('dealUpdate', ['deal' => $deal['id']]))) : (url(route('deals.store'))); ?>" class="m-form m-form--fit m-form--label-align-right form" novalidate>
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id" value="<?php echo ( ! empty($deal)) ? $deal->id : ''; ?>"/>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        <label for="deal_name">Deal Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="deal_name" name="deal_name" placeholder="Deal Name" value="{{ $deal->deal_name or "" }}" />
                    </div>
                    <div class="form-group m-form__group">
                        <label for="discount_type">Discount Type<span class="text-danger">*</span></label>
                        <select name="discount_type" class="form-control m-input m-input--square" id="discount_type">
                            <option value="">Select Discount Amount</option>
                            @foreach(config('constant.discountType') as $key => $discountType)
                            <option value="{{ $key }}" <?php echo ( ! empty($deal) && $deal->discount_type == $key) ? 'selected' : ''; ?>>{{ $discountType }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="discount_amount">Discount Amount<span class="text-danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square priceValidation" id="discount_amount" name="discount_amount" placeholder="Discount Amount" value="{{ $deal->discount_amount or "" }}" />
                    </div>
                    <div class="form-group m-form__group">
                        <label for="start_date">Select Date</label>
                        <div class='input-group' id='m_daterangepicker_2'>
                            <input type='text' class="form-control m-input" id="daterange" name="daterange" readonly  placeholder="Select date range"/>
                            <span class="input-group-addon"><i class="la la-calendar-check-o"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        <label for="min_total_amount">Minimum Total Amount</label>
                        <input type="text" class="form-control m-input m-input--square priceValidation" id="min_total_amount" name="min_total_amount" placeholder="Minimum Total Amount" value="{{ $deal->min_total_amount or "" }}">
                    </div>
                    <div class="form-group m-form__group">
                        <label for="max_discount_amount">Maximum Discount Amount</label>
                        <input type="text" class="form-control m-input m-input--square priceValidation" id="max_discount_amount" name="max_discount_amount" value="{{ $deal->max_discount_amount or "" }}" placeholder="Maximum Discount Amount">
                    </div>

                    <?php
                    $status = '';
                    $no_status = '';
                    if ( ! empty($deal))
                    {
                        if ($deal->status === 'Active')
                        {
                            $status = 'checked="checked"';
                        }
                        else if ($deal->status === 'Inactive')
                        {
                            $no_status = 'checked="checked"';
                        }
                    }
                    else
                    {
                        $status = 'checked="checked"';
                    }
                    ?>
                    <div class="form-group m-form__group">
                        <label for="status">Status : </label>
                        <div class="m-radio-inline">
                            <label class="m-radio">
                                <input type="radio" name="status" value="Active" {{$status}}> Active
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="status" value="Inactive" {{$no_status}}> Inactive
                                <span></span>
                            </label>
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
<script src="{{ url('assets/demo/default/custom/components/forms/widgets/bootstrap-daterangepicker.js') }}" type="text/javascript"></script>
<script type="text/javascript">

$('input[name="daterange"]').daterangepicker({
    "locale": {
        "format": "YYYY-MM-DD"
    },
<?php
if ( ! empty($deal))
{
    ?>
        "startDate": "<?php echo date('Y-m-d', strtotime($deal->start_date)); ?>",
                "endDate": "<?php echo date('Y-m-d', strtotime($deal->end_date)); ?>",
<?php } ?>
    "minDate": new Date()

});

$(document).ready(function ()
{
    $("#deal_create").validate({
        rules: {
            deal_name: {
                required: true,
                remote: {
                    url: "{{ url('admin/check/unique/deals/deal_name') }}",
                    type: "post",
                    data: {
                        value: function ()
                        {
                            return $("#deal_name").val();
                        },
                        id: function ()
                        {
                            return $("#id").val();
                        },
                    },
                }
            },
            discount_type: {
                required: true
            },
            discount_amount: {
                required: true,
                number: true
            },
            max_discount_amount: {
                number: true
            },
            min_total_amount: {
                number: true
            },

        },
        messages: {
            deal_name: {
                required: "Coupon code is required",

            },
            discount_type: {
                required: "Discount amount type is required"
            },
            discount_amount: {
                required: "Discount amount is required",
                number: 'Commission must be numric character',
            },

            max_discount_amount: {
                number: 'Maximum Discount amount must be number',
            },

            min_total_amount: {
                number: 'Maximum Discount amount must be number',
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