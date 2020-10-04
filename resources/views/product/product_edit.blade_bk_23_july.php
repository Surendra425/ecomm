@extends('layouts.'.$loginUser->type)
@section('title') Edit Product @endsection
@section('css')
    <style>
        .dz-image {
            width: 55px !important;
            height: 46px !important;
        }

    </style>
@endsection
@section('content')
    @php
        $pageTitle ="productAdd";
     $contentTitle ='Edit Product';
    @endphp
    <div>
    @include($loginUser->type.'.common.flash')
    <!--begin::Portlet-->
         <form id="product_create" class="m-form m-form--fit m-form--label-align-right form"
               onsubmit="return validateForm()" enctype="multipart/form-data"
              method="post" action="{{url(route('productUpdate',['product'=>$product->id]))}}" >

            {{ csrf_field() }}

             {{-- start Product Information section--}}

             <div class="m-portlet">

                <div class="m-portlet__head">
                    <h6 class="m-portlet__head-caption">Product Information</h6>
                </div>
                <fieldset class="m-portlet__body">

                    <div class="row">

                        <div class="col-md-12" style="margin-bottom: 25px;">
                            @if($loginUser->type === 'vendor')

                                <input type="hidden" id="vendor_id" name="vendor_id" value="{{$loginUser->id}}">

                            @else
                                <div class="form-group m-form__group" id="vendor_id_div">
                                    <label for="first_name">Vendor : <span class="text-danger">*</span></label>
                                    <select class="custom-select col-md-6" name="vendor_id" id="vendor_id">
                                        <option value="" selected>Select Vendor</option>
                                        @foreach($vendors as $vendor)

                                        <option value="{{ $vendor['id'] }}" {{($product->vendor_id == $vendor['id']) ? 'selected':''}}>
                                            {{ $vendor['first_name'] . ' ' . $vendor['last_name'] }}
                                        </option>

                                        @endforeach

                                    </select>
                                    <p id="vendor_id_message" class="text-danger"></p>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">

                            <div class="form-group m-form__group" id="product_title_div">
                                <label>Title<span class="text-danger">*</span></label>

                                <input type="text" value="{{$product->product_title}}"
                                       class="form-control m-input m-input--square" id="product_title"
                                       name="product_title" placeholder="Product Title">

                                <p id="product_title_message" class="text-danger"></p>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>Keyword</label>

                                <div class="m-typeahead">
                                    <select class="form-control m-select2" id="m_select2_11" name="keyword[]" multiple name="param">

                                        @foreach($keyword as $key)

                                            <option value="{{$key->keyword_id}}" selected>{{$key->keyword}}</option>

                                        @endforeach
                                        <optgroup id="keyword_search">

                                        </optgroup>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">

                            <div class="form-group m-form__group" id="long_description_div">
                                <label>Description</label>
                                <textarea name="long_description" id="long_description" class="summernote">{!!$product->long_description!!}</textarea>
                                <label id="long_description-error" class="error" for="long_description"></label>
                            </div>

                        </div>
                    </div>

                </fieldset>

            </div>
             {{-- end Product Information section--}}


             {{-- start Images and Video section--}}
            <div class="m-portlet">

                <div class="m-portlet__head">
                    <h6 class="m-portlet__head-caption">Images and Video</h6>
                </div>

                <fieldset class="m-portlet__body">

                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group m-form__group" id="productImage_div">
                                <label>Product Image</label>
                                <label id="productImages-error" class="error" for="productImages"></label>
                                <div class="col-lg-12 col-md-9 col-sm-12">
                                    <div class="m-dropzone dropzone m-dropzone--success" action="#" id="productImage">
                                        <div class="m-dropzone__msg dz-message needsclick">
                                            <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                                            <span class="m-dropzone__msg-desc">Only image are allowed for upload</span>
                                        </div>
                                    </div>
                                </div>
                                <p id="productImage_message" class="text-danger"></p>
                            </div>

                        </div>

                        <div class="m--hide">
                            <input class="do-not-ignore" type="hidden" name="productImages" id="productImages" />
                        </div>

                        <div class="col-md-6">

                            <div class="form-group m-form__group">
                                <label>Product Video</label>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="m-dropzone dropzone m-dropzone--success" action="#" id="video">
                                        <div class="m-dropzone__msg dz-message needsclick">
                                            <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                                            <span class="m-dropzone__msg-desc">Only video are allowed for upload</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        @if(!empty($product->images) && count($product->images) > 0)

                            @php
                                $imagesList = [];
                            @endphp

                            <input type="hidden" value="{{count($product->images)}}" name="totalImages" id="totalImages" >

                            <div class="col-md-6">
                                <div class="form-group m-form__group">
                                    <label>Product Images</label>

                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        @foreach($product->images as $images)

                                            @php $imagesList[] = $images->image_url; @endphp

                                            <div class="img-wrap" id="{{$images->id}}_img_div">
                                                <span class="close">&times;</span>

                                                <img src=" {{url('doc/product_image').'/'.$images->image_url }}"
                                                     width="100" height="80" data-id="{{$images->id}}"
                                                     data-productId="{{$product->id}}" data-name="{{$images->image_url}}" id="imageProduct">

                                            </div>
                                        @endforeach

                                    </div>

                                </div>
                            </div>

                            @php $imagesName = implode(',',$imagesList); @endphp

                            <div class="m--hide">
                                <textarea name="productImages1" id="productImages1">{{$imagesName}}</textarea>
                            </div>

                        @endif




                    @if(!empty($product->videos) && count($product->videos) > 0)

							<input type="hidden" value="{{count($product->videos)}}" name="totalVideo" id="totalVideo" >

                            <div class="col-md-6">

                                <div class="form-group m-form__group">
                                    <label>Product Video</label>

                                    <div class="col-lg-12 col-md-12 col-sm-12">

                                        @foreach($product->videos as $videos)

                                            @php
                                                $video = explode('.',$videos->video_url);
                                            @endphp

                                            <div class="img-wraps video" id="{{$videos->id}}_video_div">
                                                <span class="close">&times;
													
                                                <video width="320" controls>
                                                    <source data-id="{{$videos->id}}" data-name="{{$videos->video_url}}" src="{{url('doc/video').'/'.$videos->video_url }}"
                                                            type="video/mp4">
                                                   <source data-id="{{$videos->id}}" data-name="{{$videos->video_url}}" src="{{url('doc/video/ios').'/'.$videos->video_url }}"
                                                            type="video/mp4">
                                                   <source data-id="{{$videos->id}}" data-name="{{$videos->video_url}}" src="{{url('doc/video/web').'/'.$videos->video_url }}"
                                                            type="video/mp4"> 
                                                                                                    
                                                </video>

												 </span>
                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                    <div class="m--hide">
                        <textarea id="productVideo" name="productVideo"></textarea>
                    </div>

                </fieldset>

            </div>
             {{-- end Images and Video section--}}


             {{-- start Product Options section--}}
            <div class="m-portlet">

                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Product Options</h4>
                </div>

                <fieldset class="m-portlet__body" id="attr_list">

                    <div class="m-form__group form-group">
                        <label for="">Product have options?</label>

                        <div class="m-radio-inline">

                            <label class="m-radio">
                                <input type="hidden" name="old_combination" id="old_combination" value="{{(isset($product->options) && !empty($product->options)  && count($product->options) >= 1 && $product->options[0]->combination_title != '') ? 'Yes' : 'No'}}">
                                <input type="radio" name="combination" id="combinationYes" value="Yes" {{(isset($product->options) && !empty($product->options)  && count($product->options) >= 1 && $product->options[0]->combination_title != '') ? 'checked' : ''}}> Yes
                                <span></span>
                            </label>

                            <label class="m-radio">
                                <input type="radio" name="combination" id="combinationNo" value="No" {{(isset($product->options) && !empty($product->options) && count($product->options) === 1 && empty($product->options[0]->combination_title)) ? 'checked' : ''}}> No
                                <span></span>
                            </label>

                            <label id="combination-error" class="error" for="combination"></label>

                        </div>

                    </div>
                    
                    @php
                        $class='m--hide';
                    @endphp

                    @if((isset($product->options) && !empty($product->options) && count($product->options) >= 1) && $product->options[0]->combination_title != '')
                        @php
                            $class='';
                        @endphp

                    @endif
                    
                    <div class="row {{$class}}" id="optionDiv">

                        <div class="form-group m-form__group ">

                            <div id="m_repeater_3">

                                @foreach($product->options as $options)

                                    @if($options->combination_title != '')

                                        <div>

                                            <div id="productDiv_{{$options->id}}" class="row m--margin-bottom-15">
                                                <div class="col-lg-5">
                                                    <label>Option</label>
                                                    <input type="text" class="form-control m-input m-input--square re-options" id="option" name="options[{{$options->id}}][options]" value="{{$options->combination_title}}" placeholder="Option Name">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label>Qty</label>
                                                    <input type="text" class="form-control m-input m-input--square re-qty" id="qty" name="options[{{$options->id}}][qty]" value="{{$options->quantity}}" placeholder="Qty">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label>Price</label>
                                                    <input type="text" class="form-control m-input m-input--square minPrice re-price" id="price" name="options[{{$options->id}}][price]" value="{{$options->rate}}" placeholder="Price">
                                                </div>
                                                <div class="col-lg-1">
                                                        <a href="javascript:void(0);" class="removeOption btn btn-danger m-btn m-btn--icon m-btn--icon-only close-icon">
                                                            <i class="la la-remove"></i>
                                                        </a>
                                                 </div>
                                            </div>

                                        </div>

                                    @endif

                                @endforeach

                                    <div data-repeater-list="optionsDem" id="optionDivs">

                                        <div data-repeater-item class="row m--margin-bottom-15" style="display:none;">
                                            <div class="col-lg-5">
                                                <label>Option<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control m-input m-input--square re-options"  name="options"  placeholder="Option Name">
                                            </div>
                                            <div class="col-lg-3">
                                                <label>Qty<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control m-input m-input--square priceValidation re-qty" name="qty"  placeholder="Qty">
                                            </div>
                                            <div class="col-lg-3">
                                                <label>Price<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control m-input m-input--square priceValidation re-price minPrice" name="price"  placeholder="Price">
                                            </div>
                                            <div class="col-lg-1">
                                                <a href="javascript:void(0);"  data-repeater-delete="" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only close-icon">
                                                    <i class="la la-remove"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div data-repeater-create=""  id="plusDivsDis"  class="btn btn btn-primary m-btn--icon-only close-icon">
                                            <i class="la la-plus"></i>
                                        </div>
                                        {{--<div  onclick="removeClassFunction(this);" id="plusDivs"  class="btn btn btn-primary m-btn--icon-only close-icon">
                                            <i class="la la-plus"></i>
                                        </div>--}}

                                    </div>

                            </div>

                        </div>

                    </div>

                </fieldset>

                        <div id="optionNotAvailable" class="error" style="display:none">
                            Please add atleast one option
                        </div>
            </div>



            @php
                $priceClass='m--hide';
            @endphp

            @if(isset($product->options) && !empty($product->options) && count($product->options) === 1 && $product->options[0]->combination_title == '')
                @php
                    $priceClass='';
                @endphp
            @endif
            
            <div class="m-portlet {{$priceClass}}" id="priceDiv">

                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Price & Inventory</h4>
                </div>

                <fieldset class="m-portlet__body" id="attr_list">

                    <div class="" id="category_id_div">
                        <div class="row">
                            <div class="col-md-4">

                                <div class="form-group m-form__group">
                                    <label>Quantity<span class="text-danger">*</span></label>

                                    <input type="text" class="form-control m-input m-input--square priceValidation"
                                           id="qty_default" name="qty_default"
                                           value="{{ (isset($product->options[0]) && $product->options[0]->combination_title == '') ? $product->options[0]->quantity : ''}}"
                                           placeholder="Quantity">

                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group m-form__group">
                                    <label>Price<span class="text-danger">*</span></label>

                                    <input type="text" class="form-control m-input m-input--square priceValidation"
                                           id="price_default" name="price_default"
                                           value="{{ (isset($product->options[0]) && $product->options[0]->combination_title == '') ? $product->options[0]->rate : ''}}"
                                           placeholder="Price">

                                    <input type="hidden" name="option_id" id="option_id" value="{{$product->options[0]->id}}">

                                </div>

                            </div>

                        </div>
                    </div>

                </fieldset>

            </div>
             {{-- end Product Options section--}}



             {{-- start Category section--}}
            <div class="m-portlet">

                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Category</h4>
                </div>

                <fieldset class="m-portlet__body" id="attr_list">

                    <div class="row">

                        @php
                            $productShopzzCategories = [];
                        @endphp

                        @foreach($product->shopzzCategory as $shopzzCate)

                            @php
                                $productShopzzCategories[] = $shopzzCate->shopzz_category_id;
                            @endphp

                        @endforeach

                        <div class="col-md-6">

                            <div class="form-group m-form__group">
                                <label for="featured">Shopzz Category : <span class="text-danger">*</span></label>

                                <select class="custom-select col-md-4" name="shopzz_category_id[]" id="m_select2_3" multiple>

                                    @foreach($shopzzCategory as $key => $group)

                                         <optgroup label="{{ $parentCategories[$key]['category_name'] or '' }}" >

                                             @foreach($group as $item)

                                                 <option value="{{$item->id}}" {{in_array($item->id,$productShopzzCategories) ? 'selected' : ''}}> {{$item->category_name}} </option>

                                             @endforeach

                                         </optgroup>
                                    @endforeach

                                </select>

                                <label id="m_select2_3-error" class="error" for="m_select2_3"></label>

                            </div>

                        </div>

                        @php
                            $productStoreCategories = [];
                        @endphp

                        @foreach($product->storeProductCategory as $storeProductCate)

                            @php
                                $productStoreCategories[] = $storeProductCate->store_category_id;
                            @endphp

                        @endforeach

                        <div class="col-md-6">

                            <div class="form-group m-form__group" id="category_id_div">
                                <label for="featured">Store Category : <span class="text-danger">*</span></label>

                                <select class="custom-select col-md-4" name="store_category_id[]" id="m_select2_3_validate" multiple>
                                    @foreach($storeCategory as $item)
                                         <option value="{{$item->id}}" {{in_array($item->id,$productStoreCategories) ? 'selected' : ''}}> {{$item->category_name}} </option>
                                    @endforeach
                                </select>

                                <label id="m_select2_3_validate-error" class="error" for="m_select2_3_validate"></label>
                            </div>

                        </div>
                    </div>
                </fieldset>
            </div>
             {{-- end Category section--}}


             {{-- start Shipping section--}}
            <div class="m-portlet">

                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Shipping</h4>
                </div>

                <fieldset class="m-portlet__body shipping_details"
                          ng-init="ships_from='India';manufacture_country='India'">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group m-form__group ">
                                <label for="city_name">Country</label>
                            </div>
                        </div>
                    </div>

                    <div id="shippingDiv" class="row form-group m-form__group">

                        @foreach($vendorShipping as $key=> $shipping)

                            <div class="col-md-2">

                                <input type="checkbox" class="shipping-group" id="country_name{{$key+1}}" {{in_array($shipping->country_id,$productShipping) ? 'checked' : ''}} name="checkCountry[]" value="{{$shipping->country_id}}">
                                &nbsp;&nbsp;
                                <label for="country_name{{$key+1}}">{{$shipping->country_name}}</label>

                            </div>
                        @endforeach

                    </div>
                    <label id="checkCountry[]-error" class="error form-group m-form__group" for="checkCountry[]"></label>

                </fieldset>
                  
                <div class="m-portlet__foot m-portlet__foot--fit">

                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-lg-9 ml-lg-auto">

                                <button class="btn btn-success" type="submit" id="submit">Submit</button>
                                 <a href="{{ url('admin/products') }}" class="btn btn-secondary"> Cancel
                                </a>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.m-portlet__body -->
            <!-- /.m-portlet__foot m-portlet__foot--fit -->
        </form>
    </div>
