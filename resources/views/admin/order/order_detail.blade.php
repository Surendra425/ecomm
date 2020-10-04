@extends('layouts.admin')

@section('css')
<link rel="stylesheet" type="text/css" href=" {{ url('assets/demo/default/custom/components/datatables/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
@php
$pageTitle ="orderDetail";
$contentTitle ='Order Detail';
@endphp
<div class="m-portlet">
    @include('admin.common.flash')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    Order Detail
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
                    <div class="col-md-4  form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Payment Type</label>
                        <p class="form-control-static">{{ $order->payment_type }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Payment Status</label>
                        <p class="form-control-static">{{ $order->payment_status }}</p>
                    </div>
                   
                </div>
                <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Coupon Code</label>
                        <p class="form-control-static">{{ $order->coupon_code }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Order Datetime</label>
                        <p class="form-control-static">{{ date("d/m/Y H:i:s",strtotime($order->created_at)) }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Grand Total</label>
                        <p class="form-control-static">{{ (float)($order->grand_total) }} KD</p>
                    </div>
                </div>
               {{-- <div class="col-md-6">
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Sub Total</label>
                        <p class="form-control-static">{{ (float)($order->sub_total) }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Discount Amount</label>
                        <p class="form-control-static">{{ $order->discount_amount or 0 }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Total</label>
                        <p class="form-control-static">{{ (float)($order->order_total - $order->discount_amount)  }}</p>
                    </div>
                    <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                        <label class="control-label">Shipping Total</label>
                        <p class="form-control-static">{{ (float)($order->shipping_total) }}</p>
                    </div>
                    
                </div> --}} 
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
                        <th class="text-center">Vendor Name</th>
                        <th class="text-center">Rate</th>
                        {{--<th class="text-center">Delivery Status</th>--}}
                       <!--  <th class="text-center">Shipping Charges</th> -->
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Sub Total</th>
                        <!-- <th class="text-center">Shipping Total</th>
                        <th class="text-center">Grand Total</th> -->
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
                        </td>
                        <td>{{ $product->first_name." ".$product->last_name }}</td>
                        <td>{{ $product->rate }} KD</td>
                        {{--<td class="text-center">{{ $product->delivery_status }}</td>--}}
                        <!-- <td class="text-right">{{ (float)($product->shipping_charges) }}</td> -->
                        <td class="text-center">{{ $product->quantity }}</td>
                        <td class="text-center">{{ (float)($product->sub_total) }} KD</td>
                       <!-- <td class="text-right">{{ (float)($product->shipping_charges) }}</td>
                        <td class="text-right">{{ (float)($product->grand_total) }}</td> -->
                        @php $totalQty+= $product->quantity ;  @endphp 
                    </tr>
                    @endforeach
                </tbody>                
                @endif
                <tfoot>
                    <tr class="p-3 mb-2 bg-secondary text-dark">
                        <td colspan="3" class="text-right font-weight-bold"><b> Total : </b></td>
                       <!--  <td class="text-right"><b>{{ (float)($order->shipping_total) }}</b></td> -->
                        <td class="text-right font-weight-bold"><b> {{ $totalQty }}</b></td>
                        <td class="text-right font-weight-bold"><b>{{ (float)($order->sub_total) }} KD</b></td>
                       
                      <!--
                        <td class="text-right"><b>{{ (float)($order->grand_total) }}</b></td> -->
                    </tr>
                     <tr class="p-3 mb-2 bg-secondary text-dark">
                        <td colspan="3" class="text-right font-weight-bold"><b>Shipping Total(+) :</b></td>
                         <td class="text-right font-weight-bold"><b></b></td>
                        <td class="text-center font-weight-bold"><b>  {{ ($order->shipping_total == 0) ? 'FREE' : (float)($order->shipping_total).' KD' }} </b></td>
                    </tr>
                    <tr class="p-3 mb-2 bg-secondary text-dark">
                        <td colspan="3" class="text-right font-weight-bold"><b>Discount Amount(-) :</b></td>
                         <td class="text-right font-weight-bold"><b></b></td>
                        <td class="text-center font-weight-bold"><b> {{ (float)( $order->discount_amount)  }} KD</b></td>
                    </tr>
                    <tr class="p-3 mb-2 bg-secondary text-dark">
                        <td colspan="3" class="text-right font-weight-bold"><b>Grand Total :</b></td>
                         <td class="text-right font-weight-bold"><b></b></td>
                        <td class="text-center font-weight-bold"><b> {{ (float)($order->grand_total) }} KD</b></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!--end: Search Form -->
    </div>
</div>
@endsection

