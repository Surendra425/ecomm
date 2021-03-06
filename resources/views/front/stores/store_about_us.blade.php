@extends('front.layout.index')
@section('title') {{ $store->store_name }} @endsection


@section('meta')

@endsection


@section('content')

<div class="container-fluid" id="Contact">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
            </div>
        </div>
    </div>
    <div class="container-fluid" id="HomeBreadCumb">
        <div class="container" id="home-myAccount">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <span><a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">@yield('title')</span>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <h2></h2>
        </div>
        <div class="container">
            <div class="content_block clearfix">
                <?php echo $store->about_us; ?>
            </div>
        </div>
    </div>
@endsection