@extends('layouts.app')
@section('title') {{ $category->category_name }} @endsection
@section('css')
<link rel="stylesheet" href="{{ url('assets/frontend/css/swiper.min.css') }}">
@endsection
@section('content')

    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item">
                        <span class="m-nav__link-text">{{ $category->category_name }}</span>
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
                <span class="home-myAccount-1">{{ $category->category_name }}</span>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="collection">
        <div class="container">
            <h4 class="border"><b>STORES</b></h4>
            <p class="border-para">As per selected category</p>
            <div class="row">
                @if(count($stores) > 0)
                @foreach($stores as $k => $store)
                        <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 bestSellers">
                            <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                                
                                <img class="img-responsive" src="{{ ($store->store_image != "")? url('doc/store_image/').'/'.$store->store_image:url('assets/app/media/img/no_store_image_100.png') }}">
                            </a>
                            <p class="card-3-text">
                                <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                                    {{ $store->store_name }}
                                </a>
                            </p>
                        </div>
                {{--<div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
                    <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                        <img class="img-responsive"  src="{{ url('doc/store_image/'.(($store->store_image != "")?$store->store_image:"store.png")) }}"  alt="{{ $store->store_name }}">
                    </a>
                    <p class="card-3-text">
                        <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                            {{ $store->store_name }}
                        </a>
                    </p>
                </div>--}}
                @endforeach
                @else
                <div class="col-md-12">
                    <div class="noproducts">
                        <h3>No Store Available</h3>
                    </div>                    
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container-fluid" id="imgBorder-1">
        <div class="container">
            <h4 class="border"><b>PRODUCTS</b></h4>
            <p class="border-para-1" >As per selected category</p>
            <div class="row">
                @include('app.common.products')                
            </div>
        </div>
    </div>

@endsection
@section('js')
@endsection