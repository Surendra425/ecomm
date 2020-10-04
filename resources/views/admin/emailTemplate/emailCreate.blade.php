@extends('layouts.admin')
@section('content')
    @php
        $pageTitle ="emailTemplateAdd";
     $contentTitle =empty($emailTemplate) ? 'Create Email Template' : 'Edit Email Template';
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
                        <?php echo (empty($emailTemplate)) ? 'Create Email Template' : 'Edit Email Template'; ?>
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <form id="email_create" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data"
              method="post"
              action="<?php echo (!empty($emailTemplate)) ? (url(route('emailTemplateUpdate', ['email' => $emailTemplate['id']]))) : (url(route('email-template.store'))); ?>"
              novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="<?php if (!empty($emailTemplate)) {
                echo $emailTemplate->id;
            } ?>">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-form__group">
                            <label for="city_name">Title<span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="name"
                                   name="name" value="{{(!empty($emailTemplate))? $emailTemplate->name : ''}}" placeholder="Email Title">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="city_name">Subject<span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="subject"
                                   name="subject" value="{{(!empty($emailTemplate))? $emailTemplate->subject : ''}}" placeholder="Email Subject">
                        </div>
                        <div class="form-group m-form__group">
                            <label for="city_name">Content<span class="text-danger">*</span></label>
                            <textarea class="summernote" id="email_content" name="email_content"  placeholder="Email Content">{{(!empty($emailTemplate))? $emailTemplate->email_content : ''}}</textarea>
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
    <script src="{{url('assets/demo/default/custom/components/forms/widgets/summernote.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#email_create").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    subject: {
                        required: true
                    },
                    email_content: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Email Template is required"
                    },
                    subject: {
                        required: "Email Subject is required"
                    },
                    email_content: {
                        required: "Email Content is required"
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
