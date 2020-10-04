@extends('layouts.vendor')
@section('title') Social Media Detail @endsection
@php
$pageTitle ="Manage Social Media";
@endphp
@section('css')
<style type="text/css">
    .clsTimeRow
    {
        margin: 5px;
        /*border: 1px groove #EFEFEF;*/
        vertical-align: middle;
    }
    .
    .clsTimeRow div
    {
        vertical-align: middle;        
    }
    .m-form .form-control-feedback {
        margin-top: 0.2rem;
        color: #FF0000;
    }
</style>
@endsection
@section('content')
@php
$pageTitle ="Manage Social Media";
@endphp
<!--begin::Portlet-->
<div class="m-portlet">
    @include('vendor.common.flash') 
    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-truck"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        @yield('title')
                    </h3>
                </div>
            </div>
        </div>
        <form id="social_media" method="post" action="{{ url(route('social-media.store')) }}" class="m-form m-form--fit m-form--label-align-right form" novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id" value="<?php echo ( ! empty($coupon)) ? $coupon->id : ''; ?>"/>
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="facebook">Facebook</label>
                            <input type='url' class="form-control m-input" id="facebook" name="facebook"  placeholder="Facebook" value=" {{ $social_media_detail->facebook or "" }}" />
                        </div>
                        <div class="form-group m-form__group">
                            <label for="twitter">Twitter</label>
                            <input type='url' class="form-control m-input" id="twitter" name="twitter"  placeholder="Twitter" value=" {{ $social_media_detail->twitter or "" }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="google_plus">Google Plus</label>
                            <input type='url' class="form-control m-input" id="google_plus" name="google_plus"  placeholder="Google Plus" value=" {{ $social_media_detail->google_plus or "" }}" />
                        </div>
                        <div class="form-group m-form__group">
                            <label for="instagram">Instagram</label>
                            <input type='url' class="form-control m-input" id="instagram" name="instagram"  placeholder="Instagram" value=" {{ $social_media_detail->instagram or "" }}" />
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
</div>
<!--end::Portlet-->
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#social_media").validate({
            rules: {
                facebook: {
                    url: true
                },
                twitter: {
                    url: true
                },
                google_plus: {
                    url: true
                },
                instagram: {
                    url: true
                },
            },
            messages: {
                facebook: {
                    url: "Please enter valid Facebook Link"
                },
                twitter: {
                    url: "Please enter valid Twitter Link"
                },
                google_plus: {
                    url: "Please enter valid Google Plus Link"
                },
                instagram: {
                    url: "Please enter valid Instagram Link"
                },
            },
            submitHandler: function (form)
            {
                var IsValid = true;
                form.submit();
            }
        });
    });
</script>

@endsection
