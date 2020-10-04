@extends('layouts.app')
@section('title') Collections @endsection
@section('content')

    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
            </div>
        </div>
    </div>
    <div class="container-fluid" id="footerFluid">
        <div class="container" id="home-myAccount">
            <div class="col-sm-4 col-lg-3 col-xs-12 col-md-3">

                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">@yield('title')</span>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="imgBorder-1">
        <div class="container">
            <h4 class="border"><b>Collection</b></h4>
            <p class="border-para">All product collection</p>
            <div class="row">
                @foreach($collections as $k => $collection)
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 clsItemCollection">
                    <div class="CollectionText">
                        <a href="{{ url(route('collectionDetail',['collection'=>$collection->collection_slug])) }}">
                            <img class="img-responsive"  src="{{ url('doc/collection_image_temp/'.$collection->background_image) }}"  alt="{{ $collection->collection_name }}">
                        </a>
                        <p class="card-1-text">
                            <a href="{{ url(route('collectionDetail',['collection'=>$collection->collection_slug])) }}">{{ $collection->collection_name }}</a>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection

