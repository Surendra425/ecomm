@extends('layouts.admin')

@section('content')
    @php
        $pageTitle ="planAdd";
     $contentTitle =empty($plan) ? 'Create Plan' : 'Edit Plan';
    @endphp
    <!--begin::Portlet-->
    <?php //echo "<pre>";echo $plan['id'];print_r($plan);die; ?>
    <div class="m-portlet m-portlet--tab">
        @include('admin.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        <?php echo (empty($plan)) ? 'Create Plan' : 'Edit Plan'; ?>
                    </h3>
                </div>
            </div>
        </div>
        <form id="plan_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data"
              method="post"
              action="<?php echo (!empty($plan)) ? (url(route('planUpdate', ['plan' => $plan['id']]))) : (url(route('plans.store'))); ?>"
              novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{$plan->id or ''}}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group">
                            <label for="plan_name">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="plan_name"
                                   name="plan_name" value="{{$plan->plan_name or ''}}" placeholder="Plan Name">
                        </div>
                        <div class="form-group m-form__group" id="sale_div">
                            <label for="sales_percentage">Sales (%) </label>
                            <input type="text" class="form-control m-input m-input--square priceValidation" id="sales_percentage"
                                   name="sales_percentage" value="{{$plan->sales_percentage or ''}}" placeholder="Percentage Of Sales">
                            <span style="color: #f4516c" id="sale_message"></span>
                        </div>


                        <?php  $status = '';$no_status = '';
                        if (!empty($plan)) {
                            if ($plan->status === 'Active') {
                                $status = 'checked="checked"';
                            } else if ($plan->status === 'Inactive') {
                                $no_status = 'checked="checked"';
                            }
                        } else {
                            $status = 'checked="checked"';
                        } ?>
                        <div class="form-group m-form__group">
                            <label for="status">Status : </label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Active" <?php echo $status; ?>> Active
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Inactive" <?php echo $no_status;?>>
                                    Inactive
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
        $(document).ready(function () {


            $("#plan_create").validate({
                rules: {
                    plan_name: {
                        required: true,
                        remote: {
                            url: baseUrl + '/check/unique/plans/plan_name',
                            type: "post",
                            data: {
                                value: function () {
                                    return $("#plan_name").val();
                                },
                                id: function () {
                                    return $("#id").val();
                                },
                            },

                        }
                    },
                    sales_percentage: {
                        required: true
                    }
                },
                messages: {
                    plan_name: {
                        required: "Plan Name is required",
                        remote: "Plan is already exists."
                    },
                    sales_percentage: {
                        required: "Percentage of sales is required"
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
