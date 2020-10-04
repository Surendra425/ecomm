@extends('layouts.app')
@section('title') Best Seller @endsection
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
                <span class="mens">I Can Save the world best sellers</span>  
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
            <h4></h4>
            <div class="row">
                @foreach($sellers as $k => $store)
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 bestSellers">
                    <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                       {{--  <img class="img-responsive" src="{{ url('doc/store_image/'.(($store->store_image != "")?$store->store_image:"store.png")) }}"  alt="Lamp Decor Collection"> --}}
                        <img class="img-responsive" src="{{ ($store->store_image != "")? url('doc/store_image/').'/'.$store->store_image:url('assets/app/media/img/no_store_image_100.png') }}">
                    </a>
                    <p class="card-3-text">                        
                        <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                            {{ $store->store_name }}
                        </a>
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
@section('js')
@endsection