@endsection
@section('js')

    <script src="{{ url ('assets/demo/default/custom/components/forms/widgets/form-repeater.js')}}" type="text/javascript"></script>
    <script src="{{ url ('assets/demo/default/custom/components/forms/widgets/select2.js')}}"  type="text/javascript"></script>
    <script src="{{url('assets/demo/default/custom/components/forms/widgets/dropzone.js')}}" type="text/javascript"></script>
    <script src="{{url('assets/demo/default/custom/components/forms/widgets/summernote.js')}}" type="text/javascript"></script>
    <script type="text/javascript">

        var totalImages = $('#totalImages').val();
        var imagesArray = [];

    $('#plusDivsDis').on('click', function() {
        console.log('test');
    	$('#optionNotAvailable').hide();
    });
    
    $('.removeOption').on('click', function() {

       if(confirm('Are you sure want to delete this option?'))
       {
            $(this).parent().parent().remove();
       }
       
    });

        $('input[type=radio][name=combination]').on('change', function() {

            if ($(this).val() == 'Yes') {
                $("#optionDiv").removeClass('m--hide');
                $("#priceDiv").addClass('m--hide');
                $('#optionDivs').find('.m--margin-bottom-15').show('slow');

                $(this+'a[name="optionsDem[0][deleteRepeated]"]').addClass('m--hide disabled');
            }else{
                $("#optionDiv").addClass('m--hide');
                $("#priceDiv").removeClass('m--hide');
                $('#optionDivs').find('.m--margin-bottom-15').hide('slow');
            }
        });

