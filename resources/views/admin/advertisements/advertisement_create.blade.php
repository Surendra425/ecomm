@extends('layouts.admin')

@section('content')
@php
$pageTitle ="advertisementAdd";
 $contentTitle =empty($advertisement) ? 'Create Advertisement' : 'Edit Advertisement';
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
                    <?php echo (empty($advertisement)) ? 'Create Advertisement' : 'Edit Advertisement' ;?>
                </h3>
            </div>
        </div>
    </div>
    <form id="advertisement_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="{{ ( ! empty($advertisement)) ? (url(route('updateAdvertisement', ['advertisement' => $advertisement['id']]))) : (url(route('advertisement.store'))) }}" novalidate>
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id" value="{{ $advertisement->id or "" }}">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        <label for="advertisement_name">Advertisement Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="advertisement_name" name="advertisement_name" value="{{$advertisement->advertisement_name or "" }}" placeholder="Advertisement Name">
                    </div>
                    <div class="form-group m-form__group">
                        <label for="advertisement_tagline">Tag Line <span class="text-danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="advertisement_tagline" name="advertisement_tagline" value="{{ $advertisement->advertisement_tagline or "" }}" placeholder="Tag Line">
                    </div>
                    <div class="form-group m-form__group">
                        <label for="profile_image">Advertisement Background Image</label><br>
                        <label class="custom-file">
                            <input type="file" name="background_image" id="background_image" class="custom-file-input" onchange="readURL(this);">
                            <span class="custom-file-control"></span>
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="daterange">Select Date</label>
                        <div class='input-group' id='m_daterangepicker_2'>
                            <input type='text' class="form-control m-input" id="daterange" name="daterange" readonly  placeholder="Select date range"/>
                            <span class="input-group-addon"><i class="la la-calendar-check-o"></i></span>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <input type="hidden" name="background_image_1" id="background_image_1">
                        @if ( ! empty($advertisement->background_image))
                        <label class="custom-file">
                            <img id="blah" src="{{ url('/doc/advertisement_image') . '/' . $advertisement->background_image }}" width="50" height="50">
                        </label>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <?php
                    $status = '';
                    $no_status = '';
                    if ( ! empty($advertisement))
                    {
                        if ($advertisement->status === 'Active')
                        {
                            $status = 'checked="checked"';
                        }
                        else if ($advertisement->status === 'Inactive')
                        {
                            $no_status = 'checked="checked"';
                        }
                    }
                    else
                    {
                        $status = 'checked="checked"';
                    }
                    ?>
                    <div class="form-group m-form__group">
                        <label for="status">Status </label>
                        <div class="m-radio-inline">
                            <label class="m-radio">
                                <input type="radio" name="status" value="Active" {{ $status }} /> Active
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="status" value="Inactive" {{ $no_status }} /> Inactive
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <?php
                    $checked = '';
                    $no_checked = '';
                    if ( ! empty($advertisement))
                    {
                        if ($advertisement->display_status === 'No')
                        {
                            $no_checked = 'checked="checked"';
                        }
                        else if ($advertisement->display_status === 'Yes')
                        {
                            $checked = 'checked="checked"';
                        }
                    }
                    else
                    {
                        $no_checked = 'checked="checked"';
                    }
                    ?>
                    <div class="form-group m-form__group">
                        <label for="display_status">Display In Slider? </label>
                        <div class="m-radio-inline">
                            <label class="m-radio">
                                <input type="radio" name="display_status" value="Yes" {{ $checked }} /> Yes
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="display_status" value="No" {{ $no_checked }} /> No
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
                    <div class="col-lg-12 ml-lg-auto text-center">
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
<script src="{{ url('assets/demo/default/custom/components/forms/widgets/bootstrap-daterangepicker.js') }}" type="text/javascript"></script>
<script type="text/javascript">

                                       $(document).ready(function ()
                                       {
                                       $('input[name="daterange"]').daterangepicker({
                                       "locale": {
                                       "format": "YYYY-MM-DD"
                                       },
<?php
if ( ! empty($advertisement))
{
    ?>
                                           "startDate": "<?php echo date('Y-m-d', strtotime($advertisement->start_at)); ?>",
                                                   "endDate": "<?php echo date('Y-m-d', strtotime($advertisement->end_at)); ?>",
<?php } ?>
                                       "minDate": new Date()

                                       });
                                               $("#advertisement_create").validate({
                                       rules: {
                                       advertisement_name: {
                                       required: true,
                                               minlength: 2,
                                               remote: {
                                               url: baseUrl + '/check/unique/advertisements/advertisement_name',
                                                       type: "post",
                                                       data: {
                                                       value: function ()
                                                       {
                                                       return $("#advertisement_name").val();
                                                       },
                                                               id: function ()
                                                               {
                                                               return $("#id").val();
                                                               },
                                                       },
                                               }
                                       },
                                               advertisement_tagline: {
                                               required: true,
                                                       minlength: 2,
                                                       remote: {
                                                       url: baseUrl + '/check/unique/advertisements/advertisement_tagline',
                                                               type: "post",
                                                               data: {
                                                               value: function ()
                                                               {
                                                               return $("#advertisement_tagline").val();
                                                               },
                                                                       id: function ()
                                                                       {
                                                                       return $("#id").val();
                                                                       },
                                                               },
                                                       }
                                               }
                                       },
                                               messages: {
                                               advertisement_name: {
                                               required: "Advertisement Name is required",
                                                       minlength: "Advertisement Name have atleast 2 character",
                                                       remote: "Advertisement is already exists."
                                               },
                                                       advertisement_tagline: {
                                                       required: "Tag Line is required",
                                                               minlength: "Tag Line have atleast 2 character",
                                                               remote: "Advertisement Tag Line is already exists."

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
