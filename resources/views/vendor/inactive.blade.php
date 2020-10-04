@extends('layouts.vendor')
@section('title') Dashboard @endsection
@section('content')
    @php
        $pageTitle ="dashboard";
    @endphp
    <div class="m-portlet">
        @include('vendor.common.flash')
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-md-12">
                    <!--begin:: Widgets/Stats2-1 -->

                    <div class="m-widget1">
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">Your store is inactive please contact to admin.</h3>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>

@endsection
