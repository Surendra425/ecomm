@extends('layouts.vendor')
@section('title') Add Deal Products @endsection
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="Manage Deals";
@endphp
<div class="m-portlet m-portlet--mobile">
    @include('vendor.common.flash')
    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        @yield('title')
                    </h3>
                </div>
            </div>
        </div>
        <form id="deal_add_products" class="m-form m-form--fit m-form--label-align-right form" enctype="multipart/form-data" method="post" action="{{ url(route('storeDealProducts',['deal' => $deal->id])) }}" novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="deal_id" id="id" value="{{ $deal->id }}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="deal_name">Collection Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control m-input m-input--square" id="deal_name" name="deal_name" value="{{ $deal->deal_name }}" placeholder="Collection Name" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group">
                            <label for="ddlProducts">Products <span class="text-danger">*</span></label>    
                            <select class="form-control required" name="ddlProducts[]" id="ddlProducts" multiple="multiple">
                                @foreach($products as $option)
                                <option value="{{$option->id}}">{{ $option->product_title }}</option>
                                @endforeach                                    
                            </select>
                            <select class="m-select2 form-control" id="m_select2_3" name="attr_list[]" multiple="multiple">
                                <option value="">Select Products</option>
                            </select>    
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-12 ml-lg-auto text-center">
                            <button type="submit" class="btn btn-success">Add Product</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
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
    $(document).ready(function ()
    {
        $("#ddlProducts").select2();
        $("#deal_add_products").validate({
            rules: {
                ddlProducts: {
                    required: true
                }
            },
            messages: {
                ddlProducts: {
                    required: "Products is required"
                }
            }
        });
    });
</script>

@endsection

