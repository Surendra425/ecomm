@extends('layouts.admin')
@section('content')
    @php
        $pageTitle ="collectionAdd";
     $contentTitle =empty($collection) ? 'Create Collection' : 'Edit Collection';
    @endphp
    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--tab">
        @include('admin.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        <?php echo (empty($collection)) ? 'Create Collection' : 'Edit Collection'; ?>
                    </h3>
                </div>
            </div>
        </div>
        <form id="collection_create" class="m-form m-form--fit m-form--label-align-right form"
              enctype="multipart/form-data" method="post"
              action="<?php echo (!empty($collection)) ? (url(route('updateCollection', ['collection' => $collection['id']]))) : (url(route('collections.store'))); ?>"
              novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{$collection->id or ''}}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="collection_name">Collection Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="collection_name"
                                   name="collection_name" value="{{$collection->collection_name or ''}}" placeholder="Collection Name">
                        </div>

                        <div class="form-group m-form__group">
                            <label for="collection_name">Collection Name (Arabic)</label>
                            <input type="text" class="form-control m-input m-input--square" id="collection_name_ar"
                                   name="collection_name_ar" value="{{$collection->collection_name_ar or ''}}" placeholder="Arabic Collection Name">
                        </div>

                        <div class="form-group m-form__group">
                            <label for="collection_tagline">Tag Line <span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="collection_tagline"
                                   name="collection_tagline" value="{{$collection->collection_tagline or ''}}" placeholder="Tag Line">
                        </div>

                        <div class="form-group m-form__group">
                            <label for="profile_image">Collection Background Image</label><br>
                            <label class="custom-file">
                                <input type="file" name="background_image"
                                       id="background_image" class="custom-file-input" onchange="readURL(this);">
                                <span class="custom-file-control"></span>

                            </label>
<br>                        <label id="background_image-error" class="error" for="background_image"></label>
                            <span id="collection_msg" class="danger"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php  $status = '';$no_status = '';
                        if (!empty($collection)) {
                            if ($collection->status === 'Active') {
                                $status = 'checked="checked"';
                            } else if ($collection->status === 'Inactive') {
                                $no_status = 'checked="checked"';
                            }
                        } else {
                            $status = 'checked="checked"';
                        } ?>
                        <div class="form-group m-form__group">
                            <label for="status">Status </label>
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
                        <?php  $checked = '';$no_checked = '';
                        if (!empty($collection)) {
                            if ($collection->display_status === 'No') {
                                $no_checked = 'checked="checked"';
                            } else if ($collection->display_status === 'Yes') {
                                $checked = 'checked="checked"';
                            }
                        } else {
                            $no_checked = 'checked="checked"';
                        } ?>
                        <div class="form-group m-form__group">
                            <label for="display_status">Display In Slider? </label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="display_status" value="No" {{$no_checked}}> No
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="display_status" value="Yes" {{$checked}}> Yes
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <input type="hidden" name="background_image_1" id="background_image_1">
                            <img id="blah" src="{{ !empty($collection->background_image) ? url('/doc/collection_image') . '/' . $collection->background_image : url('assets/app/media/img/no-images.jpeg') }}"
                                 width="50" height="50">
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
            $('#background_image').change(function () {
                var file = $(this)[0].files[0];
                img = new Image();
                var imgheight = 100;
                var imgwidth = 100;

                img.src = _URL.createObjectURL(file);
                img.onload = function() {
                    imgheight = this.height;
                    imgwidth = this.width;
                    var minheight = (imgwidth / 12) * 7;
                    $("#height").text(imgheight);
                    console.log(imgheight);
                    console.log(imgwidth);
                    if(imgheight < 700 && imgwidth < 1200){
                        $("#collection_msg").text("Image height must be grater then 700px and width grater then 1200");
                        $("#submit").attr('disabled','disabled');
                    }
                    /*else if(minheight != imgheight)
                    {
                        $("#collection_msg").text("Invalid image resolution.");
                        $("#submit").attr('disabled','disabled');
                    }*/
                    else
                    {
                        $("#collection_msg").text("");
                        $("#submit").removeAttr('disabled');
                    }
                };
                img.onerror = function() {

                    $("#store_images_msg").text("not a valid file: " + file.type);
                }
            });
        });
        $(document).ready(function () {
            $("#collection_create").validate({
                rules: {
                    collection_name: {
                        required: true,
                        minlength: 2,
                    },
                    
                    collection_tagline: {
                        required: true,
                        minlength: 2,
                    },
                    background_image: {
                    	required: {{empty($collection) ? 'true' : 'false'}},
                    }
                },
                messages: {
                    collection_name: {
                        required: "Collection Name is required",
                        minlength: "Collection Name have atleast 2 character",
                        remote: "Collection is already exists."
                    },
                    collection_tagline: {
                        required: "Tag Line is required",
                        minlength: "Tag Line have atleast 2 character",
                        remote: "Collection Tag Line is already exists."

                    },
                    background_image: {
                    	required: 'Background image is required',
                    }
                }
            });
        });
    </script>
@endsection
