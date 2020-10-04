@extends('layouts.'.$loginUser)
@section('content')
@php
$pageTitle ="category";
$contentTitle ='Category';
@endphp
<div class="m-portlet">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-truck"></i>
						</span>
                <h3 class="m-portlet__head-text">
                    Category
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
                    <p class="form-control-static">{{ $category->category_name }}</p>
                </div>
                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                    <label class="control-label" for="vendor_name">Arabic Category Name</label>
                    <p class="form-control-static">{{ $category->category_name_ar }}</p>
                </div>
                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                    <label class="control-label" for="profile_image">Category Image</label>
                    <img src="<?php echo (!empty($category->category_image)) ? url('/doc/category_image').'/'.$category->category_image : url('assets/app/media/img/no-images.jpeg'); ?>" width="50" height="50">
                </div>
                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                    <label class="control-label" for="featured">Status</label>
                    <p class="form-control-static">{{ $category->status }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                    <label class="control-label" for="vendor_name">Parent Category Name</label>
                    <p class="form-control-static">{{ $category->parent_category_name }}</p>
                </div>
                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                    <label class="control-label" for="vendor_name">Description</label>
                    <p class="form-control-static">{{ $category->description }}</p>
                </div>
                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                    <label class="control-label" for="featured">Featured</label>
                    <p class="form-control-static">{{ $category->featured }}</p>
                </div>

            </div>

        </div>
    </div>
    </form>
</div>
@endsection
