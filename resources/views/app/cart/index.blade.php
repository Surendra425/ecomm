@extends('layouts.app')
@section('title') Shopping Cart @endsection
@section('css')
@endsection
@section('content')
@php
$TotalAmount = 0;
@endphp

    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
            </div>
            <div class="col-sm-12">
                <span class="mens">Your Cart Items</span>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="HomeBreadCumb">
        <div class="container" id="home-myAccount">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">@yield('title')</span>
            </div>
        </div>
    </div>
    <div class="container-fluid">
    </div>
    @if(!empty($stores) && count($stores) > 0)
    <form role='form' id="" action="{{ url(route('updateCart')) }}" method="POST">
        {{ csrf_field() }}
        <div class="container-fluid" id="cartStore">

            @if(count($stores) > 0)
            @foreach($stores as $store)
            <div class="container">
                <div class="col-sm-12">
                    <div class="col-sm-4">
                        <span>{{ $store->store_name }}</span>
                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="container">
                    <table class="table table-responsive-sm">
                        <thead>
                            <tr>
                                <th style="width: 40%">PRODUCT</th>
                                <th style="width: 15%">PRICE</th>
                                <th  style="width: 20%"class="tableQuantity">QUANTITY</th>
                                <th style="width: 20%">TOTAL</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($store->products as $product)
                             <tr>
                                <td class="newTable">
                                    <div class="col-md-5">
                                     <img src="{{ url('doc/product_image/'.((isset($product['images'][0]) && $product['images'][0]->image_url!="")?$product['images'][0]->image_url:"no-images.jpeg")) }}" style="height: 125px;">
                                    </div>
                                    <div class="col-md-6">
                                        <span class="key">
                                            <a href="{{ url(route('sellerProductsDetail',['productSlug'=>$product['product_slug']])) }}">
                                                {{ $product['product_title'] }}                                            
                                            </a>
                                        </span>
                                        @if(isset($product['combination_title']) && $product['combination_title'] != "")
                                        <p class="key">({{ $product['combination_title'] }})</p>
                                        @endif
                                        <p class="break">sold by 
                                            <span class="name1">
                                                <b>                                                 
                                                    <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                                                        {{ $store->store_name }}
                                                    </a>
                                                </b>
                                            </span>
                                        </p>

                                        @if($product['quantity'] > $product['combination_qty'])
                                        <span class="break error qtyOutMsg" id="qtyOutMsg{{ $product['product_combination_id'] }}"><br/>Product quantity is not available</span>
                                        @endif
                                        <span class="break error hide" id="qtyOutMsg{{ $product['product_combination_id'] }}"><br/>Product quantity is not available</span>
                                    </div>
                                </td>
                                <td class="cost clsPrice">{{ (float)($product['rate']) }} KD</td>
                                <td class="QuantityInput">
                                    <input type="number" id="quantity{{ $product['product_combination_id'] }}" data-qty="{{$product['combination_qty']}}" class="Quantity1" step="1" min="1" max="{{ $product['combination_qty'] }}" name="quantity[{{ $product['product_combination_id'] }}]" value="{{ $product['quantity'] }}" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
                                    <i class="fa fa-caret-up clsUp" aria-hidden="true"></i>
                                    <i class="fa fa-caret-down clsDown" aria-hidden="true"></i>
                                </td>
                                <td class="cost clsTotal">{{ (float)($product['rate']*$product['quantity']) }} KD</td>
                                <td>
                                    <a class="delete-data" data-name="product" href="{{ url(route('removeCartItem',["cart"=>$product['product_combination_id']])) }}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            @php
                            $TotalAmount = $TotalAmount + ($product['rate']*$product['quantity']);
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                </div>
            </div>
            @endforeach
            @endif
        </div>
        <div class="mobileCart">
            @foreach($stores as $store)
            <button class="accordion" type="button">{{ $store->store_name }}</button>
            <div class="panel">
                @foreach($store->products as $product)
                    <div class="row clsProduct">
                    <span>
                         <img src="{{ url('doc/product_image/'.((isset($product['images'][0]) && $product['images'][0]->image_url!="")?$product['images'][0]->image_url:"product.png")) }}">
                    </span>
                    <span class="cartDetails">
                    <p class="key">
                        <a href="{{ url(route('sellerProductsDetail',['productSlug'=>$product['product_slug']])) }}">
                           {{ $product['product_title'] }}
                        </a>
                    </p>
                    @if(isset($product['combination_title']) && $product['combination_title'] != "")
                                        <p class="key">({{ $product['combination_title'] }})</p>
                                        @endif
                                        <p class="break">sold by
                        <span class="name1">
                            <b>
                                <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                                    {{ $store->store_name }}
                                </a>
                            </b>
                        </span>
                    </p>
                    <input type="hidden" value="{{ (float)($product['rate']) }}" name="quantityPrice[{{ $product['product_combination_id'] }}]">
                    <div class="priceProduct">
                        <a class="delete-data" data-name="product" href="{{ url(route('removeCartItem',["cart"=>$product['product_combination_id']])) }}">
                            Remove
                        </a>
                    </div>
                    </span>
                        <span class="cartPriceDetails">
                            <div class="priceProduct clsPrice"> <a href="#"><span class="cost" >{{ (float)($product['rate']) }} KD</span></a> </div>
                        <div class="QuantityInput">

                            {{--<select class="Quantity1 qty" name="quantity[{{ $product['product_combination_id'] }}]">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>--}}
                            <input type="number" id="quantity{{ $product['product_combination_id'] }}" data-qty="{{$product['combination_qty']}}" class="Quantity1" step="1" min="1" max="{{ $product['combination_qty'] }}" name="quantity[{{ $product['product_combination_id'] }}]" value="{{ $product['quantity'] }}" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
                            <i class="fa fa-caret-up clsUp" aria-hidden="true"></i>
                            <i class="fa fa-caret-down clsDown" aria-hidden="true"></i>
                        </div>

                    <div class="priceProduct">TOTAL -
                        <a href="#">
                            <span class="cost clsTotal">{{ (float)($product['rate']*$product['quantity'])}} KD</span>
                        </a>
                    </div>

                        </span>
            </div>

                @endforeach
            </div>
            @endforeach
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12" id="tableCart">
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="total">
                                <td>TOTAL</td>
                                <td class="grand-total">{{ $TotalAmount }} KD</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
