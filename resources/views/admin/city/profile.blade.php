@extends('layouts.admin')
@section('content')
    @php
        $pageTitle ="city";
     $contentTitle ='Area';
    @endphp

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        City
                    </h3>
                </div>
            </div>
        </div>
        <form class="m-form m-form--fit">
        <div class="m-portlet__body">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="city_name">Area Name</label>
                        <p class="form-control-static">{{ $city->city_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="city_name">Arabic Area Name</label>
                        <p class="form-control-static">{{ $city->city_name_ar }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="country">Country Name</label>
                        <p class="form-control-static">{{ $country }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="status">Status</label>
                        <p class="form-control-static">{{ $city->status }}</p>
                    </div>
                </div>

            </div>
        </div>
        </form>
    </div>
@endsection