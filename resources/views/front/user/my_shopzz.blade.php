@extends('front.layout.index')
@section('title') My Shop @endsection
@section('css')
    <link rel="stylesheet" href="{{ url('assets/frontend/css/swiper.min.css') }}">
@endsection
@section('content')
    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
            </div>
            <div class="col-sm-12">
                <span class="mens">Stores Followed by you</span>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="footerFluid">
        <div class="container" id="home-myAccount">
            <div class="col-sm-4 col-lg-3 col-xs-12 col-md-3">

                <span><a href="{{ url(route('home')) }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">@yield('title') </span>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="collection">
        <div class="container">

            <h4 class="border"><b>STORES</b></h4>
            <p class="border-para">Stores Followed by you</p>
            <div class="row">
                @if(count($stores) > 0)
                    @foreach($stores as $k => $store)
                        <div class="store-img-center col-lg-2 col-md-4 col-sm-4 col-xs-6">
                            <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                                <img class="img-responsive"  src="{{ url('doc/store_image/'.(($store->store_image != "")?$store->store_image:"store.png")) }}"  alt="{{ $store->store_name }}">
                            </a>
                            <p class="card-3-text">
                                <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                                    {{ $store->store_name }}
                                </a>
                            </p>
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
@endsection