@extends('layouts.admin')
@section('css')
    <link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="Store";
$contentTitle = 'Store';
@endphp
<div class="m-portlet">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-truck"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Store
                </h3>
            </div>
        </div>
    </div>
    <form class="m-form m-form--fit">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="vendor_name">Vendor Name</label>
                        <p class="form-control-static">{{ $store->first_name }} {{ $store->last_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="description">Description</label>
                        <p class="form-control-static">{{ $store->description }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="city">City</label>
                        <p class="form-control-static">{{ $store->city }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="country">Country</label>
                        <p class="form-control-static">{{ $store->country }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Category</label>
                        <p class="form-control-static">{{ $storeCategory }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="profile_image">Store Logo</label>
                        <img src="<?php echo ( ! empty($store->store_image)) ? url('/doc/store_image') . '/' . $store->store_image : url('assets/app/media/img/no-images.jpeg'); ?>" width="50" height="50">
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="store_name">Store Name</label>
                        <p class="form-control-static">{{ $store->store_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="address">Address</label>
                        <p class="form-control-static">{{ $store->address }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="state">State</label>
                        <p class="form-control-static">{{ $store->state }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Store Status</label>
                        <p class="form-control-static">{{ $store->store_status }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Featured</label>
                        <p class="form-control-static">{{ $store->featured }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="banner_image">Store Banner Image</label>
                        <img src="<?php echo ( ! empty($store->banner_image)) ? url('/doc/store_banner_image') . '/' . $store->banner_image : url('assets/app/media/img/no-images.jpeg'); ?>" width="50" height="50">
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                </div>



                {{--<div class="col-md-12 row">--}}
                    {{--@foreach($vendorStoreCategories as $key => $category)--}}
                        {{--<div class="col-md-3 form-group m-form__group">--}}
                            {{--<img src="{{ url('doc/category_image/'.$category->category_image) }}" class="img-responsive" width="80px">--}}
                            {{--<span class="">{{ $category->category_name }}</span>--}}
                        {{--</div>--}}
                    {{--@endforeach--}}
                {{--</div>--}}

                <div class="col-md-12">
                    <br>
                </div>

                @foreach($working_time as $key=>$values)
                <div class="col-md-3">
                    <div class="form-group m-form__group margin-top-20" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="store_name">{{ucfirst($key)}}</label>
                    </div>
                </div>
                @if($values['is_fullday_open'] === 'Yes')
                <div class="col-md-4">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="store_name">Full Day Open</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                </div>
                @else
                <div class="col-md-4">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="store_name">Open Time</label>
                        <p class="form-control-static">{{$values['open_time']}}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="store_name">Close Time</label>
                        <p class="form-control-static">{{$values['closing_time']}}</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                </div>
                @endif
                @endforeach

            </div>
        </div>
    </form>


    @include('admin.stores.categories.categories_list')
</div>
@endsection