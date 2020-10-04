@extends('layouts.'.$loginUser) @section('content')
@php
$pageTitle ="product";
$contentTitle ='Product';
@endphp

<div class="col-md-12">
    @include('admin.common.flash')
    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide"> <i class="la la-truck"></i>
                    </span>
                    <h3 class="m-portlet__head-text">Product</h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="m-form m-form--fit">
                <div class="tab-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Vendor Name</label>
                                <p class="form-control-static">{{ $product->vendorDetail->first_name." ".$product->vendorDetail->last_name }}</p>
                            </div>
                            <div class="form-group m-form__group">
                                <label>I Can Save the world Category</label>
                                <p class="form-control-static">{{ $productShopzzCategory->shopzz_category_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Product Title</label>
                                <p class="form-control-static">{{ $product->product_title }}</p>
                            </div>
                            <div class="form-group m-form__group">
                                <label>Store Category</label>
                                <p class="form-control-static">{{ $productStoreCategory->store_category_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-form__group">
                                <label>Description</label>
                                <div class="row">
                                    <div class="col-md-12" style="overflow-x: scroll">
                                        {!! $product->long_description !!}
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group m-form__group">
                                <label>Images</label>
                            </div>
                            <div class="row">
                                @foreach($product->images as $images)
                                <div class="col-md-3">
                                    <div class="form-group m-form__group">
                                        <p class="form-control-static">
                                            <img src=" {{url('doc/product_image').'/'.$images->image_url }}"
                                                 width="50" height="50">
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @php
                        $video = !empty($product->videos->video_url) ? explode('.',$product->videos->video_url) : '';
                        @endphp
                        @if(!empty($product->videos->video_url))
                        <div class="col-md-12">
                            <div class="form-group m-form__group">
                                <label>Video</label>
                                <p class="form-control-static">
                                    <video width="320" height="240" controls>
                                        <source src="{{!empty($product->videos->video_url) ? url('doc/product_video').'/'.$product->videos->video_url : ''}}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    <source data-id="{{$product->videos->id}}" data-name="{{$product->videos->video_url}}" src="{{url('doc/video/ios').'/'.$product->videos->video_url }}"
                                                            type="video/mp4">
                                         <source data-id="{{$product->videos->id}}" data-name="{{$product->videos->video_url}}" src="{{url('doc/video/web').'/'.$product->videos->video_url }}"
                                                            type="video/mp4">
                                    </video>
                                </p>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12">

                            @foreach($product->options as $options)
                                @if($options->combination_title)
                                    <div class="m-demo" data-code-preview="true" data-code-html="true"
                                         data-code-js="false">
                                        <div class="m-demo__preview">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group m-form__group">
                                                            <label>Option Name</label>
                                                            <p class="form-control-static">{{ $options->combination_title }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group m-form__group">
                                                            <label>Qty</label>
                                                            <p class="form-control-static">{{ $options->quantity }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group m-form__group">
                                                            <label>Price</label>
                                                            <p class="form-control-static">{{ $options->rate }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="m-demo" data-code-preview="true" data-code-html="true"
                                         data-code-js="false">
                                        <div class="m-demo__preview">
                                            <div class="col-md-15">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group m-form__group">
                                                            <label>Qty</label>
                                                            <p class="form-control-static">{{ $options->quantity }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group m-form__group">
                                                            <label>Price</label>
                                                            <p class="form-control-static">{{ $options->rate }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endif
                            @endforeach

                            <div class="m-demo" data-code-preview="true" data-code-html="true"
                                 data-code-js="false">
                                <div class="m-demo__preview">
                                    <div class="col-md-15">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group m-form__group">
                                                    <label>Country</label>
                                                    @foreach($product->productShipping as $item)
                                                        <p class="form-control-static">{{ $item->country_name }}</p>
                                                    @endforeach
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="m-separator m-separator--dashed"></div>
        </div>
    </div>
    <!--end::Portlet-->
</div>


@endsection
