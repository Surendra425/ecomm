@extends('layouts.'.$loginUser->type)
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="productCollectionList";
$contentTitle ='Collection List';
@endphp
<div class="m-portlet m-portlet--mobile">
    @include($loginUser->type.'.common.flash')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="row">
                <div class="col-md-6">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon m--hide">
                            <i class="la la-gear"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            Collection List
                        </h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-outline-primary m-btn m-btn--custom m-btn--icon m-btn--pill m-btn--air pull-right" data-toggle="modal" data-target="#create_model">
                        <span>
                            <i class="la la-plus"></i>
                            <span>Add Collection</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
            <table id="admin-table" class="table table-striped table-bordered zero-configuration">
                <thead>
                    <tr>
                        <th>Collection Name</th>
                        <th>Tag Line</th>
                        <th>Vendor Name</th>
                        <th>Total Product</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!--end: Search Form -->
    </div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="create_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Product In Collection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="collection_add_products" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="{{ url(route('products-collections.store')) }}" novalidate>
                    {{ csrf_field() }}

                    <div class="m-portlet__body">
                        <div class="row">
                            @if($loginUser->type === 'vendor')
                            <input type="hidden" name="vendor_id" id="vendor_id" value="{{ $loginUser->id }}">
                            @else
                            <div class="col-md-12" style="margin-bottom: 25px;">
                                <div class="form-group m-form__group" id="vendor_div">
                                    <label for="first_name">Vendor : <span class="text-danger">*</span></label>
                                    <select class="custom-select col-md-6" name="vendor_id" id="vendor_id" required>
                                        <option value="" disabled="disabled" selected>Select Vendor</option>
                                        <?php foreach ($vendor as $list)
                                        {
                                            ?>
                                            <option value="<?php echo $list->id; ?>"><?php echo $list->first_name . ' ' . $list->last_name; ?></option>
<?php } ?>
                                    </select>
                                    <span style="color: #f4516c" id="vendor_message"></span>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12" style="margin-bottom: 25px;">
                                <div class="form-group m-form__group" id="collection_div">
                                    <label for="collection_id">Collection : <span class="text-danger">*</span></label>
                                    <select class="custom-select col-md-6" name="collection_id" id="collection_id" required>
                                        <option value="" disabled="disabled" selected>Select Collection</option>
                                        <?php foreach ($collection as $item)
                                        {
                                            ?>
                                            <option data-tagline="{{ $item->collection_tagline }}" data-name="{{ $item->collection_name }}" value="{{$item->id}}">{{ $item->collection_name }}</option>
<?php } ?>
                                    </select>
                                    <span style="color: #f4516c" id="collection_message"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-form__group" id="product_div">
                                    <label for="ddlProducts">Products <span class="text-danger">*</span></label>
                                    <select class="col-lg-12" name="ddlProducts[]" id="ddlProducts" multiple="multiple" required>
                                        <option></option>
                                        <optgroup id="product_search">

                                        </optgroup>

                                    </select>
                                    <span style="color: #f4516c" id="product_message"></span>
                                </div>


                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="submit" id="collection" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
@endsection
@section('js')
<script type="text/javascript" src="{{ url('assets/demo/default/custom/components/datatables/jquery.dataTables.min.js') }}"></script>
<script>
var table;
$(function ()
{
    table = $('#admin-table').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": '{{ url(route("searchProductCollection"))  }}',
            "type": "POST",
            "async": false,
        },
        columns: [
            {data: 'collection_name', name: 'collection_name'},
            {data: 'collection_tagline', name: 'collection_tagline'},
            {data: 'vendorName', name: 'vendorName'},
            {data: 'product_count', name: 'product_count'},
            {data: 'action', name: 'action', orderable: false},
        ],
    });
});

</script>
<script type="text/javascript">
    $("#collection").on('click', function ()
    {
        var vendor_id = $("#vendor_id").val();
        var product_id = $("#ddlProducts").val();
        var collection_id = $("#collection_id").val();
        if (vendor_id == null && collection_id == null && product_id == "")
        {
            $("#vendor_message").text("Please Select Vendor.");
            $("#vendor_div").addClass('has-danger');
            $("#collection_message").text("Please Select Collection.");
            $("#collection_div").addClass('has-danger');
            $("#product_message").text("Please Select Product.");
            $("#product_div").addClass('has-danger');
        }
        else if (vendor_id == null)
        {
            $("#vendor_message").text("Please Select Vendor.");
            $("#vendor_div").addClass('has-danger');
        }
        else if (collection_id == null)
        {
            $("#collection_message").text("Please Select Collection.");
            $("#collection_div").addClass('has-danger');
        }
        else if (product_id == "")
        {
            $("#product_message").text("Please Select Product.");
            $("#product_div").addClass('has-danger');
        }
        else
        {
            $("#collection_add_products").submit();
        }

    });
    $("#vendor_id").on('change', function ()
    {
        var vendor_id = $("#vendor_id").val();
        products(vendor_id);
    });
    function products(vendorId)
    {
        var html = "";
        var collection_id = $("#id").val();
        $.ajax({
            url: baseUrl + '/product/productSearch',
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'vendor_id': vendorId,
                'collection_id': collection_id
            },
            success: function (result)
            {
                var obj = jQuery.parseJSON(result);
                console.log(obj);

                if (obj.length > 0)
                {
                    $.each(obj, function (i, item)
                    {
                        html += "<option value='" + item.id + "'>" + item.product_title + "</option>";
                    });
                }
                else
                {
                    html += "<option>No Products</option>";
                }
                console.log(html);
                $('#product_search').html(html);
                $("#ddlProducts").select2();
            }});


    }
    $(document).ready(function ()
    {
        var vendor_id = $("#vendor_id").val();
        if (vendor_id != '')
        {
            products(vendor_id);
        }
        else
        {
            var html = "";
            html += "<option>No Products</option>";
            $('#product_search').html(html);
        }

        $("#ddlProducts").select2();
        $("#collection_add_products").validate({
            rules: {
                "ddlProducts[]": {
                    required: true
                },
                vendor_id: {
                    required: true
                }
            },
            messages: {
                "ddlProducts[]": {
                    required: "Products is required"
                },
                vendor_id: {
                    required: "Please select Vendor. It is required."
                }
            }
        });
    });

</script>
@endsection

