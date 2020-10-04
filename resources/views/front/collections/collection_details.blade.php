@extends('front.layout.index')
@section('title') {{ $collection->collection_name }} @endsection

@section('meta')

@endsection

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
    <div class="container-fluid" id="imgBorder-1">
        <div class="container" id="products">
            <h4 class="border"><b>PRODUCTS</b></h4>
            <p class="border-para" >As per selected collection</p>
            @include('front.common.products')
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            var url  = '{{url(route('collectionProducts'))}}';
            var page = 2;
            var lastPage = {{$products->lastPage()}}


            $(window).scroll(function () {
                if($('body').hasClass('overflow-hidden')){
                    return false;
                }
                if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    if (lastPage >= page) {
                        var data = collection = {'page': page, 'collection_id':{{$collection->id}}}
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