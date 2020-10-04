@extends('layouts.app')
@section('title') My Orders @endsection
@section('css')
<style type="text/css">
    .order-row {
        background: #EFEFEF;
        padding: 15px;
        padding-bottom: 0px;
        margin-bottom: 10px;
    }
</style>
@endsection
@section('content')

    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
            </div>
            <div class="col-sm-12">
                <span class="mens">Your Orders</span>  
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
    <div class="container-fluid" id="collection">
        <div class="container">
            @if(count($orders) > 0)
            <div class="container-fluid" id="cartStore">
                @foreach($orders as $order)
                <div class="order-row">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                                    <label class="control-label">Order No : {{ $order->order_no }}</label>
                                </div>
                                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                                    <label class="control-label">Order Datetime : {{ date("d/m/Y H:i:s",strtotime($order->created_at)) }}</label>
                                </div>
                                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                                    <label class="control-label">Grand Total : {{ (float)($order->grand_total) }} KD</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                                    <label class="control-label">Payment Type : {{ $order->payment_type }}</label>
                                </div>
                                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                                    <label class="control-label">Payment Status : {{ $order->payment_status }}</label>
                                </div>
                                @if(!empty($order->knet_payment_id))
                                <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                                   <label class="control-label">Knet Payment ID: {{ $order->knet_payment_id }}</label>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="form-group m-form__group text-right" style="margin-left: 10px; margin-right: 10px;">
                                    <a class="btn btn-xs btn-warning" href="{{ url(route('myOrderDetail',["orderNo"=>$order->order_no])) }}" >View Detail</a>
                                </div>
                                @if($order->payment_status != 'Completed')
                                <div class="form-group m-form__group text-right" style="margin-left: 10px; margin-right: 10px;">
                                    <a class="btn btn-xs btn-success" href="{{ url(route('knetPay',["orderNo"=>$order->id])) }}" >Checkout</a>
                                </div>
                                @endif
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="noproducts">
                            <h3>No Orders Available</h3>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="container">
        @if(count($orders) > 0)
        <div class="mobileCart">
            @foreach($orders as $order)
            
            <div class="panel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label">Order No : {{ $order->order_no }}</label>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label">Order Datetime : {{ date("d/m/Y H:i:s",strtotime($order->created_at)) }}</label>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label">Grand Total : {{ $order->grand_total }}</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label">Payment Type : {{ $order->payment_type }}</label>
                        </div>
                        <div class="form-group m-form__group" style="margin-left: 10px; margin-right: 10px;">
                            <label class="control-label">Payment Status : {{ $order->payment_status }}</label>
                        </div>
                        </div>
                    <div class="col-md-4">
                        <div class="form-group m-form__group text-right" style="margin-left: 10px; margin-right: 10px;">
                            <a class="btn btn-xs btn-warning" href="{{ url(route('myOrderDetail',["orderNo"=>$order->order_no])) }}" >View Detail</a>
                            @if($order->payment_status != 'Completed')
                            <a class="btn btn-xs btn-success" href="{{ url(route('knetPay',["orderNo"=>$order->id])) }}" >Checkout</a>
                             @endif
                        </div>
                        
                       
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else

        @endif
    </div>

@endsection
@section('js')
<script type="text/javascript" src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
<script type="text/javascript">
$(document).ready(function ()
{
    var acc = document.getElementsByClassName("accordion");
    var i;
    for (i = 0; i < acc.length; i++)
    {
        acc[i].addEventListener("click", function ()
        {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block")
            {
                panel.style.display = "none";
            }
            else
            {
                panel.style.display = "block";
            }
        });
    }
    $("input").focusin(function ()
    {
        $("input").css("outline", "none");
        $(this).css("border-color", "black");
    });
    $("input").focusout(function ()
    {
        $("input").css("outline", "none");
        $(this).css("border-color", "#d1cfcf");
    });

    $(".procesedCheckout").click(function ()
    {
        window.location = "{{ url(route('checkout.index')) }}";
        return false;
    });
});
</script>
@endsection

