@extends('layouts.app')
@section('title') {{ $product->product_title }} @endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ url('assets/frontend/css/slider.css') }}">
@endsection
@section('meta')
    <meta name="description" content="{{ strip_tags($product->long_description) }}"/>
    <meta property="og:url" content="{{ route('sellerProductsDetail', ['productSlug' => $product->product_slug])}}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="{{ $product->product_title }}"/>
    <meta property="og:description" content="{{ strip_tags($product->long_description) }}"/>
    <meta property="og:image" content="{{ !empty($product->images[0]) ? url('doc/product_image_temp/'.$product->images[0]->image_url)  : url('assets/app/media/img/no-images.jpeg') }}"/>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->product_title }}">
    <meta name="twitter:description" content="{{ strip_tags($product->long_description) }}">
    <meta name="twitter:image" content="{{ !empty($product->images[0]) ? url('doc/product_image_temp/'.$product->images[0]->image_url) : url('assets/app/media/img/no-images.jpeg') }}">
@endsection

@section('content')

    <div class="container-fluid" id="footerFluid">
        <div class="container" id="home-myAccount">
            <div class="col-sm-12 col-lg-12 col-xs-12 col-md-12">
                <span><a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}"
                         class="home_myaccount">Home&nbsp;</a>|&nbsp;</span>
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
                    @foreach($product->images as $k=>$image)
                        <li data-target="#bs-carousel" data-slide-to="{{ $k }}"
                            class="{{ "item".$k }} {{ ($k==0)?"active":"" }}"></li>
                    @endforeach
                        @if(!empty($product->video))
                        @foreach($product->video as $k=>$image)
                        <li data-target="#bs-carousel" data-slide-to="{{ $k }}"
                            class="{{ "item".$k }} {{ ($k==1)?"":"" }}"></li>
                            @endforeach
                        @endif
                </ol>

                <div class="carousel-inner prvw">
                    @foreach($product->images as $k=>$image)
                        <div class="item {{ ($k==0)?"active":"" }}">
                            <img src="{{ url('doc/product_image_temp/'.$image->image_url) }}">
                        </div>
                    @endforeach
                    @if(!empty($product->video))
                    @foreach($product->video as $video)
                        @php
                            $videos = explode('.',$video->video_url);
                        @endphp
                        <div class="item {{ ($k==0)?"":"" }}">
                            
                            <video width="100%" height="auto" controls="">
                             <source loop controls='true' src="{{ url('doc/video').'/'.$video->video_url }}"
                                        type="video/mp4">
                            <source data-id="{{$video->id}}" data-name="{{$video->video_url}}" src="{{url('doc/video/ios').'/'.$video->video_url }}"
                                                            type="video/mp4">
                            <source data-id="{{$video->id}}" data-name="{{$video->video_url}}" src="{{url('doc/video/web').'/'.$video->video_url }}"
                                                            type="video/mp4"> 
                                                           
                            </video>
                        </div>
                        @endforeach
                    @endif

                </div>
            </div>
            <!-- Slider for MobileView End-->
            <div class="product-slider">
                <div id="carousel" class="carousel slide">
                    <div class="carousel-inner">
                        @foreach($product->image as $k=>$image)
                            <div class="item {{ ($k==0)?"active":"" }}">
                                @if(!empty($image->image_url))
                                    <img src="{{ url('doc/product_image_temp/'.$image->image_url) }}">
                                @else
                                    @php
                                        $videos = explode('.',$image->video_url);
                                    @endphp
                                    <video width="540" height="540" controls="">
                                     <source loop controls='true' src="{{ url('doc/video').'/'.$image->video_url }}"
                                                type="video/mp4">
                                        <source data-id="{{$image->id}}" data-name="{{$image->video_url}}" src="{{url('doc/video/ios').'/'.$image->video_url }}"
                                                    type="video/mp4">
                                       <source data-id="{{$image->id}}" data-name="{{$image->video_url}}" src="{{url('doc/video/web').'/'.$image->video_url }}"
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
                            @foreach($product->image as $k=>$image)
                                <div class="item {{ ($k==0) ? "active":"" }}">
                                    <div data-target="#carousel" data-slide-to="{{ $k }}" class="thumb">
                                        @if(!empty($image->image_url))
                                            <img src="{{ url('doc/product_image_temp/'.$image->image_url) }}">
                                        @else
                                            @php
                                                $videos = explode('.',$image->video_url);
                                            @endphp
                                            <video width="110" height="110">
                                            
                                                <source src="{{ url('doc/video').'/'.$image->video_url }}"
                                                        type="video/mp4">
                                                        <source data-id="{{$image->id}}" data-name="{{$image->video_url}}" src="{{url('doc/video/ios').'/'.$image->video_url }}"
                                                            type="video/mp4">
                                                        <source data-id="{{$image->id}}" data-name="{{$image->video_url}}" src="{{url('doc/video/web').'/'.$image->video_url }}"
                                                            type="video/mp4">
                                                         
                                            </video>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if(count($product->image) > 4)
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
        <!-- Content For Mobile View -->
        <div class="col-sm-6 desktop-hide">
            <div class="product-layer">
                <figcaption>
                    {{ $product->product_title }}
                </figcaption>
                <div class="figure-detail">
                    <b class="clsProductPrice" id="optionPrice">{{ $product->combination[0]->rate }} KD</b>
                </div>
                <form action="{{ url(route('addProductsToCart')) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        @if(count($product->combination) > 1)
                            <div class="col-sm-9 col-xs-8 col-md-9">
                                <label for="Option" class="option-category">Option</label>
                                <select class="select clsproductCombination" name="option_id" id="optionsChange">
                                    @foreach($product->combination as $combination)
                                        <option value="{{ $combination->id }}" rate="{{ $combination->rate }}"
                                                quantity="{{ $combination->quantity }}">{{ $combination->combination_title }}</option>
                                    @endforeach
                                </select>
                                {{--<select name="product_combination" class="Option form-control product_combination">
                                    @foreach($product->combination as $combination)
                                    <option value="{{ $combination->id }}" rate="{{ $combination->rate }}" quantity="{{ $combination->quantity }}">{{ $combination->combination_title }}</option>
                                    @endforeach
                                </select>--}}
                            </div>
                        @else
                            <input type="hidden" id="optionsChange12" name="product_combination" value="{{ $product->combination[0]->id }}"
                                   quantity="{{ $product->combination[0]->quantity }}">
                        @endif

                        <div class="col-sm-3 col-xs-4 col-md-3 qtyDetails {{$product->combination[0]->quantity > 0 ? '': 'hide'}}">
                            <label for="Quantity" class="option-category">Quantity</label>
                            <select class="qty" id="qtyChangeDetails1" name="item_quantity">
                        		 @php $selectMaxQty = $product->combination[0]->quantity > 12 ? 12 : $product->combination[0]->quantity;
                           	@endphp
                                @for ($i = 1; $i <= $selectMaxQty; $i++)
                         			<option value="{{ $i }}">{{ $i }}</option>
                           		 @endfor
                            </select>
                            {{-- <input type="number" class="Quantity form-control" step="1" min="1" max="{{ $product->combination[0]->quantity }}"  name="item_quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
                             <i class="fa fa-caret-up productPage" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" aria-hidden="true"></i>
                             <i class="fa fa-caret-down productPage"  onclick="this.parentNode.querySelector('input[type=number]').stepDown()" aria-hidden="true"></i>
 --}}
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <br>
                            <a>
                                @if($product->combination[0]->quantity >= 1)
                                    <button class="addToCart text-center saveAddtocart"
                                            onclick="addTocartCombinationDetail('{{$product->id}}')" type="button">
                                        Add to Cart
                                    </button>
                                @else
                                    <button class="addToCart text-center saveAddtocart" disabled onclick="addTocartCombinationDetail('{{$product->id}}')" type="button">
                                        Out of Stock
                                    </button>
                                @endif
                            </a>
                        </div>
                    </div>
                </form>
                <div class="options1">
                    <div class="like_Share">
                        <a href="{{ empty($customer)?url(route('login')):'javascript:void(0)' }}"
                           class="{{ (($product->is_liked=="No")?"clsLikeProduct":"clsUnlikeProduct") }}"
                           product_id='{{ $product->id }}'>
                            <i class="fa fa-heart{{ ($product->is_liked=="No")?"-o":"" }}" aria-hidden="true"
                               style=""></i>
                        </a>
                        <a href="#" data-toggle="modal" data-target="#myModal{{$product->id}}" class="btn share"><i
                                    class="fa fa-share" aria-hidden="true"></i></a>
                        <div id="myModal{{$product->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <ul>
                                            <li>
                                                <a href="#" title="Share" class="slideUP">
                                                    <i class="fa fa-share" aria-hidden="true"></i>
                                                    <span>Share</span>
                                                    <p>Share with your friends</p>
                                                </a>
                                                <div class="social-menu">
                                                    <a class="social-sharing" data-type="facebook"
                                                       data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$product->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <i class="fa fa-facebook-square fa-2x"></i>
                                                    </a>
                                                    <a class="social-sharing" data-type="google"
                                                       data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$product->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <i class="fa fa-google-plus-square fa-2x"
                                                           aria-hidden="true"></i>
                                                    </a>
                                                    <a class="social-sharing" data-type="twitter"
                                                       data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$product->product_slug])) }}"
                                                       href="javascript:void(0);">
                                                        <i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="javascript:void(0)" class="copy-text">
                                                    <input type="hidden" name="link" class="link"
                                                           value="{{ url(route('sellerProductsDetail',['productSlug'=>$product->product_slug])) }}"/>
                                                    <i class="fa fa-link" aria-hidden="true"></i>
                                                    <span>Copy Link</span>
                                                    <p>copy product link</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrap figure-info">
                <h2 class="tit">Description</h2>
                <div class="description">
                    <div class="row">
                        <div class="col-md-12" style="overflow-x: auto">
                            {!!$product->long_description!!}
                        </div>
                    </div>
                    <ul class="delivery-summary">
                        <li>
                            @foreach($shipping as $item)
                                <label>Delivers to {{ $item->country_name }}</label>
                                <p>Deliver within <b>{{ $item->from }} to {{ $item->to }}</b> {{ $item->time }}</p>
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>
            <div class="wrap userinfo">
                <img src="{{ url('doc/store_image/'.(($store->store_image != "")?$store->store_image:"store.png")) }}">
                <span class="vendor"> Sold by 
                    <b>
                        <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                            {{ $store->store_name }}
                        </a>
                    </b>
                </span>
                <span>
                    @if($store->is_follow == "No")
                        <a href="{{ url(route('sellerFollow',['store'=>$store->id])) }}">
                        <span class="followers">Follow</span>
                    </a>
                    @else
                        <a href="{{ url(route('sellerUnfollow',['store'=>$store->id])) }}">
                        <span class="followers active"
                              style="background-color: #176c93;color: #FFFFFF;">Following</span>
                    </a>
                    @endif
                </span>
            </div>
        </div>

        <div class="container desktop-hide">
            <div class="wrap tit">
                <h4 class="border"><b>RELATED PRODUCTS</b></h4>
                <p class="border-para-1">More from same store</p>
            </div>
            <ul class="figure-shop swiper-container">
                <div class="swiper-wrapper">
                    @if(count($releted_products))
                        @foreach($releted_products as $item)
                            @php
                                $item->price = isset($item->combination[0])?$item->combination[0]->rate:0;
                            @endphp
                            @if(count($item->combination) == 1 && $item->combination[0]['combination_title']=="")

                                <li class="figure-shop-list swiper-slide">
                                    <div class="item">
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 product-item">
                                            <a href="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}">
                                                <img class="img-responsive"
                                                     src="{{!empty($item->images[0]->image_url) ? url('doc/product_image_temp/'.$item->images[0]->image_url) :url('assets/app/media/img/no-images.jpeg') }}">
                                            </a>
                                            <div class="tag">
                                                <div class="imgBorder">
                                                    <span class="type">{{ $item->product_title }}</span>
                                <span class="item">
                                     @if($item->combination[0]->quantity > 0)
                                        <form action="{{ url(route('addProductsToCart')) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="product_combination"
                                                   value="{{ $item->combination[0]->id }}">
                                        <input type="hidden" name="item_quantity" value="1"/>
                                        <button class="btn btn-sm price clsIndividualProduct {{ ($item->combination[0]->quantity < 1)?'clsStockOut':'' }}">
                                            <span>{{ (float)($item->combination[0]->rate) }} KD</span>
                                        </button>
                                    </form>
                                    @else
                                        <button type="button"  class="btn btn-sm price clsIndividualProduct clsStockOut">
                                                <span><span>Out of Stock</span></span>
                                            </button>
                                    @endif
                                </span>
                                                    <div class="name">by
                                                        <b>
                                                            <a href="{{ url(route('sellerDetail',['storeSlug'=>$item->store->store_slug])) }}">
                                                                {{ $item->store->store_name }}
                                                            </a>
                                                        </b>
                                                    </div>
                                                </div>
                                            </div>
                        <span class="heart">
                            <!--<a href="{{ url(route((($item->is_liked=="No")?"likeProduct":"unlikeProduct"),['productSlug'=>$item->product_slug])) }}">-->
                            <a href="{{ empty($customer)?url(route('login')):'javascript:void(0)' }}"
                               class="{{ (($item->is_liked=="No")?"clsLikeProduct":"clsUnlikeProduct") }}"
                               product_id='{{ $item->id }}'>
                                <i class="fa fa-heart{{ ($item->is_liked=="No")?"-o":"" }}" aria-hidden="true"
                                   style=""></i>
                            </a>
                        </span>
                                            <div class="more">
                                                <div class="modal-mobile">
                                                    <a href="#" data-toggle="modal"  data-target="#myModal{{$item->id}}">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </a>

                                                </div>
                                                {{--<div class="dropup">
                                                    <div class="dropdown-toggle"  type="button" data-toggle="dropdown">
                                                        <a href="#">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="javascript:void(0);" title="Share" class="slideUP">
                                                                <i class="fa fa-share" aria-hidden="true"></i>
                                                                <span>Share</span>
                                                                <p>Share with your friends</p></a>
                                                            <div class="social-menu">
                                                                <a class="social-sharing" data-type="facebook"
                                                                   data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                                   href="javascript:void(0);">
                                                                    <i class="fa fa-facebook-square fa-2x"></i>
                                                                </a>
                                                                <a class="social-sharing" data-type="google"
                                                                   data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                                   href="javascript:void(0);">
                                                                    <i class="fa fa-google-plus-square fa-2x"
                                                                       aria-hidden="true"></i>
                                                                </a>

                                                                <a class="social-sharing" data-type="twitter"
                                                                   data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                                   href="javascript:void(0);">
                                                                    <i class="fa fa-twitter-square fa-2x"
                                                                       aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="copy-text">
                                                                <input type="hidden" name="link" class="link"
                                                                       value="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"/>
                                                                <i class="fa fa-link" aria-hidden="true"></i>
                                                                <span>Copy Link</span>
                                                                <p>copy product link</p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if(count($item->combination) >= 1  && $item->combination[0]['combination_title']!="")

                                <li class="figure-shop-list swiper-slide">
                                    <div class="item">
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6  product-item">
                                            <a href="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}">
                                                <img class="img-responsive"
                                                     src="{{!empty($item->images[0]->image_url) ? url('doc/product_image_temp/'.$item->images[0]->image_url) :url('assets/app/media/img/no-images.jpeg') }}">
                                            </a>
                                            <div class="tag">
                                                <div class="imgBorder">
                                                    <span class="type">{{ $item->product_title }}</span>
                                <span id="modal">
                                    <button type="button" class="btn btn-info btn-sm ratebutton" rate="{{ $item->combination[0]->rate }}" quantity="{{ $item->combination[0]->quantity }}" data-toggle="modal"
                                            data-target="#myModals{{$item->id}}"><b>{{$item->combination[0]['rate']}}
                                            &nbsp;KD</b></button>

                                </span>
                                <span class="dropdown">
                                    <button id="dropdown" class="btn  dropdown-toggle btnDropdown" type="button">
                                        <b>{{ $item->combination[0]->rate }} KD</b>
                                    </button>
                                    <ul class="dropdown-menu" id="select">
                                        <span>SELECT OPTION</span>
                                        <form action="{{ url(route('addProductsToCart')) }}"
                                              method="POST" <?php echo ($item->combination[0]->quantity < 1) ? 'onsubmit="return false"' : ""; ?> >
                                            {{ csrf_field() }}
                                            <li>
                                                <div class="row">
                                                    <div class="col-xs-8 col-md-8">
                                                        <select name="product_combination"
                                                                class="form-control product_combination clsproductCombination"
                                                                id="selectoption{{$item->id}}">
                                                            @foreach($item->combination as $combination)
                                                                <option value="{{ $combination->id }}"
                                                                        quantity="{{ $combination->quantity }}"
                                                                        rate="{{ $combination->rate }}">{{ $combination->combination_title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4 col-md-4" style="margin-top: 10px">
                                                        <span>
                                                            <input type="number" id="qtySelected{{$item->id}}"
                                                                   name="item_quantity"
                                                                   class="form-control item_quantity" min="1" value="1">
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                @if($item->combination[0]->quantity > 0)
                                                    <button id="dropdown-save" class='saveAddtocart' type="button"
                                                            onclick="addTocartCombinationWeb('{{$item->id}}')">Add to Cart</button>
                                                @else
                                                    <button id="dropdown-save" class='saveAddtocart' onclick="addTocartCombinationWeb('{{$item->id}}')" type="button" disabled>Out of Stock</button>
                                                @endif
                                            </li>
                                        </form>
                                    </ul>
                                </span>
                                                    <div class="name">by
                                                        <b>
                                                            <a href="{{ url(route('sellerDetail',['storeSlug'=>$item->store->store_slug])) }}">
                                                                {{ $item->store->store_name }}
                                                            </a>
                                                        </b>
                                                    </div>
                                                </div>
                                            </div>
                        <span class="heart">
                            <!--<a href="{{ url(route((($item->is_liked=="No")?"likeProduct":"unlikeProduct"),['productSlug'=>$item->product_slug])) }}">-->
                            <a href="{{ empty($customer)?url(route('login')):'javascript:void(0)' }}"
                               class="{{ (($item->is_liked=="No")?"clsLikeProduct":"clsUnlikeProduct") }}"
                               product_id='{{ $item->id }}'>
                                <i class="fa fa-heart{{ ($item->is_liked=="No")?"-o":"" }}" aria-hidden="true"></i>
                            </a>
                        </span>
                                            <span class="count">100</span>
                                            <div class="more">
                                                <div class="modal-mobile">
                                                    <a href="#" data-toggle="modal" data-target="#myModal{{$item->id}}">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </a>

                                                </div>
                                                {{--<div class="dropup">
                                                    <div class="dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <a href="#">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="javascript:void(0);" title="Share" class="slideUP">
                                                                <i class="fa fa-share" aria-hidden="true"></i>
                                                                <span>Share</span>
                                                                <p>Share with your friends</p>
                                                            </a>
                                                            <div class="social-menu">
                                                                <a class="social-sharing" data-type="facebook"
                                                                   data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                                   href="javascript:void(0);">
                                                                    <i class="fa fa-facebook-square fa-2x"></i>
                                                                </a>
                                                                <a class="social-sharing" data-type="google"
                                                                   data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                                   href="javascript:void(0);">
                                                                    <i class="fa fa-google-plus-square fa-2x"
                                                                       aria-hidden="true"></i>
                                                                </a>

                                                                <a class="social-sharing" data-type="twitter"
                                                                   data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                                                   href="javascript:void(0);">
                                                                    <i class="fa fa-twitter-square fa-2x"
                                                                       aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="copy-text">
                                                                <input type="hidden" name="link" class="link"
                                                                       value="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"/>
                                                                <i class="fa fa-link" aria-hidden="true"></i>
                                                                <span>Copy Link</span>
                                                                <p>copy product link</p>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @if(count($item->combination) == 0)
                            @endif
                        @endforeach
                    @endif
                </div>
            </ul>
        </div>
        <div class="col-sm-6 mobile-hide">
            <div class="col-sm-12 col-xs-12 pd-0">
                <h3 class="Adidas-product">{{ $product->product_title }}</h3>
            </div>
            <div class="col-sm-12 col-xs-12 pd-0">
                <span class="name-1">by
                    <b>
                        <a href="{{ url(route('sellerDetail',['storeSlug'=>$store->store_slug])) }}">
                            {{ $store->store_name }}
                        </a>
                    </b>
                </span>
                <span>
                    &nbsp;&nbsp;
                    {{-- <a href="#"><span class="fa fa-star checked"></span></a>
                    <a href="#"><span class="fa fa-star checked"></span></a>
                    <a href="#"><span class="fa fa-star checked"></span></a>
                    <a href="#"><span class="fa fa-star checked"></span></a>
                    <a href="#"><span class="fa fa-star unchecked"></span></a> --}}
                     <span class="textDashboard">
                                @for($i=1;$i<=5;$i++)
                                <span class="fa {{ ($product->review >= $i)?("fa-star checked"):((($product->review > ($i-1)) && ($product->review < $i))?"fa-star-half-empty checked":"fa-star unchecked") }}"></span>&nbsp;
                                @endfor
                            </span>
                    <span style="color: black">&nbsp;&nbsp;({{ $store->city }}, {{ $store->country }}
                            )</span>&nbsp;&nbsp;
                    @if($store->is_follow == "No")
                        <a href="{{ url(route('sellerFollow',['store'=>$store->id])) }}">
                        <span class="followers">Follow</span>
                    </a>
                    @else
                        <a href="{{ url(route('sellerUnfollow',['store'=>$store->id])) }}">
                        <span class="followers active"
                              style="background-color: #176c93;color: #FFFFFF;">Following</span>
                    </a>
                    @endif
                </span>
            </div>
            <div class="row" id="button-price">
                <span class="item">
                    <p class="label-product success-product new-label-1 price-label">
                        <span class="price-1 clsProductPrice" id="optionPrice1">{{ $product->combination[0]->rate }}
                            KD</span>
                    </p>
                </span>
            </div>
            <div class="options">
                <form action="{{ url(route('addProductsToCart')) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        @if(count($product->combination) > 1)
                            <div class="col-sm-9">
                                <label for="Option" class="option-category">Option</label>
						<select class="select clsproductCombination" name="option_id"
							id="optionsChangeMobile1"> @foreach($product->combination as
							$combination)
							<option value="{{ $combination->id }}"
								rate="{{ $combination->rate }}"
								quantity="{{ $combination->quantity }}">{{
								$combination->combination_title }}</option> @endforeach
						</select>

					</div>
                        @else
                            <input type="hidden" id="optionsChangeMobile12" name="product_combination"
                                   value="{{ $product->combination[0]->id }}"
                                   quantity="{{ $product->combination[0]->quantity }}">
                        @endif
                        <div class="col-sm-3 qtyDetails {{$product->combination[0]->quantity > 0 ? '': 'hide'}}">
                            <label for="Quantity" class="option-category">Quantity</label>
                            <select class="qty" id="qtyChangeMobile1" name="item_quantity">
                            @php $selectMaxQty = $product->combination[0]->quantity > 12 ? 12 : $product->combination[0]->quantity;
                           	@endphp
                                @for ($i = 1; $i <= $selectMaxQty; $i++)
                         			<option value="{{ $i }}">{{ $i }}</option>
                           		 @endfor
                            </select>
                            {{--<input type="number" class="Quantity form-control"  step="1" min="1" max="{{ $product->combination[0]->quantity }}"  name="item_quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
                            <i class="fa fa-caret-up productPage" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" aria-hidden="true"></i>
                            <i class="fa fa-caret-down productPage"  onclick="this.parentNode.querySelector('input[type=number]').stepDown()" aria-hidden="true"></i>--}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <a>
                                @if($product->combination[0]->quantity >= 1)
                                    <button class="addToCart text-center saveAddtocart"
                                            onclick="addTocartCombinationDetailMobile('{{$product->id}}')"
                                            type="button">
                                        Add to Cart
                                    </button>
                                @else
                                    <button class="addToCart text-center saveAddtocart" onclick="addTocartCombinationDetailMobile('{{$product->id}}')" type="button" disabled>
                                        Out of Stock
                                    </button>
                                @endif
                            </a>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="deliver-product">
                            <hr>
                            @foreach($shipping as $item)
                                <h5>Delivers to {{ $item->country_name }}</h5>
                                <div>Deliver within <b>{{ $item->from }} to {{ $item->to }}</b> {{ $item->time }}</div>
                            @endforeach
                            <hr>
                            <h5>Share this Product</h5>
                            <div class="social-menu">
                                <a class="social-sharing" data-type="facebook"
                                   data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$product->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <i class="fa fa-facebook-square fa-2x"></i>
                                </a>
                                <a class="social-sharing" data-type="google"
                                   data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$product->product_slug])) }}"
                                   href="javascript:void(0);">
                                    <i class="fa fa-google-plus-square fa-2x" aria-hidden="true"></i>
                                </a>
                                <a class="social-sharing" data-type="twitter"
                                   data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$product->product_slug])) }}"
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
        <div class="container mobile-hide">
            <div class="col-sm-12">
                <h4 class="product-info">
                    <div>Product Info</div>
                </h4>
                <div class="row">
                    <div class="col-md-12" style="overflow-x: auto">
                        {!!$product->long_description!!}
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mobile-hide" id="imgBorder-1">
            <div class="container" id="related-products">
                <h4 class="border"><b>RELATED PRODUCTS</b></h4>
                <p class="border-para-1">More from same store</p>
                @include('app.common.relatedProducts')
            </div>
        </div>



