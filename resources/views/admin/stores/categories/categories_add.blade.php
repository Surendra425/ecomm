@extends('layouts.admin')
@section('title') <?php echo (empty($category_data)) ? 'Create Product Category' : 'Edit Product Category'; ?> @endsection

@section('content')
@php
    $pageTitle ="Create Product Category";
    $contentTitle = (empty($category_data)) ? 'Create Product Category' : 'Edit Product Category';
@endphp
<!--begin::Portlet-->
<?php //echo "<pre>";echo $category_data['id'];print_r($category_data);die; ?>
<div class="m-portlet m-portlet--tab">
    @include('vendor.common.flash')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    @yield('title')
                </h3>
            </div>
        </div>
    </div>

    <form id="store_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data"
          method="post"
          action="<?php echo ( ! empty($category_data)) ? (url(route('updateStoreVendorCategory', ['category' => $category_data->id]))) : (route('vendorStoreCategoryStore')); ?>"
          novalidate>
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id" value="{{$category_data->id or ''}}">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group m-form__group">
                        <label for="vendor_category_name">Category Name : <span class="danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="category_name"
                               name="category_name" value="{{$category_data->category_name or ''}}" placeholder="Category Name">
                    </div></br>
                </div>

                <input type="hidden" name="vendor_id" value="{{ $vendor_id }}">
                <input type="hidden" name="store_id" value="{{ $store_id }}">

                <div class="col-md-6">
                    @php $checked='';$no_checked='checked'; @endphp
                    @if(!empty($category_data))
                    @if($category_data->featured === 'No')
                    @php $no_checked='checked="checked"'; @endphp
                    @elseif($category_data->featured === 'Yes')
                    @php $checked='checked="checked"'; @endphp
                    @else
                    @php $no_checked='checked="checked"'; @endphp
                    @endif
                    @endif
                    <div class="form-group m-form__group">
                        <label for="featured">Is Featured : </label>
                        <div class="m-radio-inline">
                            <label class="m-radio">
                                <input type="radio" name="featured" value="No" {{$no_checked}} required=""> No
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="featured" value="Yes" {{$checked}}> Yes
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="profile_image">Category Image</label><br>
                        <label class="custom-file">
                            <input type="file" name="category_image"
                                   id="category_image" class="custom-file-input" onchange="readURL(this);">
                            <span class="custom-file-control text-"></span>
                        </label>
                        <span class="danger" id="categoryImage"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    @php  $status='checked';$no_status=''; @endphp
                    @if(!empty($category_data))
                    @if($category_data->status === 'Active')
                    @php $status='checked="checked"'; @endphp
                    @elseif($category_data->status === 'Inactive')
                    @php $no_status='checked="checked"'; @endphp
                    @else {
                    @php $status='checked="checked"'; @endphp
                    @endif
                    @endif
                    <div class="form-group m-form__group">
                        <label for="status">Status : </label>
                        <div class="m-radio-inline">
                            <label class="m-radio">
                                <input type="radio" name="status" value="Active" {{$status}}> Active
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="status" value="Inactive" {{$no_status}}>
                                Inactive
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group m-form__group" >
                        <input type="hidden"name="category_image_1" id="category_image_1" value="{{(!empty($category_data->category_image)) ? $category_data->category_image : ''}}">
                        <img id="blah" src="<?php echo !empty($category_data->category_image) ? url('/doc/category_image') . '/' . $category_data->category_image :url('assets/app/media/img/no_category_image_100.png'); ?>" width="50" height="50">
                    </div>
                </div>
            </div>


        </div>
        <div class="m-portlet__foot m-portlet__foot--fit">
            <div class="m-form__actions m-form__actions">
                <div class="row">
                    <div class="col-lg-9 ml-lg-auto">
                        <button type="submit" id="submit" class="btn btn-success">Submit</button>
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
    $(document).ready(function(){

        var _URL = window.URL || window.webkitURL;

        $('#category_image').change(function () {
            var file = $(this)[0].files[0];
            img = new Image();
            var imgwidth = 0;
            var imgheight = 0;

            img.src = _URL.createObjectURL(file);
            img.onload = function() {
                imgwidth = this.width;
                imgheight = this.height;

                $("#width").text(imgwidth);
                $("#height").text(imgheight);
                if(imgwidth != imgheight ){
                    $("#categoryImage").text("Image height and width must be same");
                    $("#submit").attr('disabled','disabled');
                }else{
                    $("#categoryImage").text("");
                    $("#submit").removeAttr('disabled');
                }
            };
        });
    });
    $(document).ready(function ()
    {
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
            },
            submitHandler: function (form)
            {
                form.submit();
            }
        });
    });
</script>
@endsection
