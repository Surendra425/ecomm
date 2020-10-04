@extends('front.layout.index')
@section('title') Best Seller @endsection

@section('meta')
  
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
            <div class="row" id="stores">
                @include('front.common.store_box')
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            var url  = "{{url(route('getStoresList'))}}";
            var page = 2;
            var lastPage = {{$stores->lastPage()}}

            $(window).scroll(function () {
                if($(window).scrollTop() == $(document).height() - $(window).height()) {
                    if (lastPage >= page) {
                        var data = collection = {'page': page}
                        loadStores(url, data, lastPage, isViewMoreBtn = 0);
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