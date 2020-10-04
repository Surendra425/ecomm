@extends('layouts.vendor')
@section('title') Update Subscription @endsection
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="collectionList";
@endphp
<div class="m-portlet m-portlet--mobile">
    @include('admin.common.flash')
    <div class="m-portlet m-portlet--tab">

        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        @yield('title')
                    </h3>
                </div>
            </div>
        </div>
        <form id="formSubscribe" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="{{ url('vendor/update-subscription') }}" novalidate>
            {{ csrf_field() }}
            <div class="m-portlet__body">
                <div class="m-pricing-table-3 m-pricing-table-3--fixed">
                    <div class="m-pricing-table-3__items">
                        <div class="row m-row--no-padding">
                            <?php foreach ($Plans as $key => $val) : ?>
                                <div class="m-pricing-table-3__item col-lg-1"></div>
                                <div class="m-pricing-table-3__item col-lg-4" style="background: aliceblue">
                                    <div class="m-pricing-table-3__wrapper">
                                        <h3 class="m-pricing-table-3__title"><?php echo $val['plan_name']; ?><br>
                                            <span class="m-pricing-table-3__description" style="margin-top: 0.5rem;"><?php echo $val['sales_percentage']; ?> % from the sales</span>
                                        </h3>
                                        <br>
                                        <span class="m-pricing-table-3__description">
                                            <div class="m-radio-inline">
                                                <?php if (count($val['Options'])) : ?>
                                                    <?php foreach ($val['Options'] as $v) : ?>
                                                        <label class="m-radio">
                                                            <input type="radio" class="required" name="plan_option" value="<?php echo $v['id']; ?>"> <?php echo (number_format($v['price'], 2)) . " KD for " . $v['duration']; ?>
                                                            <span></span>
                                                        </label><br/>
                                                    <?php endforeach; ?>	
                                                <?php endif; ?>
                                            </div>
                                        </span>
                                        <label id="plan_option-error" class="error" for="plan_option"></label>
                                    </div>
                                </div>	
                                <div class="m-pricing-table-3__item col-lg-1"></div>
                            <?php endforeach; ?>							 		
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-12 ml-lg-auto text-center">
                            <button type="submit" class="btn btn-success">Update Subscription</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#formSubscribe").validate({
            rules: {
                plan_option: {
                    required: true
                }
            },
            messages: {
                plan_option: {
                    required: "Plan option is required"
                }
            }
        });
    });
</script>

@endsection

