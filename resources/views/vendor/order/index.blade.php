@extends('layouts.vendor')
@section('title') Order List @endsection
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@php
$pageTitle ="Manage Orders";
@endphp
@section('content')
<div class="m-portlet m-portlet--mobile">
    @include('vendor.common.flash')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    @yield('title')
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
        <!--begin: Search Form -->
        <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
            <table id="order-table" class="table table-striped table-bordered zero-configuration">
                <thead>
                    <tr>
                        <th>Customer&nbsp;Name</th>
                        <th>Order&nbsp;No</th>
                        <th>Order&nbsp;Datetime</th>
                        <th>Sub&nbsp;Total&nbsp;(KD)</th>
                        <th>Grand&nbsp;Total&nbsp;(KD)</th>
                        <th>Payment&nbsp;Type</th>
                        <th>Payment&nbsp;Status</th>
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
    table = $('#order-table').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": '{{ url(route("vendorOrderSearch"))  }}',
            "type": "POST",
            "async": false,
        },
        columns: [
            {data: 'customer_name', name: 'customer_name'},
            {data: 'order_no', name: 'order_no'},
            {data: 'created_at', name: 'created_at'},
            {data: 'sub_total', name: 'sub_total'},
            {data: 'grand_total', name: 'grand_total'},
            {data: 'payment_type', name: 'payment_type'},
            {data: 'payment_status', name: 'payment_status'},
            {data: 'action', name: 'action', orderable: false},
        ],
        "aaSorting": [[2,'desc']],
    });
});

</script>
@endsection

