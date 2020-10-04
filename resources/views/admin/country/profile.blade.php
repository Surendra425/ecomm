@extends('layouts.admin')
@section('content')
    @php
        $pageTitle ="country";
    $contentTitle ='Country';
    @endphp
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        Country
                    </h3>
                </div>
            </div>
        </div>
        <form class="m-form m-form--fit">
        <div class="m-portlet__body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="vendor_name">Country Name</label>
                        <p class="form-control-static">{{ $country->country_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="vendor_name">Arabic Country Name</label>
                        <p class="form-control-static">{{ $country->country_name_ar }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">ISO3 Code</label>
                        <p class="form-control-static">{{ $country->iso3 }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Status</label>
                        <p class="form-control-static">{{ $country->status }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Short Name</label>
                        <p class="form-control-static">{{ $country->short_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Country Code</label>
                        <p class="form-control-static">{{ $country->country_code }}</p>
                    </div>

                </div>

            </div>
        </div>
        </form>
    </div>
@endsection