@extends('layouts.vendor')
@section('title') Edit Shipping Detail @endsection
@php
$pageTitle ="Manage Shipping";
@endphp
@section('css')
<style type="text/css">
    .clsTimeRow
    {
        margin: 5px;
        /*border: 1px groove #EFEFEF;*/
        vertical-align: middle;
    }
    .
    .clsTimeRow div
    {
        vertical-align: middle;        
    }
    .m-form .form-control-feedback {
        margin-top: 0.2rem;
        color: #FF0000;
    }
</style>
@endsection
@section('content')
<!--begin::Portlet-->
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
        <form id="social_media" method="post" action="{{ url(route('updateVendorShipping', ['shipping' => $shipping->id])) }}" class="m-form m-form--fit m-form--label-align-right form" novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{$shipping->id or ''}}"/>
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group m-form__group ">
                            <label for="city_name">Country</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group m-form__group ">
                            <label for="city_name">Charge</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group m-form__group ">
                            <label for="city_name">From</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group m-form__group ">
                            <label for="city_name">To</label>
                        </div>
                    </div>
                        <input type="hidden" name="country_name" value="{{$shipping->country_name}}">
                        <input type="hidden" name="country_id" value="{{$shipping->country_id}}">
                        <div class="col-md-3">
                            <div class="form-group m-form__group ">
                                <label for="city_name">{{$shipping->country_name}}</label>
                            </div>
                            <br>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-form__group ">
                                <input type="text" class="form-control form-control-danger" value="{{$shipping->charge}}" id="charge" name="charge" placeholder="Charge">
                            </div>
                            <br>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-form__group ">
                                <input type="text" class="form-control form-control-danger" value="{{$shipping->from}}" id="from" name="from" placeholder="Form">
                            </div>
                            <br>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-form__group ">
                                <input type="text" class="form-control form-control-danger" value="{{$shipping->to}}" id="to" name="to" placeholder="To">
                            </div>
                            <br>
                        </div>

                </div>
            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-12 ml-lg-auto text-center">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!--end::Portlet-->
@endsection
@section('js')
<script type="text/javascript" src="{{ url ('assets/demo/default/custom/components/forms/widgets/select2.js')}}"></script>

@endsection
