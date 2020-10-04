@extends('front.layout.index')
@section('title') Checkout @endsection
@section('css')

@endsection

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
        <form name="myform" id="formCheckout" class="require-validation"  action="{{ url(route('storeOrder')) }}" method="POST"  data-stripe-publishable-key="pk_test_51HY3VWI4FjP0xpF8AI7BA04NcnSfUvMdQVarVFz1qxAup2VHOQFuJB7fPY6Jx7xC126NuXTozThExLbJyqhKD0bD00PC7ZDGvS">
            {{ csrf_field() }}
            <input type="hidden" name="sub_total" id="sub_total" value="{{ $subTotal }}"/>
            <input type="hidden" name="shipping_total" id="shipping_total" value="0"/>
            <input type="hidden" name="grand_total" value="{{ $totalAmount }}" id="grand_total"/>
            <input type="hidden" name="discount_amount" value="0" id="discount_amount"/>

            <div class="container">
                <div class="col-sm-6">

                    @if(empty($customer))

                        <div class="panel1 panel-success">
                            <div class="panel-heading">
                                <button class="accordionPersonal" type="button">PERSONAL DETAILS</button>
                                <div class="signin">
                                    <a href="{{route('login')}}" class="button-btn"><b>SIGN IN</b></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row clsAddressField">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="first_name">First&nbsp;Name<span class="asteric">*</span></label>
                                            <input id="first_name" name="first_name" class="form-control clsLoginField" type="text" data-validation="required" tabindex="1" placeholder="First Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name">Last&nbsp;Name<span class="asteric">*</span></label>
                                            <input id="last_name" name="last_name" class="form-control clsLoginField" type="text" data-validation="required" tabindex="2" placeholder="Last Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email<span class="asteric">*</span></label>
                                            <input id="email" name="email" class="form-control clsLoginField" type="text" data-validation="required" tabindex="3" placeholder="Email">
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
                            @php  $selctedAddress = '';  @endphp
                            @if(!empty($customerAddress) && $customerAddress->count() > 0)
                                <div class="row">
                                    @foreach($customerAddress as $address)
                                        @if($address->is_selected == 'Yes')
                                            @php $selctedAddress = $address->id;  @endphp
                                        @endif
                                        <div class="col-md-6 ship-address">
                                            <b>{{ $address->full_name }}</b><br>
                                            <button class="btn btn-success btnAddress selectAddress" id="addressSelectd{{$address->id}}"
                                                    data-addressId="{{ $address->id }}" data-countryId = {{$address->country_id}} data-cityId = {{$address->city_id}}
                                                    type="button"><span class=""></span>Deliver to this Address</button>

                                            <a class="btn btn-danger btnAddress dlt-address" data-addressid="{{$address->id}}" data-target="#deleteAddressModal" data-toggle="modal" href="#"><i class="fa fa-trash"></i></a>
                                        </div>
                                    @endforeach
                                    <div class="col-md-6 ship-address">
                                        <br>
                                        <button class="btn btn-success btnAddress" id="addressAddress"  type="button">Add New Address</button>
                                    </div>
                                </div>
                                <br/><br/>
                            @endif

                            @include('front.checkout.shipping_detail')

                            @if(empty($customer))
                                <div class="row clsAddressField">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>


                                                <input type="checkbox" id="account" name="account" value="Yes" tabindex="16" checked> <span class="label-text">Create an Account?</span>
                                                <input type="hidden" id="account_chk" name="account" value="No">
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


                    <input type="hidden" name="payment_type" value="CreditCard">

                  <div class="col-md-12">

                        <div class="form-group">
                          <label for="creditCardNumber"> Card Number <span class="asteric">*</span></label>
                          <input  autocomplete='off'  id="cardNumber" class='form-control  clsLoginField card-number' size='20'  type='text' name="cardNumber" placeholder="Card Number">
                      </div>

                    </div>

                  <div class="col-md-6">
                    <div class="form-group">
                     <label for="MM"> MM <span class="asteric">*</span></label>
                     <select class="form-control clsLoginField card-expiry-month" id="mm" name="mm" tabindex="6">
                        <option value="">MM</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                     <label for="YYYY"> YYYY <span class="asteric">*</span></label>
                     <input  class='form-control card-expiry-year clsLoginField' placeholder='YYYY' size='4' name="yyyy" type='text' minlength="4" maxlength="4">
                 </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                     <label for="CVC"> CVC <span class="asteric">*</span></label>
                     <input  class='form-control card-cvc clsLoginField' placeholder='CVC' size='4' name="cvc"  type='text'>
                 </div>
                </div>






                    <div class="panel1 panel-success hide">
                        <div class="panel-heading hide">
                            <button class="accordionPayment" type="button">PAYMENT METHOD</button>
                        </div>
                        <div class="panel-body PAYMENT">

                            <div class="radioGrp hide">
                                <input type="radio" value="CreditCard" id="creditCard"  checked name="payment_type">
                                <span class="radio_custom"></span>
                                <label for="creditCard" class="payment-method">
                                    <img alt="credit-card_icon" src="{{ url('assets/frontend/images/credit-card_icon.png') }}">&nbsp;Credit Card
                                </label>
                            </div>
                            <label id="payment_type-error" class="error" for="payment_type" style="display: none;">please select payment method</label>

                            <!-- <div class="cardClass"> -->

                                <!-- <div class="row">
                                <div class="col-md-12">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                          <label for="creditCardNumber"> Card Number <span class="asteric">*</span></label>
                                            <input  autocomplete='off'  id="cardNumber" class='form-control  clsLoginField card-number' size='20'  type='text' name="cardNumber" placeholder="Card Number">
                                    </div>
                                    </div>
                                    </div>
                                </div>


                                 <div class="row">
                                     
                                     <div class="col-md-12">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                   <label for="MM"> MM <span class="asteric">*</span></label>
                                                   <select class="form-control clsLoginField card-expiry-month" id="mm" name="mm" tabindex="6">
                                                            <option value="">MM</option>
                                                            <option value="01">01</option>
                                                            <option value="02">02</option>
                                                            <option value="03">03</option>
                                                            <option value="04">04</option>
                                                            <option value="05">05</option>
                                                            <option value="06">06</option>
                                                            <option value="07">07</option>
                                                            <option value="08">08</option>
                                                            <option value="09">09</option>
                                                            <option value="10">10</option>
                                                            <option value="11">11</option>
                                                            <option value="12">12</option>
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                   <label for="YYYY"> YYYY <span class="asteric">*</span></label>
                                                   <input  class='form-control card-expiry-year clsLoginField' placeholder='YYYY' size='4' name="yyyy" type='text' minlength="4" maxlength="4">
                                                </div>
                                         </div>
                                    </div>
                                </div>

                                <div class="row">
                                     <div class="col-md-12">
                                        <div class="col-md-8">
                                                <div class="form-group">
                                                   <label for="CVC"> CVC <span class="asteric">*</span></label>
                                                    <input  class='form-control card-cvc clsLoginField' placeholder='CVC' size='4' name="cvc"  type='text'>
                                                </div>
                                        </div>
                                        
                                    </div>
                                </div> -->




                                       <!--  <input  autocomplete='off'  id="cardNumber" class='form-control card-number' size='20'  type='text' name="cardNumber">
 -->
                                    <!--  <input autocomplete='off'  class='form-control card-cvc' placeholder='ex. 311' size='4'  type='text'>
                                     <input class='form-control card-expiry-month' placeholder='MM' size='2'  type='text'>
                                     <input  class='form-control card-expiry-year' placeholder='YYYY' size='4'  type='text'> -->
                            
                            <!-- </div> -->

                            <div class="radioGrp hide">
                                <input type="radio" id="test2" value="KNet" name="payment_type">
                                <span class="radio_custom"></span>
                                <label  for="test2" class="payment-method">
                                    <img alt="knet_icon" src="{{ url('assets/frontend/images/knet_icon.png') }}">&nbsp;KNET
                                </label>
                            </div>
                            <!-- <div class="radioGrp hide" id="codDiv">
                                <input type="radio" value="Cash on Delivery" id="test4" name="payment_type">
                                <span class="radio_custom"></span>
                                <label  for="test4" class="payment-method">
                                    <img alt="cash_icon" src="{{ url('assets/frontend/images/cash_icon.png') }}">&nbsp;Cash</label>
                            </div> -->
                            <!-- <label id="payment_type-error" class="error" for="payment_type"></label> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-offset-3 col-lg-3 col-md-offset-3 col-md-3 col-sm-offset-2 col-sm-4" id="CheckoutCart">
                    <div class="row">
                        <h4 class="border1"><b>DISCOUNT CODES</b></h4>
                        <p class="border-para">Enter your coupon code if you have one</p>
                        <input class="from-control couponCode" type="text" placeholder="Enter coupon code" name="coupon_code" id="coupon_code">
                        <a class="btn btn-danger btnAddress removePromoCode" href="Javascript:void(0)"><i class="fa fa-trash"></i></a>
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
                            <td class="cartPrice text-right"> {{ (float)($subTotal) }} KD</td>
                        </tr>
                        <tr>
                            <td>Shipping</td>
                            <td></td> <td></td>
                            <td class="cartPrice text-right clsShipping"> {{ (float)(isset($shippingTotal))?$shippingTotal.'KD':'FREE' }} </td>
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
                            <td class="cartPrice text-right clsTotalAmountCell"> {{ (float)($totalAmount) }} KD</td>
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

    <!-- Delete address modal -->
    <div id="deleteAddressModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                    <input type="hidden" name="address_id" value="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Address</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Are you sure want to delete address ?</label><br/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-submit deleteAddress btn-success" >Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
            </div>
        </div>
    </div>
    <!-- Delete address modal -->

