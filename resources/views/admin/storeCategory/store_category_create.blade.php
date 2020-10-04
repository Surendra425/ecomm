@extends('layouts.admin')

@section('content')
    @php
        $pageTitle ="storesCategoryAdd";
     $contentTitle =empty($vendorCategory) ? 'Create Store Category' : 'Edit Store Category';
    @endphp
    <!--begin::Portlet-->
    <?php //echo "<pre>";echo $vendorCategory['id'];print_r($vendorCategory);die; ?>
    <div class="m-portlet m-portlet--tab">
        @include('admin.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        <?php echo (empty($vendorCategory)) ? 'Create Store Category' : 'Edit Store Category'; ?>
                    </h3>
                </div>
            </div>
        </div>

        <form id="store_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data"
              method="post"
              action="<?php echo (!empty($vendorCategory)) ? (url(route('AdminStoreCategoryUpdate', ['vendorCategory' => $vendorCategory['id']]))) : (url(route('stores-category.store'))); ?>"
              novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{$vendorCategory->id or ''}}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-12 m-form__group">
                        <div class="form-group m-form__group">
                            <label for="vendor_category_name">Category Name : <span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="vendor_category_name"
                                   name="vendor_category_name" value="{{$vendorCategory->vendor_category_name or ''}}" placeholder="Category Name">
                        </div>
                    </div>
                    <div class="col-md-6 m-form__group">
                        <?php  $status = '';$no_status = '';
                        if (!empty($vendorCategory)) {
                            if ($vendorCategory->status === 'Active') {
                                $status = 'checked="checked"';
                            } else if ($vendorCategory->status === 'Inactive') {
                                $no_status = 'checked="checked"';
                            }
                        } else {
                            $status = 'checked="checked"';
                        } ?>
                        <div class="form-group m-form__group">
                            <label for="status">Status : </label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Active" {{$status}}> Active
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Inactive" {{$no_status}}> Inactive
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 m-form__group">
                        <?php  $checked = '';$no_checked = '';
                        if (!empty($vendorCategory)) {
                            if ($vendorCategory->featured === 'No') {
                                $no_checked = 'checked="checked"';
                            } else if ($vendorCategory->featured === 'Yes') {
                                $checked = 'checked="checked"';
                            }
                        } else {
                            $no_checked = 'checked="checked"';
                        } ?>
                        <div class="form-group m-form__group">
                            <label for="featured">Is Featured : </label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="featured" value="No" {{$no_checked}}> No
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="featured" value="Yes" {{$checked}}> Yes
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
        $(document).ready(function () {
            $("#store_create").validate({
                rules: {
                    vendor_category_name: {
                        required: true,
                        minlength: 2,
                        remote: {
                            url: baseUrl + '/check/unique/vendor_category/vendor_category_name',
                            type: "post",
                            data: {
                                value: function () {
                                    return $("#vendor_category_name").val();
                                },
                                id: function () {
                                    return $("#id").val();
                                },
                            },
                        }
                    }
                },
                messages: {
                    vendor_category_name: {
                        required: "Category Name is required",
                        remote: "Store Category Name is already exists."
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
