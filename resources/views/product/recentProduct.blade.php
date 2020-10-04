@extends('layouts.'.$loginUser)
@section('content')
    @php
        $pageTitle ="recentProduct";
     $contentTitle ='Recent Product';
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
                                <p class="form-control-static">{{ $product->vendor_name }}</p>
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
                                <p class="form-control-static">{{ $product->long_description }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            @foreach($productImage as $images)
                                <div class="col-md-3">
                                    <div class="form-group m-form__group">
                                        <label>Images</label>
                                        <p class="form-control-static">
                                            <img src=" {{url('doc/product_image').'/'.$images->image_url }}"
                                                 width="50" height="50">
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @php
                            $video = explode('.',$productVideo->video_url);
                        @endphp
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group m-form__group">
                                    <label>Video</label>
                                    <p class="form-control-static">
                                        <video width="320" height="240" controls>
                                            <source src="{{url('doc/product_video').'/'.$productVideo->video_url}}" type="video/{{str_replace('"','',$video[1])}}">
                                            Your browser does not support the video tag.
                                        </video>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @if($productAttrCombination[0]->combination_title)
                            @foreach($productAttrCombination as $options)
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
                                                        <p class="form-control-static">{{ $productShipping->quantity }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group m-form__group">
                                                        <label>Price</label>
                                                        <p class="form-control-static">{{ $productShipping->rate }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        @else
                            <div class="m-demo" data-code-preview="true" data-code-html="true"
                                 data-code-js="false">
                                <div class="m-demo__preview">
                                    <div class="col-md-15">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group m-form__group">
                                                    <label>Qty</label>
                                                    <p class="form-control-static">{{ $productAttrCombination[0]->quantity }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group m-form__group">
                                                    <label>Price</label>
                                                    <p class="form-control-static">{{ $productAttrCombination[0]->rate }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="m-demo" data-code-preview="true" data-code-html="true"
                             data-code-js="false">
                            <div class="m-demo__preview">
                                <div class="col-md-15">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group m-form__group">
                                                <label>Country</label>
                                                <p class="form-control-static">{{ $productShipping->country_name }}</p>
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
