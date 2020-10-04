@extends('layouts.admin')
@section('title') State @endsection
@section('content')
    @php
        $pageTitle ="state";
    $contentTitle ='State';
    @endphp

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-truck"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        State
                    </h3>
                </div>
            </div>
        </div>
        <form class="m-form m-form--fit">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="vendor_name">State Name</label>
                         <p class="form-control-static">{{ $state->state_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Status</label>
                        <p class="form-control-static">{{ $state->status }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Country Name</label>
                     <p class="form-control-static">{{ $country }}</p>
                    </div>
                </div>

            </div>
        </div>
        </form>
    </div>
@endsection