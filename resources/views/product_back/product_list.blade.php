@extends('layouts.'.$loginUser)
@section('css')
    <link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    @php
        $pageTitle ="productList";
    @endphp
    <div class="m-portlet m-portlet--mobile">
        @include($loginUser.'.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">
                        Product List
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <a href="{{ url(route('products.create'))}}" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill pull-right">
                                    <span>
                                        <i class="la la-list-alt"></i>
                                        <span>Add Product</span>
                                    </span>
            </a>
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">

                <table id="admin-table" class="table table-striped table-bordered zero-configuration">
                    <thead>
                    <tr>
                        <th>Product Title</th>
                        <th>Vendor Name</th>
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
    <script>
        var table;
        $(function() {
            table = $('#admin-table').DataTable({
            	//"scrollX": true,
            	processing: true,
                serverSide: true,
                "ajax": {
                    "url":'{{ url(route("productSearch"))  }}',
                    "type": "POST",
                    "async": false,
                },
                columns: [
                    { data: 'product_title', name: 'product_title' },
                    { data: 'vendor_name', name: 'vendor_name' },
                    { data: 'brand_name', name: 'brand_name' },
                    { data: 'category_name', name: 'category_name' },
                    { data: 'shipping_class', name: 'shipping_class' },
                    { data: 'featured', name: 'featured' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false },
                ],
            });
        });

    </script>
@endsection

