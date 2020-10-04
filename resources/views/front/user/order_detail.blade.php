@extends('front.layout.index')
@section('title') Order Detail @endsection
@section('content')

<style type="text/css">
    .order-row {
        background: #EFEFEF;
        padding: 15px;
        padding-bottom: 0px;
        margin-bottom: 10px;
    }
    .control-label
    {
        font-weight: bold;
    }
    .mobileCart .m-form__group {
        border-bottom: 1px solid;
        margin-bottom: 10px;
    }
    .clsTotal,.tableQuantity
    {
        text-align: right;
    }
    #cartStore .clsHead {
        background-color: #262626;
        color: white;
        height: 42px;
    }
    .mobileCart .clsHead {
        background-color: #262626;
        color: white;
        height: 42px;    font-weight: bold;
        font-size: 16px;
        padding: 10px 0px 0px 0px;
    }
</style>

    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
            </div>
            <div class="col-sm-12">
                <span class="mens">{{ $order->order_no }}</span>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="footerFluid">
        <div class="container" id="home-myAccount">
            <div class="col-sm-4 col-lg-3 col-xs-12 col-md-3">
                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">@yield('title')</span>
            </div>
        </div>
    </div>

    <input type="hidden" id="customer_id" name="customer_id" value="{{ ((!empty($customer))?($customer->id):"") }}">

    <div class="container-fluid orderDetail">
        <div class="container-fluid" id="cartStore">
            <div class="container">
                <div class="row clsHead">
                    <div class="col-md-12 col-sm-4">                        
                        <span>Order Detail</span>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row order-row">
                   
                        <div class="col-sm-4 col-md-4">
                            <div class=" m-form__group" >
                                <label class="control-label">Order No : </label>
                                <label class="form-control-static">{{ $order->order_no }}</label>
                            </div>
                            <div class=" m-form__group" >
                                <label class="control-label">Payment Type : </label>
                                <label class="form-control-static">{{ $order->payment_type }}</label>
                            </div>
                            <div class=" m-form__group" >
                                <label class="control-label">Payment Status : </label>
                                <label class="form-control-static">{{ $order->payment_status }}</label>
                            </div>

                            @if($order->payment_type == 'Knet')
                                <div class=" m-form__group" >
                                    <label class="control-label">Transaction Id : </label>
                                    <label class="form-control-static">{{ $order->knet_tran_id }}</label>
                                </div>

                            @endif


                            @if($order->coupon_code)
                                <div class=" m-form__group" >
                                    <label class="control-label">Coupon Code : </label>
                                    <label class="form-control-static">{{ $order->coupon_code }}</label>
                                </div>
                            @endif
                            @if(!empty($order->knet_payment_id))
                                <div class="form-group m-form__group">
                                    <label class="control-label">Knet Payment ID:  </label>
                                    <label class="form-control-static">{{ $order->knet_payment_id }}</label>
                                </div>
                            @endif

                            @if(!empty($order->credit_card_transaction_id))
                                <div class="form-group m-form__group">
                                    <label class="control-label">Transaction ID:  </label>
                                    <label class="form-control-static">{{ $order->credit_card_transaction_id }}</label>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class=" m-form__group" >
                                <label class="control-label">Sub Total : </label>
                                <label class="form-control-static">{{ (float)($order->sub_total) }} KD</label>
                            </div>
                            <div class=" m-form__group" >
                                <label class="control-label">Shipping Total : </label>
                                <label class="form-control-static">{{ (float)($order->shipping_total) }} KD</label>
                            </div>
                            @if($order->discount_amount)
                                <div class=" m-form__group" >
                                    <label class="control-label">Discount Amount : </label>
                                    <label class="form-control-static">{{ (float)($order->discount_amount) }} KD</label>
                                </div>
                            @endif
                            <div class=" m-form__group" >
                                <label class="control-label">Grand Total : </label>
                                <label class="form-control-static">{{ (float)($order->grand_total) }} KD</label>
                            </div>

                            <div class=" m-form__group" >
                                <label class="control-label">Order Datetime : </label>
                                <label class="form-control-static">{{ date("d/m/Y",strtotime($order->created_at)) }}</label>
                            </div>

                            @if(!empty($order->credit_card_transaction_receipt))
                                <div class="form-group m-form__group">
                                    <label class="control-label">Transaction Receipt:  </label>
                                    <label class="form-control-static">{{ $order->credit_card_transaction_receipt }}</label>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class=" m-form__group" >
                                <label class="control-label">Shipping Address : </label>
                                @if(!empty($address))
                                    <p class="form-control-static">
                                        {{ $address['full_name'] }}<br>
                                        {{ (isset($address['block'])?($address['block'].", "):"").(isset($address['building'])?($address['building']):"") }}@if(!empty($address['building']))<br/>@endif
                                        {{ !empty($address['street']) ?  $address['street'] : '' }}@if(!empty($address['street']))<br/>@endif
                                        {{ $address['additional_directions'] }}@if(!empty($address['additional_directions']))<br/>@endif
                                        {{ $address['city'] }} <br/>
                                        {{ $address['country']}} <br/>
                                    </p>
                                @endif
                            </div>
                        </div>
                   
                </div>
            </div>
        </div>
        <div class="container-fluid" id="cartStore">
            <div class="container">
                <div class="row clsHead">
                    <div class="col-md-12 col-sm-4">                        
                        <span>Order Products</span>   
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="container">
                    <table class="table table-responsive-sm">
                        <thead>
                        <tr>
                            <th>PRODUCT</th>
                            <th>PRICE</th>
                            <th class="tableQuantity">QUANTITY</th>
                            <th class="clsTotal">TOTAL</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order_products as $product)
                            <tr>
                                <td class="newTable">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="{{ url('doc/product_image/'.((isset($product['image']) && $product['image']!="")?$product['image']:"product.png")) }}" style="height: 125px;">
                                        </div>
                                        <div class="col-md-6">
                                            <span class="key">
                                                <a href="{{ url(route('showProductDetails',['productSlug'=>$product['product_slug']])) }}">
                                                    {{ $product['product_title'] }}
                                                </a>
                                            </span>

                                            @if(isset($product['combination_title']) && $product['combination_title'] != "")
                                                <span class="key">({{ $product['combination_title'] }})</span>
                                            @endif

                                            <p class="break">sold by
                                                <span class="name1">
                                                    <b>
                                                        <a href="{{ url(route('sellerDetail',['storeSlug'=>$product['store_slug']])) }}">
                                                            {{ $product['store_name'] }}
                                                        </a>
                                                    </b>
                                                </span>
                                            </p>
                                            
                                            @if(!empty($product['note']))
                                                <b>Note:</b><p>{{$product['note'] }}</p>
                                                
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="cost clsPrice">{{ $product['price'] }} KD</td>
                                <td class="cost clsTotal">{{ $product['quantity'] }}</td>
                                <td class="cost clsTotal">{{ $product['sub_total'] }} KD</td>
                                <td style="width: 10%;text-align: center">
                                    @php
                                        $RateUrl = (empty($customer))?(url(route('login'))):"javascript:viewRatingModel({$product['product_id']},{$product['product_combination_id']})";
                                    @endphp
                                    <a href="{{ $RateUrl }}" title="Add Review">
                                        <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <hr>
                </div>
            </div>
        </div>

        <div class="mobileCart">
            <div class="panel" style="display: block">
                <div class="row clsHead">
                    <div class="col-md-12">
                        <div class="col-sm-4">
                            <span>Order Detail</span>
                        </div>
                        <div class="col-sm-4">
                        </div>
                    </div>
                </div>
                <div class="row order-row">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class=" m-form__group" >
                            <label class="control-label">Order No : </label>
                            <p class="form-control-static">{{ $order->order_no }}</p>
                        </div>
                        <div class=" m-form__group" >
                            <label class="control-label">Payment Type : </label>
                            <p class="form-control-static">{{ $order->payment_type }}</p>
                        </div>
                        <div class=" m-form__group" >
                            <label class="control-label">Payment Status : </label>
                            <p class="form-control-static">{{ $order->payment_status }}</p>
                        </div>
                        @if($order->coupon_code)
                        <div class=" m-form__group" >
                            <label class="control-label">Coupon Code : </label>
                            <p class="form-control-static">{{ $order->coupon_code }}</p>
                        </div>
                        @endif
                        <div class="m-form__group" >
                            <label class="control-label">Order Datetime : </label>
                            <p class="form-control-static">{{ date("d/m/Y H:i:s",strtotime($order->created_at)) }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class=" m-form__group" >
                            <label class="control-label">Sub Total : </label>
                            <p class="form-control-static">{{ (float)($order->sub_total) }}&nbsp;KD</p>
                        </div>
                        <div class=" m-form__group" >
                            <label class="control-label">Shipping Total : </label>
                            <p class="form-control-static">{{ (float)($order->shipping_total) }}&nbsp;KD</p>
                        </div>
                        {{--<div class=" m-form__group" >
                            <label class="control-label">Total : </label>
                            <p class="form-control-static">{{ (float)($order->order_total) }}&nbsp;KD</p>
                        </div>--}}
                        @if($order->discount_amount)
                        <div class=" m-form__group" >
                            <label class="control-label">Discount Amount : </label>
                            <p class="form-control-static">{{ (float)($order->discount_amount) }}&nbsp;KD</p>
                        </div>
                        @endif
                        <div class=" m-form__group" >
                            <label class="control-label">Grand Total : </label>
                            <p class="form-control-static">{{ (float)($order->grand_total) }}&nbsp;KD</p>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class=" m-form__group" >
                            <label class="control-label">Shipping Address : </label>

                             @if(!empty($address))
                                    <p class="form-control-static">
                                        {{ $address['full_name'] }}
                                        {{ (isset($address['block'])?($address['block'].", "):"").(isset($address['building'])?($address['building']):"") }}@if(!empty($address['building']))<br/>@endif
                                        {{ !empty($address['street']) ?  $address['street'] : '' }}@if(!empty($address['street']))<br/>@endif
                                        {{ $address['additional_directions'] }}@if(!empty($address['additional_directions']))<br/>@endif
                                        {{ $address['city'] }} <br/>
                                        {{ $address['country']}} <br/>
                                    </p>
                                @endif                            
                            
                        </div>  
                     <!--
                                @if($order->payment_status != 'Completed')
                                <div class="form-group m-form__group text-right" style="margin-left: 10px; margin-right: 10px;">
                                    <a class="btn btn-xs btn-success" href="{{ url(route('knetPay',["orderNo"=>$order->id])) }}" >Checkout</a>
                                </div>
                                @endif
                                  -->            
                    </div>
                </div>
            </div>
        </div>

        <div class="mobileCart">
            <button class="accordion" type="button">Order Products</button>
            <div class="panel">
                @foreach($order_products as $product)
                <div class="row clsProduct order-row">
                    <center>
                        <img src="{{ url('doc/product_image/'.((isset($product['image']) && $product['image']!="")?$product['image']:"product.png")) }}">
                    </center> 
                    <p class="key">
                         <a href="{{ url(route('showProductDetails',['productSlug'=>$product['product_slug']])) }}">
                            {{ $product['product_title'] }}
                        </a>
                    </p>
                     @if(isset($product['combination_title']) && $product['combination_title'] != "")
                                                <span class="key">({{ $product['combination_title'] }})</span>
                                            @endif
                                            <p class="break">sold by
                                                <span class="name1">
                                                    <b>
                                                        <a href="{{ url(route('sellerDetail',['storeSlug'=>$product['store_slug']])) }}">
                                                            {{ $product['store_name'] }}
                                                        </a>
                                                    </b>
                                                </span>
                                            </p>

                    <div class="priceProduct clsPrice">PRICE - <a href="#"><span class="cost">{{ $product['price'] }} KD</span></a> </div>

                    <div class="priceProduct clsPrice">QUANTITY - <a href="#"><span class="cost">{{ $product['quantity'] }}</span></a></div>

                    <div class="priceProduct">TOTAL - <a href="#"><span class="cost">{{ $product['sub_total'] }} KD</span></a></div>

                    <div class="priceProduct">
                         @php
                                        $RateUrl = (empty($customer))?(url(route('login'))):"javascript:viewRatingModel({$product['product_id']},{$product['product_combination_id']})";

                                    @endphp
                                    <a href="{{ $RateUrl }}" title="Add Review">
                                        <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                                    </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>    



    <div id="productRatingModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form id="product_review" onsubmit="return validateForm()" method="POST" action="{{ url(route('rateProduct')) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{ ((!empty($customer))?($customer->id):"") }}">
                    <input type="hidden" name="product_id" id="product_id">
                    <input type="hidden" name="product_combination_id" id="product_combination_id">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Rate Product</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Rating : </label><br/>
                                <input type="hidden"  value="0" id="rating" name="rating" class="do-not-ignore rating" data-filled="fa fa-star checked" data-empty="fa fa-star"/>

                            <span class="textDashboard" id="ratingProduct">

                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success testBtn" >Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')

<script>
    function viewRatingModel(productID,productCombinationId)
    {

        var user_id = $("#customer_id").val();
        $("#product_id").val(productID);
        $("#product_combination_id").val(productCombinationId);
        $("#productRatingModal").modal("show");
        var html = "";

        $.ajax({
            url: baseUrl + '/get/product-review/product_review/product_id',
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'value': productID,
                'product_combination_id': productCombinationId,
                'user_id': user_id
            },
            success: function (result)
            {
                var obje = jQuery.parseJSON(result);

                var rateClass = "";
                if (obje != '')
                {
                    $("#ratingProduct").empty();
                    $.each(obje, function (ie, items)
                    {
                        for (i = 1; i <= 5; i++)
                        {

                            if (obje[0]['rating'] >= i)
                            {
                                rateClass += "fa-star checked";
                            }
                            else
                            {
                                rateClass += "fa-star unchecked";
                            }
                            html += '<a rating="' + i + '" class="clsRate"><span class="fa ' + rateClass + '"></span>&nbsp;</a>';
                        }
                    });
                    $("#review").text(obje[0]['review_text']);
                }
                else
                {
                    $("#ratingProduct").empty();
                    for (i = 1; i <= 5; i++)
                    {
                        html += '<a rating="' + i + '" class="clsRate"><span class="fa fa-star unchecked"></span>&nbsp;</a>';
                    }
                }
                $("#ratingProduct").append(html);

            }});
    }

    $(document).ready(function ()
    {
        $(document).on("click", ".clsRate", function ()
        {
            var rating = $(this).attr("rating");
            
            $(document).find(".clsRate").each(function (key, input)
            {
                if ($(input).attr("rating") <= rating)
                {
                    $(input).find(".fa-star").removeClass("unchecked").addClass("checked");
                }
                else
                {
                    $(input).find(".fa-star").removeClass("checked").addClass("unchecked");
                }
            });
            $(document).find("#rating").val(rating);
        });
    });

    function validateForm() {
        rating = $('#product_review #rating').val();

        if(rating==0){
            //$("#productRatingModal").modal("hide");
            return false;
        }
        return  true;
    }

@if($order->is_mail_send == 2 && ($order->payment_status == 'Pending' || $order->payment_status == 'Completed' || ($order->payment_status != 'Failed' && $order->payment_status != 'Cancelled')))

    isShowLoader = false;
    $(document).ready(function(){
        $.ajax({
            url:"{{url('sendordersuccessmail/'.base64_encode($order->id))}}",
            type:'get',
            success:function(response){
                console.log(response);
            }
        });
    });
@endif

</script>
@endsection