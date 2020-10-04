@extends('layouts.app')
@section('title') Home @endsection
@section('content')
<!-- begin::Slider -->
@include('app.common.slider')
<!-- end::Slider -->
<div class="container-fluid" id="collection">
    <!-- begin::Slider -->
    @include('app.common.collection_list')
    <!-- end::Slider -->
    <div class="container"> 
        <h4 class="border"><b>FEATURED STORES</b></h4>
        <p class="border-para" >Made For You</p>
    </div>
</div>
<!-- begin::Feature Store Slider -->
@include('app.common.feature_store')

<!-- end:: Feature Store Slider -->
<div class="container-fluid" id="imgBorder-1">
    <div class="container" id="related-products">
        <h4 class="border"><b>FEEDS</b></h4>
        <p class="border-para-1" >Sellers Recent Products</p>
        <!-- begin::Recent Product -->
        @include('app.common.products')
        <!-- end:: Recent Product -->
    </div>
</div>
<!-- begin::Advertisement Slider -->
@include('app.common.advertisement_slider')
<!-- end:: Advertisement Slider -->
<!-- view more content  start-->

<div class="container viewmore-content homepage">

</div>
<div class="container">
    <div id="viewmore">
        <button id="btnViewMoreRelatedProducts">VIEW MORE</button>
    </div>
</div>
@include('app.common.footer')

@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function ()
    {
        var Page = 1, PageLimit = 20, HasMoreProducts = "{{ ($products->count() >= 20)?'Yes':'No' }}",IsRequestSent = 0;

        
        
        /* $(window).scroll(function () {
            if (Math.round($(window).scrollTop()) >= $(document).height() - window.innerHeight) {
                if (HasMoreProducts == "Yes" && $(window).width() >= 960 &&  IsRequestSent != 1) {
                    var data = {
                        "start": (Page * PageLimit),
                        "limit": PageLimit
                    };
                    IsRequestSent = 1;
                    $(".loader").show();
                    $.ajax({
                        url: "{{ url(route('getMoreProducts')) }}",
                        dataType: 'text',
                        type: 'post',
                        contentType: 'application/x-www-form-urlencoded',
                        data: data,
                        success: function (data, textStatus, jQxhr) {
                            var NewProducts = 0;
                            var CurrentProducts = $("#related-products").find(".product-item").length;
                            if (data != "") {
                                $("#related-products").append(data);
                                var TotalProducts = $("#related-products").find(".product-item").length;
                                NewProducts = TotalProducts - CurrentProducts;
                            }
                            if (NewProducts < PageLimit) {
                                HasMoreProducts = "No";
                                $("#btnViewMoreRelatedProducts").hide();
                            }
                            else {
                                Page = Page + 1;
                            }
                            IsRequestSent = 0;
                            $(".loader").hide();
                        },
                        error: function (jqXhr, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                }
            }
        }); */
        var mySwiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            spaceBetween: 20,
            freeMode: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                1400: {
                    slidesPerView: 3
                },
                992: {
                    slidesPerView: 2.2
                },
                768: {
                    slidesPerView: 2
                },
                736: {
                    slidesPerView: 1.7
                },
                640: {
                    slidesPerView: 1.5
                },
                568: {
                    slidesPerView: 1.3
                },
                480: {
                    slidesPerView: 1.1
                }
            }
        });
        $(document).on("click", "#btnViewMoreRelatedProducts", function (e)
        {
        	if (HasMoreProducts == "Yes" &&  IsRequestSent != 1) {
                var data = {
                    "start": (Page * PageLimit),
                    "limit": PageLimit
                };
                IsRequestSent = 1;
                $(".loader").show();
                $.ajax({
                    url: "{{ url(route('getMoreProducts')) }}",
                    dataType: 'text',
                    type: 'post',
                    contentType: 'application/x-www-form-urlencoded',
                    data: data,
                    success: function (data, textStatus, jQxhr) {
                        var NewProducts = 0;
                        var CurrentProducts = $("#related-products").find(".product-item").length;
                        if (data != "") {
                            $("#related-products").append(data);
                            var TotalProducts = $("#related-products").find(".product-item").length;
                            NewProducts = TotalProducts - CurrentProducts;
                        }
                        if (NewProducts < PageLimit) {
                            HasMoreProducts = "No";
                            $("#btnViewMoreRelatedProducts").hide();
                        }
                        else {
                            Page = Page + 1;
                        }
                        IsRequestSent = 0;
                        $(".loader").hide();
                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            }
        });
        $(document).ajaxStart(function ()
        {
            $(".loader").show();
        });
        if (HasMoreProducts == "No")
        {
            $("#btnViewMoreRelatedProducts").hide();
        }

    });
</script>   
@endsection