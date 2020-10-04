@extends('layouts.'.$loginUser->type)
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="productCollectionAdd";
 $contentTitle ='Add Collection Products';
@endphp

<div class="m-portlet m-portlet--mobile">
    @include($loginUser->type.'.common.flash')
    <div class="m-portlet m-portlet--tab">
        @include('admin.common.flash')
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Add Collection Products
                    </h3>
                </div>
            </div>
        </div>
        <form id="collection_add_products" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="{{ url(route('products-collections.store')) }}" novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="collection_id" id="id" value="{{ $collection->id }}">

            <div class="m-portlet__body">
                <div class="row">
                    <input type="hidden" name="vendor_id" id="vendor_id" value="{{ $vendorId }}">

                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="collection_name">Collection Name </label>
                            <input type="text" class="form-control m-input m-input--square" id="collection_name" name="collection_name" value="{{ $collection->collection_name }}" placeholder="Collection Name" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="collection_tagline">Tag Line </label>
                            <input type="text" class="form-control m-input m-input--square" id="collection_tagline" name="collection_tagline" value="{{ $collection->collection_tagline }}" placeholder="Tag Line" disabled="disabled">
                        </div>
                        <br>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group m-form__group">
                            <label for="ddlProducts">Products <span class="text-danger">*</span></label>    
                            <select class="form-control" name="ddlProducts[]" id="ddlProductsId" multiple="multiple" required>
                                <optgroup id="product_search">

                                </optgroup>

                            </select>
                            <label id="ddlProducts-error" class="error" for="ddlProducts"></label>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-12 ml-lg-auto text-center">
                            <button type="submit" class="btn btn-success">Add Product</button>
                            <a href="{{ url(route('products-collections.index')) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $("#vendor_id").on('change',function(){
        var vendor_id = $("#vendor_id").val();
        products(vendor_id);
    });
    function products(vendorId){
        var html="";
        $('#product_search').html('');
        var collection_id = $("#id").val();
         $.ajax({
            url:  baseUrl + '/product/productSearch',
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data : {
                'vendor_id' : vendorId,
                'collection_id' :collection_id
            },
            success: function(result){
                var obj = jQuery.parseJSON(result);
                if(obj.length > 0){
                    $.each(obj, function(i, item) {
                        html += "<option value='"+ item.id +"'>"+ item.product_title +"</option>";
                    });

                }else{
                    html +="<option>No Products</option>";

                }
                $('#product_search').html(html);
                $("#ddlProducts").select2();
            }});

    }
    $(document).ready(function ()
    {
        var vendor_id = $("#vendor_id").val();
        if(vendor_id != ''){
            products(vendor_id);
        }else{
            var html="";
            html +="<option>No Products</option>";
            $('#product_search').html(html);
        }

        $("#ddlProductsId").select2();
        $("#collection_add_products").validate({
            rules: {
                "ddlProducts[]": {
                    required: true
                }
            },
            messages: {
                "ddlProducts[]": {
                    required: "Products is required"
                }
            }
        });
    });
</script>

@endsection

