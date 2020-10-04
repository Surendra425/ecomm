@extends('layouts.app')
@section('title') Checkout @endsection
@section('css')
    <style type="text/css">
        .error {
            margin: 0px 5px 0px 0px;
            text-align: left;
            font-size: 15px;
            color: #ef5350;
            display: inline-block;
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
            <form name="myform" id="formCheckout" action="{{ url(route('storeOrder')) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="sub_total" value="{{ $SubTotal }}"/>
                <input type="hidden" name="shipping_total" id="shippingTotal" value="0"/>
                <input type="hidden" name="order_total" value="{{ $SubTotal }}" id="order_total"/>
                <input type="hidden" name="grand_total" value="{{ $TotalAmount }}" id="grand_total"/>
                <input type="hidden" name="discount_amount" value="" id="discount_amount"/>
                <input type="hidden" name="coupon_code" value="" id="coupon_code"/>
                <div class="container">
                    <div class="col-sm-6">

                        @if(empty($customer))
                            <div class="panel1 panel-success">
                                <div class="panel-heading">
                                    <button class="accordionPersonal" type="button">PERSONAL DETAILS</button>
                                </div>
                                <div class="panel-body">
                                    <div class="row clsAddressField">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="first_name">First&nbsp;Name<span class="asteric">*</span></label>
                                                <input id="first_name" name="first_name" class="form-control clsLoginField" type="text" data-validation="required" placeholder="First Name">
                                            </div>
                                            <div class="form-group">
                                                <label for="last_name">Last&nbsp;Name<span class="asteric">*</span></label>
                                                <input id="last_name" name="last_name" class="form-control clsLoginField" type="text" data-validation="required" placeholder="Last Name">
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email<span class="asteric">*</span></label>
                                                <input id="email" name="email" class="form-control clsLoginField" type="text" data-validation="required" placeholder="Email">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="panel1 panel-success">
                            <div class="panel-heading">
                                <button class="accordionShipping" type="button">SHIPPING DETAILS</button>
                            </div>
                            <div class="panel-body">
                                @php $selctedAddress = '';  @endphp
                                @if(!empty($customer_address))
                                    <div class="row">
                                        @foreach($customer_address as $address)
                                            @if($address->is_selected == 'Yes')
                                                @php $selctedAddress = $address->id;  @endphp
                                            @endif
                                            <div class="col-md-6 ship-address">
                                                <b>{{ $address->full_name }}</b><br>
                                                <button class="btn btn-success btnAddress" id="addressSelectd{{$address->id}}" address_id="{{ $address->id }}" onclick="shippingCountry('{{$address->full_name}}','{{$address->block or ""}}','{{$address->building or ""}}','{{$address->street or ""}}','{{$address->additional_directions or ""}}','{{$address->city_id}}','{{$address->country_id}}','{{$address->country}}','{{$address->mobile}}')" type="button">Deliver to this Address</button>
                                                <a class="btn btn-danger btnAddress" href="{{route('deleteUserAddress',['address'=>$address->id])}}"><i class="fa fa-trash"></i></a>
                                            </div>
                                        @endforeach
                                            <div class="col-md-6 ship-address">
                                            <br>
                                                <button class="btn btn-success btnAddress" onclick="addNewAddress()" id="addressAddress"  type="button">Add New Address</button>
                                            </div>
                                    </div>
                                    <br/><br/>
                                @endif
                                <div class="row clsAddressField" id="addNewAddressDiv" style="display:block;">
                                    <input type="hidden" id="selected_address_id" value="{{$selctedAddress or ''}}" name="selected_address_id" />
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="full_name">Address Title <span class="asteric">*</span></label>
                                            <input id="full_name" name="full_name" class="form-control clsLoginField"  type="text" placeholder="e.g. Home,Office,Dwaniya...etc." >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="country_id">Country <span class="asteric">*</span></label>
                                            <select class="form-control clsLoginField" id="country" name="country">
                                                @foreach($country as $item)
                                                    <option value="{{$item->id}}" data-countryname = {{$item->country_name}} {{$item->country_name == 'Kuwait' || $item->country_name == 'KUWAIT' ? 'selected' : ''}} >{{$item->country_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="city_id">Area <span class="asteric">*</span></label>
                                            <input type="hidden" value="{{!empty($address->city_id) ? $address->city_id : ''}}" name="city_ids" id="city_ids">
                                            <select class="form-control clsLoginField" id="city" name="city">
                                                <option value="{{!empty($address) && !empty($city) &&  $address->city_id == $city->id ? $address->city_id : ''}}" {{!empty($address) && !empty($city) &&  $address->city_id == $city->id ? 'selected' : ''}}>{{ !empty($city) && $address->city_id == $city->id ?  $city->city_name : 'Select Area'}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group hide" id="additional_directions_div">
                                            <label for="additional_directions">Additional Directions </label>
                                            <input id="additional_directions"  name="additional_directions" class="form-control clsLoginField" type="text"  placeholder="Additional Directions">
                                        </div>
                                        </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="block">Block <span class="asteric">*</span></label>
                                            <input id="block" name="block" class="form-control clsLoginField" type="text" placeholder="Block" >
                                        </div>
                                        <div class="form-group">
                                            <label for="street">Street <span class="asteric">*</span></label>
                                            <input id="street" name="street" class="form-control clsLoginField" type="text" placeholder="Street" >
                                        </div>
                                        <div class="form-group">
                                            <label for="avenue">Avenue </label>
                                            <input type="text" id="avenue" name="avenue" class="form-control clsLoginField" placeholder="Avenue">
                                        </div>
                                        <div class="form-group">
                                            <label for="building">Building <span class="asteric">*</span></label>
                                            <input type="text" id="building" name="building" class="form-control clsLoginField" placeholder="Building" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="floor">Floor</label>
                                            <input type="text" id="floor" name="floor" class="form-control clsLoginField" placeholder="Floor">
                                        </div>
                                        <div class="form-group">
                                            <label for="apartment">Apartment</label>
                                            <input type="text" id="apartment" name="apartment" class="form-control clsLoginField" placeholder="Apartment">
                                        </div>

                                        <div class="form-group">
                                            <label for="mobile">Mobile <span class="asteric">*</span></label>
                                            <input type="text" id="mobile_no" name="mobile_no" class="form-control phonNumberOnly clsLoginField" placeholder="Mobile">

                                        </div>
                                        <div class="form-group">
                                            <label for="landline">Landline Number</label>
                                            <input type="text" id="landline" name="landline" class="form-control phonNumberOnly clsLoginField" placeholder="Landline Number">
                                        </div>
                                    </div>
                                 </div>
                                @if(empty($customer))
                                <div class="row clsAddressField">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" id="account" name="account" value="yes" checked> <span class="label-text">Create an Account?</span>
                                                </label>
                                            </div>
                                            <div class="form-group customerAccount">
                                                <label for="password">Gender <span class="asteric">*</span></label>
                                                <div>
                                                    <label>
                                                        <input type="radio" name="gender" value="Male"> Male
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="gender" value="Female" > Female
                                                    </label>
                                                </div>
                                                <label id="gender-error" class="error" for="gender"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 customerAccount">
                                            <div class="form-group">
                                                <label for="password">Password <span class="asteric">*</span></label>
                                                <input id="password" name="password" class="form-control clsLoginField" type="password"  placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="col-md-6 customerAccount">
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirm Password <span class="asteric">*</span></label>
                                                <input id="password_confirmation" name="password_confirmation" class="form-control clsLoginField" type="password"  placeholder="Confirm Password">
                                            </div>
                                        </div>

                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="panel1 panel-success">
                            <div class="panel-heading">
                                <button class="accordionPayment" type="button">PAYMENT METHOD</button>
                            </div>
                            <div class="panel-body PAYMENT">
                                
                                <div class="radioGrp">
                                    <input type="radio" value="CreditCard" id="creditCard" name="payment_type">
                                    <span class="radio_custom"></span>
                                    <label for="creditCard" class="payment-method">
                                        <img src="{{ url('assets/frontend/images/credit-card_icon.png') }}">&nbsp;Credit Card
                                    </label>
                                </div>
                                  <div class="radioGrp">
                                    <input type="radio" id="test2" value="KNet" name="payment_type">
                                    <span class="radio_custom"></span>
                                    <label  for="test2" class="payment-method">
                                        <img src="{{ url('assets/frontend/images/knet_icon.png') }}">&nbsp;KNET
                                    </label>
                                </div>
                                <div class="radioGrp hide" id="codDiv">
                                    <input type="radio" value="Cash on Delivery" id="test4" name="payment_type">
                                    <span class="radio_custom"></span>
                                    <label  for="test4" class="payment-method">
                                    <img src="{{ url('assets/frontend/images/cash_icon.png') }}">&nbsp;Cash</label>
                                </div> 
                                <label id="payment_type-error" class="error" for="payment_type"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-offset-3 col-lg-3 col-md-offset-3 col-md-3 col-sm-offset-2 col-sm-4" id="CheckoutCart">
                        <div class="row">
                            <h4 class="border1"><b>DISCOUNT CODES</b></h4>
                            <p class="border-para">Enter your coupon code if you have one</p>
                            <input class="from-control couponCode" type="text" placeholder="Enter coupon code" name="coupon" id="coupon">
                            <span class="error text-danger"></span>
                            <span class="success text-success"></span>
                            <div class="apply text-center">
                                <button type="button" id="btnApplyCoupan"><b>APPLY</b></button>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr class="cartTotal">
                                <th>Cart Total</th>
                                <th></th>
                                <th></th><th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Subtotal</td>
                                <td></td>
                                <td></td>
                                <td class="cartPrice text-right"> {{ (float)($SubTotal) }} KD</td>
                            </tr>
                            <tr>
                                <td>Shipping</td>
                                <td></td> <td></td>
                                <td class="cartPrice text-right clsShipping"> {{ (float)($ShippingTotal) }} KD</td>
                            </tr>
                            {{-- <tr>
                                <td>Tax</td>
                                <td></td> <td></td>
                                <td class="cartPrice text-right">0 KD</td>
                            </tr> --}}
                            <tr class="clsDiscount">
                                <td>Discount</td>
                                <td></td> <td></td>
                                <td class="cartPrice text-right clsDiscountCell">0 KD</td>
                            </tr>
                            <tr class="total">
                                <td >TOTAL</td>
                                <td></td> <td></td>
                                <td class="cartPrice text-right clsTotalAmountCell"> {{ (float)($TotalAmount) }} KD</td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <button class="procesedPay" id="chekoutButton" type="button">Checkout</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
@endsection
@section('js')
    <script type="text/javascript" src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
    <script type="text/javascript">
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        
        $("#chekoutButton").on('click',function(){
            $("#formCheckout").submit();
        });

        function country(countryId) {
            var city_id = $("#city_ids").val();

            $.ajax({
                url: " {{url('admin/get/unique/city/country_id') }}",
                method: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'value': countryId
                },
                success: function (result)
                {
                    var obj = jQuery.parseJSON(result);
                    var html = '';
                    if (obj.length > 0)
                    {
                        html += "<option value=''>Select Area</option>";
                        $.each(obj, function (i, item)
                        {
                            if(city_id == item.id){
                                html += "<option value='" + item.id + "' selected>" + item.city_name + "</option>";
                            }else{
                                html += "<option value='" + item.id + "'>" + item.city_name + "</option>";
                            }

                        });
                        $('#city').html(html);
                    }
                    else
                    {
                        html += "<option value=''>Select Area</option>";
                        $('#city').html(html);
                    }
                }
            });
        }
        $('#account').click(function(){
            if($(this).is(':checked')){
                $(".customerAccount").removeClass('hide');
            } else {
                $(".customerAccount").addClass('hide');
            }
        });

        //customerAccount

        function addNewAddress(){
            $("#full_name").val('');
            $("#block").val('');
            $("#building").val('');
            $("#street").val('');
            $("#additional_directions").val('');
            $("#city").val('');
            $("#city_ids").val('');
            $("#mobile_no").val('');
            $("#country").val('2');
            $("#selected_address_id").val('');
            country(2);
            $("#addNewAddressDiv").removeClass('hide');
            $("#addNewAddressDiv").css('display','block');
        }
        function shippingCountry(title,block,building,street,additional,cityName,countryName,country,mobile_no){
            //alert(countryName);
            $("#full_name").val(title);
            $("#block").val(block);
            $("#building").val(building);
            $("#street").val(street);
            $("#additional_directions").val(additional);
            $("#city").val(cityName);
            $("#city_ids").val(cityName);
            $("#country").val(countryName);
            $("#mobile_no").val(mobile_no);
            var countryName = countryName;
            var cityName = cityName;
            
            shippingDetails(countryName,country,cityName);
            
        }

        function shippingDetails(countryName,country,cityName){
            var coupon = $("#discount_amount").val();
            //alert(country);
            if(country == 'Kuwait' || country == 'KUWAIT'){
                $("#codDiv").removeClass('hide');
            }else{
                $("#codDiv").addClass('hide');
            } 
            //$("#codDiv").removeClass('hide');
            var data = {
                "countryName": countryName,
                "cityName": cityName,
                "coupon": coupon,
            };
            $.ajax({
                url: "{{ url(route('applyShippping')) }}",
                dataType: 'text',
                type: 'post',
                contentType: 'application/x-www-form-urlencoded',
                data: data,
                success: function (data, textStatus, jQxhr)
                {
                    
                    data = JSON.parse(data);
                    console.log(data);
                    if (data.error)
                    {
                        $("#alert-danger").text(data.error);
                        toastr.error(data.error, 'Error!');
                        $("#chekoutButton").attr('disabled','disabled');
                    }else{
                        if(data.coupon != 0){
                            $("#discount_amount").val(data.coupon);
                        }
                        
                        var ShippingTotal = data.ShippingTotal;
                        var grand_total = data.TotalAmount;
                        var total = parseFloat(grand_total);
                        $(".clsShipping").html(ShippingTotal + " KD");
                        $(".clsTotalAmountCell").html(total + " KD");
                        $("#grand_total").val(grand_total);
                        $("#shippingTotal").val(ShippingTotal);
                        $("#chekoutButton").removeAttr('disabled','disabled');
                       
                    }

                }
            });
        }
        $('#city').change(function(e) {
            var countryName = $("#country").val();
            var country = $('#country option:selected').data('countryname');
            var cityName = $("#city").val();
            shippingDetails(countryName,country,cityName);
        });

        $("#country").change(function ()
        {
            var countryId = this.value;
            country(countryId);
        });
        

        $(document).ready(function ()
        {
            var countryId = $("#country").val();
            //alert(countryId);
            country(countryId);


            var paymentType = $('input[name=payment_type]:checked').val();
            if(paymentType == 'Cash on Delivery'){
                $("#chekoutButton").text("Checkout");
            }/*else{
                $("#chekoutButton").text("Processed to Pay");
            }*/
            $('input[type=radio][name=payment_type]').change(function() {
                if (this.value == 'Cash on Delivery') {
                    $("#chekoutButton").text("Checkout");
                }
                /*else {
                    $("#chekoutButton").text("Processed to Pay");
                }*/
            });

            var selected_address_id = $("#selected_address_id").val();
            //alert(selected_address_id);
            if(selected_address_id != ''){
                if (selected_address_id)
                {
                    var city_id = $("#city_ids").val();
                    $(".btnAddress").removeClass("active");
                    $(this).addClass("active");
                    $("#selected_address_id").val(selected_address_id);
                    $("#city_ids").val(city_id);
                    $(".clsAddressField").hide();
                    //$(".accordionPayment").trigger("click");
                }
                $( "#addressSelectd"+selected_address_id ).click();
            }
            $(".clsDiscount").hide();
            $("#btnApplyCoupan").click(function ()
            {
                var coupon = $("#coupon").val();
                var shippingTotal = $("#shippingTotal").val();
                var row = $(this).closest(".row");
                $(row).find(".error").text("");
                $(row).find(".success").text("");
                if (coupon != "")
                {
                    var order_total = $("#order_total").val();
                    var data = {
                        "coupon": coupon,
                        "order_total": order_total
                    };
                    $.ajax({
                        url: "{{ url(route('applyCouponCode')) }}",
                        dataType: 'text',
                        type: 'post',
                        contentType: 'application/x-www-form-urlencoded',
                        data: data,
                        success: function (data, textStatus, jQxhr)
                        {
                            data = JSON.parse(data);
                            if (data.status == 1)
                            {
                                var DiscountAmount = data.couponDiscountAmount;
                                var couponCode = data.couponCode;

                                var totalWithDis = order_total - DiscountAmount;
                                var grand_total = parseInt(totalWithDis) + parseInt(shippingTotal);

                                var total = parseFloat(grand_total);
                                var discount = DiscountAmount.toFixed(2);
                                $(".clsDiscountCell").html(discount + " KD");
                                $(".clsTotalAmountCell").html(total + " KD");
                                $(".clsDiscount").show();
                                $(row).find(".success").text(data.msg);
                                $("#discount_amount").val(DiscountAmount);
                                $("#sub_total").val(totalWithDis);
                                $("#coupon_code").val(couponCode);
                                $("#grand_total").val(grand_total);
                            }
                            else
                            {
                                $(row).find(".error").text(data.msg);
                            }
                        },
                        error: function (jqXhr, textStatus, errorThrown)
                        {
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $(row).find(".error").text("Please enter coupon code.");
                }
            });

            $(".btnAddress").click(function ()
            {
                var address_id = $(this).attr("address_id");
                if (address_id)
                {
                    var city_id = $("#city_ids").val();
                     $(".btnAddress").removeClass("active");
                    $(this).addClass("active");
                    $("#selected_address_id").val(address_id);
                    $("#city_ids").val(city_id);
                    $("#city").val(city_id);
                    $(".clsAddressField").css('display','none');
                    //$(".accordionPayment").trigger("click");
                }
            });


            $("#formCheckout").validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength: 2
                    },
                    last_name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: baseUrl+'/check/uniqueNotGuest/users/email',
                            type: "post",
                            data: {
                                value: function() {
                                    return $( "#email" ).val();
                                },
                                id: function() {
                                    return $( "#id" ).val();
                                },
                            },
                        }
                    },
                    mobile_no: {
                        required: true,
                        minlength: 8,
                        number:true,
                        /* remote: {
                            url: baseUrl+'/check/uniqueNotGuest/users/mobile_no',
                            type: "post",
                            data: {
                                value: function() {
                                    return $( "#mobile_no" ).val();
                                },
                                id: function() {
                                    return $( "#id" ).val();
                                },
                            },
                        } */
                    },
                    full_name: {
                        required: true,
                        minlength: 2
                    },
                    landline_no: {
                        minlength: 8,
                        number: true,
                    },
                    gender: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    },
                    city: {
                        required: true
                    },
                    country: {
                        required: true
                    },
                    street: {
                        required: true
                    },
                    block: {
                        required: true
                    },
                    building: {
                        required: true
                    },
                    payment_type: {
                        required: true,
                    },
                },
                messages: {
                    first_name: {
                        required: "First Name is required",
                        minlength: "First Name have atleast 2 character"
                    },
                    last_name: {
                        required: "Last Name is required",
                        minlength: "Last Name have atleast 2 character"
                    },
                    mobile_no: {
                        required: "Phone Number is required",
                        minlength: "Phone Number have atleast 8 character",
                        number: "Invalid phone number",
                        //remote: "Phone Number is already register with us."
                    },
                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email address.",
                        remote: "Email is already register with us."
                    },
                    full_name: {
                        required: "Address Title is required",
                        minlength: "Address Title have atleast 2 character"
                    },
                    landline_no: {
                        minlength: "Mobile Number have atleast 8 character",
                        number: "Invalid phone number",
                    },
                    gender: {
                        required: "Gender is required"
                    },
                    password: {
                        required: "Password is required",
                        minlength: "Password have atleast 6 character"
                    },
                    password_confirmation: {
                        required: "Confirm password is required",
                        equalTo: "Password and confirm password not match"
                    },
                    city: {
                        required: "Area is required",
                    },
                    country: {
                        required: "Country is required",
                    },
                    street: {
                        required: "Street is required",
                    },
                    block: {
                        required: "Block is required",
                    },
                    building: {
                        required: "Building is required",
                    },
                    payment_type: {
                        required: "please select payment method",
                    },
                },
                submitHandler: function (form)
                {
                    //$(".clsAddressField").show();
                    var address_id = $("#selected_address_id").val();
                    if (!address_id)
                    {
                        $(".clsAddressField").show();
                    }
                    $(".accordionShipping").trigger("click");
                    if ($("#formCheckout").valid())
                    {
                        $.blockUI(
                                {
                                    message: "<img src='{{ url('assets/loader.gif') }}' class='loaderGif'  style='height: 100px;width: 100px;'/>",
                                    centerX: true,
                                    centerY: true,

                                });
                        form.submit();
                    }
                    $(document).find("input.error").focus();
                    return false;
                }
            });
            /*$(".panel-heading").click(function ()
            {
                $("#formCheckout").valid();
                $(this).parent().addClass('active').find('.panel-body').slideToggle('fast');
                $(".panel-heading").not(this).parent().removeClass('active').find('.panel-body').slideUp('fast');
            });*/
        });
    </script>
@endsection

