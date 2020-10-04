@extends('layouts.'.$loginUser)
@section('title') <?php echo (empty($product)) ? 'Create Product' : 'Edit Product'; ?> @endsection
@section('css')
@endsection
@section('content')
    @php
        $pageTitle ="productAdd";
    @endphp
    <div class="m-portlet">
    @include($loginUser.'.common.flash')
    <!--begin::Portlet-->
        <form id="store_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data"
              method="post" action="{{url(route('products.store'))}}" novalidate>
            {{ csrf_field() }}

            <div id="sf1" class="frm" style="display: block;">
                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Step 1 of 5 - Product Information</h4>
                </div>
                <fieldset class="m-portlet__body">
                <div class="row">
                	<div class="col-md-12" style="margin-bottom: 25px;">
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
                	</div>
                	<div class="col-md-6">
                        <div class="form-group m-form__group" id="product_title_div">
                             <label>Title<span class="text-danger">*</span></label>
                             <input type="text" class="form-control m-input m-input--square" id="product_title" name="product_title" placeholder="Product Title">
                             <p id="product_title_message" class="text-danger"></p>
                        </div>
                        <div class="form-group m-form__group" id="brand_name_div">
                            <label>Brand Name<span class="text-danger">*</span></label>
                            <input class="form-control m-input m-input--square" type="text" id="brand_name" name="brand_name" placeholder="Brand Name">
                            <p id="brand_name_message" class="text-danger"></p>
                        </div>
                        <div class="form-group m-form__group" id="long_description_div">
                            <label>Long Description<span class="text-danger">*</span></label>
                            <textarea class="form-control m-input m-input--square" id="long_description" name="long_description"
                                      placeholder="Product Long Description"></textarea>
                            <p id="long_description_message" class="text-danger"></p>
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
                        <div class="form-group m-form__group">
                                <label>Arabic Long Description </label>
                                <textarea class="form-control m-input m-input--square" id="long_description_arabic" name="long_description_arabic"
                                          placeholder="Product Long Description in Arabic"></textarea>
                         </div>
                    </div>
                </div>
                </fieldset>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-lg-9 ml-lg-auto">
                                <button class="btn btn-primary open1 pull-right" type="button" onclick="next(2)">Next <span
                                            class="fa fa-arrow-right"></span></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="sf2" class="frm" style="display: none">
                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Step 2 of 5 - Images and Video</h4>
                </div>
                
                <fieldset class="m-portlet__body">
                <div class="row">
                	<div class="col-md-6">
                    	<div class="form-group m-form__group" id="productImage_div">
                            <label>Product Image</label>
                            <div class="col-lg-12 col-md-9 col-sm-12">
                                <div class="m-dropzone dropzone m-dropzone--success" action="#" id="productImage">
                                    <div class="m-dropzone__msg dz-message needsclick">
                                   		<h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                                        <span class="m-dropzone__msg-desc">Only video are allowed for upload</span>
                                    </div>
                                </div>
                            </div>
                            <p id="productImage_message" class="text-danger"></p>
                        </div>
                	</div>
                    <div class="m--hide">
                        <textarea name="productImages" id="productImages"></textarea>
                    </div>

                	<div class="col-md-6">
                    	<div class="form-group m-form__group">
                            <label>Product Video</label>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="m-dropzone dropzone m-dropzone--success" action="{{url(route('uploadVideo'))}}" id="video">
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
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-lg-12 ml-lg-auto">
                                <button class="btn btn-warning" type="button" onclick="back(1)"><span
                                            class="fa fa-arrow-left"></span> Back
                                </button>
                                <button class="btn btn-primary pull-right" type="button" onclick="next(3)">Next <span
                                            class="fa fa-arrow-right"></span></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="sf3" class="frm" style="display: none;">
                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Step 3 of 5 - Category &amp; Attribute Details</h4>
                </div>
                
                <fieldset class="m-portlet__body" id="attr_list">
                    <div class="form-group m-form__group" id="category_id_div">
                        <label for="featured">Category : </label>
                        <select class="custom-select col-md-4" name="category_id" id="category_id">

                        </select>
                        <p id="category_id_message" class="text-danger"></p>
                    </div>
                    <div class="m-form__group form-group">
                        <label for="">Attribute</label>
                        <div class="m-checkbox-inline" id="attribute_id_div">
                            <select class="m-select2 col-md-6" id="m_select2_3" name="attr_list[]" multiple="multiple">
                                 @foreach($attribute as $option)
                                <option value="{{$option->id}}">{{$option->attribute_name}}</option>
                                @endforeach
                       		 </select>
                            <p id="attribute_id_message" class="text-danger"></p>
                        </div>
                    </div>
                    <div class="row" id="attrValue">

                    </div>

                    <div class="m-form__group form-group">
                        <label for="">Use Combination for Qty?</label>
                         <div class="m-radio-inline">
                             <label class="m-radio">
                                <input type="radio" name="combination" id="combinationYes" value="Yes"> Yes
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="combination" id="combinationNo" value="No"> No
                                <span></span>
                            </label>
                        </div>
                    </div>
                     <!--begin::Preview-->
                         <div id="combinationDiv">
                         </div>
                     <!--end::Preview-->
                     <div id="defultPrice" class="m--hide">
                         <div class="row">
                         	<div class="col-md-4">
                            	<div class="form-group m-form__group">
                                      <label>Quantity</label>
                                      <input type="text" class="form-control m-input m-input--square" id="qty_default" name="qty_default" placeholder="Quantity">
                                </div>
                            </div>
                             <div class="col-md-4">
                            	<div class="form-group m-form__group">
                                      <label>Price<span class="text-danger">*</span></label>
                                      <input type="text" class="form-control m-input m-input--square" id="price_default" name="price_default" placeholder="Price">
                                </div>
                            </div>
                            <div class="col-md-4">
                            	<div class="form-group m-form__group">
                                      <label>Discount&nbsp;(%)<span class="text-danger">*</span></label>
                                      <input type="text" class="form-control m-input m-input--square" id="discount_default" name="discount_default" placeholder="Discount">
                                </div>
                            </div>
                         </div>
                     </div>

                </fieldset>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-lg-12 ml-lg-auto">
                                <button class="btn btn-warning" type="button" onclick="back(2)"><span
                                            class="fa fa-arrow-left"></span> Back
                                </button>
                                <button class="btn btn-primary pull-right" type="button" onclick="next(4)">Next <span
                                            class="fa fa-arrow-right"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="sf4" class="frm add-shipping" style="display: none">
                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Step 4 of 5 - Shipping Detail</h4>
                </div>
                
                <fieldset class="m-portlet__body shipping_details"
                          ng-init="ships_from='India';manufacture_country='India'">
                    	<div class="form-group m-form__group" id="shipping_id_div">
                            <label>Shipping Class<span class="text-danger">*</span></label>
                            <select name="shipping_class" id="shipping_class" class="custom-select col-md-6">

                            </select>
                            <p id="shipping_id_message" class="text-danger"></p>
                    	</div>
                    	<div id="shippingDiv" class="col-md-12">

                    	</div>
                    	<div class="row">
                    		<div class="col-md-6">
                    			<div class="form-group m-form__group">
                                      <label>weight</label>
                                      <input type="text" class="form-control m-input m-input--square" id="weight" name="weight" placeholder="weight">
                                </div>
                                <div class="form-group m-form__group">
                                      <label>Height</label>
                                      <input type="text" class="form-control m-input m-input--square" id="height" name="height" placeholder="Height">
                                </div>
                    		</div>
                    		<div class="col-md-6">
                    			<div class="form-group m-form__group">
                                      <label>Length</label>
                                      <input type="text" class="form-control m-input m-input--square" id="length" name="length" placeholder="Length">
                                </div>
                                <div class="form-group m-form__group">
                                      <label>Width</label>
                                      <input type="text" class="form-control m-input m-input--square" id="width" name="width" placeholder="Width">
                                </div>
                    		</div>
                    	</div>

                </fieldset>

                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-lg-12 ml-lg-auto">
                                <button class="btn btn-warning" type="button" onclick="back(3)"><span
                                            class="fa fa-arrow-left"></span> Back
                                </button>
                                <button class="btn btn-primary open1 pull-right" type="button" onclick="next(5)">Next <span
                                            class="fa fa-arrow-right"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="sf5" class="frm" style="display: none">
                <div class="m-portlet__head">
                    <h4 class="m-portlet__head-caption">Step 5 of 5 - Return &amp; Exchange</h4>
                </div>
                
                <fieldset class="m-portlet__body policy">
                    <div class="form-group m-form__group">
                        <label >Return Policy<span class="text-danger">*</span></label>
                            <select name="return" id="return" class="custom-select col-md-6">
                                <option value="No Return">No return</option>
                                <option value="15 days return">15 days return</option>
                                <option value="30 days return">30 days return</option>
                            </select>
                    </div>
                    <div class="form-group m-form__group">
                         <div class="m-checkbox-inline">
                            <label class="m-checkbox">
                                <input id="use_exchange" name="use_exchange" type="checkbox" checked=""> Use same rules for exchanges
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
                        <label>Return Policy Description</label>
                        <textarea id="return_policy_description" name="return_policy_description"
                                  class="form-control">This item is non-returnable and non-exchangeable.</textarea>
                    </div>
                    <div class="form-group m-form__group m--hide" id="excnageDescDiv">
                        <label>Exchange Policy Description</label>
                        <textarea id="exchange_policy_description" name="exchange_policy_description"
                                  class="form-control">This item is non-returnable and non-exchangeable.</textarea>
                    </div>
                    <div class="form-group m-form__group">
                        <button type="button" id="descReset" class="btn btn-primary">Reset</button>
                    </div>
                </fieldset>

                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-lg-12 ml-lg-auto">
                                <button class="btn btn-warning" type="button" onclick="back(4)"><span
                                            class="fa fa-arrow-left"></span> Back
                                </button>
                                <button class="btn btn-primary open1 pull-right" type="submit">Submit</button>
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
<script type="text/javascript">

        $("#productImage").dropzone(
                {
                    url: "{{url(route('uploadImage'))}}" ,
                    acceptedFiles: "image/*",
                    maxFiles: 5, // Maximum Number of Files
                    maxFilesize: 2, // MB
                    addRemoveLinks: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(file, response){
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
                    // maxFilesize: 2, // MB
                    addRemoveLinks: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (file, response) {
                        $("#productVideo").append(response);
                        console.log(response);
                        // alert(response);
                    }
                }
        );

   		function next(page_id) {
            var current_page = page_id - 1;
            var vendor_id = $("#vendor_id").val();
            var product_title = $("#product_title").val();
            var brand_name = $("#brand_name").val();
            var long_description = $("#long_description").val();
            if(page_id == '2'){
                if (vendor_id != '' && product_title != '' && brand_name != '' && long_description != '') {
                    $("#sf" + page_id).css("display", "block");
                    $("#sf" + current_page).css("display", "none");
                } else {
                    $("#vendor_id_message").text("Please select Vendor. It's Required.");
                    $("#vendor_id_div").addClass('has-danger');
                    $("#product_title_message").text("Title is Required.");
                    $("#product_title_div").addClass('has-danger');
                    $("#brand_name_message").text("Brand Name is Required.");
                    $("#brand_name_div").addClass('has-danger');
                    $("#long_description_message").text("Description is Required.");
                    $("#long_description_div").addClass('has-danger');
                }
            }
            if(page_id == '3'){
                var productImages = $("#productImages").val();
                if(productImages != ''){
                    $("#sf"+page_id).css("display", "block");
                    $("#sf"+current_page).css("display", "none");
                }else{
                    $("#productImage_message").text("Please choose at least one image.");
                    $("#productImage_div").addClass('has-danger');
                }
            }
            if(page_id == '4'){
                var attribute = $("#m_select2_3").val();
                var category_id = $("#category_id").val();
                if(attribute != '' && category_id !=''){
                    $("#sf"+page_id).css("display", "block");
                    $("#sf"+current_page).css("display", "none");
                }else{
                    $("#attribute_id_message").text("Please choose at least one attribute.");
                    $("#attribute_id_div").addClass('has-danger');
                    $("#category_id_message").text("Please select Category.");
                    $("#category_id_div").addClass('has-danger');
                }
            }
            if(page_id == '5'){
                var shipping_class = $("#shipping_class").val();
                if(shipping_class !=''){
                    $("#sf"+page_id).css("display", "block");
                    $("#sf"+current_page).css("display", "none");
                }else{
                    $("#shipping_id_message").text("Please select Shipping Class.");
                    $("#shipping_id_div").addClass('has-danger');
                }
            }

            //var form = $("#store_create");

        }

        function back(page_id) {
            var current_page = page_id+1;
            $("#sf"+page_id).css("display", "block");
            $("#sf"+current_page).css("display", "none");
        }
        var getCombinations = function(allOptionsArray, combination) {
    	    if(allOptionsArray.length > 0) {
        	   for(var i=0; i < allOptionsArray[0].length; i++) {
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

        $('input[type=radio][name=combination]').on('change', function() {
        	if($(this).val() == 'Yes'){
        		$("#combinationDiv").removeClass("m--hide");
        		var data = $('#m_select2_3').select2('data');
        		var tem_color = [];
        		var attr=[];
        		var attr_val=[];
        		$.each(data, function(i, item) {
            		name=item.text.replace(' ','_');
            		$('#div'+item.id+' input:text').each(function(index){
            			attr_val.push($(this).val());
               		});
            		attr[item.id]=(attr_val);
            		attr_val=[];
        		});
        		attr = $.grep(attr ,function(n){
    			    return(n);
    			});
        		var htmlText = '';
        		var combination = {codes : [], result : [], counter : 0};
    			var productName='';


            	getCombinations(attr, combination);
            	$.each(combination.result, function (i,item){
        				productName = item.join('-');

        			productName = item.join('-');
            		htmlText += '<div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">';
             		htmlText += '<div class="m-demo__preview">';
             		htmlText += '<h4>'+productName+'</h4>';
             		htmlText += '<input type="hidden" value="'+productName+'" id="combination_name" name="combination_name[]">';
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
        	else{
        		$("#defultPrice").removeClass("m--hide");
        		$("#combinationDiv").addClass("m--hide");
        	}
       })

        $("#use_exchange").on('change',function (){
               if($('#use_exchange').is(':checked') == true){
                   $("#excnageDiv").removeClass("m--hide");
                   $("#excnageDescDiv").removeClass("m--hide");
               }else{
            	   $("#excnageDiv").addClass("m--hide");
            	   $("#excnageDescDiv").addClass("m--hide");
               }
        });

        $("#descReset").on('click', function () {
            $("#exchange_policy_description").text("");
            $("#return_policy_description").text("");
        });

        $('#vendor_id').on('change', function (e) {
        	var vendor_id = $('#vendor_id').val();
        	var html = '';
            var htmlCate = '';
            htmlCate += "<option value=''>Select Category</option>";
            $('#category_id').empty();
            $.ajax({
                url:  baseUrl + '/get/product/product_category/added_by_user_id',
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
                    //console.log(obje.length);
                    if(obje.length > 0){

                        $.each(obje, function(i, items) {

                            htmlCate += "<option value='"+ items.id +"'>"+items.category_name +"</option>";
                        });

                    }
                    //console.log(htmlCate);
                    $('#category_id').append(htmlCate);

                }});
            $('#shipping_class').empty();
        	$.ajax({
                url:  baseUrl + '/get/shippingClass/shipping/vendor_id',
                method: "POST",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data:{
                    'value' : vendor_id
                },
                success: function(result){
                    var obj = jQuery.parseJSON(result);
                    obj = $.grep(obj ,function(n){
                        return(n);
                    });
                    if(obj.length >0){
                    	html += "<option value=''>Select Shipping Class</option>";
                        $.each(obj, function(i, item) {
                            html += "<option value='"+ item.id +"'>"+item.shipping_class +"</option>";
						});
                        $('#shipping_class').append(html);
                    }else{
                        html += "<option value=''>Select Shipping Class</option>";
                        $('#shipping_class').append(html);
                    }

                }});


        });
        $('#shipping_class').on('change', function (e) {
        	var shippingClass = $('#shipping_class').val();
        	var vendor_id = $('#vendor_id').val();
        	var html = '';
        	$.ajax({
                url:  baseUrl + '/get/shippingData/shipping/id',
                method: "POST",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data:{
                	'value' : shippingClass,
                	'id' : vendor_id

                },
                success: function(result){
                    var obj = jQuery.parseJSON(result);
                    console.log(obj);
                     if(obj.length >0){
                        $.each(obj, function(i, item) {
                            var cityName='';

                           if (item.city_name.length > 20)
                        	{
                        		cityName = item.city_name.substring(0,20)+'...';
                        	} else{
                        		cityName = item.city_name;
                            	}
                            html +='<div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">';
                        	html +='<div class="m-demo__preview">';
                        	html +='<div class="row">';
                        	html +='<div class="col-md-4">';
                        	html +='<div class="form-group m-form__group">';
                        	html +='<label>Country : </label>';
                        	html +='<input type="hidden" id="country_id" name="country_id[]" value="'+item.country_id+'">';
                        	html +='<input type="hidden" id="country_name" name="country_name[]" value="'+item.country_name+'">';
                        	html +='<label>'+item.country_name+'</label>';
                        	html +='</div>';
                        	html +='</div>';

                        	html +='<div class="col-md-4">';
                        	html +='<div class="form-group m-form__group">';
                        	html +='<label>City : </label>';
                        	html +='<input type="hidden" id="city_id" name="city_id[]" value="'+item.city_id+'">';
                        	html +='<input type="hidden" id="city_name" name="city_name[]" value="'+cityName+'">';
                        	html +='<label>'+cityName+'</label>';
                     	    html +='</div>';
                        	html +='</div>';
                        	html +='<div class="col-md-4">';
                        	html +='<div class="form-group m-form__group">';
                        	html +='<label>Charges</label>';
                        	html +='<input type="text" class="form-control m-input m-input--square" id="charge" name="charge[]" placeholder="Charges" value="'+item.shipping_charge+'">';
                        	html +='</div>';
                        	html +='</div>';
                        	html +='<div class="col-md-4">';
                        	html +='<div class="form-group m-form__group">';
                        	html +='<label>Delivery&nbsp;Day&nbsp;1</label>';
                        	html +='<input type="text" class="form-control m-input m-input--square" id="day_1" name="day_1[]" placeholder="Delivery Day 1" value="'+item.delivery_day_1+'">';
                        	html +='</div>';
                        	html +='</div>';
                        	html +='<div class="col-md-4">';
                        	html +='<div class="form-group m-form__group">';
                        	html +='<label>Delivery&nbsp;Day&nbsp;2</label>';
                        	html +='<input type="text" class="form-control m-input m-input--square" id="day_2" name="day_2[]" placeholder="Delivery Day 2" value="'+item.delivery_day_2+'">';
                        	html +='</div>';
                        	html +='</div>';
                        	html +='</div>';
                        	html +='</div>';
                     		html +='</div>';

						});
                        $('#shippingDiv').append(html);
                    }

                }});

        });

        $('#m_select2_3').on('select2:unselecting', function (e) {
        	var unselected_value = $('#m_select2_3').val();
        	var divName = "div"+unselected_value;
        	 $("#"+divName).remove();

        });
        $("#m_select2_3").on('select2:select', function (e) {
            var htmlText='';
            //var ids = $('option', this).filter(':selected:last').val();
            //var name = $('option', this).filter(':selected:last').text();
            var data = $('#m_select2_3').select2('data');
            var ids='';
            $.each(data, function(i, item) {
          		 name=item.text;
          		 ids = item.id;
          		if ($('#div'+ids).length < 1 ) {
          			htmlText += '<div class="col-md-12" id="div'+ids+'">';
             		htmlText += '<div class="m-form__group form-group">';
             		htmlText += '<label>'+name +' Options</label>';
             		htmlText += '<input type="hidden" name="'+name+'[]" id="'+name+'" value="'+ids+'">';
             		htmlText += '</div>';
             		htmlText += '<div class="row" id="attr_div'+ids+'">';
             		htmlText += '<div class="col-md-3" style="margin-top:10px" id="'+ids+'div">';
             		htmlText += '<div class="m-form__group form-group">';
             		htmlText += '<input type="text" class="form-control m-input m-input--square" id="'+name+'Value" name="'+name+'Value[]">';
             		htmlText += '</div>';
             		htmlText += '</div>';
             		htmlText += '</div>';
             		htmlText += '<div class="m-form__group form-group">';
             		htmlText += '<button class="btn btn btn-primary m-btn m-btn--icon" style="margin-bottom:10px" type="button" id="addbutton" onclick="addDiv(\''+ids+'\',\''+name+'\');"><i class="la la-plus"></i></button>';
             		htmlText += '</div>';
             		htmlText += '</div>';
             		$('#attrValue').append(htmlText);
          	    }
       		});
         });
        function addDiv(ids,name) {
        	 var htmlText="";
             htmlText += '<div class="col-md-3" style="margin-top:10px" id="'+ids+'div">';
             htmlText += '<div class="m-form__group form-group">';
     		htmlText += '<input type="text" class="form-control m-input m-input--square" id="'+name+'Value" name="'+name+'Value[]">';
     		htmlText += '</div>';
     		htmlText += '</div>';
             $(htmlText).insertAfter("#"+ids+"div");
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
        $(document).ready(function () {

            keywords();
        	if($('#use_exchange').is(':checked') == true){
                $("#excnageDiv").removeClass("m--hide");
                $("#excnageDescDiv").removeClass("m--hide");
            }
        	$("#m_select2_3").select2({
            	placeholder: "Select Attribute "
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