@endsection

@section('js')

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.js"></script>

    <script>

        $('#cardNumber').mask("9999 9999 9999 9999");

        $(document).ready(function () {

         

            $("input[name='payment_type']").change(function() {

               var  paymentTypeValue = $(this).val();


                if(paymentTypeValue == "CreditCard")
                {
                    $('.cardClass').removeClass('hide');
                }else{
                    $('.cardClass').addClass('hide');
                }
                


            });

            $('.removePromoCode').hide();

            $('#country_id').trigger('change');

            $("#formCheckout").validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength: 2,
                        maxlength:50,
                    },
                    last_name: {
                        required: true,
                        minlength: 2,
                        maxlength:50,
                    },
                    email: {
                        required: true,
                        email: true,
                        regex:emailpattern,
                        remote: {
                            url: baseUrl+'/check/uniqueNotGuest/users/email',
                            type: "post",
                            async:false,
                            data: {
                                value: function() {
                                    return $( "#email" ).val();
                                },
                                userType : 'guest',
                            },
                        }
                    },
                    mobile: {
                        required: true,
                        minlength: 7,
                        maxlength:17,
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
                    landline: {
                        minlength: 7,
                        maxlength:17,
                        number: true,
                    },
                    gender: {
                        required: function(element){
                            return $('input[type="checkbox"][name="account"]').is(':checked');
                        },
                    },
                    password: {
                        required: function(element){
                            return $('input[type="checkbox"][name="account"]').is(':checked');
                        },
                        minlength: 6,
                        maxlength:15,
                    },
                    password_confirmation: {
                        required: function(element){
                            return $('input[type="checkbox"][name="account"]').is(':checked');
                        },
                        equalTo: "#password"
                    },
                    city_id: {
                        required: true
                    },
                    country_id: {
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
                     cardNumber: {
                        required: true,
                    },
                    mm:{
                       required: true, 
                   },
                   yyyy:{
                       required: true, 
                       digits: true,
                        minlength: 4,
                        maxlength:4
                   },
                   cvc:{
                       required: true, 
                   },
                },
                messages: {
                    first_name: {
                        required: "First Name is required",
                        minlength: "First Name have atleast 2 character",
                        maxlength: "First Name not greater than {0} character",
                    },
                    last_name: {
                        required: "Last Name is required",
                        minlength: "Last Name have atleast 2 character",
                        maxlength: "Last Name not greater than {0} character",
                    },
                    mobile: {
                        required: "Mobile Number is required",
                        minlength: "Mobile Number have atleast {0} character",
                        maxlength:"Mobile Number must be 7-17 numeric characters",
                        number: "Invalid phone number",
                        //remote: "Phone Number is already register with us."
                    },
                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email address.",
                        regex: "Please enter a valid email address.",
                        remote: "Email is already register with us."
                    },
                    full_name: {
                        required: "Address Title is required",
                        minlength: "Address Title have atleast {0} character"
                    },
                    landline: {
                        minlength: "Landline Number have atleast {0} character",
                        maxlength: "Landline Number must be 7-17 numeric characters",
                        number: "Invalid Landline Number",
                    },
                    gender: {
                        required: "Gender is required"
                    },
                    password: {
                        required: "Password is required",
                        minlength: "Password have atleast {0} character",
                        maxlength:"Password must be 6-15 Alpha numeric characters",
                    },
                    password_confirmation: {
                        required: "Confirm password is required",
                        equalTo: "Password and confirm password not match"
                    },
                    city_id: {
                        required: "Area is required",
                    },
                    country_id: {
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
                    cardNumber: {
                        required: "please enter card number",
                    },
                    mm: {
                        required: "please select month",
                    },
                    yyyy: {
                        required: "please enter year",
                        digits:"please enter only number"
                    },
                     cvc: {
                        required: "please enter cvc",
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
                                    message: '<img alt="loader" src="{{ url('assets/loader_new.gif') }}" class="loaderGif"  style="height: 100px;width: 100px;"/>',
                                    centerX: true,
                                    centerY: true,

                                });

                           

                                // if($("input[name='payment_type']:checked").val() == "CreditCard")
                                // {
                                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                                    Stripe.createToken({
                                        number: $('.card-number').val(),
                                        cvc: $('.card-cvc').val(),
                                        exp_month: $('.card-expiry-month').val(),
                                        exp_year: $('.card-expiry-year').val()
                                    }, stripeResponseHandler);
                                // }
                                // else{
                                //     form.submit();
                                // }


                       
                    }
                    $(document).find("input.error").focus();
                    return false;


                   
                }
            });



            var $form  = $(".require-validation");

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    var token = response['id'];
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
             }

        })

    </script>
@endsection