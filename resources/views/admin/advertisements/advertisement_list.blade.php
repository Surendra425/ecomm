@extends('layouts.admin')
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="advertisementList";
$contentTitle ='Advertisement Detail';
@endphp
<div class="m-portlet m-portlet--mobile">
    @include('admin.common.flash')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Advertisement Detail
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
                        <th>Advertisement Name</th>
                        <th>Tag Line</th>
                        <th>Display In Slider</th>
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
    table = $('#admin-table').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": '{{ url(route("advertisementSearch"))  }}',
            "type": "POST",
            "async": false,
        },
        columns: [
            {data: 'advertisement_name', name: 'advertisement_name'},
            {data: 'advertisement_tagline', name: 'advertisement_tagline'},
            {data: 'display_status', name: 'display_status'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false},
        ],
    });
});

</script>
@endsection