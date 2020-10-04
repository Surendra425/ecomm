@extends('layouts.'.$loginUser)
@section('css')
    <link rel="stylesheet" href="{{ url('assets/vendors/wizard/css1/style.css') }}">
@endsection
@section('content')
    @php
        $pageTitle ="productAdd";
    @endphp
    <div class="m-portlet">
    @include($loginUser.'.common.flash')
    <!--begin::Portlet-->
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
          Nikita@123456
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        Edit Product
                    </h3>
                </div>
            </div>
        </div>
        <div class="form-wizard form-wizard-shadow form-header-classic form-body-classic">

            <form id="store_create" class="m-form m-form--fit m-form--label-align-right form"
                  enctype="multipart/form-data"
                  method="post" action="{{url(route('products.store'))}}" novalidate>
            {{ csrf_field() }}

            <input type="hidden" name="vendorId" id="vendorId" value="{{$product->vendor_id}}">
            <!-- Form progress -->
                <div class="form-wizard-steps form-wizard-tolal-steps-5">
                    <div class="form-wizard-progress">
                        <div class="form-wizard-progress-line" data-now-value="12.25" data-number-of-steps="4"
                             style="width: 12.25%;"></div>
                    </div>
                    <!-- Step 1 -->
                    <div class="form-wizard-step active">
                        <div class="form-wizard-step-icon"><i class="la la-cube" aria-hidden="true"></i></div>
                        <p>Product</p>
                    </div>
                    <!-- Step 1 -->

                    <!-- Step 2 -->
                    <div class="form-wizard-step">
                        <div class="form-wizard-step-icon"><i class="la la-camera-retro" aria-hidden="true"></i></div>
                        <p>Image & Video</p>
                    </div>
                    <!-- Step 2 -->

                    <!-- Step 3 -->
                    <div class="form-wizard-step">
                        <div class="form-wizard-step-icon"><i class="la la-cubes" aria-hidden="true"></i></div>
                        <p>Category & Attribute</p>
                    </div>
                    <!-- Step 3 -->

                    <!-- Step 4 -->
                    <div class="form-wizard-step">
                        <div class="form-wizard-step-icon"><i class="la la-truck" aria-hidden="true"></i></div>
                        <p>Shipping Details</p>
                    </div>
                    <!-- Step 4 -->

                    <!-- Step 5 -->
                    <div class="form-wizard-step">
                        <div class="form-wizard-step-icon"><i class="la la-file-text-o" aria-hidden="true"></i></div>
                        <p>Return Policy</p>
                    </div>
                    <!-- Step 5 -->
                </div>
                <!-- Form progress -->


                <!-- Form Step 1 -->
                <fieldset class="m-portlet__body" style="display: block;">
                    <!-- Progress Bar -->
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="25"
                             aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <!-- <div class="m-portlet__head">
                         <h4 class="m-portlet__head-caption">Step 1 of 5 - Product Information</h4>
                     </div>-->
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 25px;">
                            <div class="form-group m-form__group">
                                <label for="first_name">Vendor : <span class="text-danger">*</span></label>
                                <select class="custom-select col-md-6" name="vendor_id" id="vendor_id">
                                    <option value="" selected>Select Vendor</option>
                                    <?php foreach($vendor as $list) { ?>
                                    <option value="<?php echo $list->id; ?>" {{($product->vendor_id == $list->id) ? 'selected':''}}><?php echo $list->first_name . ' ' . $list->last_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Title<span class="text-danger">*</span></label>
                                <input type="text" class="form-control m-input m-input--square" id="product_title"
                                       name="product_title" value="{{$product->product_title}}"
                                       placeholder="Product Title">
                            </div>
                            <div class="form-group m-form__group">
                                <label>Brand Name<span class="text-danger">*</span></label>
                                <input class="form-control m-input m-input--square" type="text" id="brand_name"
                                       name="brand_name" value="{{$product->brand_name}}" placeholder="Brand Name">
                            </div>
                            <div class="form-group m-form__group">
                                <label>Long Description<span class="text-danger">*</span></label>
                            <textarea class="form-control m-input m-input--square" id="long_description"
                                      name="long_description"
                                      placeholder="Product Long Description">{{$product->long_description}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Keyword</label>
                                <div class="m-typeahead">
                                    <select class="form-control m-select2" id="m_select2_11" name="keyword[]" multiple
                                            name="param">

                                        @foreach($keyword as $key)
                                            <option value="{{$key->keyword_id}}" selected>{{$key->keyword}}</option>
                                        @endforeach
                                        <optgroup id="keyword_search">

                                        </optgroup>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label>Arabic Long Description </label>
                                <textarea class="form-control m-input m-input--square" id="long_description_arabic"
                                          name="long_description_arabic"
                                          placeholder="Product Long Description in Arabic">{{$product->long_description_arabic}}</textarea>
                            </div>
                        </div>
                    </div><br>
                    <div class="form-wizard-buttons">
                        <button type="button" class="btn btn-next">Next</button>
                    </div>
                </fieldset>
                <!-- Form Step 1 -->

                <!-- Form Step 2 -->
                <fieldset>
                    <!-- Progress Bar -->
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40"
                             aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Product Image</label>
                                <div class="col-lg-12 col-md-9 col-sm-12">
                                    <div class="m-dropzone dropzone m-dropzone--success" action="#" id="productImage">
                                        <div class="m-dropzone__msg dz-message needsclick">
                                            <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                                            <span class="m-dropzone__msg-desc">Only image are allowed for upload</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m--hide">
                            <textarea name="productImages" id="productImages"></textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Product Video</label>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="m-dropzone dropzone m-dropzone--success"
                                         action="{{url(route('uploadVideo'))}}" id="video">
                                        <div class="m-dropzone__msg dz-message needsclick">
                                            <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                                            <span class="m-dropzone__msg-desc">Only video are allowed for upload</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       @if(!empty($productImage) && count($productImage) > 0)
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Product Images</label>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    @foreach($productImage as $images)
                                        <div class="img-wrap">
                                            <span class="close">&times;</span>
                                            <img src=" {{url('doc/product_image').'/'.$images->image_url }}"
                                                 width="100" height="80" data-id="{{$images->id}}" data-name="{{$images->image_url}}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                      @endif
                        @if(!empty($productVideo) && count($productVideo) > 0)
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Product Video</label>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    @foreach($productVideo as $videos)
                                        <div class="img-wrap">
                                            <span class="close">&times;</span>
                                            <video width="100" controls>
                                                <source src="{{url('doc/product_video').'/'.$videos->video_url }}" type="video/mp4">
                                            </video>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="m--hide">
                            <textarea id="productVideo" name="productVideo"></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="form-wizard-buttons">
                        <button type="button" class="btn btn-previous">Previous</button>
                        <button type="button" class="btn btn-next">Next</button>
                    </div>
                </fieldset>
                <!-- Form Step 2 -->

                <!-- Form Step 3 -->
                <fieldset>
                    <!-- Progress Bar -->
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="60"
                             aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="form-group m-form__group">
                        <label for="featured">Category : </label>
                        <select class="custom-select col-md-4" name="category_id" id="category_id">
                            @foreach($category as $list)
                             <option value="{{$list->id}}" {{($list->id==$productCategoryAttr[0]->category_id) ? 'selected' : ''}}>{{$list->category_name}}</option>
                             @endforeach
                        </select>
                    </div>
                    @php $attrList = [];
                    @endphp
                    @foreach($productCategoryAttr as $attr)
                        @php $attrList[] = $attr->attr_id;
                        @endphp
                        @endforeach
                    <div class="m-form__group form-group">
                        <label for="">Attribute</label>
                        <div class="m-checkbox-inline">
                            <select class="m-select2 col-md-12" id="m_select2_3" name="attr_list[]" multiple="multiple">
                                @foreach($attribute as $option)
                                    <option value="{{$option->id}}" {{(in_array($option->id,$attrList)) ? 'selected' : ''}}>{{$option->attribute_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" id="attrValue">
                        @foreach($productCategoryAttr as $attri)
                        <div class="col-md-12" id="div{{$attri->attr_id}}">
                            <div class="m-form__group form-group">
                               <label>{{$attri->attribute_name}} Options</label>
                               <input type="hidden" name="{{$attri->attribute_name}}[]" id="{{$attri->attribute_name}}" value="{{$attri->attr_id}}">
                               </div>
                           <div class="row" id="attr_div{{$attri->attr_id}}">
                               @foreach($productAttr as $value)
                                    <?php if($attri->attribute_name == $value->attribute_name){ ?>
                                <div class="col-md-3" id="{{$attri->attr_id}}div">
                                   <div class="m-form__group form-group">
                                        <input type="text" class="form-control m-input m-input--square" id="{{$attri->attribute_name}}Value" value="{{$value->attribute_value}}" name="{{$attri->attribute_name}}Value[]">
                                        </div>
                                    </div>
                                   <?php } ?>
                               @endforeach
                                </div>

                            <div class="m-form__group form-group">
                                <button class="btn btn btn-primary m-btn m-btn--icon" type="button" id="addbutton" onclick="addDiv('{{$attri->attr_id}}','{{$attri->attribute_name}}');"><i class="la la-plus"></i></button>
                                </div>
                            </div>
                    @endforeach
                    </div>

                    <div class="m-form__group form-group">
                        <label for="">Use Combination for Qty?</label>
                        <div class="m-radio-inline">
                            <label class="m-radio">
                                <input type="radio" name="combination" id="combinationYes" {{(!empty($productAttrCombination[0]->combination_title)) ? 'checked' : ''}} value="Yes"> Yes
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="combination" id="combinationNo" {{(!empty($productAttrCombination[0]->combination_title)) ? '' : 'checked'}} value="No"> No
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <!--begin::Preview-->
                    <div id="combinationDiv">
                        @if(!empty($productAttrCombination[0]->combination_title))
                        @foreach($productAttrCombination as $combi)
                            <div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                                <div class="m-demo__preview">
                                    <h4>{{$combi->combination_title}}</h4>
                                   <input type="hidden" value="' + {{$combi->combination_title}} + '" id="combination_name" name="combination_name[]">
                                    <div class="col-md-15">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group m-form__group">
                                                   <input type="text" class="form-control m-input m-input--square" value="{{$combi->quantity}}" id="qty" name="qty[]" placeholder="Qty">
                                                    </div>
                                                </div>
                                            <div class="col-md-3">
                                               <div class="form-group m-form__group">
                                                   <input type="text" class="form-control m-input m-input--square" value="{{$combi->rate}}" id="prices" name="prices[]" placeholder="Price">
                                                    </div>
                                               </div>
                                            <div class="col-md-3">
                                                <div class="form-group m-form__group">
                                                    <input type="text" class="form-control m-input m-input--square" value="{{$combi->discount_percentage}}" id="discount" name="discount[]" placeholder="Discount (%)">
                                                   </div>
                                                </div>

                                            <div class="col-md-3">
                                                <div class="form-group m-form__group">
                                                   <div class="col-lg-12 col-md-12 col-sm-12">

                                                        <!--<div class="m-dropzone dropzone m-dropzone--success" name="combination_image" id="m-dropzone-three">
                                                            <div class="m-dropzone__msg dz-message needsclick">
                                                                <h5 class="m-dropzone__msg-title">Product Image.</h3>
                                                                    <div class="m--hide">
                                                                        <textarea id="productCombinationImage[]"></textarea>
                                                                        </div>
                                                                    </div>
                                                            </div>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                        @endif
                    </div>
                    <!--end::Preview-->
                    <div id="defultPrice" class="m--hide">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group m-form__group">
                                    <label>Quantity</label>
                                    <input type="text" class="form-control m-input m-input--square" id="qty_default"
                                           name="qty_default" placeholder="Quantity">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group m-form__group">
                                    <label>Price<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square" id="price_default"
                                           name="price_default" placeholder="Price">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group m-form__group">
                                    <label>Discount&nbsp;(%)<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square"
                                           id="discount_default" name="discount_default" placeholder="Discount">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-wizard-buttons">
                        <button type="button" class="btn btn-previous">Previous</button>
                        <button type="button" class="btn btn-next">Next</button>
                    </div>
                </fieldset>
                <!-- Form Step 3 -->

                <!-- Form Step 4 -->
                <fieldset>
                    <!-- Progress Bar -->
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="80"
                             aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="form-group m-form__group">
                        <label>Shipping Class<span class="text-danger">*</span></label>
                        <select name="shipping_class" id="shipping_class" class="custom-select col-md-6">
                            @foreach($shipping as $ship)
                            <option value="{{$ship->id}}" {{($ship->id == $productShipping->shipping_id) ? 'selected' : ''}}> {{$ship->shipping_class}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div id="shippingDiv" class="col-md-12">
                        <!--<div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                            <div class="m-demo__preview">
                                <div class="row">
                                    <div class="col-md-4"></div>
                                        <div class="form-group m-form__group">
                                            <label>Country : </label>
                                            <input type="hidden" id="country_id" name="country_id" value="' + item.country_id + '">
                                            <input type="hidden" id="country_name" name="country_name" value="' + item.country_name + '">
                                            <label>' + item.country_name + '</label>
                                            </div>
                                        </div>
                                    <div class="col-md-4">
                                        <div class="form-group m-form__group">
                                            <label>State : </label>
                                            <input type="hidden" id="state_id" name="state_id" value="' + item.state_id + '">
                                            <input type="hidden" id="state_name" name="state_name" value="' + stateName + '">
                                            <label>' + stateName + '</label>
                                            </div>
                                        </div>
                                    <div class="col-md-4">
                                        <div class="form-group m-form__group">
                                            <label>City : </label>
                                            <input type="hidden" id="city_id" name="city_id" value="' + item.city_id + '">
                                            <input type="hidden" id="city_name" name="city_name" value="' + cityName + '">
                                            <label>' + cityName + '</label>
                                            </div>
                                        </div>
                                    <div class="col-md-4">
                                        <div class="form-group m-form__group">
                                            <label>Charges</label>
                                            <input type="text" class="form-control m-input m-input--square" id="charge" name="charge" placeholder="Charges" value="' + item.shipping_charge + '">
                                            </div>
                                        </div>
                                    <div class="col-md-4">
                                        <div class="form-group m-form__group">
                                            <label>Delivery&nbsp;Day&nbsp;1</label>
                                            <input type="text" class="form-control m-input m-input--square" id="day_1" name="day_1" placeholder="Delivery Day 1" value="' + item.delivery_day_1 + '">
                                            </div>
                                        </div>
                                    <div class="col-md-4">
                                       <div class="form-group m-form__group">
                                            <label>Delivery&nbsp;Day&nbsp;2</label>
                                            <input type="text" class="form-control m-input m-input--square" id="day_2" name="day_2" placeholder="Delivery Day 2" value="' + item.delivery_day_2 + '">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                         -->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>weight</label>
                                <input type="text" class="form-control m-input m-input--square" id="weight"
                                       name="weight" placeholder="weight">
                            </div>
                            <div class="form-group m-form__group">
                                <label>Height</label>
                                <input type="text" class="form-control m-input m-input--square" id="height"
                                       name="height" placeholder="Height">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Length</label>
                                <input type="text" class="form-control m-input m-input--square" id="length"
                                       name="length" placeholder="Length">
                            </div>
                            <div class="form-group m-form__group">
                                <label>Width</label>
                                <input type="text" class="form-control m-input m-input--square" id="width" name="width"
                                       placeholder="Width">
                            </div>
                        </div>
                    </div><br>
                    <div class="form-wizard-buttons">
                        <button type="button" class="btn btn-previous">Previous</button>
                        <button type="button" class="btn btn-next">Next</button>
                    </div>
                </fieldset>
                <!-- Form Step 4 -->

                <!-- Form Step 5 -->
                <fieldset>
                    <!-- Progress Bar -->
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="form-group m-form__group">
                        <label>Return Policy<span class="text-danger">*</span></label>
                        <select name="return" id="return" class="custom-select col-md-6">
                            <option value="No Return">No return</option>
                            <option value="15 days return">15 days return</option>
                            <option value="30 days return">30 days return</option>
                        </select>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m-checkbox-inline">
                            <label class="m-checkbox">
                                <input id="use_exchange" name="use_exchange" type="checkbox" checked=""> Use same rules
                                for exchanges
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group m-form__group m--hide" id="excnageDiv">
                        <label>Exchange Policy<span class="text-danger">*</span></label>
                        <select class="custom-select col-md-6" name="exchange" id="exchange">
                            <option value="No Return">No return</option>
                            <option value="15 days return">15 days return</option>
                            <option value="30 days return">30 days return</option>
                        </select>
                    </div>
                    <div class="form-group m-form__group">
                        <label>Description</label>
                        <textarea id="return_exchange_policy_description" name="return_exchange_policy_description"
                                  class="form-control">This item is non-returnable and non-exchangeable.</textarea>
                    </div>
                    <div class="form-group m-form__group">
                        <button type="button" id="descReset" class="btn btn-previous">Reset</button>
                    </div><br>
                    <div class="form-wizard-buttons">
                        <button type="button" class="btn btn-previous">Previous</button>
                        <button type="submit" class="btn btn-submit">Submit</button>
                    </div>
                </fieldset>
                <!-- Form Step 5 -->

            </form>

        </div>
        <!-- Form Wizard -->


    </div>
@endsection
@section('js')

    <script src="{{ url('assets/vendors/wizard/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/vendors/wizard/js/form-wizard.js') }}"></script>
    <script src="{{ url ('assets/demo/default/custom/components/forms/widgets/form-repeater.js')}}"
            type="text/javascript"></script>
    <script src="{{ url ('assets/demo/default/custom/components/forms/widgets/select2.js')}}"
            type="text/javascript"></script>
    <script src="{{url('assets/demo/default/custom/components/forms/widgets/dropzone.js')}}"
            type="text/javascript"></script>
    <script type="text/javascript">

        $("#productImage").dropzone(
                {
                    url: "{{url(route('uploadImage'))}}",
                    acceptedFiles: "image/*",
                    maxFiles: 5, // Maximum Number of Files
                    maxFilesize: 2, // MB
                    addRemoveLinks: true,
                    success: function (file, response) {
                        $("#productImages").append(response);
                        console.log(response);
                        // alert(response);
                    }
                }
        );
        $("#video").dropzone(
                {
                    url: "{{url(route('uploadVideo'))}}",
                    acceptedFiles: ".mp4,.mkv,.avi",
                    maxFiles: 1, // Maximum Number of Files
                    //maxFilesize: 2, // MB
                    addRemoveLinks: true,
                    success: function (file, response) {
                        $("#productVideo").append(response);
                        console.log(response);
                        // alert(response);
                    }
                }
        );

        //image reomve
        $('.img-wrap .close').on('click', function() {
            var id = $(this).closest('.img-wrap').find('img').data('id');
            var imageUrl = $(this).closest('.img-wrap').find('img').data('name');
            alert(imageUrl);
            $.ajax({
                url: baseUrl + '/delete/product_images/id',
                method: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'value': id,
                    'name' : imageUrl,
                    'path' : 'doc/product_image'
                }
            });
        });
        function next(page_id) {

            var current_page = page_id - 1;
            $("#sf" + page_id).css("display", "block");
            $("#sf" + current_page).css("display", "none");
        }

        function back(page_id) {
            var current_page = page_id + 1;
            $("#sf" + page_id).css("display", "block");
            $("#sf" + current_page).css("display", "none");
        }
        var getCombinations = function (allOptionsArray, combination) {
            if (allOptionsArray.length > 0) {
                for (var i = 0; i < allOptionsArray[0].length; i++) {
                    var tmp = allOptionsArray.slice(0);
                    //alert(tmp);
                    combination.codes[combination.counter] = allOptionsArray[0][i];
                    tmp.shift();
                    combination.counter++;
                    getCombinations(tmp, combination);
                }
            } else {
                var combi = combination.codes.slice(0);
                combination.result.push(combi);
            }
            combination.counter--;
        }

        $('input[type=radio][name=combination]').on('change', function () {
            if ($(this).val() == 'Yes') {
                $("#combinationDiv").removeClass("m--hide");
                var data = $('#m_select2_3').select2('data');
                var tem_color = [];
                var attr = [];
                var attr_val = [];
                $.each(data, function (i, item) {
                    name = item.text.replace(' ', '_');
                    $('#div' + item.id + ' input:text').each(function (index) {
                        attr_val.push($(this).val());
                    });
                    attr[item.id] = (attr_val);
                    attr_val = [];
                });
                attr = $.grep(attr, function (n) {
                    return (n);
                });
                var htmlText = '';
                var combination = {codes: [], result: [], counter: 0};
                var productName = '';


                getCombinations(attr, combination);
                $.each(combination.result, function (i, item) {
                    productName = item.join('-');

                    productName = item.join('-');
                    htmlText += '<div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">';
                    htmlText += '<div class="m-demo__preview">';
                    htmlText += '<h4>' + productName + '</h4>';
                    htmlText += '<input type="hidden" value="' + productName + '" id="combination_name" name="combination_name[]">';
                    htmlText += '<div class="col-md-15">';
                    htmlText += '<div class="row">';
                    htmlText += '<div class="col-md-3">';
                    htmlText += '<div class="form-group m-form__group">';
                    htmlText += '<input type="text" class="form-control m-input m-input--square" id="qty" name="qty[]" placeholder="Qty">';
                    htmlText += '</div>';
                    htmlText += '</div>';
                    htmlText += '<div class="col-md-3">';
                    htmlText += '<div class="form-group m-form__group">';
                    htmlText += '<input type="text" class="form-control m-input m-input--square" id="prices" name="prices[]" placeholder="Price">';
                    htmlText += '</div>';
                    htmlText += '</div>';
                    htmlText += '<div class="col-md-3">';
                    htmlText += '<div class="form-group m-form__group">';
                    htmlText += '<input type="text" class="form-control m-input m-input--square" id="discount" name="discount[]" placeholder="Discount (%)">';
                    htmlText += '</div>';
                    htmlText += '</div>';

                    htmlText += '<div class="col-md-3">';
                    htmlText += '<div class="form-group m-form__group">';
                    htmlText += '<div class="col-lg-12 col-md-12 col-sm-12">';

                    /*htmlText += '<div class="m-dropzone dropzone m-dropzone--success" name="combination_image" id="m-dropzone-three">';
                     htmlText += '<div class="m-dropzone__msg dz-message needsclick">';
                     htmlText += '<h5 class="m-dropzone__msg-title">Product Image.</h3>';
                     htmlText += '<div class="m--hide">';
                     htmlText += '<textarea id="productCombinationImage[]"></textarea>';
                     htmlText += '</div>';
                     htmlText += '</div>';
                     htmlText += '</div>';*/
                    htmlText += '</div>';
                    htmlText += '</div>';
                    htmlText += '</div>';
                    htmlText += '</div>';
                    htmlText += '</div>';
                    htmlText += '</div>';
                    htmlText += '</div>';

                });
                $('#combinationDiv').append(htmlText);
                $("#defultPrice").addClass("m--hide");
            }
            else {
                $("#defultPrice").removeClass("m--hide");
                $("#combinationDiv").addClass("m--hide");
            }
        })

        $("#use_exchange").on('change', function () {
            if ($('#use_exchange').is(':checked') == true) {
                $("#excnageDiv").removeClass("m--hide");
            } else {
                $("#excnageDiv").addClass("m--hide");
            }
        });

        $("#descReset").on('click', function () {
            $("#return_exchange_policy_description").text("");
        });

        $('#vendor_id').on('change', function (e) {
            var vendor_id = $('#vendor_id').val();
            var html = '';
            var htmlText = '';
            $.ajax({
                url: baseUrl + '/get/unique/shipping/vendor_id',
                method: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'value': vendor_id
                },
                success: function (result) {
                    var obj = jQuery.parseJSON(result);
                    obj = $.grep(obj, function (n) {
                        return (n);
                    });
                    if (obj.length > 0) {
                        html += "<option value=''>Select Shipping Class</option>";
                        $.each(obj, function (i, item) {
                            html += "<option value='" + item.shipping_class + "'>" + item.shipping_class + "</option>";
                        });
                        $('#shipping_class').append(html);
                    } else {
                        html += "<option value=''>Select Shipping Class</option>";
                        $('#shipping_class').append(html);
                    }

                }
            });
            $.ajax({
                url: baseUrl + '/get/product/product_category/added_by_user_id',
                method: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'value': vendor_id
                },
                success: function (result) {
                    var obje = jQuery.parseJSON(result);
                    obje = $.grep(obje, function (n) {
                        return (n);
                    });

                    console.log(obje);
                    if (obje.length > 0) {
                        htmlText += "<option value=''>Select Category</option>";
                        $.each(obje, function (i, items) {
                            htmlText += "<option value='" + items.id + "'>" + items.category_name + "</option>";
                        });
                        $('#category_id').append(htmlText);
                    } else {
                        htmlText += "<option value=''>Select Category</option>";
                        $('#shipping_class').append(html);
                    }

                }
            });

        });

        $('#shipping_class').on('change', function (e) {
            var shippingClass = $('#shipping_class').val();
            var vendor_id = $('#vendor_id').val();
            var html = '';
            $.ajax({
                url: baseUrl + '/get/unique/shipping/shipping_class',
                method: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'value': shippingClass,
                    'id': vendor_id

                },
                success: function (result) {
                    var obj = jQuery.parseJSON(result);
                    console.log(obj);
                    if (obj.length > 0) {
                        $.each(obj, function (i, item) {
                            var stateName = '';
                            var cityName = '';
                            if (item.state_name.length > 5) {
                                stateName = item.state_name.substring(0, 10) + '...';
                            } else {
                                stateName = item.state_name;
                            }
                            if (item.city_name.length > 5) {
                                cityName = item.city_name.substring(0, 10) + '...';
                            } else {
                                cityName = item.city_name;
                            }
                            html += '<div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">';
                            html += '<div class="m-demo__preview">';
                            html += '<div class="row">';
                            html += '<div class="col-md-4">';
                            html += '<div class="form-group m-form__group">';
                            html += '<label>Country : </label>';
                            html += '<input type="hidden" id="country_id" name="country_id" value="' + item.country_id + '">';
                            html += '<input type="hidden" id="country_name" name="country_name" value="' + item.country_name + '">';
                            html += '<label>' + item.country_name + '</label>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="col-md-4">';
                            html += '<div class="form-group m-form__group">';
                            html += '<label>State : </label>';
                            html += '<input type="hidden" id="state_id" name="state_id" value="' + item.state_id + '">';
                            html += '<input type="hidden" id="state_name" name="state_name" value="' + stateName + '">';
                            html += '<label>' + stateName + '</label>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="col-md-4">';
                            html += '<div class="form-group m-form__group">';
                            html += '<label>City : </label>';
                            html += '<input type="hidden" id="city_id" name="city_id" value="' + item.city_id + '">';
                            html += '<input type="hidden" id="city_name" name="city_name" value="' + cityName + '">';
                            html += '<label>' + cityName + '</label>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="col-md-4">';
                            html += '<div class="form-group m-form__group">';
                            html += '<label>Charges</label>';
                            html += '<input type="text" class="form-control m-input m-input--square" id="charge" name="charge" placeholder="Charges" value="' + item.shipping_charge + '">';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="col-md-4">';
                            html += '<div class="form-group m-form__group">';
                            html += '<label>Delivery&nbsp;Day&nbsp;1</label>';
                            html += '<input type="text" class="form-control m-input m-input--square" id="day_1" name="day_1" placeholder="Delivery Day 1" value="' + item.delivery_day_1 + '">';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="col-md-4">';
                            html += '<div class="form-group m-form__group">';
                            html += '<label>Delivery&nbsp;Day&nbsp;2</label>';
                            html += '<input type="text" class="form-control m-input m-input--square" id="day_2" name="day_2" placeholder="Delivery Day 2" value="' + item.delivery_day_2 + '">';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';

                        });
                        $('#shippingDiv').append(html);
                    }

                }
            });

        });

        $('#m_select2_3').on('select2:unselecting', function (e) {
            var unselected_value = $('#m_select2_3').val();
            var divName = "div" + unselected_value;
            $("#" + divName).remove();

        });
        $("#m_select2_3").on('select2:select', function (e) {
            var htmlText = '';
            //var ids = $('option', this).filter(':selected:last').val(); 
            //var name = $('option', this).filter(':selected:last').text(); 
            var data = $('#m_select2_3').select2('data');
            var ids = '';
            $.each(data, function (i, item) {
                name = item.text;
                ids = item.id;
                if ($('#div' + ids).length < 1) {
                    htmlText += '<div class="col-md-12" id="div' + ids + '">';
                    htmlText += '<div class="m-form__group form-group">';
                    htmlText += '<label>' + name + ' Options</label>';
                    htmlText += '<input type="hidden" name="' + name + '[]" id="' + name + '" value="' + ids + '">';
                    htmlText += '</div>';
                    htmlText += '<div class="row" id="attr_div' + ids + '">';
                    htmlText += '<div class="col-md-3" style="margin-top:10px" id="' + ids + 'div">';
                    htmlText += '<div class="m-form__group form-group">';
                    htmlText += '<input type="text" class="form-control m-input m-input--square" id="' + name + 'Value" name="' + name + 'Value[]">';
                    htmlText += '</div>';
                    htmlText += '</div>';
                    htmlText += '</div>';
                    htmlText += '<div class="m-form__group form-group">';
                    htmlText += '<button class="btn btn btn-primary m-btn m-btn--icon" style="margin-bottom:10px" type="button" id="addbutton" onclick="addDiv(\'' + ids + '\',\'' + name + '\');"><i class="la la-plus"></i></button>';
                    htmlText += '</div>';
                    htmlText += '</div>';
                    $('#attrValue').append(htmlText);
                }
            });
        });
        function addDiv(ids, name) {
            var htmlText = "";
            htmlText += '<div class="col-md-3" style="margin-top:10px" id="' + ids + 'div">';
            htmlText += '<div class="m-form__group form-group">';
            htmlText += '<input type="text" class="form-control m-input m-input--square" id="' + name + 'Value" name="' + name + 'Value[]">';
            htmlText += '</div>';
            htmlText += '</div>';
            $(htmlText).insertAfter("#" + ids + "div");
        }
        /* var state=[];
         $("#m_select2_11").on('',function (){
         var html='';
         alert($(this).val);

         $.each($("#m_select2_3 option:selected"), function(){
         state.push($(this).val());
         });

         state.length = 0;
         });*/


        function keywords() {
            var html = "";
            $.ajax({
                url: baseUrl + '/get/keyword/keywords',
                method: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

                success: function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.length > 0) {
                        $.each(obj, function (i, item) {
                            html += "<option value='" + item.id + "'>" + item.keyword + "</option>";
                        });
                        $('#keyword_search').html(html);
                    } else {
                        $('#keyword_search').html(html);
                    }

                }
            });

        }
        $(document).ready(function () {

            keywords();
            if ($('#use_exchange').is(':checked') == true) {
                $("#excnageDiv").addClass("m--hide");
            }
            $("#m_select2_3").select2({
                placeholder: "Select a Attribute "
            });
            $("#store_create").validate({
                rules: {
                    category_name: {
                        required: true,
                        minlength: 2
                    }
                },
                messages: {
                    category_name: {
                        required: "Category Name is required"
                    }
                }
            });
        });

    </script>
@endsection
