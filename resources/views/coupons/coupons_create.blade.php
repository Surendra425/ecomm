@extends('layouts.'.$loginUser->type)

@section('content')
@php
$pageTitle ="couponAdd";
 $contentTitle =empty($coupon) ? 'Create Coupon' : 'Edit Coupon';
@endphp
<!--begin::Portlet-->
<?php //$todayDate = date('Y/m/d'); ?>
<div class="m-portlet m-portlet--tab">
    @include($loginUser->type.'.common.flash')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    <?php echo (empty($coupon)) ? 'Create Coupon' : 'Edit Coupon'; ?>
                </h3>
            </div>
        </div>
    </div>

    <form id="coupon_create" method="post" action=" <?php echo ( ! empty($coupon)) ? (url(route('couponUpdate', ['coupon' => $coupon['id']]))) : (url(route('coupons.store'))); ?>" class="m-form m-form--fit m-form--label-align-right form" novalidate>
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id" value="<?php echo ( ! empty($coupon)) ? $coupon->id : ''; ?>"/>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        <label for="coupon_code">Coupon Code<span class="text-danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="coupon_code" name="coupon_code" placeholder="Coupon Code" value="<?php
                        if ( ! empty($coupon))
                        {
                            echo $coupon->coupon_code;
                        }
                        ?>">
                    </div>
                    <div class="form-group m-form__group">
                        <label for="discount_type">Discount Type<span class="text-danger">*</span></label>
                        <select name="discount_type" class="form-control m-input m-input--square" id="discount_type">
                            <option value="">Select Discount Amount</option>
                            @foreach(config('constant.discountType') as $key => $discountType)
                            <option value="{{ $key }}" <?php echo ( ! empty($coupon) && $coupon->discount_type == $key) ? 'selected' : ''; ?>>{{ $discountType }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="discount_amount">Discount Amount/Percentage<span class="text-danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square priceValidation" id="discount_amount" name="discount_amount" placeholder="Discount Amount/Percentage" value="<?php
                        if ( ! empty($coupon))
                        {
                            echo $coupon->discount_amount;
                        }
                        ?>">
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
                        <label for="min_total_amount">Minimum Order Amount</label>
                        <input type="text" class="form-control m-input m-input--square priceValidation" id="min_total_amount" name="min_total_amount" placeholder="Minimum Order Amount" value="<?php
                        if ( ! empty($coupon))
                        {
                            echo $coupon->min_total_amount;
                        }
                        ?>">
                        <label id="min_total_amount-error" class="error" for="min_total_amount"></label>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="max_discount_amount">Maximum Discount Amount</label>
                        <input type="text" class="form-control m-input m-input--square priceValidation" id="max_discount_amount" name="max_discount_amount" value="<?php
                        if ( ! empty($coupon))
                        {
                            echo $coupon->max_discount_amount;
                        }
                        ?>" placeholder="Maximum Discount Amount">
                    </div>

                    <?php
                    $status = '';
                    $no_status = '';
                    if ( ! empty($coupon))
                    {
                        if ($coupon->status === 'Active')
                        {
                            $status = 'checked="checked"';
                        }
                        else if ($coupon->status === 'Inactive')
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
                                <input type="radio" name="status" value="Active" <?php echo $status; ?>> Active
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="status" value="Inactive" <?php echo $no_status; ?>> Inactive
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
if ( ! empty($coupon))
{
    ?>
        "startDate": "<?php echo date('Y-m-d', strtotime($coupon->start_date)); ?>",
                "endDate": "<?php echo date('Y-m-d', strtotime($coupon->end_date)); ?>",
<?php } ?>
    "minDate": new Date()

});

$(document).ready(function ()
{
    $("#coupon_create").validate({
        rules: {
            coupon_code: {
                required: true,
                remote: {
                    url: "{{ url('admin/check/unique/coupons/coupon_code') }}",
                    type: "post",
                    data: {
                        value: function ()
                        {
                            return $("#coupon_code").val();
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
                number: true,
                min: function(element) {

                    if($('#discount_type').val() == 'fixed_amount')
                    {
                        if($("#discount_amount").val() <= parseInt(element.value)){
                            return 0;
                        }
                        return $("#discount_amount").val();
                    }
                    return $("#discount_amount").val();

                }
            },

        },
        messages: {
            coupon_code: {
                required: "Coupon code is required",
                remote: "Coupon code is already taken."

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
                min: 'Minimum order amount must be greater than discount amount'
            }
        },
        submitHandler: function (form)
        {
            if(checkMinOrder()){
                form.submit();
            }

        }
    });
});


function checkMinOrder() {

    if( $("#discount_amount").val() <= parseInt($('#min_total_amount').val()) ){
        return 1;
    }
    $('#min_total_amount-error').text('Minimum order amount must be greater than discount amount');
    $('#min_total_amount-error').show();
    return 0;
}
/*const dotNumberRegex = /[^\d.]|\.(?=.*\.)/g;
const subst=``;
$('#discount_amount').keypress(function(){
    const str=this.value;

    const result = str.replace(dotNumberRegex, subst);
    this.value=result;

});
$('#min_total_amount').keypress(function(){
    const str=this.value;
    const result = str.replace(dotNumberRegex, subst);
    this.value=result;

});
$('#max_discount_amount').keypress(function(){
    const str=this.value;
    const result = str.replace(dotNumberRegex, subst);
    this.value=result;

});*/
</script>
@endsection