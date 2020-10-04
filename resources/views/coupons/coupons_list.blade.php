@extends('layouts.'.$loginUser)
@section('title') Coupon List @endsection
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="couponList";
 $contentTitle ='Coupon List';
@endphp
<div class="m-portlet m-portlet--mobile">
    @include('admin.common.flash')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="row">
                <div class="col-md-6">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon m--hide">
                            <i class="la la-gear"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            @yield('title')
                        </h3>
                    </div>

                </div>
                <div class="col-md-6">
                    <a href="{{ url(route('coupons.create')) }}" class="btn btn-outline-primary m-btn m-btn--custom m-btn--icon m-btn--pill m-btn--air pull-right">
                        <span>
                            <i class="la la-plus"></i>
                            <span>Add Coupon</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
        <!--begin: Search Form -->
        <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
            <table id="admin-table" class="table table-striped table-bordered zero-configuration">
                <thead>
                    <tr>
                        <th>Coupon Code</th>
                        <th>Discount Type</th>
                        <th>Discount Amount</th>
                        <th>Minimum Total Amount</th>
                        <th>Maximum Discount Amount</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!--end: Search Form -->
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ url('assets/demo/default/custom/components/datatables/jquery.dataTables.min.js') }}"></script>
<script>
var table;
$(function ()
{
table = $('#admin-table').DataTable({
    "scrollX": true,
    processing: true,
    serverSide: true,
    "ajax": {
        "url": '{{ url(route("couponSearch"))  }}',
        "type": "POST",
        "async": false,
    },
    columns: [
        {data: 'coupon_code', name: 'coupon_code'},
        {data: 'discount_type', name: 'discount_type'},
        {data: 'discount_amount', name: 'discount_amount'},
        {data: 'min_total_amount', name: 'min_total_amount'},
        {data: 'max_discount_amount', name: 'max_discount_amount'},
        {data: 'start_date', name: 'start_date'},
        {data: 'end_date', name: 'end_date'},
        {data: 'status', name: 'status'},
        {data: 'action', name: 'action', orderable: false},
    ],
});
});

</script>
@endsection

