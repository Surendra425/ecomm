@extends('layouts.'.$loginUser->type)
@section('title') Add New Product @endsection
@section('css')
    <style>
        .dz-image{
            width: 55px !important;
            height: 46px !important;
        }
    </style>
@endsection
@section('content')
    @php
        $pageTitle ="productAdd";
        $contentTitle ='Add Product';
    @endphp
    <div>
    @include($loginUser->type.'.common.flash')

    <!--begin::Portlet-->
        <form id="product_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data"
              method="post" action="{{url(route('products.store'))}}" novalidate>
            {{ csrf_field() }}
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
                                    <?php foreach($vendor as $list) { ?>
                                    <option value="<?php echo $list->id; ?>"><?php echo $list->first_name .' '.$list->last_name; ?></option>
                                    <?php } ?>
                                </select>
                                <p id="vendor_id_message" class="text-danger"></p>
                            </div>
                            @endif
                	</div>

                	<div class="col-md-6">
                        <div class="form-group m-form__group" id="product_title_div">
                             <label>Title<span class="text-danger">*</span></label>
                             <input type="text" class="form-control m-input m-input--square" id="product_title" name="product_title" placeholder="Product Title">
                             <p id="product_title_message" class="text-danger"></p>
                        </div>

                     </div>

                     <div class="col-md-6">
                         <div class="form-group m-form__group">
                             <label>Keyword</label>
                             <div class="m-typeahead">
                                 <select class="form-control m-select2" id="m_select2_11" name="keyword[]" multiple name="param">
                                     <option></option>
                                     <optgroup id="keyword_search">

                                     </optgroup>
                                 </select>

                             </div>
                         </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group m-form__group" id="long_description_div">
                            <label>Description</label>
                            <textarea name="long_description" id="long_description" class="summernote"></textarea>

                            {{-- <textarea class="form-control m-input m-input--square" id="long_description" name="long_description"
                                       placeholder="Product Long Description"></textarea>--}}
                            <label id="long_description-error" class="error" for="long_description"></label>
                        </div>
                    </div>

                </div>
                </fieldset>

            </div>

            <div class="m-portlet">

                <div class="m-portlet__head">
                    <h6 class="m-portlet__head-caption">Images and Video</h6>
                </div>
                
                <fieldset class="m-portlet__body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group m-form__group" id="productImage_div">
                                <label>Product Image<span class="text-danger">*</span></label>
                                <label id="productImages-error" class="error" for="productImages"></label>
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
                            <input class="do-not-ignore" type="hidden" name="productImages" id="productImages" />
                        </div>


                        {{--<div class="m--hide">
                            <textarea class="do-not-ignore" name="productImages" id="productImages"></textarea>
                        </div>--}}

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

                        <div class="m--hide">
                            <textarea id="productVideo" name="productVideo"></textarea>
                        </div>
                    </div>
                     <!-- <video class="hidden" width="320" height="240" controls>
                          <source src="" type="video/mp4">
                     </video> -->
                </fieldset>
            </div>

            <div class="m-portlet">

                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Product Options</h4>
                </div>

                <fieldset class="m-portlet__body" id="attr_list">

                    <div class="m-form__group form-group">
                        <label for="">Product have options?</label>
                        <div class="m-radio-inline">
                            <label class="m-radio">
                                <input type="radio" name="combination" id="combinationYes" value="Yes"> Yes
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="combination" id="combinationNo" value="No"> No
                                <span></span>
                            </label>
                            <label id="combination-error" class="error" for="combination"></label>
                        </div>
                    </div>

                    <div class="row m--hide" id="optionDiv">
                        <div class="form-group m-form__group ">
                            <div id="m_repeater_3">
                                <div data-repeater-list="options">

                                    <div data-repeater-item class="row m--margin-bottom-15">
                                        <div class="col-lg-5">
                                            <label>Option<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control m-input m-input--square re-options" name="options" placeholder="Option Name">
                                            <label class="error" for="options"></label>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Qty<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control m-input m-input--square priceValidation re-qty" name="qty" placeholder="Qty">
                                            <label class="error" for="qty"></label>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Price<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control m-input m-input--square priceValidation re-price minPrice" name="price" placeholder="Price">
                                            <label class="error" for="price"></label>
                                        </div>
                                        <div class="col-lg-1">
                                            <a href="#" data-repeater-delete="" name="deleteRepeated" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only close-icon" >
                                                <i class="la la-remove"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div data-repeater-create="" class="btn btn btn-primary m-btn--icon-only close-icon">
                                        <i class="la la-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </fieldset>

            </div>

            <div class="m-portlet m--hide" id="priceDiv">

                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Price & Inventory</h4>
                </div>

                <fieldset class="m-portlet__body" id="attr_list">
                    <div class="form-group m-form__group" id="category_id_div">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group m-form__group">
                                    <label>Quantity<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square priceValidation" id="qty_default" name="qty_default" placeholder="Quantity" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group m-form__group">
                                    <label>Price<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control m-input m-input--square priceValidation minPrice" id="price_default" name="price_default" placeholder="Price">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

            </div>

            <div class="m-portlet">

                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Category</h4>
                </div>

                <fieldset class="m-portlet__body" id="attr_list">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-form__group">
                                <label>I Can Save the world Category : <span class="text-danger">*</span></label>
                                <select class="custom-select col-md-4" name="shopzz_category_id[]" id="m_select2_3" multiple>
                                    <option value="default">Select I Can Save the world Category</option>
                                    @foreach($mainCategory as $key => $group)

                                         <optgroup label="{{ $parentCategories[$key]['category_name'] or '' }}" >
                                             @foreach($group as $item)
                                             <option value="{{$item->id}}"> {{$item->category_name}} </option>
                                             @endforeach
                                         </optgroup>
                                         
                                    @endforeach
                                </select>
                                <label id="m_select2_3-error" class="error" for="m_select2_3"></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-form__group" id="category_id_div">
                                <label>Store Category : <span class="text-danger">*</span></label>
                                    <select class="custom-select col-md-4" name="store_category_id[]" id="m_select2_3_validate" multiple >

                                    </select>

                                <label id="m_select2_3_validate-error" class="error" for="m_select2_3_validate"></label>
                            </div>
                        </div>
                    </div>
                </fieldset>

            </div>

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


                    </div>


                </fieldset>

            </div>

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
    var totalImages = 0;
    var imagesArray = [];
    // Check product have option
    $('input[type=radio][name=combination]').on('change', function() {

        if ($(this).val() == 'Yes') {

            $("#optionDiv").removeClass('m--hide');
            $("#priceDiv").addClass('m--hide');
            $('a[name="options[0][deleteRepeated]"]').addClass('m--hide disabled');

        }else{

            $("#optionDiv").addClass('m--hide');
            $("#priceDiv").removeClass('m--hide');

        }

    });


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
            file.acceptDimensions = done;
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
                     maxFilesize: 10, // MB
                    acceptedFiles: "video/*",
                    timeout: 180000,
                    addRemoveLinks: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                   /*  accept: function(file,done) {
                        var ext = (file.name).split('.')[1]; // get extension from file name
                        if (ext == 'webm' || ext == 'mp4') {
                            done();
                        }else{
                            alert("You can only upload .webm, .mp4 video formats"); // error message for user
                            this.removeFile(file);
                        }
                    }, */
                    init: function() {
                        this.on("maxfilesexceeded", function(file){
                            alert("You are only allow to upload 1 Product Video");
                            var _ref;
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                        });
                    },
                    error:function(file, response){
                        alert(response);
                        this.removeFile(file);
                    },
                    success: function (file, response) {
                        console.log(response);
                        $("#productVideo").append(response);
                        // alert(response);
                    },
                    removedfile: function(file) {

                            var str1 = $("#productImages").val();
                           var img = file.name;

                            var str2 = img.replace(/ /g, '-');
                            var str2 = str2.split(".");

                                var rgxp = new RegExp(str2[0], "g");
                                if(str1.match(rgxp)){
                                    var url = $("#productVideo").val();
                                    var remove = str1;
                                    url = url.replace (remove, "");
                                    $("#productVideo").empty();
                                    $("#productVideo").append(url);
                                }
                       // this.removeFile(file);
                            var _ref;
                            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;

                    }
                }
        );



        // Get keywords
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

    // Jquery validate method
    $(function () {

        $("#product_create").validate(
                {
                    //debug:true,
                    rules: {
                        "shopzz_category_id[]": {
                            required: true
                        },
                        "store_category_id[]": {
                            required: true
                        },
                        country: {
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
                        productImages:{
                            required: true,
                        },
                        price_default: {
                            required: true,
                            min: 0.01,
                        },
                        combination: {
                            required: true
                        },

                    },
                    messages: {
                        "shopzz_category_id[]": {
                            required: "Please select I Can Save the world category."
                        },
                        "store_category_id[]": {
                            required: "Please select store category."
                        },
                        country: {
                            required: "Please select country."
                        },
                        product_title: {
                            required: "Product Title is required."
                        },
                        vendor_id: {
                            required: "Please select Vendor."
                        },
                        qty_default: {
                            required: "Qty is required.",
                            min : "Qty must be grater than 0"
                        },
                        productImages:{
                            required: "Product Image is required.",
                        },
                        price_default: {
                            required: "Price is required.",
                            min : "Price must be grater than 0"
                        },
                        combination: {
                            required: "Please select option.",
                        },
                    },
                    ignore: ':hidden:not(.do-not-ignore)',

                    submitHandler: function (form) {
                        //console.log(form);
                        if (showProductImagesValidation()) {
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

            $.validator.addMethod("priceNumber", $.validator.methods.required,
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
        	
            var vendor_id = $('#vendor_id').val();

            if(vendor_id != ''){
                category(vendor_id);
                shipping(vendor_id);
            }

            keywords();

            $('#m_select2_3').select2({
                placeholder: "Select main category"
            });

            $('#m_select2_3_validate').select2({
                placeholder: "Select category"
            });
        });

    // Load Store category
    function category(vendor_id){

        $('#m_select2_3_validate').empty();
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

                if(obje.length > 0){

                    $.each(obje, function(i, items) {

                        htmlCate += "<option value='"+ items.id +"'>"+items.category_name +"</option>";

                    });

                }
                //console.log(htmlCate);
                $('#m_select2_3_validate').append(htmlCate);

            }});
    }

    function shipping(vendor_id){

        var html = '';
        $('#shippingDiv').empty();

        $.ajax({

            url:  baseUrl + '/get/shipping/vendor_shipping_detail/vendor_id',
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{
                'value' : vendor_id
            },
            success: function(result){
                var obje = jQuery.parseJSON(result);

                if(obje.length > 0){

                    $.each(obje, function(i, items) {

                        html += '<div class="col-md-2">';
                        html += '<input type="checkbox" class="shipping-group" id="country_name'+i+'" checked name="checkCountry[]" value="'+items.country_id+'">';
                        html += '&nbsp;&nbsp;';
                        html += '<label for="country_name'+i+'">'+items.country_name+'</label>';
                        html += '</div>';

                    });

                }
                $('#shippingDiv').append(html);

            }});
    }
    $('#vendor_id').on('change', function (e) {

        var vendor_id = $('#vendor_id').val();
        category(vendor_id);
        shipping(vendor_id);

     });


    // Product image validation method
    function showProductImagesValidation() {
        var productImage = $('#productImages').val().trim();

        if (productImage == '' && totalImages == 0) {

            $('#productImages-error').show();
            return false;
        }

        $('#productImages-error').hide();
        return true;
    }
    </script>
@endsection
