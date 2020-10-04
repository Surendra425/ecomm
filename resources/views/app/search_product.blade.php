@extends('layouts.app')
@section('title') {{$keyword}} @endsection
@section('css')
<link rel="stylesheet" href="{{ url('assets/frontend/css/swiper.min.css') }}">
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
                            <h3>Your search "{{$keyword}}" did not match any products or stores</h3>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="container-fluid" id="imgBorder-1">
        <div class="container">
            <h4 class="border"><b>RECENT PRODUCTS</b></h4>
            @include('app.common.relatedProducts')
        </div>
    </div>--}}

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
    @if(empty($vendor))
    <div class="container-fluid" id="collection">
        <div class="container">
            <h4 class="border"><b>STORES</b></h4>
            <p class="border-para">As per search</p>
            <div class="row" style="text-align: center;">
                @if(count($store) > 0)
                @foreach($store as $item)

                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
                    <a href="{{url(route('sellerDetail',['storeSlug'=>$item->store_slug]))}}"> 
                       
                        <img class="img-responsive" src="{{ ($item->store_image != "")? url('doc/store_image/').'/'.$item->store_image:url('assets/app/media/img/no_store_image_100.png') }}">
                    </a>
                    <p class="card-3-text"><a href="#">{{$item->store_name}}</a></p>
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
        <div class="container">
            <h4 class="border"><b>PRODUCTS</b></h4>
            <p class="border-para-1" >As per search</p>
            @include('app.common.products')
        </div>
    </div>

@endif
@endsection
@section('js')
@endsection