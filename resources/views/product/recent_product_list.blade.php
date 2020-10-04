@extends('layouts.'.$loginUser)
@section('css')
    <link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    @php
        $pageTitle ="recentProductList";
     $contentTitle ='Recent Product List';
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
                        Recent Product List
                    </h3>
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
                        <th>Vendor Name</th>
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
                    "url":'{{ url(route("recentproductSearch"))  }}',
                    "type": "POST",
                    "async": false,
                },
                columns: [
                    { data: 'product_title', name: 'product_title' },
                    { data: 'vendor_name', name: 'vendor_name' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false },
                ],
            });
        });
    </script>
@endsection