<!--                    <div class="updateCart">
                        <button type="submit"><b>UPDATE CART</b></button>
                    </div>-->
                </div>
                <div class="col-md-4 col-sm-3 col-xs-12 text-center">
                    <button class="procesedCheckout"  type="submit">Processed to Checkout</button>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </form>
    @else
    <div class="noproducts">
        <h3>Cart is Empty</h3>
    </div>
    @endif

@endsection
@section('js')
<script type="text/javascript" src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
<script type="text/javascript">
$(document).ready(function ()
{
    function calcTotal()
    {
        var GrandTotal = 0;
        $(document).find("td.clsTotal").each(function (input)
        {
            var amt = parseFloat($(this).text());
            GrandTotal = GrandTotal + amt;
        });
        GrandTotal = parseFloat(GrandTotal.toFixed(2));
        $(".grand-total").html(GrandTotal + " KD");
    }
    $(document).on("click", ".clsUp", function (e)
    {
        this.parentNode.querySelector('input[type=number]').stepUp();
        var input = this.parentNode.querySelector('input[type=number]');

        
        var val = input.value;
        var name = input.name;
        $(".mobileCart").find("[name='" + name + "']").val(val);
        $("#cartStore").find("[name='" + name + "']").val(val);
        $(input).trigger("change");
        calcTotal();
    });
    $(document).on("click", ".clsDown", function (e)
    {
        
        this.parentNode.querySelector('input[type=number]').stepDown();
        var input = this.parentNode.querySelector('input[type=number]');
        var val = input.value;
        var name = input.name;
        
        $(".mobileCart").find("[name='" + name + "']").val(val);
        $("#cartStore").find("[name='" + name + "']").val(val);
        $(input).trigger("change");
        calcTotal();

    });
    $(document).on("change", ".Quantity1", function (e)
    {
        var input = this;
        var qty = $(this).data("qty");
        var val = input.value;
        var name = input.name;
        var name1 = name.replace("quantity", "quantityPrice");
        var name2 = name1.replace('"', "");

        var qtyId = name.replace("[", "");
        var qtyId1 = qtyId.replace("]", "");
        var qtyName = qtyId1.replace("quantity", "qtyOutMsg");

        
        $('#'+qtyId1).val(val);

            if(qty < val){
                $("#"+qtyName).removeClass('hide');
            }else{
                $("#"+qtyName).addClass('hide');
            }

        $(".mobileCart").find("[name='" + name + "']").val(val);
        $("#cartStore").find("[name='" + name + "']").val(val);
        var price = $("#cartStore").find("[name='" + name + "']").closest("tr").find(".clsPrice").text();
        var price1 = $("[name='" + name2 + "']").val();
        if(price != ''){
            var total = parseFloat(price) * parseFloat(val);
        }else{
            var total = parseFloat(price1) * parseFloat(val);
        }

        total = parseFloat(total.toFixed(2));
        $("#cartStore").find("[name='" + name + "']").closest("tr").find(".clsTotal").html(parseFloat(total) + " KD");
        $(".mobileCart").find("[name='" + name + "']").closest(".clsProduct").find(".clsTotal").html(parseFloat(total) + " KD");
        calcTotal();
    });


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
});
</script>
@endsection

