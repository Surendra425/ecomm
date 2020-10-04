@extends('layouts.admin')
@section('content')
    @php
        $pageTitle ="collection";
     $contentTitle ='Collection';
    @endphp
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        Collection
                    </h3>
                </div>
            </div>
        </div>
        <form class="m-form m-form--fit">
        <div class="m-portlet__body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="collection_name">Collection Name</label>
                        <p class="form-control-static">{{ $collection->collection_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="collection_name">Arabic Collection Name</label>
                        <p class="form-control-static">{{ $collection->collection_name_ar }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="status">Status</label>
                        <p class="form-control-static">{{ $collection->status }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="background_image">Collection Background Image</label>
                         <img src="<?php echo (!empty($collection->background_image)) ? url('/doc/collection_image').'/'.$collection->background_image : url('assets/app/media/img/no-images.jpeg'); ?>" width="50" height="50">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="collection_tagline">Tag Line</label>
                        <p class="form-control-static">{{ $collection->collection_tagline }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label" for="display_status">Display In Slider</label>
                        <p class="form-control-static">{{ $collection->display_status }}</p>
                    </div>

                </div>

            </div>
        </div>
        </form>
    </div>
@endsection

