@extends('layouts.'.$loginUser)

@section('content')
    @php
        $pageTitle ="productCategoryAdd";
     $contentTitle =empty($productCategory) ? 'Create Product Category' : 'Edit Product Category';
    @endphp
    <!--begin::Portlet-->
    <?php //echo "<pre>";echo $productCategory['id'];print_r($productCategory);die; ?>
    <div class="m-portlet m-portlet--tab">
        @include($loginUser.'.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        <?php echo (empty($productCategory)) ? 'Create Product Category' : 'Edit Product Category'; ?>
                    </h3>
                </div>
            </div>
        </div>


        <form id="store_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="<?php echo (!empty($productCategory)) ?  (url(route('productCategoryUpdate',['productCategory'=>$productCategory['id']]))) : (url(route('products-category.store'))); ?>" novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="<?php if(!empty($productCategory)){ echo $productCategory->id; } ?>">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group" >
                            <label for="vendor_category_name">Category Name : <span class="danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="category_name" name="category_name" value="<?php if(!empty($productCategory)){ echo $productCategory->category_name ; } ?>" placeholder="Category Name" >
                        </div>
                        <div class="form-group m-form__group" >
                            <label for="vendor_category_name_ar">Category Name (Arabic) : </label>
                            <input type="text" class="form-control m-input m-input--square" id="category_name_ar" name="category_name_ar" value="<?php if(!empty($productCategory)){ echo $productCategory->category_name_ar ; } ?>" placeholder="Arabic Category Name" >
                        </div>

                        <div class="form-group m-form__group" >
                            <label for="featured">Category : </label>
                            <select class="custom-select col-md-6" name="parent_category_id" id="parent_category_id">
                                <option value="" selected="">Select Parent Category</option>
                                <?php foreach($category as $list) { ?>
                                <option value="<?php echo $list->id; ?>" <?php echo (!empty($productCategory) && ($productCategory->parent_category_id == $list->id)) ? 'selected' : ''; ?>><?php echo $list->category_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group m-form__group" >
                            <label for="vendor_category_icon">Category Icon : <span class="danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="category_icon" name="category_icon" value="<?php if(!empty($productCategory)){ echo $productCategory->category_icon ; } ?>" placeholder="Ex: fa fa-users" >
                        </div>
                        <div class="form-group m-form__group" >
                            <label for="description">Description : </label>
                            <textarea class="form-control m-input m-input--square" id="description" name="description"><?php if(!empty($productCategory)){ echo $productCategory->description ; } ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="profile_image">Category Image</label><br>
                            <label class="custom-file">
                                <input type="file" name="category_image"
                                       id="category_image" class="custom-file-input" onchange="readURL(this);">
                                <span class="custom-file-control"></span>
                            </label>
                        </div>
                        
                        <div class="form-group m-form__group" >
                            <input type="hidden"name="category_image_1" id="category_image_1" value="<?php if(!empty($productCategory->category_image)) { echo $productCategory->category_image;} ?>">
                            <img id="blah" src="<?php echo !empty($productCategory->category_image) ? url('/doc/category_image').'/'.$productCategory->category_image : url('assets/app/media/img/no_category_image_100.png'); ?>" height="50">

                        </div>
                        <div class="form-group m-form__group">
                            <label for="profile_image">Background Image</label><br>
                            <label class="custom-file">
                                <input type="file" name="background_image"
                                       id="background_image" class="custom-file-input" onchange="readURL1(this);">
                                <span class="custom-file-control"></span>
                            </label>
                        </div>
                        <div class="form-group m-form__group" >
                            <input type="hidden"name="background_image_1" id="background_image_1" value="<?php if(!empty($productCategory->background_image)) { echo $productCategory->background_image;} ?>">
                            <img id="blah1" src="<?php echo !empty($productCategory->background_image) ? url('/doc/category_image/mobile').'/'.$productCategory->background_image : url('assets/app/media/img/no_category_image_100.png'); ?>" height="50">
                        </div>
                        <?php  $checked='';$no_checked='';
                        if(!empty($productCategory)){
                            if($productCategory->featured === 'No'){
                                $no_checked='checked="checked"';
                            }else if($productCategory->featured === 'Yes'){
                                $checked='checked="checked"';
                            }
                        } else {
                            $no_checked='checked="checked"';
                        } ?>
                        <?php  $status='';$no_status='';
                        if(!empty($productCategory)){
                            if($productCategory->status === 'Active'){
                                $status='checked="checked"';
                            }else if($productCategory->status === 'Inactive'){
                                $no_status='checked="checked"';
                            }
                        } else {
                            $status='checked="checked"';
                        } ?>
                        <div class="form-group m-form__group" >
                            <label for="status">Status : </label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Active" <?php echo $status; ?>> Active
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Inactive" <?php echo $no_status;?>> Inactive
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group m-form__group" >
                            <label for="featured">Is Featured : </label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="featured" value="No" <?php echo $no_checked; ?>> No
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="featured" value="Yes" <?php echo $checked;?>> Yes
                                    <span></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-9 ml-lg-auto">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--end::Portlet-->
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function ()
        {
            $("#store_create").validate({
                rules: {
                    category_name: {
                        required: true,
                        minlength: 2
                    },
                    category_icon: {
                        required: true
                    }
                },
                messages: {
                    category_name: {
                        required: "Category Name is required"
                    },
                    category_icon: {
                        required: "Category Icon is required"
                    }
                },
                submitHandler: function (form)
                {
                    form.submit();
                }
            });
        });
    </script>
@endsection
