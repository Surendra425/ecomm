@extends('layouts.vendor')
@section('title') Subscription Detail @endsection
@php
$pageTitle ="Manage Subscription";
@endphp
@section('content')
<div class="m-portlet">
    @include('vendor.common.flash')
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
                            <label class="control-label" for="first_name">Name</label>
                            <p class="form-control-static">{{ $vendor->first_name." ".$vendor->last_name }}</p>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="mobile_no">Email</label>
                            <p class="form-control-static">{{ $vendor->email }}</p>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="mobile_no">Mobile</label>
                            <p class="form-control-static">{{ $vendor->mobile_no }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="plan_name">Plan Name</label>
                            <p class="form-control-static">{{ $activePlan->plan_name }}</p>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="start_date">Start Date</label>
                            <p class="form-control-static">{{ date('d/m/Y h:i A',strtotime($activePlan->start_at)) }}</p>
                        </div>
                        @if($subscription->status == "Canceled")
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="start_date">End Date</label>
                            <p class="form-control-static">{{ date('d/m/Y h:i A',strtotime($activePlan->end_at)) }}</p>
                        </div>
                        @endif
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label" for="plan_name">Subscription Status</label>
                            <p class="form-control-static">{{ $subscription->status }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            @if($subscription->status == "Active")
                            <a href="{{ route("unSubscribeVendorPlan", ['vendor_subscription' => $subscription->id ]) }}" class="btn btn-danger btn-sm btnUnsubscribe">Unsubscribe</a>
                            <a href="{{ route("updateSubscribeVendorPlan") }}" class="btn btn-info btn-sm btnChangeSubscription">Change Subscription Plan</a>
                            @else
                            <a href="{{ route("updateSubscribeVendorPlan") }}" class="btn btn-success btn-sm btnChangeSubscription">Apply New Subscription</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
