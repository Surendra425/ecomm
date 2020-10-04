@extends('front.layout.index')
@section('title') My Orders @endsection
@section('content')

    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
            </div>
            <div class="col-sm-12">
                <span class="mens">Your Orders </span>
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

    <div class="container-fluid myorder_block" id="collection">
        <div class="container">
            @if(count($orders) > 0)
                <div class="container-fluid myorder_sl" id="cartStore">
                    @include('front.user.order_box')
                </div>
            @else
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="noproducts">
                            <h3>No Orders Available</h3>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
@section('js')
    <script>
        $(document).ready(function(){
            var url  = "{{url(route('showMoreOrders'))}}";
            var page = 2;
            var lastPage = {{$orders->lastPage()}}

            $(window).scroll(function () {
                if($(window).scrollTop() == $(document).height() - $(window).height()) {
                    if (lastPage >= page) {

                        var data = collection = {'page': page}

                        loadOrders(url, data, lastPage, isViewMoreBtn = 0);
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