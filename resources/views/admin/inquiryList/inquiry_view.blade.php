@extends('layouts.admin')
@section('content')
@php
$pageTitle ="Inquiryview";
$contentTitle ='Inquiry view';
@endphp
<div class="m-portlet">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-truck"></i>
						</span>
                <h3 class="m-portlet__head-text">
                    Inquiry view
                </h3>
            </div>
        </div>
    </div>
    <form class="m-form m-form--fit">
    <div class="m-portlet__body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                    <label class="control-label" for="plan_name">Email</label>
                    <p class="form-control-static">{{ $inquiry->email or '' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                    <label class="control-label" for="featured">Subject</label>
                    <p class="form-control-static">{{ $inquiry->subject or '' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                    <label class="control-label" for="featured"> Message</label>
                    <p class="form-control-static">{{ $inquiry->description or '' }}</p>
                </div>
            </div>

        </div>
    </div>
    </form>
</div>
@endsection