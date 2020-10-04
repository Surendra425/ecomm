@extends('layouts.app')
@section('title') Best Products @endsection
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
                <span class="mens">I Can Save the world best products</span>  
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
    <div class="container-fluid" id="imgBorder-1">
        <div class="container">
            <h4></h4>
            <div class="row">
                @include('app.common.products')                
            </div>
        </div>
    </div>

@endsection
@section('js')
@endsection