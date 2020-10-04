@extends('layouts.admin')

@section('content')
    @php
        $pageTitle ="planOptionAdd";
        $contentTitle =empty($planOption) ? 'Create Plan Option' : 'Edit Plan Option';
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
                       <?php echo (empty($planOption)) ? 'Create Plan Option' : 'Edit Plan Option'; ?>
                    </h3>
                </div>
            </div>
        </div>
        <form id="planOption_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="<?php echo (!empty($planOption)) ?  (url(route('planOptionUpdate',['option'=>$planOption['id']]))) : (url(route('plan-options.store'))); ?>" novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{$planOption->id or ''}}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group" >
                            <label for="plan_name">Select Plan Name : <span class="text-danger">*</span></label>
                            <select class="custom-select col-md-6"  id="plan_id" name ="plan_id">
                                <option value="" selected="">Select Plan</option>
                                <?php foreach($plan as $list) { ?>
                                <option value="{{$list->id}}" <?php echo (!empty($planOption) && $planOption->plan_id == $list->id) ? 'selected' : ''  ?>>{{$list->plan_name}}</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group m-form__group" id="price_div">
                            <label for="sales_percentage">Price : </label>
                            <input type="text" class="form-control m-input m-input--square priceValidation" id="price" name="price" value="{{$planOption->price or ''}}" placeholder="Price" >
                            <span style="color: #f4516c" id="price_message"></span>
                        </div>
                        <div class="form-group m-form__group" >
                            <label for="sales_percentage">Description : </label>
                            <textarea class="form-control m-input m-input--square" id="description" name="description" placeholder="Description">{{$planOption->description or ''}}</textarea>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group" id="duration_div">
                            <label for="sales_percentage">Duration : </label>
                            <input type="text" class="form-control priceValidation" id="duration" name="duration" value="{{$planOption->timeDuration or ''}}" placeholder="Duration" >
                            <span style="color: #f4516c" id="duration_message"></span>
                        </div>
                        <div class="form-group m-form__group" >
                            <label for="duration_time">Select Duration Period : </label>
                            <select class="custom-select col-md-6" id="duration_time" name="duration_time">
                                <option value="month" {{(!empty($planOption) && $planOption->period == 'month') ?'selected':''}}>Month</option>
                                <option value="months" {{(!empty($planOption) && $planOption->period == 'months') ?'selected':''}}>Months</option>
                                <option value="year" {{(!empty($planOption) && $planOption->period == 'year') ?'selected':''}}>Year</option>
                            </select>
                        </div>
                        <?php  $status='';$no_status='';
                        if(!empty($planOption)){
                            if($planOption->status === 'Active'){
                        $status='checked="checked"';
                            }else if($planOption->status === 'Inactive'){
                        $no_status='checked="checked"';
                        }
                        } else {
                        $status='checked="checked"';
                        } ?>
                        <div class="form-group m-form__group" >
                            <label for="status">Status : </label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Active" <?php echo $status; ?>> Active
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Inactive" <?php echo $no_status;?>> Inactive
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
                        <div class="col-lg-9 ml-lg-auto">
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
    <script type="text/javascript">

        /*$("#price").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                $("#price_message").text("Digits Only.");
                $("#price_div").addClass('has-danger');
                return false;
            }else{
                $("#price_message").text("");
                $("#price_div").removeClass('has-danger');
            }
        });*/


        $(document).ready(function ()
        {
            $("#planOption_create").validate({
                rules: {
                    price: {
                        required: true
                    },
                    plan_id : {
                        required: true
                    },
                    duration: {
                        required: true
                    }
                },
                messages: {
                    price: {
                        required: "Price is required"
                    },
                    plan_id: {
                        required: "Please select plan. It is required."
                    },
                    duration: {
                        required: "Duration is required"
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
