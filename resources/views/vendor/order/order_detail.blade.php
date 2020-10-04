@extends('layouts.vendor')
@section('title') Order Detail @endsection
@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@php
$pageTitle ="Manage Orders";
$contentTitle ='Order Detail';
@endphp
@section('content')
<div class="m-portlet">
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
    <form class="m-form m-form--fit">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Customer Name</label>
                        <p class="form-control-static">{{ $order->customer_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Payment Type</label>
                        <p class="form-control-static">{{ $order->payment_type }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Payment Status</label>
                        <p class="form-control-static">{{ $order->payment_status }}</p>
                    </div>
                    {{--<div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Coupon Code</label>
                        <p class="form-control-static">{{ $order->coupon_code }}</p>
                    </div>--}}
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Order Datetime</label>
                        <p class="form-control-static">{{ date("d/m/Y H:i:s",strtotime($order->created_at)) }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Sub Total</label>
                        <p class="form-control-static">{{ $orderProduct->sub_total }} KD</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Shipping</label>
                        <p class="form-control-static">{{ ($orderProduct->shipping_charges == 0) ? 'FREE' : $orderProduct->shipping_charges.' KD' }}</p>
                    </div>
                    {{--<div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Discount Amount</label>
                        <p class="form-control-static">{{ $order->discount_amount }}</p>
                    </div>--}}
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Grand Total</label>
                        <p class="form-control-static">{{ $orderProduct->grand_total }} KD</p>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
<div class="m-portlet">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Order Shipping Address
                </h3>
            </div>
        </div>
    </div>
    <form class="m-form m-form--fit">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Full Name</label>
                        <p class="form-control-static">{{ $shipping_address->full_name }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Block</label>
                        <p class="form-control-static">{{ $shipping_address->block }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Street</label>
                        <p class="form-control-static">{{ $shipping_address->street }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Building</label>
                        <p class="form-control-static">{{ $shipping_address->building }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Additional Directions</label>
                        <p class="form-control-static">{{ $shipping_address->additional_directions }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Avenue</label>
                        <p class="form-control-static">{{ $shipping_address->avenue }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Floor</label>
                        <p class="form-control-static">{{ $shipping_address->floor }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Apartment</label>
                        <p class="form-control-static">{{ $shipping_address->apartment }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Area</label>
                        <p class="form-control-static">{{ $shipping_address->city }}</p>
                    </div>
                    
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Country</label>
                        <p class="form-control-static">{{ $shipping_address->country }}</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Order Products
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
                        <th style="width:40%" class="text-center">Product Name</th>

                        <th class="text-center">Rate</th>
                        {{--<th class="text-center">Delivery Status</th>--}}
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Sub Total</th>
                        {{--<th class="text-center">Action</th>--}}
                    </tr>
                </thead>
                @php $totalQty = 0 ;  @endphp
            @if($order_products->count())
                <tbody>
                    @foreach($order_products as $product)   
                    <tr>
                        <td style="width:40%">{{ $product->product_title }} {{($product->combination_title) ? '('.$product->combination_title.')' : ''}} <br>
                            @if(!empty($product->note))
                             <b>Note: </b> {{ $product->note }}
                            @endif
                            
                        </td><td class="text-center">{{ $product->rate }} KD</td>
                        {{--<td class="text-center">{{ $product->delivery_status }}</td>--}}
                        <td class="text-center">{{ $product->quantity }}</td>
                        <td class="text-center">{{ (float)($product->sub_total) }} KD</td>
                        {{--<td><button class="btn btn-info btn-xs btn-sm  btnUpdate" delivery_status='{{ $product->delivery_status }}' order_product_id='{{ $product->id }}'>Update</button></td>--}}
                    </tr>
                    @php $totalQty+= $product->quantity ;  @endphp
                    @endforeach
                </tbody>                
                @endif
                <tfoot>
                <tr class="p-3 mb-2 bg-secondary text-dark">
                    <td colspan="2" class="text-right font-weight-bold"><b> Total : </b></td>

                    <td class="text-center font-weight-bold"><b>{{ $totalQty }}</b></td>
                    <td class="text-center font-weight-bold"><b><center>{{ (float)($orderProduct->sub_total) }} KD</b></td>

                </tr>
                <tr class="p-3 mb-2 bg-secondary text-dark">
                    <td colspan="2" class="text-right font-weight-bold"><b>Shipping Total(+) :</b></td>
                    <td class="text-right font-weight-bold"><b></b></td>
                    <td class="text-center font-weight-bold"><b>  {{ ($orderProduct->shipping_charges == 0) ? 'FREE' : ((float)($orderProduct->shipping_charges).' KD') }} </b></td>
                </tr>
                {{--<tr class="p-3 mb-2 bg-secondary text-dark">
                    <td colspan="2" class="text-right font-weight-bold"><b>Discount Amount(-) :</b></td>
                    <td class="text-right font-weight-bold"><b></b></td>
                    <td class="text-right font-weight-bold"><b> {{ (float)( $orderProduct->discount_amount)  }} KD</b></td>
                </tr>--}}
                <tr class="p-3 mb-2 bg-secondary text-dark">
                    <td colspan="2" class="text-right font-weight-bold"><b>Grand Total :</b></td>
                    <td class="text-right font-weight-bold"><b></b></td>
                    <td class="text-center font-weight-bold"><b> {{ (float)($orderProduct->sub_total + $orderProduct->shipping_charges) }} KD</b></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!--end: Search Form -->
    </div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="update_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="attr_create" class="m-form m-form--fit m-form--label-align-right form" method="post" action="{{ url(route('updateOrderProductStatus')) }}" novalidate>
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Order Product Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="update_model_body">
                    <input type="hidden" name="order_product_id" id="order_product_id" value="">
                    <label class="control-label" for="delivery_status">Delivery Status</label>
                    <select class="custom-select form-control" name="delivery_status" id="delivery_status">
                        <option value="Pending">Pending</option>
                        <option value="Sent">Sent</option>
                        <option value="Delivered">Delivered</option>                        
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit"  class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function ()
    {
        $(".btnUpdate").click(function ()
        {
            var delivery_status = $(this).attr("delivery_status");
            var order_product_id = $(this).attr("order_product_id");
            $("#order_product_id").val(order_product_id);
            $("#delivery_status").val(delivery_status);
            $("#update_model").modal("show");
        });
    });
</script>
@endsection

