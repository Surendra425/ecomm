<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') | I Can Save the world</title>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="base-url" content="{{ url('/') }}">
    @yield('meta')
    <link rel="stylesheet" type="text/css" href="{{ url('assets/frontend/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('assets/frontend/css/style.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('assets/frontend/css/responsive.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('assets/frontend/css/swiper.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('assets/frontend/css/datepicker.css') }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ url('assets/frontend/font-awesome-4.7.0/css/font-awesome.min.css') }}"/>
    <link rel="shortcut icon" href="{{ url('assets/demo/demo2/media/img/logo/favicon.png') }}"/>
    <link rel="stylesheet" href="{{ url('assets/frontend/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ url('assets/frontend/toastr-master/build/toastr.min.css') }}">
    <style>
        .search-tag {
            margin: 0px;
            height: 2.32em !important;
            -webkit-appearance: none;
        }
    </style>
    @yield('css')
</head>
<body>
<!-- begin::Header -->
@include('app.common.header')
<!-- end::Header -->
<div class="wrapper">
    <!-- begin::Content -->
@yield('content')
<!-- end::Content -->
    <!-- begin::Footer -->

    <!-- end::Footer -->
    <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up" aria-hidden="true"></i>
    </button>
</div>
<script type="text/javascript" src="{{ url('assets/frontend/js/jquery-3.2.1.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/frontend/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/frontend/js/common.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/frontend/js/jquery.touchSwipe.min.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/frontend/js/owl.carousel.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/vendors/base/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/frontend/js/swiper.min.js') }}"></script>
<script type="text/javascript" src="{{ url('front/webpack.js.download') }}"></script>
<script type="text/javascript" src="{{ url('front/vendor.js.download') }}"></script>
<script type="text/javascript" src="{{ url('front/app.js.download') }}"></script>
<script type="text/javascript" src="{{ url('assets/frontend/toastr-master/build/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/frontend/blockui-master/jquery.blockUI.js') }}"></script>
<script type="text/javascript">

    var baseUrl = $('meta[name="base-url"]').attr('content');
    $(document).on("change", ".product_combination", function () {
        var quantity = $(this).find("option:selected").attr("quantity");
        if (quantity < 1) {
            $(this).closest("form").attr("onsubmit", "return false");
            $(this).closest("form").find("[type='submit']").text("Out of Stock");
        }
        else {
            $(this).closest("form").removeAttr("onsubmit");
            $(this).closest("form").find("[type='submit']").text("Add to Cart");
        }
    });

    $(document).ready(function () {

    	@if(session()->has('error1'))
    		toastr.error("{{session()->get('error1')}}", 'Error!');
        @endif

        setTimeout(function () {
            $('.alert-success').fadeOut('fast');
        }, 15000); // <-- time in milliseconds
        setTimeout(function () {
            $('.alert-danger').fadeOut('fast');
        }, 15000); // <-- time in milliseconds
    });

    $(".userAddress1").click(function (e) {
        e.preventDefault();
        var address_id = $(this).data('address_id');
        $.ajax({
            url: "{{url('/my-address/change-address')}}",
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'address_id': address_id
            },
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.success) {
                    toastr.success(obj.success, 'Success');
                    $(".userAddress1").removeClass('active');
                    $("#addressSelected" + address_id).addClass('active');
                    userAddress();
                } else {
                    toastr.error(obj.error, 'Error!');
                }
            },
            complete: function () {
                $("#spinner").hide();
                $("#spinner").removeClass("spinner");
                $("#mask").removeClass("mask");
            }
        });
    });
    $(".userAddress").click(function (e) {
        e.preventDefault();
        var address_id = $(this).data('address_id');
        $.ajax({
            url: "{{url('/my-address/change-address')}}",
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'address_id': address_id
            },
            success: function (result) {
                $(".userAddress").removeClass('active');
                var obj = jQuery.parseJSON(result);
                if (obj.success) {
                    toastr.success(obj.success, 'Success');
                    $("#addressSelect" + address_id).addClass('active');
                    userAddress();
                } else {
                    toastr.error(obj.error, 'Error!');
                }
            },
            complete: function () {
                $("#spinner").hide();
                $("#spinner").removeClass("spinner");
                $("#mask").removeClass("mask");
            }
        });
    });
    function addToCart(combinationId, qty) {
        $.ajax({
            url: "{{url('carts/adds')}}",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            xhrFields: {
                withCredentials: true
            },
            data: {
                'product_combination': combinationId,
                'item_quantity': qty,
            },
            success: function (result) {
                if (result.success) {
                    $(".my-cart-badge").text(result.totalCartItem);
                } else {
                    var obj = jQuery.parseJSON(result);
                    console.log(obj);
                    if (obj.error) {
                        toastr.error(obj.error, 'Error!');

                    }
                    $(".my-cart-badge").text(obj.totalCartItem);
                }

            },
            complete: function () {
                $("#spinner").hide();
                $("#spinner").removeClass("spinner");
                $("#mask").removeClass("mask");
            }
        });
    }
    function addTocartCombination(productID) {
        var combinationId = $("#optionsChanges" + productID).val();
        var qty = $("#qty" + productID).val();
        //alert(qty);
        addToCart(combinationId, qty);
        $(".modal").modal('hide');
    }
     function addTocartCombination1(productID) {
        var combinationId = $("#optionsChanges" + productID).val();
        var qty = $("#qty1" + productID).val();
        //alert(qty);
        addToCart(combinationId, qty);
        $(".modal").modal('hide');
    }
    function addTocartCombinationWeb(productID) {
        //alert('hi');
        var combinationId = $("#selectoption" + productID).val();
        var qty = $("#qtySelected" + productID).val();
         addToCart(combinationId, qty);
        
    }
    function addTocartCombinationWeb1(productID) {
        var combinationId = $("#selectoption" + productID).val();
        var qty = $("#qtySelected1" + productID).val();
        //alert(qty);
         addToCart(combinationId, qty);
        
    }
    function addTocartCombinationDetail(productID) {
        var combinationId = $("#optionsChange").val();
        var combinationId1 = $("#optionsChange12").val();
        var qty = $("#qtyChangeDetails1").val();

        if (combinationId != undefined) {
            addToCart(combinationId, qty);
        } else {
            addToCart(combinationId1, qty);
        }

    }
    function addTocartCombinationDetailMobile(productID) {
        var combinationId = $("#optionsChangeMobile1").val();
        var combinationId1 = $("#optionsChangeMobile12").val();
        var qty = $("#qtyChangeMobile1").val();
        /*alert(combinationId1);
         alert(qty);return false;*/
        if (combinationId != undefined) {
            addToCart(combinationId, qty);
        } else {
            addToCart(combinationId1, qty);
        }

    }
    $(document).on('keypress', '.phonNumberOnly', function (evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 47 && charCode > 31
                && (charCode < 48 || charCode > 57))
            return false;

        return true;
    });
    $(document).ready(function (e) {
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
        $("#fromSubscribe").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: "Email is required",
                    email: "Please enter valid email address"
                }
            },
            submitHandler: function (form) {

                var data = {
                    "email": $("#subscriber_email").val()
                };
                $.ajax({
                    url: "{{ url(route('subscribe')) }}",
                    dataType: 'text',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: data,
                    success: function (data, textStatus, jQxhr) {
                        data = JSON.parse(data);
                        if (data.status == "1") {

                            toastr.success(data.msg, 'Success');
                            //                        $.toaster({message: data.msg, priority: 'success', 'timeout': 15000, });
                        }
                        else {
                            toastr.error(data.msg, 'Error!');
                            //                        $.toaster({message: data.msg, priority: 'danger', 'timeout': 15000});
                        }
                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
                return false;
            }
        });
    });
    function userAddress() {
        var storeId = $("#storeId").val();
        var storeSlug = $("#storeSlug").val();
        // alert(storeId);
        if (storeId != undefined) {
            $.ajax({
                url: "{{url('my-address/address')}}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                xhrFields: {
                    withCredentials: true
                },
                data: {
                    'storeId': storeId,
                },
                success: function (result) {
                    console.log(result);
                    //result = JSON.parse(result);
                    //console.log(result.error);
                    if (result.error) {
                       //$("#btnAddress").addClass('hide');
                        $("#btnAddress").removeClass('hide');
                        $("#addressHasFrom").addClass('hide');
                        $("#addressHasCharge").addClass('hide');
                        $("#btnAddress2").addClass('hide');
                        var url = baseUrl + '/address/create';
                        var html = '<a href="url" class="btn btn-xs  btn-success seller-btn" id="btnAddress1"> Add Address</a>';
                        $("#frombtn").append(html);
                    } else if (result.shipping) {
                        console.log(result.shipping);
                        $("#btnAddress1").addClass('hide');
                        $("#btnAddress2").addClass('hide');
                        $("#addressHasFrom").removeClass('hide');
                        $("#addressHasCharge").removeClass('hide');
                        $("#btnAddress").addClass('hide');
                        $("#from").removeClass('hide');
                        $("#charge").removeClass('hide');
                        $("#from").css('display', 'block');
                        $("#charge").css('display', 'block');
                        var form = result.shipping.from;
                        var to = result.shipping.to;
                        var time = result.shipping.time;
                        var charge = result.shipping.charge;
                        if(charge != 0){
                            $("#charge").text(parseFloat(charge) + ' KD');
                        }else{
                            $("#charge").text('Free');
                        }
                        $("#from").text(form + '-' + to + ' ' + time);
                        
                        $("#addressHasFrom").text(form + '-' + to + ' ' + time);
                        $("#addressHasCharge").text(parseFloat(charge) + ' KD');
                    } else if (result.shipping && result.address) {
                        console.log(result.shipping.from);
                        $("#btnAddress1").addClass('hide');
                        $("#btnAddress2").addClass('hide');
                        $("#addressHasFrom").removeClass('hide');
                        $("#addressHasCharge").removeClass('hide');
                        $("#btnAddress").addClass('hide');
                        $("#from").removeClass('hide');
                        $("#charge").removeClass('hide');
                        var form = result.shipping.from;
                        var to = result.shipping.to;
                        var time = result.shipping.time;
                        var charge = result.shipping.charge;
                        $("#from").text(form + '-' + to + ' ' + time);
                        if(charge != 0){
                            $("#charge").text(parseFloat(charge) + ' KD');
                        }else{
                            $("#charge").text('Free');
                        }
                        $("#addressHasFrom").text(form + '-' + to + ' ' + time);
                        $("#addressHasCharge").text(parseFloat(charge) + ' KD');
                    }
                    else if (result.address) {

                        $("#btnAddress").addClass('hide');
                        $("#btnAddress1").addClass('hide');
                        $("#btnAddress").addClass('hide');
                        $("#from").removeClass('hide');
                        $("#charge").removeClass('hide');
                        $("#addressHasFrom").removeClass('hide');
                        $("#addressHasCharge").removeClass('hide');
                        $("#from").text('-');
                        $("#charge").text('-');
                        $("#addressHasFrom").text('-');
                        $("#addressHasCharge").text('-');
                    }
                    // console.log(result.shipping.from);

                },
                complete: function () {
                    $("#spinner").hide();
                    $("#spinner").removeClass("spinner");
                    $("#mask").removeClass("mask");
                }
            });
        }

    }
    $(document).bind("ajaxStart.mine", function () {
        $.blockUI(
                {
                    message: "<img src='{{ url('assets/loader.gif') }}' class='loaderGif'  style='height: 100px;width: 100px;'/>",
                    centerX: true,
                    centerY: true,

                });
    });
    $(document).bind("ajaxStop.mine", function () {
        $.unblockUI();
    });
    
    $(document).on('click', '.delete-data', function (e) {
        var name = $(this).data('name');

        if (confirm('Are you sure want to delete this ' + name + '?')) {

            return true;
        }

        e.preventDefault();

        return false;
    });
   
    function searchFunction(keyword) {
        //alert(keyword);
        $('#srch-term').val(keyword);
        $("#searchKey").submit();
    }

    function storeSearchFunction(keyword) {
        //alert(keyword);
        $('#srch-term3').val(keyword);
        $("#searchKey1").submit();
    }

    /* $("#srch-term").keyup(function (event) {
        $("#search-div").css("display", "block");
        var searchTerm = $("#srch-term").val();
        var html = '';
        $(document).unbind(".mine");
        $.ajax({
            url: "{{url('/gets/keyword/keywords')}}",
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'value': searchTerm
            },
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.length > 0) {
                    $.each(obj, function (i, item) {
                        html += "<a class='searc-keyword' onclick='searchFunction(&apos;" + item.keyword + "&apos;)'><li><span id='keyword'>" + item.keyword + "</span></li></a>"
                    });
                    $('#search-div').removeClass('hidden');
                    $('#search-parm').html(html);
                }
            },
            complete: function () {
                $("#spinner").hide();
                $("#spinner").removeClass("spinner");
                $("#mask").removeClass("mask");
            }
        });
        if (event.which == 10 || event.which == 13) {
            $("#srch-button").submit();
        }
    }); */
    /* $("#srch-term1").keyup(function (event) {
        $("#search-div1").css("display", "block");
        var searchTerm = $("#srch-term1").val();
        var html = '';
        $(document).unbind(".mine");
        $.ajax({
            url: "{{url('/gets/keyword/keywords')}}",
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'value': searchTerm
            },
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.length > 0) {
                    $.each(obj, function (i, item) {
                        html += "<li><a class='searc-keyword' onclick='searchFunction(&apos;" + item.keyword + "&apos;)'><span id='keyword'>" + item.keyword + "</span></a></li>"
                    });
                    $('#search-div1').removeClass('hidden');
                    $('#search-parm1').html(html);
                }
            }
        });
        if (event.which == 10 || event.which == 13) {
            $("#srch-button").submit();
        }
    }); */

    $(document).click(function () {
        $(".search-div2").css("display", "none");
        $("#search-div3").css("display", "none");
        $("#search-div1").css("display", "none");
        $("#search-div").css("display", "none");
    });

    $(".search-term2").keyup(function (event) {
        $(".search-div2").css("display", "block");
        //alert(event.which);
        var searchTerm = $(".search-term2").val();
        //alert(searchTerm);
        console.log(searchTerm);
        var html = '';
        $(document).unbind(".mine");
        $.ajax({
            url: "{{url('/gets/keyword/keywords')}}",
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'value': searchTerm
            },
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.length > 0) {
                    $.each(obj, function (i, item) {
                        html += "<a class='searc-keyword' onclick='searchFunction(&apos;" + item.keyword + "&apos;)'><li><span id='keyword'>" + item.keyword + "</span></li></a>"
                    });
                    $('.search-div2').removeClass('hidden');
                    $('.search-parm2').html(html);
                }
            }
        });
        if (event.which == 10 || event.which == 13) {
            $("#srch-button").submit();
        }
    });
    /* $("#srch-term3").keyup(function (event) {
        $("#search-div3").css("display", "block");
        var searchTerm = $("#srch-term3").val();
        var vendorId = $("#storeId").val();
        //console.log(storeId);
        var html = '';
        $(document).unbind(".mine");
        $.ajax({
            url: "{{url('/gets/keywords/keywords')}}",
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'value': searchTerm,
                'vendorId': vendorId,
            },
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.length > 0) {
                    $.each(obj, function (i, item) {
                        html += "<a onclick='storeSearchFunction(&apos;" + item.keyword + "&apos;)'><li><span>" + item.keyword + "</span></li></a>"
                    });
                    $('#search-div3').removeClass('hidden');
                    $('#search-parm3').html(html);
                }
            }
        });
        if (event.which == 10 || event.which == 13) {
            $("#srch-button").submit();
        }
    }); */
    $(document).ready(function () {


        $("#myCarousel").swipe({

            swipe: function (event, direction, distance, duration, fingerCount, fingerData) {

                if (direction == 'left') $(this).carousel('next');
                if (direction == 'right') $(this).carousel('prev');

            },
            allowPageScroll: "vertical"

        });
        $("#myCarousel-1").swipe({

            swipe: function (event, direction, distance, duration, fingerCount, fingerData) {

                if (direction == 'left') $(this).carousel('next');
                if (direction == 'right') $(this).carousel('prev');

            },
            allowPageScroll: "vertical"

        });


    });

</script>
<script>

    $("#selectoption").change(function () {
        var dataid = $("#selectoption option:selected").attr('rate');
        $("#optionPrice1").text(parseFloat(dataid) + " KD");
    });
    function priceUpdate(productid) {
        // alert(productid);
        var dataid = $("#optionsChanges" + productid + "option:selected").attr('rate');
        // alert(dataid);
        //alert( $(this).find(":selected").val() );
    }
    $(".optionchanges").change(function () {

        // alert(productid);
        // alert(dataid);
        $("#optionPrice" + productid).text(parseFloat(dataid) + " KD");
    });
</script>
@yield('js')
</body>
</html>