@extends('layouts.'.$loginUser->type)
@section('title') Deal Detail @endsection
@section('content')
@php
$pageTitle ="Manage Deals";
@endphp
<div class="m-portlet">
    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-truck"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        @yield('title')
                    </h3>
                </div>
            </div>
        </div>
        <form class="m-form m-form--fit">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="deal_name">Deal Name</label>
                            <p class="form-control-static">{{ $deal->deal_name }}</p>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="discount_type">Discount Type</label>
                            <p class="form-control-static">{{ $deal->discount_type }}</p>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="min_total_amount">Minimum Total Amount</label>
                            <p class="form-control-static">{{ $deal->min_total_amount }}</p>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="start_date">Start Date</label>
                            <p class="form-control-static">{{ $deal->start_date }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="featured">Status</label>
                            <p class="form-control-static">{{ $deal->status }}</p>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="discount_amount">Discount Amount</label>
                            <p class="form-control-static">{{ $deal->discount_amount }}</p>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="max_discount_amount">Maximum Total Amount</label>
                            <p class="form-control-static">{{ $deal->max_discount_amount }}</p>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="end_date">End Date</label>
                            <p class="form-control-static">{{ $deal->end_date }}</p>
                        </div>

                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
@endsection
