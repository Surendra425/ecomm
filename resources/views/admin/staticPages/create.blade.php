@extends('layouts.admin')
@section('content')
    @php
        $pageTitle =$page->slug;
        $pageTitle1 ='pageAdd';
        $title = (empty($page)) ? 'Create Static Page' : 'Edit '.$page->page_name.' Content';
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
                        <?php echo (empty($page)) ? 'Create Static Page' : 'Edit '.$page->page_name.' Content'; ?>
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <form id="admin_create" class="m-form m-form--fit m-form--label-align-right form"
              enctype="multipart/form-data" method="post"
              action="<?php echo ( ! empty($page)) ? (url(route('pageUpdate', ['page' => $page['id']]))) : (url(route('pages.store'))); ?>"
              novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{$page->id or ''}}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group">
                            <label for="title">Page Name</label>
                            <p class="form-control-static" for="title">{{$page->page_name or ''}}</p>
                            {{--<input type="text" class="form-control m-input m-input--square" id="page_name"
                                   name="page_name" value="{{$page->page_name or ''}}" placeholder="Page Name">--}}
                        </div>

                        <div class="form-group m-form__group">
                            <label for="title">Headline</label>
                            <input type="text" class="form-control m-input m-input--square" id="headline"
                                   name="headline" value="{{$page->headline or ''}}" placeholder="Headline">
                        </div>

                        <div class="form-group m-form__group">
                            <label for="title">Arabic Headline</label>
                            <input type="text" class="form-control m-input m-input--square" id="headline_ar"
                                   name="headline_ar" value="{{$page->headline_ar or ''}}" placeholder="Arabic Headline">
                        </div>

                        <div class="form-group m-form__group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" placeholder="Description" class="summernote">{{$page->description or ''}}</textarea>
                            <label id="description-error" class="error" for="description" style="display: none">Description is required</label>
                        </div>

                        <div class="form-group m-form__group">
                            <label for="description_ar">Arabic Description</label>
                            <textarea name="description_ar" id="description_ar" placeholder="Arabic Description" class="summernote">{{$page->description_ar or ''}}</textarea>
                            <label id="description_ar-error" class="error" for="description_ar" style="display: none">Arabic Description is required</label>
                        </div>

                    </div>
                </div>

            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions">
                    <button type="submit" id="submit" class="btn btn-success">Submit</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Portlet-->



@endsection
@section('js')
    {{--<script src="{{url('assets/demo/default/custom/components/forms/widgets/summernote.js')}}" type="text/javascript"></script>--}}
    <script src="{{url('assets/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function ()
        {
            CKEDITOR.replace('description');
            CKEDITOR.replace('description_ar', {
                language: 'ar'
            });
            
            $("#admin_create").validate({
                rules: {

                    description: {
                        required: true,
                    },

                },
                messages: {

                    description: {
                        required: "Description is required",
                    },

                },
                submitHandler: function (form)
                {
                    $("#description-error").hide();
                    var IsValid = true;
                    var input = $("description");
                    var val = $("#description").val().replace(/<\/p>/gi, "\n").replace(/<br\/?>/gi, "\n").replace(/<\/?[^>]+(>|$)/g, "");
                    if ($.trim(val) == "")
                    {
                        $("#description-error").text("Description is required").show();
                        IsValid = false;
                        return false;
                    }
                    form.submit();
                }
            });
        });
    </script>
@endsection