@foreach($releted_products as $item)
    @if(count($item->combination) == 1 && $item->combination[0]['combination_title']=="")
        <div id="myModal{{$item->id}}" class="modal fade model-mobile1" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                                data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <ul>
                            <li>
                                <a href="#" title="Share" class="slideUP">
                                    <i class="fa fa-share"
                                       aria-hidden="true"></i>
                                    <span>Share</span>
                                    <p>Share with your friends</p>
                                </a>
                                <div class="social-menu">
                                    <a class="social-sharing"
                                       data-type="facebook"
                                       data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <i class="fa fa-facebook-square fa-2x"></i>
                                    </a>
                                    <a class="social-sharing"
                                       data-type="google"
                                       data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <i class="fa fa-google-plus-square fa-2x"
                                           aria-hidden="true"></i>
                                    </a>
                                    <a class="social-sharing"
                                       data-type="twitter"
                                       data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <i class="fa fa-twitter-square fa-2x"
                                           aria-hidden="true"></i>
                                    </a>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0)"
                                   class="copy-text">modal fade in
                                    <input type="hidden" name="link"
                                           class="link"
                                           value="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"/>
                                    <i class="fa fa-link"
                                       aria-hidden="true"></i>
                                    <span>Copy Link</span>
                                    <p>copy product link</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @if(count($item->combination) >= 1  && $item->combination[0]['combination_title']!="")
        <div class="modal fade" id="myModals{{$item->id}}" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">SELECT OPTION</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url(route('addProductsToCart')) }}"
                              method="POST" <?php echo ($item->combination[0]->quantity < 1) ? 'onsubmit="return false"' : ""; ?> >
                            {{ csrf_field() }}
                            <ul>
                                <li>
                                    <div class="row">
                                        <div class="col-xs-9">
                                            <select name="product_combination"
                                                    class="form-control product_combination clsproductCombination"
                                                    id="optionsChanges{{$item->id}}"
                                                    onchange="priceUpdate('{{$item->id}}')">
                                                @foreach($item->combination as $combination)
                                                    <option value="{{ $combination->id }}"
                                                            quantity="{{ $combination->quantity }}"
                                                            rate="{{ $combination->rate }}">{{ $combination->combination_title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-3" style="margin-top: 10px">
                                            {{-- <select class="form-control item_quantity Quantity1" name="item_quantity">
                                                @php $selectMaxQty = $item->combination[0]->quantity > 12 ? 12 : $item->combination[0]->quantity;
                                               	@endphp
                                                    @for ($i = 1; $i <= $selectMaxQty; $i++)
                                             			<option value="{{ $i }}">{{ $i }}</option>
                                               		 @endfor
                                             </select>--}}
                                            <select class="form-control item_quantity Quantity1" id="qty{{$item->id}}"
                                                    name="item_quantity">
                                               @php $selectMaxQty = $item->combination[0]->quantity > 12 ? 12 : $item->combination[0]->quantity;
                           	@endphp
                                @for ($i = 1; $i <= $selectMaxQty; $i++)
                         			<option value="{{ $i }}">{{ $i }}</option>
                           		 @endfor
                                            </select>

                                            {{-- <span >
                                                 <input type="number" name="item_quantity" class="form-control item_quantity" min="1" value="1">
                                             </span>--}}
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    @if($item->combination[0]->quantity > 0)
                                        <button class="addToCart-modal saveAddtocart" onclick="addTocartCombination('{{$item->id}}')"
                                                type="button">Add to Cart
                                        </button>
                                    @else
                                        <button class="addToCart-modal saveAddtocart" onclick="addTocartCombination('{{$item->id}}')" type="button" disabled>Out of Stock</button>
                                    @endif
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="myModal{{$item->id}}" class="modal fade model-mobile1" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                                data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <ul>
                            <li>
                                <a href="#" title="Share" class="slideUP">
                                    <i class="fa fa-share"
                                       aria-hidden="true"></i>
                                    <span>Share</span>
                                    <p>Share with your friends</p>
                                </a>
                                <div class="social-menu">
                                    <a class="social-sharing"
                                       data-type="facebook"
                                       data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <i class="fa fa-facebook-square fa-2x"></i>
                                    </a>
                                    <a class="social-sharing"
                                       data-type="google"
                                       data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <i class="fa fa-google-plus-square fa-2x"
                                           aria-hidden="true"></i>
                                    </a>
                                    <a class="social-sharing"
                                       data-type="twitter"
                                       data-url="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"
                                       href="javascript:void(0);">
                                        <i class="fa fa-twitter-square fa-2x"
                                           aria-hidden="true"></i>
                                    </a>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0)"
                                   class="copy-text">
                                    <input type="hidden" name="link"
                                           class="link"
                                           value="{{ url(route('sellerProductsDetail',['productSlug'=>$item->product_slug])) }}"/>
                                    <i class="fa fa-link"
                                       aria-hidden="true"></i>
                                    <span>Copy Link</span>
                                    <p>copy product link</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
@section('js')
    {{--<script type="text/javascript" src="{{url('assets/frontend/js/owl.carousel.js')}}"></script>--}}
    <script type="text/javascript" src="{{url('assets/frontend/js/carousel-swipe.js')}}"></script>
    <script type="text/javascript">

        $("#optionsChange1").change(function () {
            var dataid = $("#optionsChange1 option:selected").attr('rate');
            $("#optionPrice1").text(dataid + " KD");
        });
        $("#optionsChange").change(function () {
            var dataid = $("#optionsChange option:selected").attr('rate');
            $("#optionPrice").text(dataid + " KD");
        });

        /*$('#thumbcarousel .carousel-inner .item').each(function ()
         {
         var itemToClone = $(this);

         for (var i = 1; i < 4; i++)
         {
         itemToClone = itemToClone.next();

         // wrap around if at end of item collection
         if (!itemToClone.length)
         {

         itemToClone = $(this).siblings(':first');
         }
         // grab item, clone, add marker class, add to collection
         itemToClone.children(':first-child').clone()
         .addClass("cloneditem-" + (i))
         .appendTo($(this));
         }
         });*/

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
        $(".viewmore-content .col-lg-3.col-md-3.col-sm-4.col-xs-6").hide();
        $("#viewmore button").on('click', function () {
            $(".viewmore-content .col-lg-3.col-md-3.col-sm-4.col-xs-6").show();
            $(this).hide();
        });
        $('.readMore').click(function () {
            if ($(".learnMore").hasClass("collapse in")) {
                $(this).text('Read more');
            }
            else {
                $(this).text('Show Less');
            }
        });
        $(document).ready(function () {
            $("#bs-carousel").carousel({
                wrap: false,
            });
        });
        var Page = 1, PageLimit = 8, HasMoreProducts = "{{ (count($releted_products)>=8)?'Yes':'No' }}",IsRequestSent = 0;
        $(document).ready(function () {

            $(".loader").hide();
            $(document).on("change", ".product_combination", function () {
                var quantity = $(this).find("option:selected").attr("quantity");
                var rate = $(this).find("option:selected").attr("rate");
                var html = "<span class='price-1'>" + rate + " KD</span>";
                $(document).find("#button-price").find(".price-label").html(html);
                $(this).closest("form").find(".Quantity").attr("max", quantity);
                if (quantity > 0) {
                    $(this).closest("form").find(".addToCart").attr("type", "submit");
                    $(this).closest("form").find(".addToCart").html("<img src=\'{{ url('assets/frontend/images/button_image.png') }}\'>Add to Cart");
                }
                else {
                    $(this).closest("form").find(".addToCart").attr("type", "button");
                    $(this).closest("form").find(".addToCart").html('Out of Stock');
                }
            });

            $('#thumbcarousel').bind('mousewheel', function (e) {
                if (e.originalEvent.wheelDelta / 120 > 0 || e.originalEvent.detail < 0) {
                    $(this).carousel('next');
                }
                else {
                    $(this).carousel('prev');
                }
            });
            var fixed = document.getElementById("thumbcarousel");
            fixed.addEventListener('mousewheel', function (e) {
                e.preventDefault();
            }, false);
            if (HasMoreProducts == "No") {
                $("#btnViewMoreRelatedProducts").hide();
            }
        });
        $(window).scroll(function () {
            if (Math.round($(window).scrollTop()) >= $(document).height() - window.innerHeight) {
                if (HasMoreProducts == "Yes" && $(window).width() >= 960 &&  IsRequestSent != 1) {
                    var data = {
                        "start": (Page * PageLimit),
                        "limit": PageLimit
                    };
                    IsRequestSent = 1;
                    $(".loader").show();
                    $.ajax({
                        url: "{{ url(route('getRelatedProducts',['product'=>$product->id])) }}",
                        dataType: 'text',
                        type: 'post',
                        contentType: 'application/x-www-form-urlencoded',
                        data: data,
                        success: function (data, textStatus, jQxhr) {
                            var NewProducts = 0;
                            var CurrentProducts = $("#related-products").find(".product-item").length;
                            if (data != "") {
                                $("#related-products").append(data);
                                var TotalProducts = $("#related-products").find(".product-item").length;
                                NewProducts = TotalProducts - CurrentProducts;
                            }
                            if (NewProducts < PageLimit) {
                                HasMoreProducts = "No";
                                $("#btnViewMoreRelatedProducts").hide();
                            }
                            else {
                                Page = Page + 1;
                            }
                            IsRequestSent = 0;
                            $(".loader").hide();
                        },
                        error: function (jqXhr, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                }
            }
        });
        var mySwiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            spaceBetween: 0,
            freeMode: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            breakpoints: {
                1400: {
                    slidesPerView: 3

                },

                992: {
                    slidesPerView: 2.2
                },

                768: {
                    slidesPerView: 2


                },
                736: {
                    slidesPerView: 2.5
                },
                640: {
                    slidesPerView: 2.5
                },
                568: {
                    slidesPerView: 2.5
                },

                480: {
                    slidesPerView: 2.5

                }

            }

        });
    </script>
@endsection