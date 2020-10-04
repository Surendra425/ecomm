@extends('front.layout.index')
@section('title') My Likes @endsection
@section('meta')
<meta name="is-my-like-page" content="1">
@endsection
@section('content')

    <div class="container-fluid" id="MensCollection">
        <div class="container">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                @yield('title')
            </div>
            <div class="col-sm-12">
                <span class="mens">Products liked by you</span>
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
    <div class="my-like-div">
    <div class="container-fluid" id="imgBorder-1">
        <div class="container" id="products">
            <h4 class="border"><b>PRODUCTS</b></h4>
            <p class="border-para" >Products liked by you</p>
            <div class="row">
                @include('front.common.products')
            </div>
        </div>
    </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            
            var url  = "{{url(route('myLikesProducts'))}}";
            var page = 2;
            var lastPage = {{$products->lastPage()}}

            $(window).scroll(function () {
                if($('body').hasClass('overflow-hidden')){
                    return false;
                }
                if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    if (lastPage >= page) {
                        var data = collection = {'page': page}
                        loadProducts(url, data, lastPage, 0);
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

