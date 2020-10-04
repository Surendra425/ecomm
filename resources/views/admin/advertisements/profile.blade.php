@extends('layouts.admin')
@section('content')
@php
$pageTitle ="advertisement";
$contentTitle ='Advertisement';
@endphp
<div class="m-portlet">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Advertisement
                </h3>
            </div>
        </div>
    </div>
    <form class="m-form m-form--fit">
        <div class="m-portlet__body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="advertisement_name">Advertisement Name</label>
                        <p class="form-control-static">{{ $advertisement->advertisement_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="status">Status</label>
                        <p class="form-control-static">{{ $advertisement->status }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="status">Start Date</label>
                        <p class="form-control-static">{{ date("d/m/Y",strtotime($advertisement->start_at)) }}</p>
                        
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="status">End Date</label>
                        <p class="form-control-static">{{ date("d/m/Y",strtotime($advertisement->end_at)) }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="advertisement_tagline">Tag Line</label>
                        <p class="form-control-static">{{ $advertisement->advertisement_tagline }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="display_status">Display In Slider</label>
                        <p class="form-control-static">{{ $advertisement->display_status }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="background_image">Advertisement Background Image</label><br/>
                        <img src="<?php echo ( ! empty($advertisement->background_image)) ? url('/doc/advertisement_image') . '/' . $advertisement->background_image : url('assets/app/media/img/no-images.jpeg'); ?>" width="50" height="50">
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

