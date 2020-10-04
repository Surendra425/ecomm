@extends('front.layout.index')
@section('title') {{$keyword}} @endsection

@section('meta')

@endsection

@section('content')

    @if(!$searchCount > 0)
        <div class="container-fluid" id="MensCollection">
            <div class="container">
                <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                        <li class="m-nav__item">
                            <span class="m-nav__link-text">Your search "{{$keyword}}"</span>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-12">
                    <span class="mens"></span>
                </div>
            </div>
        </div>
        <div class="container-fluid" id="footerFluid">
            <div class="container" id="home-myAccount">
                <div class="col-sm-4 col-lg-3 col-xs-12 col-md-3">

                    <span><a href="{{ url(route('home')) }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                    <span class="home-myAccount-1">{{ $keyword }}</span>
                </div>
            </div>
        </div>
        <br>

        <div class="container-fluid" id="product">
            <div class="container" id="home-myAccount">
                <div class="col-md-12">
                    <div class="noproducts">
                        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                            <li class="m-nav__item">
                                <h3>Your search "{{$keyword}}" did not match any products @if(!$storeSearch) or stores @endif</h3>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="container-fluid" id="MensCollection">
            <div class="container">
                <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                        <li class="m-nav__item">
                            <span class="m-nav__link-text">{{$searchCount}} results for "{{$keyword}}"</span>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-12">
                    <span class="mens"></span>
                </div>
            </div>
        </div>
        <div class="container-fluid" id="footerFluid">
            <div class="container" id="home-myAccount">
                <div class="col-sm-4 col-lg-3 col-xs-12 col-md-3">
                    <span><a href="{{ url(route('home')) }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                    <span class="home-myAccount-1">{{ $keyword }}</span>
                </div>
            </div>
        </div>
        @if(!$storeSearch)
            <div class="container-fluid" id="collection">
                <div class="container">
                    <h4 class="border"><b>STORES</b></h4>
                    <p class="border-para">As per search</p>
                    <div class="row" style="text-align: center;">

                        @if(count($stores) > 0)
                            @foreach($stores as $store)

                                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
                                    <a href="{{url(route('sellerDetail',['storeSlug'=>$store->store_slug]))}}">

                                        <img class="img-responsive" src="{{ ($store->store_image != "")? url('doc/store_image/').'/'.$store->store_image:url('assets/app/media/img/no_store_image_100.png') }}">
                                    </a>
                                    <p class="card-3-text"><a href="#">{{$store->store_name}}</a></p>
                                </div>
                            @endforeach

                        @else
                            <div class="noproducts">
                                <h3>No Store Available</h3>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endif
        <div class="container-fluid" id="imgBorder-1">
            <div class="container" id="products">
                <h4 class="border"><b>PRODUCTS</b></h4>
                <p class="border-para-1" >As per search</p>
                @include('front.common.products')
            </div>
        </div>

    @endif
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            var url  = "{{url(route('ajaxSearchProducts'))}}";
            var page = 2;
            var lastPage = {{$products->lastPage()}}

            $(window).scroll(function () {
                if($('body').hasClass('overflow-hidden')){
                    return false;
                }
                if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    if (lastPage >= page) {
                        var data = collection = {'page': page, 'keyword':'{{$keyword}}'}
                        loadProducts(url, data, lastPage, 0);
                        page++;
                    }
                }
            });
            $(document).ajaxStart(function ()
            {
                $(".loader").show();
            });
        });
    </script>
@endsection