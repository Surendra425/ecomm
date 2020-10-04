@extends('front.layout.index')
@section('title') My Cart @endsection

@section('meta')

@endsection

@section('content')
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
  <div class="main-cart-div">
    @if(!empty($cartProducts) && count($cartProducts) > 0)
        <form role='form' id="" action="{{--{{ url(route('updateCart')) }}--}}" method="POST">
            {{ csrf_field() }}
            <div class="container-fluid webCart" id="cartStore">
                @php $TotalAmount = 0; @endphp
                @if(count($cartProducts) > 0)
                    @foreach($cartProducts as $key=> $store)

                        <div class="container">
                            <div class="col-sm-12 clsHead">                                
                                <span>{{ $store[0]->store_name }}</span>                                
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
                                    @foreach($store as $product)

                                        <tr data-product-count-id="{{$product['id']}}">
                                            <td class="newTable">
                                                <div class="col-md-5">
                                                    <img src="{{ url('doc/product_image/'.((isset($product->product->image->file_name) && $product->product->image->file_name !="")?$product->product->image->file_name:"no-images.jpeg")) }}" style="height: 125px;">
                                                </div>
                                                <div class="col-md-6">
                                        <span class="key">
                                            <a href="{{ url(route('showProductDetails',['productSlug'=>$product['product_slug']])) }}">
                                                {{ $product['product_title'] }}
                                            </a>
                                        </span>
                                                    @if(isset($product['combination_title']) && $product['combination_title'] != "")
                                                        <p class="key">({{ $product['combination_title'] }})</p>
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
                                                    <span class="break error qtyOutMsg" id="qtyOutMsg{{ $product['option_id'] }}" style="display: none"><br/>Product quantity is not available</span>
                                                </div>
                                            </td>
                                            <td class="cost clsPrice">{{ (float)($product['price']) }} KD</td>
                                            <td class="QuantityInput">

                                                <input type="text" onDrop="return false" onPaste="return false" id="quantity1{{ $product['id'] }}" data-qty="{{$product['available_qty']}}"
                                                       class="Quantity1" min="1" max="{{ $product['available_qty'] }}" name="quantity{{ $product['product_combination_id'] }}"
                                                       data-price="{{$product['price']}}" data-cart-id="{{$product['id'] }}" data-cart-product-id="{{$product['product_id']}}"
                                                       data-product-combination-id="{{$product['option_id']}}" value="{{ $product['quantity'] }}"
                                                       title="Qty" maxlength="{{ strlen($product['available_qty']) }}" >
                                                <i class="fa fa-caret-up clsUp" aria-hidden="true"></i>
                                                <i class="fa fa-caret-down clsDown" aria-hidden="true"></i>
                                            </td>
                                            <td class="cost clsTotal">{{ (float)($product['price']*$product['quantity']) }} KD</td>
                                            <td>
                                                <a class="delete-data" data-product-id="{{$product['id']}}" data-product-name="{{$product['product_title']}}" data-option-name="{{$product['combination_title']}}" data-toggle="modal" href="#" data-target="#cartRemoveModal">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @php
                                       $TotalAmount = $TotalAmount + ($product['price']*$product['quantity']);
                                    @endphp
                                    @endforeach
                                </table>
                             </div>
                        </div>
                    @endforeach
                @endif
            </div>


            <div class="mobileCart">
                @php $TotalAmount = 0; @endphp
                @if(count($cartProducts) > 0)
                    @foreach($cartProducts as $key=> $store)
                    <button class="accordion" type="button">{{ $store[0]->store_name }}</button>

                        <div class="panel">                        
                                   
                                    @foreach($store as $product)

                                        <div data-product-count-id="{{$product['id']}}">
                                            <div class="row clsProduct">
                                                
                                                <span> 
                                                    <img src="{{ url('doc/product_image/'.((isset($product->product->image->file_name) && $product->product->image->file_name !="")?$product->product->image->file_name:"no-images.jpeg")) }}">
                                                </span>
                                                
                                    <span class="cartDetails">
                                               
                                        <p class="key">
                                            <a class="name2" href="{{ url(route('showProductDetails',['productSlug'=>$product['product_slug']])) }}">
                                                {{ $product['product_title'] }}
                                            </a>
                                        </p>
                                                    @if(isset($product['combination_title']) && $product['combination_title'] != "")
                                                        <p class="key">({{ $product['combination_title'] }})</p>
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
                                                    <span class="break error qtyOutMsg" id="qtyOutMsg{{ $product['option_id'] }}" style="display: none"><br/>Product quantity is not available</span>

                                                    <div class="priceProduct">
                                                <a class="delete-data" data-product-id="{{$product['id']}}" data-product-name="{{$product['product_title']}}" data-option-name="{{$product['combination_title']}}" data-toggle="modal" href="#" data-target="#cartRemoveModal">
                                                    Remove
                                                </a>
                                            </div>

                                        </span>

                                        <div class="cartPriceDetails" id="cartPriceDetails{{$product['option_id']}}">
                                            <div class="priceProduct clsPrice"><a href="#"><span class="cost">{{ (float)($product['price']) }} KD</span></a></div>
                                            <div class="QuantityInputM">

                                                <input type="text" onDrop="return false" onPaste="return false" id="quantity2{{ $product['id'] }}" data-qty="{{$product['available_qty']}}"
                                                       class="Quantity2" min="1" max="{{ $product['available_qty'] }}" name="quantity{{ $product['product_combination_id'] }}"
                                                       data-price="{{$product['price']}}" data-cart-id="{{$product['id'] }}" data-cart-product-id="{{$product['product_id']}}"
                                                       data-product-combination-id="{{$product['option_id']}}" value="{{ $product['quantity'] }}"
                                                       title="Qty" maxlength="{{ strlen($product['available_qty']) }}" >
                                                <i class="fa fa-caret-up clsUp" aria-hidden="true"></i>
                                                <i class="fa fa-caret-down clsDown" aria-hidden="true"></i>
                                            </div>
                                            <div class="priceProduct">TOTAL
                                                <a href="#">
                                                    <span class="cost clsTotal">{{ (float)($product['price']*$product['quantity']) }} KD</span>
                                                </a>                                            
                                            </div>
                                            
                                        </div>
                                               
                                    </div>
                                            
                                        </div>
                                    @php
                                       $TotalAmount = $TotalAmount + ($product['price']*$product['quantity']);
                                    @endphp
                                    @endforeach
                             
                             
                        </div>
                    @endforeach
                @endif
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
                        <a href="{{url(route('checkouts'))}}"><button class="procesedCheckout" type="button">Proceed to Checkout</button></a>
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
   </div>
    <!-- Modal -->
    <div id="cartRemoveModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">

                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{ ((!empty($customer))?($customer->id):"") }}">
                    <input type="hidden" name="cart_id"  id="cart_id" value="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Remove Product</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Are you sure want to remove "<b><span id="product_name"></span></b>" from your cart ? </label>
                            </div>
                            <div class="col-md-6">

                            <span class="textDashboard">

                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-submit btn-success" >Remove</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var trId = "";
        $(document).on('click','.delete-data',function(){

           var product_name = $(this).data('product-name');
           var product_id = $(this).data('product-id');
           var product_option = $(this).data('option-name');

           $('#cartRemoveModal').find('#cart_id').val(product_id);
           $('#cartRemoveModal').find('#product_name').text(product_name+' '+product_option);

            trId=$(this).closest('#cartStore .table tbody tr').data('product-count-id');
        });


        $(document).on('click','#cartRemoveModal .btn-submit',function(){

           $.ajax({

              url:"{{route('cart.remove')}}",
              type:"post",
              data:{'cart_id':$('#cartRemoveModal #cart_id').val()},

              success:function(response){
                if(response.status){
                    toastr.success(response.message,'Success!');

                    var cartCount = 0;
                    $('#cartStore .table > tbody > tr').each(function(){
                        cartCount++;
                        if($(this).data('product-count-id') == trId){
                            $(this).remove();
                        }
                    });

                    $('.cartPosition .my-cart-badge a').text(cartCount-1);
                    $('.main-cart-div').load(location.href + " .main-cart-div>*");
                    $('#cartRemoveModal').modal('hide');

                }else {
                    toastr.error(response.message, 'Error!');
                }
              },
           });
        });


        // WebCart
        $(document).on('click','.QuantityInput .clsUp',function(){

            var maxlimit = $(this).prev('.Quantity1').attr('max');
            var currQty = parseInt($(this).prev('.Quantity1').val());
            var cartId = $(this).prev('.Quantity1').data('cart-id');

            if(currQty < maxlimit){
                $(this).prev('.Quantity1').val(currQty+1);
                updateQty($('#quantity1'+cartId));
            }

        });
        $(document).on('click','.QuantityInput .clsDown',function(){

            var maxlimit = $(this).prev().prev('.Quantity1').attr('max');
            var currQty = parseInt($(this).prev().prev('.Quantity1').val());
            var cartId = $(this).prev().prev('.Quantity1').data('cart-id');

            if(currQty != 1){
                $(this).prev().prev('.Quantity1').val(currQty - 1);
                updateQty($('#quantity1'+cartId));
            }
        });

        $('.Quantity1').keypress(function(e){

            if (this.value.length == 0 && e.which == 48 ){
                return false;
            }else if(!(e.charCode >=47 && e.charCode <=58) &&  e.charCode != 13){
                return false;
            }else{
                if(e.charCode == 13){
                    updateQty($(this));
                }
                return true;
            }
        });

        $('.Quantity2').keypress(function(e){

            if (this.value.length == 0 && e.which == 48 ){
                return false;
            }else if(!(e.charCode >=47 && e.charCode <=58) &&  e.charCode != 13){
                return false;
            }else{
                if(e.charCode == 13){
                    updateQty($(this));
                }
                return true;
            }
        });

        // WebCart over

        // mobileCart
        $(document).on('click','.mobileCart .QuantityInputM .clsUp',function(){

            var maxlimit = $(this).prev('.Quantity2').attr('max');
            var currQty = parseInt($(this).prev('.Quantity2').val());
            var cartId = $(this).prev('.Quantity2').data('cart-id');

            if(currQty < maxlimit){
                $(this).prev('.Quantity2').val(currQty+1);
                updateQty($('#quantity2'+cartId));
            }

        });
        $(document).on('click','.mobileCart .QuantityInputM .clsDown',function(){

            var maxlimit = $(this).prev().prev('.Quantity2').attr('max');
            var currQty = parseInt($(this).prev().prev('.Quantity2').val());
            var cartId = $(this).prev().prev('.Quantity2').data('cart-id');

            if(currQty != 1){
                $(this).prev().prev('.Quantity2').val(currQty - 1);
                updateQty($('#quantity2'+cartId));
            }
        });



        // qty change call update cart qty
        function updateQty(event){

            //$('.btn-checkout').closest('li').show();
            var productId =$(event).data('cart-product-id');
            $('.error-product'+productId).text('');

            var cartId = $(event).data('cart-id');

            //var weight = $(event).data('product-weight');

            var price = $(event).data('price');

            var qty = parseInt($(event).val().trim());

            qty =(qty == '' || isNaN(qty) || qty <= 0) ? 1 :  qty;

            var product_combination_id =$(event).data('product-combination-id');

            var response = JSON.parse(addToCart(product_combination_id,qty,'cart',cartId));

            var $window = $(window);
            var selectedDiv = ($window.width() > 768) ? '.webCart' : '.mobileCart';


            if(response.status){
                $(event).val(qty);

                var productPrice = parseFloat(qty*price).toFixed(2);
                $('#cartPriceDetails'+product_combination_id).find('.priceProduct a .clsTotal').text(productPrice+ ' KD');
                $(event).closest('tr').find('.clsTotal').text(productPrice+ ' KD');
                $(selectedDiv+' #qtyOutMsg'+product_combination_id).hide();

                totalPrice();
            }else{
                $('.btn-checkout').closest('li').hide();
                $(selectedDiv+' #qtyOutMsg'+product_combination_id).show();

            }

            var errorCt = 0;
            $(selectedDiv+' .qtyOutMsg').each(function(e){

                if($(this).is(':visible')){
                    errorCt++;
                }
            });
            if(errorCt>0){
                $('.procesedCheckout').attr('disabled',true);
            }else{
                $('.procesedCheckout').attr('disabled',false);
            }


        }

        // get total price of all items
        function totalPrice() {
            var totalQty = 0;
            var totalPrice = 0;
            var $window = $(window);
            var selectedDiv = ($window.width() > 768) ? '1' : '2';

            $(".Quantity"+selectedDiv).each(function(e) {
                var cartId = $(this).data('cart-id');

                var qty = parseInt($('#quantity'+selectedDiv+cartId).val());

                var price = parseFloat($(this).data('price'));

                totalPrice += (price * qty);

                totalQty += qty;
            });

            totalPrice = parseFloat(totalPrice).toFixed(2);

            $('.grand-total').html(totalPrice+" KD");

        }

        $('.Quantity1').change(function(e){
            updateQty($(this));
        });

        $('.Quantity2').change(function(e){
            updateQty($(this));
        });
    </script>
@endsection