@extends('layouts.admin')


@section('content')
    @php
        $pageTitle ="countryAdd";
    $contentTitle =empty($country) ? 'Create Country' : 'Edit Country';
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
                        <?php echo (empty($country)) ? 'Create Country' : 'Edit Country'; ?>
                    </h3>
                </div>
            </div>
        </div>
        <form id="country_create" class="m-form m-form--fit m-form--label-align-right form"
              enctype="multipart/form-data" method="post"
              action="<?php echo (!empty($country)) ? (url(route('countryUpdate', ['country' => $country['id']]))) : (url(route('country.store'))); ?>"
              novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="{{$country->id or ''}} ?>">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="country_name">Country Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="country_name"
                                   name="country_name" value="{{$country->country_name or ''}}" placeholder="Country Name" @if(isset($country) &&$country->country_name != '' && $country->country_name == 'KUWAIT') readonly @endif>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="country_name">Country Name (Arabic)</label>
                            <input type="text" class="form-control m-input m-input--square" id="country_name_ar"
                                   name="country_name_ar" value="{{$country->country_name_ar or ''}}" placeholder="Arabic Country Name" @if(isset($country) &&$country->country_name_ar != '' && $country->country_name_ar == 'الكويت') readonly @endif>
                        </div>
                        <div class="form-group m-form__group">
                            <label for="short_name">Country Short Name </label>
                            <input type="text" class="form-control m-input m-input--square" id="short_name"
                                   name="short_name" value="{{$country->short_name or ''}}" placeholder="e.g. For Kuwait - KWT or KW">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="country_code">Country Code </label>
                            <input type="text" class="form-control m-input m-input--square" id="country_code"
                                   name="country_code" value="{{$country->country_code or ''}}" placeholder="Country Code">
                        </div>
                        <?php  $status = '';$no_status = '';
                        if (!empty($country)) {
                            if ($country->status === 'Active') {
                                $status = 'checked="checked"';
                            } else if ($country->status === 'Inactive') {
                                $no_status = 'checked="checked"';
                            }
                        } else {
                            $status = 'checked="checked"';
                        } ?>
                        <div class="form-group m-form__group">
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
            $("#country_create").validate({
                rules: {
                    country_name: {
                        required: true,
                        minlength: 2,
                        remote: {
                            url: baseUrl + '/check/unique/country/country_name',
                            type: "post",
                            data: {
                                value: function () {
                                    return $("#country_name").val();
                                },
                                id: function() {
                                    return $( "#id" ).val();
                                },
                            },

                        }
                    }
                },
                messages: {
                    country_name: {
                        required: "Country Name is required",
                        remote: "Country is already exists."
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
