@extends('layouts.vendor')
@section('title') Deal Product List @endsection
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="Manage Deals";
@endphp
<div class="m-portlet m-portlet--mobile">
    @include('vendor.common.flash')
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
                    <a href="{{ url(route('addDealProducts', ['deal' => $deal->id])) }}" class="btn btn-outline-primary m-btn m-btn--custom m-btn--icon m-btn--pill m-btn--air pull-right">
                        <span>
                            <i class="la la-plus"></i>
                            <span>Add Deal Product</span>
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
                        <th>Product Title</th>
                        <th>Brand Name</th>
                        <th>Category Name</th>
                        <th>Shipping Class</th>
                        <th>Featured</th>
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
<script type="text/javascript">
var table;
$(function ()
{
    var table = $('#admin-table').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": "{{ url(route('dealProductSearch', ['deal' => $deal->id]))  }}",
            "type": "POST",
            "async": false,
        },
        columns: [
            {data: 'product_title', name: 'product_title'},
            {data: 'brand_name', name: 'brand_name'},
            {data: 'category_name', name: 'category_name'},
            {data: 'shipping_class', name: 'shipping_class'},
            {data: 'featured', name: 'featured'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false},
        ],
    });
});

</script>
@endsection

