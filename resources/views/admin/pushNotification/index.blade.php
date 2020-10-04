@extends('layouts.admin')
@section('content')
    @php
        $pageTitle = 'Push Notifications';

        $contentTitle = 'Push Notifications';
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
                        <?php echo 'Send Push Notification'; ?>
                    </h3>
                </div>
            </div>
        </div>

        <!--begin::Form-->
        <form id="admin_create" class="m-form m-form--fit m-form--label-align-right form"
              enctype="multipart/form-data" method="post"
              action="{{ route('sendPushNotification') }}"
              novalidate>
            {{ csrf_field() }}

            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group m-form__group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control m-input m-input--square" id="title"
                                   name="title" value="" placeholder="Notification Title">
                        </div>

                        <div class="form-group m-form__group">
                            <label for="description">Message</label>
                            <textarea name="message" id="message" placeholder="Message" class="form-control"  rows="5"></textarea>
                            <label id="message-error" class="error" for="message" style="display: none">Message is required</label>
                        </div>

                        <div class="form-group m-form__group">
                            <label for="title">Arabic Title</label>
                            <input type="text" class="form-control m-input m-input--square" id="title_ar"
                                   name="title_ar" value="" placeholder="Arabic Notification Title">
                        </div>

                        <div class="form-group m-form__group">
                            <label for="description">Arabic Message</label>
                            <textarea name="message_ar" id="message_ar" placeholder="Arabic Message" class="form-control"  rows="5"></textarea>
                            <label id="message_ar-error" class="error" for="message_ar" style="display: none">Message is required</label>
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

    <script type="text/javascript">

        $(document).ready(function ()
        {
            //CKEDITOR.replace('description');

            $("#admin_create").validate({
                rules: {

                    title: {
                        required: true,
                    },
                    title_ar: {
                        required: true,
                    },
                    message: {
                        required: true,
                    },
                    message_ar: {
                        required: true,
                    },

                },
                messages: {

                    title: {
                        required: "Title is required",
                    },

                    title_ar: {
                        required: "Arabic Title is required",
                    },

                    message: {
                        required: "Message is required",
                    },
                    message_ar: {
                        required: "Arabic Message is required",
                    },

                },
                submitHandler: function (form)
                {
                    $("#message-error").hide();
                    var IsValid = true;
                    var input = $("description");
                    var val = $("#message").val().replace(/<\/p>/gi, "\n").replace(/<br\/?>/gi, "\n").replace(/<\/?[^>]+(>|$)/g, "");

                    if ($.trim(val) == "")
                    {
                        $("#message-error").text("Message is required").show();
                        IsValid = false;
                        return false;
                    }

                    form.submit();
                }
            });
        });
    </script>
@endsection
