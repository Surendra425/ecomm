@extends('layouts.'.$loginUser)
@section('content')
@php
$pageTitle ="shippingClass";
$contentTitle ='Shipping Class';
@endphp

<div class="col-md-12">
    @include('admin.common.flash')
    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-truck"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Shipping Class
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <form class="m-form m-form--fit">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label>Vendor Name</label>
                            <p class="form-control-static">{{ $shipping->vendor_name }}</p>
                        </div>
                        <div class="form-group m-form__group">
                            <label>Country</label>
                            <p class="form-control-static">{{ $shipping->country_name }}</p>
                        </div>
                        <div class="form-group m-form__group">
                            <label>City</label>
                            <p class="form-control-static">{{ $shipping->city_name }}</p>
                        </div>
                        <div class="form-group m-form__group">
                            <label>Status</label>
                            <p class="form-control-static">{{ $shipping->status }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label>Shipping Class</label>
                            <p class="form-control-static">{{ $shipping->shipping_class }}</p>
                        </div>
                        <div class="form-group m-form__group">
                            <label>State</label>
                            <p class="form-control-static">{{ $shipping->state_name }}</p>
                        </div>
                        <div class="form-group m-form__group">
                            <label>Charges</label>
                            <p class="form-control-static">{{ $shipping->shipping_charge }}</p>
                        </div>
                        <div class="form-group m-form__group">
                            <label>Delivery Day 2</label>
                            <p class="form-control-static">{{ $shipping->delivery_day_2 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--end::Form-->
    </div>
    <!--end::Portlet-->
</div>


@endsection