@extends('layouts.admin')
@section('content')
    @php
        $pageTitle ="cityAdd";
    $contentTitle =empty($city) ? 'Create Area' : 'Edit Area';
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

                        <?php echo (empty($city)) ? 'Create Area' : 'Edit Area'; ?>
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <form id="city_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data"
              method="post"
              action="<?php echo (!empty($city)) ? (url(route('cityUpdate', ['city' => $city['id']]))) : (url(route('city.store'))); ?>"
              novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="<?php if (!empty($city)) {
                echo $city->id;
            } ?>">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group">
                            <label for="city_name">Area Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="city_name"
                                   name="city_name" value="{{$city->city_name or ""}}" placeholder="Area Name">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="city_name">Area Name (Arabic)</label>
                            <input type="text" class="form-control m-input m-input--square" id="city_name_ar"
                                   name="city_name_ar" value="{{$city->city_name_ar or ""}}" placeholder="Arabic Area Name">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="state_id">Country Name <span class="text-danger">*</span></label>
                            <select class="custom-select col-md-6" name="country_id" id="country_id">
                                <option value="" selected="">Select Country</option>
                                <?php foreach($country as $list) { ?>
                                <option value="<?php echo $list->id; ?>" <?php echo (!empty($city) && ($city->country_id === $list->id)) ? 'selected' : ''; ?>><?php echo $list->country_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php  $status = '';$no_status = '';
                        if (!empty($city)) {
                            if ($city->status === 'Active') {
                                $status = 'checked="checked"';
                            } else if ($city->status === 'Inactive') {
                                $no_status = 'checked="checked"';
                            }
                        } else {
                            $status = 'checked="checked"';
                        } ?>
                        <div class="form-group m-form__group">
                            <label for="status">Status</label>
                            <div class="m-radio-inline">
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Active" <?php echo $status; ?>> Active
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" name="status" value="Inactive" <?php echo $no_status;?>>
                                    Inactive
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Portlet-->


@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#city_create").validate({
                rules: {
                    city_name: {
                        required: true,
                    },
                    country_id: {
                        required: true
                    }
                },
                messages: {
                    city_name: {
                        required: "City Name is required",
                    },
                    country_id: {
                        required: "Please select country. It is required."
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
