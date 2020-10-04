@extends('layouts.app')
@section('title') {{ $collection->collection_name }} @endsection
@section('content')

    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                {{ $collection->collection_name }}
            </div>
            <div class="col-sm-12">
                <span class="mens">{{ $collection->collection_tagline }}</span>  
            </div>
        </div>
    </div>
    <div class="container-fluid" id="footerFluid">
        <div class="container" id="home-myAccount">
            <div class="col-sm-4 col-lg-3 col-xs-12 col-md-3">

                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">{{ $collection->collection_name }}</span>
            </div>
        </div>
    </div>
    {{--<div class="container-fluid" id="collection">
        <div class="container">
            <h4 class="border"><b>STORES</b></h4>
            <p class="border-para">As per selected collection</p>
            <div class="row">
                @if(count($stores))
                @foreach($stores as $k => $store)
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
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
    </div>--}}
    <div class="container-fluid" id="imgBorder-1">
        <div class="container">
            <h4 class="border"><b>PRODUCTS</b></h4>
            <p class="border-para" >As per selected collection</p>
            @include('app.common.relatedProducts')
        </div>
    </div>

@endsection


