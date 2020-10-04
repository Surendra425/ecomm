@extends('layouts.admin')
@section('title') Plan Option @endsection
@section('content')
    @php
        $pageTitle ="planOption";
    $contentTitle ='Plan Option';
    @endphp
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-truck"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        Plan Option
                    </h3>
                </div>
            </div>
        </div>
        <form class="m-form m-form--fit">
        <div class="m-portlet__body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="plan_name">Plan Name</label>
                        <p class="form-control-static">{{ $planOption->plan_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="duration">Duration</label>
                        <p class="form-control-static">{{ $planOption->duration }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Status</label>
                        <p class="form-control-static">{{ $planOption->status }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="price">Price</label>
                        <p class="form-control-static">{{ $planOption->price }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="description">Description</label>
                        <p class="form-control-static">{{ $planOption->description }}</p>
                    </div>
                </div>

            </div>
        </div>
        </form>
    </div>
@endsection