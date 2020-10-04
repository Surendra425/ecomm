@extends('front.layout.index')
@section('title') I Can Save the World - Best Offer ! Online Shopping | Buy & Sell @endsection

@section('meta')

@endsection

@section('content')
<!-- begin::Slider -->
@include('front.homePageCommon.slider')
<!-- end::Slider -->

<div class="container-fluid" id="collection">
    <!-- begin::Slider -->
    @include('front.homePageCommon.collection_list')
    <!-- end::Slider -->
    <div class="container">
        <h4 class="border"><b>FEATURED STORES</b></h4>
        <p class="border-para" >Made For You</p>
    </div>
</div>

<!-- begin::Feature Store Slider -->
@include('front.homePageCommon.feature_store')
<!-- end:: Feature Store Slider -->

<div class="container-fluid" id="imgBorder-1">
    <div class="container" id="products">
        <h4 class="border"><b>FEEDS</b></h4>
        <p class="border-para-1" >Sellers Products</p>
        <!-- begin::Recent Product -->
    @include('front.common.products')
    <!-- end:: Recent Product -->
    </div>
</div>
<div id="viewmore">
    <button id="btnViewMoreRelatedProducts">VIEW MORE</button>
</div>
@endsection

@section('js')
    <script>
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

        $(document).ready(function(){
            var url  = '{{ url(route('homeProducts')) }}';
            var page = 2;
            var lastPage = {{$products->lastPage()}}


            $(document).on("click", "#btnViewMoreRelatedProducts", function (e)
            {
                if($('body').hasClass('overflow-hidden')){
                    return false;
                }
                    var data = {'page': page};
                    loadProducts(url, data, lastPage, 1);
                    page++;
                    $(".loader").hide();
                
            });

            $(document).ajaxStart(function ()
            {
                $(".loader").show();
            });
        });


    </script>
@endsection
@section('footer')
    @include('front.common.footer')
@endsection

