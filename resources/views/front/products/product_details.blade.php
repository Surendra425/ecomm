@extends('front.layout.index')
@section('title') {{ $product->product_title }} @endsection
@section('css')
    {{--<link rel="stylesheet" href="{{ url('assets/frontend/css/lightslider.css') }}">--}}
<link rel="stylesheet" type="text/css" href="{{ url('assets/frontend/css/slider.css') }}">
@endsection
@section('meta')
    <meta charset="utf-8">
<meta name="description" content="{{ strip_tags($product->long_description) }}" />
<meta property="og:url" content="{{ route('showProductDetails', ['productSlug' => $product->product_slug])}}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ $product->product_title }}" />
<meta property="og:description" content="{{ strip_tags($product->long_description) }}" />
<meta property="og:image" content="{{ url('doc/product_image/'.$product->images[0]->image_url) }}" />
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $product->product_title }}">
<meta name="twitter:description" content="{{ strip_tags($product->long_description) }}">
<meta name="twitter:image" content="{{ url('doc/product_image/'.$product->images[0]->image_url) }}">
@endsection

@section('content')
<div class="product-detail">
    <div class="container-fluid" id="footerFluid">
        <div class="container" id="home-myAccount">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <span><a href="{{ url('home') }}" class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
                <span class="home-myAccount-1">{{ $product->product_title }}</span>
            </div>
        </div>
    </div>
    <div class="container">

                <div class="col-sm-6 pd-m-0">
                    <!-- Slider for MobileView -->
                    <div id="bs-carousel" class="carousel fade-carousel slide desktop-hide" data-ride="carousel"
                         data-wrap="false" data-interval="false">

                        <div class="overlay"></div>
                        <ol class="carousel-indicators">

                            @foreach($product->images as $ik=>$image)
                                <li data-target="#bs-carousel" data-slide-to="{{ $ik }}"
                                    class="{{ "item".$ik }} {{ ($ik==0)?"active":"" }}"></li>
                            @endforeach

                            @if(!empty($product->videos))

                                @foreach($product->videos as $vk=> $video)
                                    <li data-target="#bs-carousel" data-slide-to="{{ $vk }}"
                                        class="{{ "item".$vk }} {{ ($vk==1)?"":"" }}"></li>
                                @endforeach

                            @endif
                        </ol>

                        <div class="carousel-inner prvw">

                            @foreach($product->images as $ik=> $image)

                                <div class="item {{ ($ik==0)?"active":"" }}">
                                    <img alt="item" src="{{ url('doc/product_image_temp/'.$image->file_name) }}">
                                </div>

                            @endforeach

                            @if(!empty($product->videos))

                                @foreach($product->videos as $k=> $video)

                                    <div class="item {{ ($k==0)?"":"" }}">

                                        <video width="100%" height="auto" controls="">
                                            <source loop controls='true' src="{{ url('doc/video').'/'.$video->file_name }}"
                                                    type="video/mp4">
                                            <source data-id="{{$video->product_id}}" data-name="{{$video->file_name}}" src="{{url('doc/video/ios').'/'.$video->file_name }}"
                                                    type="video/mp4">
                                            <source data-id="{{$video->product_id}}" data-name="{{$video->file_name}}" src="{{url('doc/video/web').'/'.$video->file_name }}"
                                                    type="video/mp4">

                                        </video>
                                    </div>

                                @endforeach

                            @endif

                        </div>
                    </div>

                    <!-- Slider for MobileView End-->
                    @php  $images = collect($product->images);
                          $videos = collect($product->videos);
                          $productMedias = $images->merge($videos);
                    @endphp
                    <div class="product-slider">

                        <div id="carousel" class="carousel slide">

                            <div class="carousel-inner">
                                @foreach($productMedias as $k=> $media)

                                    <div class="item {{ ($k==0)?"active":"" }}">

                                        @if(!empty($media->image_url))
                                            <img alt="media" src="{{ url('doc/product_image_temp/'.$media->file_name) }}">
                                        @else
                                            <video width="540" height="540" controls="">
                                                <source loop controls='true' src="{{ url('doc/video').'/'.$media->file_name }}"
                                                        type="video/mp4">
                                                <source data-id="{{$video->product_id}}" data-name="{{$media->file_name}}" src="{{url('doc/video/ios').'/'.$media->file_name }}"
                                                        type="video/mp4">
                                                <source data-id="{{$video->product_id}}" data-name="{{$media->file_name}}" src="{{url('doc/video/web').'/'.$media->file_name }}"
                                                        type="video/mp4">
                                            </video>
                                        @endif

                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="clearfix">
                            <div id="thumbcarousel" class="carousel slide" data-interval="false">
                                <div class="carousel-inner">

                                    @foreach($productMedias as $k=> $media)

                                        <div class="item {{ ($k==0) ? "active":"" }}">

                                            <div data-target="#carousel" data-slide-to="{{ $k }}" class="thumb">

                                                @if(!empty($media->image_url))
                                                    <img alt="media" src="{{ url('doc/product_image_temp/'.$media->file_name) }}">
                                                @else
                                                    <video width="110" height="110">

                                                        <source src="{{ url('doc/video').'/'.$media->file_name }}"
                                                                type="video/mp4">
                                                        <source data-id="{{$media->id}}" data-name="{{$media->file_name}}" src="{{url('doc/video/ios').'/'.$media->file_name }}"
                                                                type="video/mp4">
                                                        <source data-id="{{$media->id}}" data-name="{{$media->file_name}}" src="{{url('doc/video/web').'/'.$media->file_name }}"
                                                                type="video/mp4">

                                                    </video>
                                                @endif

                                            </div>

                                        </div>
                                    @endforeach

                                </div>
                                @if(count($product->images) > 4)
                                    <a class="left" href="#thumbcarousel" role="button" data-slide="prev">
                                        <i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>
                                    </a>
                                    <a class="right" href="#thumbcarousel" role="button" data-slide="next">
                                        <i class="glyphicon glyphicon-menu-right" aria-hidden="true"></i>
                                    </a>
                                @else
                                    <a class="left">
                                        <i class="glyphicon glyphicon-menu-left" aria-hidden="true"></i>
                                    </a>
                                    <a class="right">
                                        <i class="glyphicon glyphicon-menu-right" aria-hidden="true"></i>
                                    </a>

                                @endif

                            </div>
                        </div>
                    </div>
                </div>

        <div class="col-sm-6">
            <div class="">
                <h3 class="Adidas-product">{{ $product->product_title }}</h3>
            </div>
            <div class="">
                <span class="name-1">by
                    <b>
                        <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                            {{ $store->store_name }}
                        </a>
                    </b>
                </span>
                <div>

                    <span class="textDashboard">
                        @for($i=1;$i<=5;$i++)
                            <span class="fa {{ ($store->rating >= $i)?("fa-star checked"):((($store->rating > ($i-1)) && ($store->rating < $i))?"fa-star-half-empty checked":"fa-star unchecked") }}"></span>&nbsp;
                        @endfor
                    </span>

                    <span style="color: black">&nbsp;&nbsp;({{ $store->city }}, {{ $store->country }})</span>&nbsp;&nbsp;
                    @php
                        $followUrl = (empty(\Auth::guard('customer')->user()))?(url(route('login'))):"javascript:followUnFollow();";
                    @endphp
                    <div id="followunfolllow">
                        @if($store->is_follow == 1)
                        <a href="{{ $followUrl }}">
                            <span class="followers">UnFollow</span>
                        </a>
                        @else
                        <a href="{{ $followUrl }}">
                            <span class="followers">Follow</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row" id="button-price">
                <div class="item">
                    <p class="label-product success-product new-label-1 price-label">
                        <span class="clsProductPrice" >{{ $product->options[0]->rate }} KD</span>
                    </p>
                </div>
            </div>
            <div class="options">
                <form action="{{ url(route('addProductsToCart')) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">

                        @if(count($product->options) > 1)
                            <div class="col-sm-9 col-xs-8 col-md-9">
                                <label for="Option" class="option-category">Option</label>
                                <select class="select clsproductCombination" name="option_id" id="product_option_id">
                                    @foreach($product->options as $combination)
                                        <option value="{{ $combination->id }}" data-rate="{{ $combination->rate }}" data-quantity="{{ $combination->quantity }}">{{$combination->combination_title }}</option>
                                    @endforeach
                                </select>

                            </div>

                        @else
                            <input type="hidden" id="product_option_id" name="product_option_id" value="{{ $product->options[0]->id }}" data-quantity="{{ $product->options[0]->quantity }}">
                        @endif

                        <div class="col-sm-3 col-xs-4 col-md-3 qtyDetails {{$product->options[0]->quantity > 0 ? '': 'hide'}}">
                            <label for="Quantity" class="option-category">Quantity</label>
                            <select class="qty" id="qtyChangeMobile1" name="item_quantity">
                                @php
                                    $selectMaxQty = $product->options[0]->quantity > 12 ? 12 : $product->options[0]->quantity;
                                @endphp

                                @for ($i = 1; $i <= $selectMaxQty; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8 addtocart_block">
                            <div>
                                @if($product->options[0]->quantity >= 1)
                                    <button class="addToCart text-center addToCartFromProductDetail saveAddtocart"
                                            data-product-id="{{$product->id}}"
                                            type="button">
                                        Add to Cart
                                    </button>
                                @else
                                    <button class="addToCart text-center saveAddtocart addToCartFromProductDetail" data-product-id="{{$product->id}}" type="button" disabled>
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="deliver-product">
                            <hr>
                            @foreach($shipping as $item)
                                <h5>Delivers to {{ $item->country_name }}</h5>
                                <div>Deliver within <b>{{ $item->delivery_from }} to {{ $item->delivery_to }}</b> {{ $item->time }}</div>
                            @endforeach
                            <hr>
                            <h5>Share this Product</h5>
                            <div class="social-menu">
                                <a class="social-sharing" data-type="facebook"
                                   data-url="{{ url(route('showProductDetails',['productSlug'=>$product->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <i class="fa fa-facebook-square fa-2x"></i>
                                </a>
                                <a class="social-sharing" data-type="google"
                                   data-url="{{ url(route('showProductDetails',['productSlug'=>$product->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <i class="fa fa-google-plus-square fa-2x" aria-hidden="true"></i>
                                </a>
                                <a class="social-sharing" data-type="twitter"
                                   data-url="{{ url(route('showProductDetails',['productSlug'=>$product->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i>
                                </a>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--product info--}}

    <div class="container">
        <div class="col-sm-12">
            <h4 class="product-info">
                <span class="borderr">Product Info</span>
            </h4>
            <p style="word-wrap: break-word;word-break: break-all">
                {!!$product->long_description!!}
            </p>
        </div>
    </div>
    <div class="container-fluid " id="imgBorder-1">
        <div class="container" id="related-products">
            <h4 class="border"><b>RELATED PRODUCTS</b></h4>
            <p class="border-para-1">More from same store</p>
            @include('front.common.products')
        </div>
    </div>
    <div class="container text-center loader">
        <img alt="loader" src="{{ url('assets/loader_new.gif') }}" style=" height: 180px; width: 80px;">
    </div>
