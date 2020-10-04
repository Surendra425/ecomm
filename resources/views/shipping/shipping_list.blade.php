@extends('layouts.'.$loginUser->type)
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="shippingClassList";
$contentTitle ='Shipping Class List';
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
                            Shipping Class List
                        </h3>
                    </div>

                </div>
                <div class="col-md-6">
                    <a href="{{ url(route('shipping-class.create')) }}" class="btn btn-outline-primary m-btn m-btn--custom m-btn--icon m-btn--pill m-btn--air pull-right">
                        <span>
                            <i class="la la-plus"></i>
                            <span>Add Shipping Class</span>
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
                        <th>Shipping Class</th>
                        <th>Vendor Name</th>
                        <th>Country Name</th>
                        <th>City Name</th>
                        <th>Shipping Charge</th>
                        <th>Delivery Day 1</th>
                        <th>Delivery Day 2</th>
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
        "scrollY": "200px",
        processing: true,
        serverSide: true,
        "ajax": {
            "url": '{{ url(route("shippingClassSearch"))  }}',
            "type": "POST",
            "async": false,
        },
        columns: [
            {data: 'shipping_class', name: 'shipping_class'},
            {data: 'vendor_name', name: 'vendor_name'},
            {data: 'country_name', name: 'country_name'},
            {data: 'city_name', name: 'city_name'},
            {data: 'shipping_charge', name: 'shipping_charge'},
            {data: 'delivery_day_1', name: 'delivery_day_1'},
            {data: 'delivery_day_2', name: 'delivery_day_2'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false},
        ],
    });
});

</script>
@endsection

