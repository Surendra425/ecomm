@extends('front.layout.index')
@section('title') {{ $page->page_name }} @endsection
@section('css')
    <link rel="stylesheet" href="{{ url('assets/frontend/css/swiper.min.css') }}">
@endsection

@section('meta')
    <meta charset="utf-8">
<meta name="description" content="{{ $page->description }}" />
<meta property="og:url" content="{{ $page->slug }}" />
<meta property="og:type" content="E-Commerce" />
<meta property="og:title" content="{{ $page->headline }}" />
<meta property="og:description" content="{{ $page->description }}" />
<meta property="og:image" content="" />

@endsection

@section('content')
     
     <div class="container-fluid" id="Contact">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                {{ $page->page_name }}
            </div>
        </div>
    </div>
    <div class="container-fluid" id="HomeBreadCumb">
        <div class="container" id="home-myAccount">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">{{ $page->page_name }}</span>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <h4>{{$page->headline or 'About Us'}}</h4><br>
                <?php echo !empty($page->description) ? $page->description : 'No content' ?>
            </div>
        </div>
    </div>

@endsection