function removeClassFunction(name) {

$("#optionDivs").removeClass('m--hide');
$("#plusDivs").addClass('m--hide');
$("#plusDivsDis").removeClass('m--hide');
}
        /*$("#productImage").dropzone(
                {
                    url: "{{url(route('uploadImage'))}}" ,
                    acceptedFiles: "image/!*",
                    maxFiles: 5, // Maximum Number of Files
                    maxFilesize: 2, // MB
                    addRemoveLinks: true,

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    accept: function(file, done) {
                    	var totalImages = $("#totalImages").val();
                   	 if(totalImages == 5){
                   		 alert("You are only allow to upload 5 Product Image"); // error message for user
                            this.removeFile(file);
                       }
                   	else{
                    	done();
                    }
                       /!*  var ext = (file.name).split('.')[1]; // get extension from file name
                        if (ext == 'jpeg' || ext == 'jpg' || ext == 'png' || ext == 'gif') {
                            done();
                        }else{
                            alert("You have uploaded an invalid image file type."); // error message for user
                            this.removeFile(file);
                        } *!/
                    },
                    init: function() {
                        this.on("maxfilesexceeded", function(file){
                            alert("You are only allow to upload 5 Product Image");
                            var _ref;
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                        });
                        this.on("thumbnail", function(file) {
                            console.log(file.width);
                            console.log(file.height);
                           /!* if (file.width <= 550 && file.height <= 550) {
                                //file.rejectDimensions()
                                alert("Your image must be larger than "+file.width+"px by "+file.height+"px");
                                this.removeFile(file);
                            }else if(file.width != file.height){
                                alert("Your image height and width must be same.");
                                this.removeFile(file);
                            }
                            else*!/ if(file.size > 4194304){
                                alert("Your image size is grater then 2MB.");
                                this.removeFile(file);
                            }
                        });
                    },
                    error:function(file, response){
                        alert(response);
                        this.removeFile(file);
                    },
                    success: function(file, response){
                        $("#productImages").append(response);
                        console.log(response);
                        // alert(response);
                    },


                    removedfile: function(file) {

                            var str1 = $("#productImages").val();
                            var str =  str1.split('""');
                            var img = file.name;

                            var str2 = img.replace(/ /g, '-');
                            var str2 = str2.split(".");
                            $.each(str, function( index, value ) {
                                var rgxp = new RegExp(str2[0], "g");
                                if(value.match(rgxp)){
                                    var url = $("#productImages").val();
                                    var remove = value;
                                    url = url.replace (remove, "");
                                    $("#productImages").empty();
                                    $("#productImages").append(url);
                                }
                            });
                       // this.removeFile(file);
                            var _ref;
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                    }
                }
        );*/

    var maxImageWidth = 200, maxImageHeight = 195;

    $("#productImage").dropzone({

        url: "{{url(route('uploadImage'))}}" ,
        acceptedFiles: "image/*",
        maxFiles: 5, // Maximum Number of Files
        maxFilesize: 2, // MB
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        init: function() {

            this.on("maxfilesexceeded", function(file) {
                alert("You are only allow to upload 5 Product Image");
                var _ref;
                return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
            });

            this.on("thumbnail", function(file) {

                if (file.size > 4194304) {
                    alert("Your image size is grater then 2MB.");
                    this.removeFile(file);
                }

                if(file.width < file.height){
                    alert("Your image height should be less then image width.");
                    file.rejectDimensions();
                    return false;
                }

                if (file.width < maxImageWidth || file.height < maxImageHeight) {
                    alert("Your image height & width must be grater then 200px required.");
                    file.rejectDimensions();
                    return false;
                }
                else {
                    file.acceptDimensions();
                }
            });
        },
        accept: function(file, done) {
            console.log(totalImages);
            if(totalImages >= 5){
                alert("You are only allow to upload 5 Product Image"); // error message for user
                this.removeFile(file);
            }
            else{
                totalImages++;
                file.acceptDimensions = done;
            }

            file.rejectDimensions = function() { done("Image width or height too big."); };
        },
        error: function(file, response) {

            this.removeFile(file);
        },
        success: function(file, response) {

            var productImages = $("#productImages").val();


            if(productImages.trim() == '')
            {
                productImages = response;
            }
            else
            {
                productImages +=','+response;
            }

            imagesArray[file.name] =response;


            $("#productImages").val(productImages);
            showProductImagesValidation();
        },

        removedfile: function(file) {

            var productImages = $("#productImages").val();

            var productImagesArray =  productImages.split(',');

            productImages = productImages.replace(imagesArray[file.name]+',',"");
            productImages = productImages.replace(imagesArray[file.name],"");

            $("#productImages").val(productImages);
            showProductImagesValidation();
            var _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;

            /*  console.log(file);
             if(confirm('Do you want to delete?')){

             var str1 = $("#productImagesNames").val();
             var str = str1.split('""');
             var img = file.name;

             var str2 = img.replace(/ /g, '-');
             var str2 = str2.split(".");
             $.each(str, function(index, value) {
             var rgxp = new RegExp(str2[0], "g");
             if (value.match(rgxp)) {
             var url = $("#productImagesNames").val();
             var remove = value;
             url = url.replace(remove, "");
             $("#productImagesNames").empty();
             $("#productImagesNames").append(url);
             }
             });
             this.removeFile(file);
             }

             var _ref;

             return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0; */
        },

    });

        $("#video").dropzone(
                {
                   
                    url: "{{url(route('uploadVideo'))}}",
                    maxFiles: 1, // Maximum Number of Files
                     maxFilesize: 20, // MB
                     timeout: 180000,
                    addRemoveLinks: true,
                    acceptedFiles: "video/.3gp,.avi,.flv,.mpg,.mov,.mp4,.webm",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                     accept: function(file,done) {
                    	 var totalVideo = $("#totalVideo").val();
                    	 if(totalVideo == 1){
                    		 alert("You are only allow to upload 1 Product Video	"); // error message for user
                             this.removeFile(file);
                        }else{
                        	done();
                        }
                       /*  var ext = (file.name).split('.')[1]; // get extension from file name
                        if (ext == 'webm' || ext == 'mp4') {
                            done();
                        }else{
                            alert("You can only upload .webm, .mp4 video formats"); // error message for user
                            this.removeFile(file);
                        } */
                    }, 
                    init: function() {
                        this.on("maxfilesexceeded", function(file){
                            alert("You are only allow to upload 1 Product Video");
                            var _ref;
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                        });
                    },
                    success: function (file, response) {

                        if(response == "error"){
                            alert('This video file not generate thumbnail image, Please try with another extension like .3gp .avi .flv .mpg .mov .mp4 .wmv .webm');
                            this.removeFile(file);
                            return;
                        }else if(response == "error1"){
                            alert('This file not supported, Please try again with different format');
                            this.removeFile(file);
                            return;
                        }
                        $("#productVideo").append(response);
                        // alert(response);
                    },
                    error:function(file, response){
                        alert(response);
                         this.removeFile(file);
                    },
                    removedfile: function(file) {

                            var str1 = $("#productImages").val();
                            var img = file.name;

                            //var str2 = img.replace(/ /g, '-');
                            var str2 = img.split(".");

                            /*var rgxp = new RegExp(str2[0], "g");

                            if(str1.match(rgxp)){
                                var url = $("#productVideo").val();
                                var remove = str1;
                                url = url.replace (remove, "");
                                $("#productVideo").empty();
                                $("#productVideo").append(url);
                            }*/

                            var _ref;
                       // this.removeFile(file);
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;

                    }
                }
        );

    // Product image validation method
    function showProductImagesValidation() {
        var productImage = $('#productImages').val().trim();
        var productImages1 = $('#productImages1').val().trim();


        if (productImage == '' && totalImages == 0) {

            $('#productImages-error').show();
            return false;
        }

        $('#productImages-error').hide();
        return true;
    }

        function keywords(){

            var html="";

            $.ajax({
                url:  baseUrl + '/get/keyword/keywords',
                method: "POST",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

                success: function(result){

                    var obj = jQuery.parseJSON(result);

                    if(obj.length > 0){
                        $.each(obj, function(i, item) {
                            html += "<option value='"+ item.id +"'>"+ item.keyword +"</option>";
                        });
                        $('#keyword_search').html(html);

                    }else{
                        $('#keyword_search').html(html);
                    }

                }});
        }

        function validateForm()
        {
            var a=$("#productImages1").val();
            var b=$("#productImages").val();
            if(b == "" && a == ""){
                $("#productImage_message").text("Product Image is required.");
                return false;
            }
        }
       
        $(function () {
            var form = document.querySelector("#product_create");
            $("#product_create").validate(
                    {
                        rules: {
                            "shopzz_category_id[]": {
                                required: true
                            },
                            "store_category_id[]": {
                                required: true
                            },
                            "checkCountry[]": {
                                required: true
                            },
                            vendor_id: {
                                required: true
                            },
                            product_title: {
                                required: true
                            },
                            qty_default: {
                                required: true,
                                min: 0
                            },
                            price_default: {
                                required: true,
                                min: 0.01
                            },
                            combination: {
                                required: true
                            }
                        },
                        messages: {
                            "shopzz_category_id[]": {
                                required: "Please select shopzz category."
                            },
                            "store_category_id[]": {
                                required: "Please select store category."
                            },
                            "checkCountry[]": {
                                required: "Please select Country."
                            },
                            product_title: {
                                required: "Product Title is required."
                            },
                            vendor_id: {
                                required: "Please select Vendor."
                            },
                            qty_default: {
                                required: "Qty is required.",
                                min : "Qty must be grater then 0"
                            },
                            price_default: {
                                required: "Price is required.",
                                min : "Price must be grater than 0"
                            },
                            combination: {
                                required: "Please select option."
                            }
                        },
                        ignore: ':hidden:not(.do-not-ignore)',
                        debug:false,
                        submitHandler: function (form) {

                        	var optionsCount = 0;
                        	
                        	$( ".re-options" ).each(function( index ) {
                        		optionsCount++;
                            });
                        	  $('#optionNotAvailable').hide();
                            if(optionsCount == 0 && $('input:radio[name="combination"]:checked').val() == 'Yes')
                            {
                            	$('#optionNotAvailable').show();
                            	$('#combinationYes').focus();
                                return false;
                            }

                            $('#optionNotAvailable').hide();

                            if(showProductImagesValidation()){
                                form.submit();
                            }

                        }
                    });
        });

        $(document).ready(function () {
        	
            $.validator.addMethod("optionRequired", $.validator.methods.required,
                    "Option is required");

            $.validator.addMethod("qtyRequired", $.validator.methods.required,
                    "Qty is required");

            $.validator.addMethod("priceRequired", $.validator.methods.required,
                    "Price is required");

            $.validator.addMethod("qtyNumber", $.validator.methods.number,
                    "Qty must be number");

            $.validator.addMethod("priceNumber", $.validator.methods.number,
                    "Price must be number");
            
            $.validator.addMethod("qtyGrater", $.validator.methods.min,
            "Qty must be grater then 0");

            $.validator.addMethod("priceMin", $.validator.methods.min,
            "Price must be grater than 0");

            jQuery.validator.addClassRules("re-options", {
                optionRequired: true,
            });

            jQuery.validator.addClassRules("re-qty", {
                qtyRequired: true,
                qtyNumber:true,
                qtyGrater : false
            });
            jQuery.validator.addClassRules("re-price", {
                priceRequired: true,
                priceNumber:true,
            });

            jQuery.validator.addClassRules("minPrice", {
            	priceMin: 0.01
            }, "Price must be grater than 0");

            keywords();

            $('#m_select2_3').select2({
                placeholder: "Select main category"
            });
            $('#m_select2_3_validate').select2({
                placeholder: "Select category"
            });

        });

        //image reomve
        $('.img-wrap .close').on('click', function () {

            if(confirm('Are you sure want to delete this image?')) {

                var total = $("#totalImages").val();
                var productId = $(this).closest('.img-wrap').find('img').attr('data-productId');
                var id = $(this).closest('.img-wrap').find('img').data('id');
                var imageUrl = $(this).closest('.img-wrap').find('img').data('name');

                $.ajax({
                    url: baseUrl + '/delete-product-images',
                    method: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        'productId':productId,
                        'imageId': id,
                    },
                    success: function (result) {

                        if(result.status) {

                            $("#totalImages").val(total - 1);
                            totalImages = totalImages -1;
                            $("#" + id + "_img_div").remove();

                            var image = $("#productImages1").val();

                            var rgxp = new RegExp(imageUrl, "g");

                            if (image.match(rgxp)) {

                                image = image.replace(imageUrl, "");

                                $("#productImages1").empty();
                                $("#productImages1").append(image);

                            }
                        }else{
                                alert(result.msg);
                        }
                    }
                });
            }
        });



        $('.video .close').on('click', function () {

            if(confirm('Are you sure want to delete this video?')) {

                var total = $("#totalVideo").val();
                var id = $(this).closest('.video').find('source').data('id');
                var videoUrl = $(this).closest('.video').find('source').data('name');

                $.ajax({

                    url: baseUrl + '/delete-product_video',
                    method: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        'videoId': id,
                    },
                    success: function (result) {
                        if(result.status) {

                            $("#totalVideo").val(total - 1);
                            $("#" + id + "_video_div").remove();

                            var video = $("#productVideo").val();

                            var rgxp = new RegExp(videoUrl, "g");

                            if (video.match(rgxp)) {

                                video = video.replace(videoUrl, "");

                                $("#productVideo").empty();
                                $("#productVideo").append(video);
                            }
                        }
                    }
                });
            }
        });


        function category(vendor_id){

            var htmlCate = '';

            htmlCate += "<option value=''>Select Category</option>";


            $.ajax({
                url:  baseUrl + '/get/product/vendor_product_categories/vendor_id',
                method: "POST",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data:{
                    'value' : vendor_id
                },

                success: function(result){

                    var obje = jQuery.parseJSON(result);
                    obje = $.grep(obje ,function(n){
                        return(n);
                    });
                    //console.log(obje);
                    if(obje.length > 0){

                        $.each(obje, function(i, items) {
                            htmlCate += "<option value='"+ items.id +"'>"+items.category_name +"</option>";
                        });

                    }
                    //console.log(htmlCate);
                    $('#m_select2_3_validate').html(htmlCate);

                }});
        }

        function shipping(vendor_id){

            var html = '';

            $.ajax({
                url:  baseUrl + '/get/shipping/vendor_shipping_detail/vendor_id',
                method: "POST",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data:{
                    'value' : vendor_id
                },
                success: function(result){

                    var obje = jQuery.parseJSON(result);
                    obje = $.grep(obje ,function(n){
                        return(n);
                    });

                    if(obje.length > 0){

                        $.each(obje, function(i, items) {

                            html += '<div class="col-md-2">';
                            html += '<input type="checkbox" class="shipping-group" id="country_name'+i+'" checked name="checkCountry[]" value="'+items.country_id+'">';
                            html += '&nbsp;&nbsp;';
                            html += '<label for="country_name'+i+'">'+items.country_name+'</label>';
                            html += '</div>';

                        });

                    }

                    $('#shippingDiv').html(html);

                }});
        }
        
        $('#vendor_id').on('change', function (e) {
            var vendor_id = $('#vendor_id').val();
            category(vendor_id);
            shipping(vendor_id);
        });

    </script>
@endsection
