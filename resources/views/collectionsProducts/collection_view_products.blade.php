@extends('layouts.'.$loginUser)
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="CollectionView";
$contentTitle ='Collection Details';
@endphp
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet m-portlet--tab">
        @include($loginUser.'.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Collection Details
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        <label for="collection_name">Collection Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="collection_name" name="collection_name" value="{{ $collection->collection_name }}" placeholder="Collection Name" disabled="disabled">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group">
                        <label for="collection_tagline">Tag Line <span class="text-danger">*</span></label>
                        <input type="text" class="form-control m-input m-input--square" id="collection_tagline" name="collection_tagline" value="{{ $collection->collection_tagline }}" placeholder="Tag Line" disabled="disabled">
                    </div>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Collection Products</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                                <table id="products-table" class="table table-striped table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Product Image</th>
                                            <th>Product Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                    
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ url('assets/demo/default/custom/components/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function ()
{

    var table = $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": '{{ url(route("collectionProductsSearch", ["collectionProducts" => $vendor]))  }}',
            "type": "POST",
            "async": false,
        },
        columns: [
            {data: 'image', name: 'product_title', orderable: false },
            {data: 'product_title', name: 'product_title'},
            {data: 'action', name: 'action', orderable: false }
        ],
    });
});
</script>

@endsection