</div>
@endsection
@section('js')

    <script src="{{url('assets/frontend/js/carousel-swipe.js')}}"></script>
    
    <script>
        var Page = 1, HasMoreProducts = "{{ (count($products)>=8)?'Yes':'No' }}",IsRequestSent = 0;

        var totalItems = $('#thumbcarousel .carousel-inner .item').length;

        $('#thumbcarousel .carousel-inner .item').each(function () {
            var itemToClone = $(this);
            // alert(totalItems);
            if (totalItems > 4) {
                for (var i = 1; i < 4; i++) {
                    itemToClone = itemToClone.next();

                    // wrap around if at end of item collection

                    if (!itemToClone.length) {
                        itemToClone = $(this).siblings(':first');
                    }

                    // grab item, clone, add marker class, add to collection
                    itemToClone.children(':first-child').clone()
                            .addClass("cloneditem-" + (i))
                            .appendTo($(this));
                }
            }
            else {
                for (var i = 1; i < totalItems; i++) {
                    itemToClone = itemToClone.next();

                    // wrap around if at end of item collection

                    if (!itemToClone.length) {
                        itemToClone = $(this).siblings(':first');
                    }

                    // grab item, clone, add marker class, add to collection
                    itemToClone.children(':first-child').clone()
                            .addClass("cloneditem-" + (i))
                            .appendTo($(this));
                }
            }
        });
        var fixed = document.getElementById("thumbcarousel");

        fixed.addEventListener('mousewheel', function (e) {
            e.preventDefault();
        }, false);

        $(document).ready(function () {

            $("#bs-carousel").carousel({
                wrap: false,
            });

            if (HasMoreProducts == "No") {
                $("#btnViewMoreRelatedProducts").hide();
            }

            $('#thumbcarousel').bind('mousewheel', function (e) {
                if (e.originalEvent.wheelDelta / 120 > 0 || e.originalEvent.detail < 0) {
                    $(this).carousel('next');
                }
                else {
                    $(this).carousel('prev');
                }
            });
        });

        function followUnFollow()
        {
            $.ajax({
                url:'{{ url(route('followUnFollowStore')) }}',
                type:'post',
                data:'store_id='+{{$store->id}},
                success:function(response){

                    $('#followunfolllow').load(location.href + " #followunfolllow>*");

                }
            });
        }
    </script>
@endsection