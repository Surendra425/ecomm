@extends('layouts.admin')
@section('title') Store Category @endsection
@section('content')
    @php
        $pageTitle ="StoreCategory";
     $contentTitle ='Store Category';
    @endphp
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-truck"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        Store Category
                    </h3>
                </div>
            </div>
        </div>
        <form class="m-form m-form--fit">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="vendor_name">Category Name</label>
                        <p class="form-control-static">{{ $vendorCategory->vendor_category_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Status</label>
                        <p class="form-control-static">{{ $vendorCategory->status }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="featured">Featured</label>
                        <p class="form-control-static">{{ $vendorCategory->featured }}</p>
                    </div>

                </div>

            </div>
        </div>
        </form>
    </div>
@endsection